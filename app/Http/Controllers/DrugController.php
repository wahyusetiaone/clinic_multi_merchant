<?php

namespace App\Http\Controllers;

use App\Models\Drug;
use App\Models\DrugDetail;
use App\Models\DrugStock;
use App\Models\Manufacturer;
use App\Models\DrugGroup;
use App\Models\DrugCategory;
use App\Models\Unit;
use App\Models\Label;
use App\Models\Warehouse;
use App\Models\Location;
use App\Models\Branch; // Import Branch
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator; // Untuk validasi AJAX

class DrugController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Drug::class);

        $user = Auth::user();
        $drugs = collect();

        if ($user->hasRole('super_owner')) {
            // Super Owner bisa melihat semua obat
            $drugs = Drug::with(['manufacturer', 'drugGroup', 'drugCategory', 'unit', 'label', 'branch', 'drugDetail'])->get();
        } elseif ($user->hasRole('organization_owner')) {
            // Organization Owner hanya melihat obat di organisasinya
            if ($user->ownedOrganization) {
                $branchIds = $user->ownedOrganization->branches->pluck('id');
                $drugs = Drug::whereIn('branch_id', $branchIds)
                    ->with(['manufacturer', 'drugGroup', 'drugCategory', 'unit', 'label', 'branch', 'drugDetail'])
                    ->get();
            }
        } elseif ($user->hasAnyRole(['admin_clinic', 'admin_pharmacy'])) {
            // Admin Klinik/Farmasi melihat obat di organisasi yang sama dengan cabangnya
            if ($user->employee && $user->employee->organization_id) {
                $organizationId = $user->employee->organization_id;
                $branchIds = Branch::where('organization_id', $organizationId)->pluck('id');
                $drugs = Drug::whereIn('branch_id', $branchIds)
                    ->with(['manufacturer', 'drugGroup', 'drugCategory', 'unit', 'label', 'branch', 'drugDetail'])
                    ->get();
            }
        }

        return view('drugs.index', compact('drugs'));
    }

    /**
     * Show the form for creating a new resource (Wizard Step 1).
     */
    public function create()
    {
        $this->authorize('create', Drug::class);

        $user = Auth::user();
        $branches = collect();
        $manufacturers = Manufacturer::all();
        $drugGroups = DrugGroup::all();
        $drugCategories = DrugCategory::all();
        $units = Unit::all();
        $labels = Label::all();

        // Filter cabang berdasarkan peran
        if ($user->hasRole('super_owner')) {
            $branches = Branch::all();
        } elseif ($user->hasRole('organization_owner')) {
            if ($user->ownedOrganization) {
                $branches = $user->ownedOrganization->branches;
            }
        } elseif ($user->hasAnyRole(['admin_clinic', 'admin_pharmacy'])) {
            if ($user->employee && $user->employee->organization_id) {
                $branches = Branch::where('organization_id', $user->employee->organization_id)->get();
            }
        }

        // Ambil gudang yang relevan untuk step 3 (stok)
        // Awalnya, kita mungkin tidak perlu semua gudang di sini,
        // tapi ini sebagai persiapan jika dropdown gudang ada di step 1 atau 2.
        // Untuk step 3, kita akan load berdasarkan cabang yang dipilih dinamis.
        $warehouses = collect();
        if ($branches->isNotEmpty()) {
            $branchIds = $branches->pluck('id');
            $warehouses = Warehouse::whereIn('branch_id', $branchIds)->get();
        }


        return view('drugs.create', compact('manufacturers', 'drugGroups', 'drugCategories', 'units', 'labels', 'branches', 'warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     * This method handles data from all wizard steps.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Drug::class);

        // Debugging request data
        // dd($request->all());

        DB::beginTransaction();

        try {
            // Validasi Step 1: Data Obat
            $validatedDrug = $request->validate([
                'code' => ['nullable', 'string', 'max:255', Rule::unique('drugs')], // Code bisa otomatis, jadi nullable
                'name' => 'required|string|max:255',
                'manufacturer_id' => 'nullable|exists:manufacturers,id',
                'group_id' => 'nullable|exists:drug_groups,id',
                'category_id' => 'nullable|exists:drug_categories,id',
                'unit_id' => 'required|exists:units,id', // unit_id untuk unit dasar obat
                'drug_type' => 'required|in:Non Konsinyasi,Konsinyasi',
                'min_stock' => 'nullable|integer|min:0',
                'description' => 'nullable|string',
                'indication' => 'nullable|string',
                'content' => 'nullable|string',
                'dosage' => 'nullable|string',
                'packaging' => 'nullable|string',
                'side_effects' => 'nullable|string',
                'precursor_active_ingredient' => 'nullable|string',
                'label_id' => 'nullable|exists:labels,id',
                'branch_id' => 'required|exists:branches,id',
            ]);

            // Validasi Step 2: Detail Obat
            $validatedDrugDetail = $request->validate([
                'hna' => 'required|numeric|min:0',
                'selling_price_1' => 'required|numeric|min:0',
                'discount_1' => 'nullable|numeric|min:0|max:100',
                'selling_price_2' => 'nullable|numeric|min:0',
                'discount_2' => 'nullable|numeric|min:0|max:100',
                'selling_price_3' => 'nullable|numeric|min:0',
                'discount_3' => 'nullable|numeric|min:0|max:100',
                'barcode' => ['nullable', 'string', 'max:255', Rule::unique('drug_details')],
            ]);

            // Validasi Step 3: Stok Obat (jika ada)
            $validatedDrugStocks = $request->validate([
                'stocks' => 'array', // Pastikan ini adalah array
                'stocks.*.warehouse_id' => 'required|exists:warehouses,id',
                'stocks.*.location_id' => 'required|exists:locations,id',
                'stocks.*.stock_quantity' => 'required|integer|min:1',
                'stocks.*.unit_id' => 'required|exists:units,id', // unit_id untuk unit stok (misal: box, strip)
                'stocks.*.batch_number' => 'required|string|max:255',
                'stocks.*.expiration_date' => 'required|date|after_or_equal:today',
            ]);

            // Ensure branch_id is set correctly based on user's access
            // If user is not super_owner, automatically assign their branch_id (or organization's branch_id)
            $user = Auth::user();
            if (!$user->hasRole('super_owner')) {
                // For organization_owner, use their owned organization's branches
                if ($user->hasRole('organization_owner') && $user->ownedOrganization) {
                    $accessibleBranchIds = $user->ownedOrganization->branches->pluck('id')->toArray();
                    // Ensure the requested branch_id is one of the accessible ones
                    if (!in_array($validatedDrug['branch_id'], $accessibleBranchIds)) {
                        abort(403, 'Anda tidak memiliki izin untuk membuat obat di cabang ini.');
                    }
                }
                // For admin_clinic/admin_pharmacy, use their employee's organization branches
                elseif ($user->hasAnyRole(['admin_clinic', 'admin_pharmacy']) && $user->employee && $user->employee->organization_id) {
                    $accessibleBranchIds = Branch::where('organization_id', $user->employee->organization_id)->pluck('id')->toArray();
                    if (!in_array($validatedDrug['branch_id'], $accessibleBranchIds)) {
                        abort(403, 'Anda tidak memiliki izin untuk membuat obat di cabang ini.');
                    }
                } else {
                    abort(403, 'Akses ditolak. Anda tidak memiliki cabang yang terhubung.');
                }
            }


            // Create Drug
            $drug = Drug::create($validatedDrug);

            // Create DrugDetail
            $drugDetail = new DrugDetail($validatedDrugDetail);
            $drug->drugDetail()->save($drugDetail);

            // Create DrugStocks
            if (isset($validatedDrugStocks['stocks']) && is_array($validatedDrugStocks['stocks'])) {
                foreach ($validatedDrugStocks['stocks'] as $stockData) {
                    // Validasi tambahan: Pastikan gudang dan lokasi berada di cabang yang sama dengan obat
                    $warehouse = Warehouse::find($stockData['warehouse_id']);
                    $location = Location::find($stockData['location_id']);

                    if (!$warehouse || $warehouse->branch_id !== $drug->branch_id) {
                        throw new \Exception("Gudang ID {$stockData['warehouse_id']} tidak valid atau bukan milik cabang obat.");
                    }
                    if (!$location || $location->warehouse_id !== $warehouse->id) {
                        throw new \Exception("Lokasi ID {$stockData['location_id']} tidak valid atau bukan milik gudang terpilih.");
                    }

                    $drug->drugStocks()->create($stockData);
                }
            }

            DB::commit();

            return redirect()->route('drugs.index')->with('success', 'Obat berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage()); // Untuk debugging
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan obat: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Drug $drug)
    {
        $this->authorize('view', $drug);

        // Eager load semua relasi yang diperlukan untuk tampilan detail
        $drug->load(['manufacturer', 'drugGroup', 'drugCategory', 'unit', 'label', 'branch', 'drugDetail', 'drugStocks.warehouse', 'drugStocks.location', 'drugStocks.unit']);

        return view('drugs.show', compact('drug'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Drug $drug)
    {
        $this->authorize('update', $drug);

        $user = Auth::user();
        $branches = collect();
        $manufacturers = Manufacturer::all();
        $drugGroups = DrugGroup::all();
        $drugCategories = DrugCategory::all();
        $units = Unit::all();
        $labels = Label::all();

        // Filter cabang berdasarkan peran
        if ($user->hasRole('super_owner')) {
            $branches = Branch::all();
        } elseif ($user->hasRole('organization_owner')) {
            if ($user->ownedOrganization) {
                $branches = $user->ownedOrganization->branches;
            }
        } elseif ($user->hasAnyRole(['admin_clinic', 'admin_pharmacy'])) {
            if ($user->employee && $user->employee->organization_id) {
                $branches = Branch::where('organization_id', $user->employee->organization_id)->get();
            }
        }

        // Ambil semua gudang yang relevan dengan cabang yang bisa diakses user,
        // terutama gudang yang terkait dengan obat yang sedang diedit.
        $warehouses = Warehouse::whereIn('branch_id', $branches->pluck('id'))->get();

        // Eager load drugDetail dan drugStocks untuk form edit
        $drug->load(['drugDetail', 'drugStocks.warehouse', 'drugStocks.location', 'drugStocks.unit']);


        return view('drugs.edit', compact('drug', 'manufacturers', 'drugGroups', 'drugCategories', 'units', 'labels', 'branches', 'warehouses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Drug $drug)
    {
        $this->authorize('update', $drug);

        DB::beginTransaction();

        try {
            // Validasi Step 1: Data Obat
            $validatedDrug = $request->validate([
                'code' => ['nullable', 'string', 'max:255', Rule::unique('drugs')->ignore($drug->id)],
                'name' => 'required|string|max:255',
                'manufacturer_id' => 'nullable|exists:manufacturers,id',
                'group_id' => 'nullable|exists:drug_groups,id',
                'category_id' => 'nullable|exists:drug_categories,id',
                'unit_id' => 'required|exists:units,id',
                'drug_type' => 'required|in:Non Konsinyasi,Konsinyasi',
                'min_stock' => 'nullable|integer|min:0',
                'description' => 'nullable|string',
                'indication' => 'nullable|string',
                'content' => 'nullable|string',
                'dosage' => 'nullable|string',
                'packaging' => 'nullable|string',
                'side_effects' => 'nullable|string',
                'precursor_active_ingredient' => 'nullable|string',
                'label_id' => 'nullable|exists:labels,id',
                'branch_id' => 'required|exists:branches,id',
            ]);

            // Validasi Step 2: Detail Obat
            $validatedDrugDetail = $request->validate([
                'hna' => 'required|numeric|min:0',
                'selling_price_1' => 'required|numeric|min:0',
                'discount_1' => 'nullable|numeric|min:0|max:100',
                'selling_price_2' => 'nullable|numeric|min:0',
                'discount_2' => 'nullable|numeric|min:0|max:100',
                'selling_price_3' => 'nullable|numeric|min:0',
                'discount_3' => 'nullable|numeric|min:0|max:100',
                'barcode' => ['nullable', 'string', 'max:255', Rule::unique('drug_details')->ignore($drug->drugDetail->id ?? null)],
            ]);

            // Pastikan branch_id yang diminta valid untuk user yang login
            $user = Auth::user();
            if (!$user->hasRole('super_owner')) {
                // Logic ini harus memastikan branch_id yang dipilih memang bisa diakses oleh user
                // dan juga bahwa user memiliki otorisasi untuk mengupdate obat di cabang tersebut
                if ($user->hasRole('organization_owner') && $user->ownedOrganization) {
                    $accessibleBranchIds = $user->ownedOrganization->branches->pluck('id')->toArray();
                    if (!in_array($validatedDrug['branch_id'], $accessibleBranchIds)) {
                        abort(403, 'Anda tidak memiliki izin untuk memperbarui obat ke cabang ini.');
                    }
                } elseif ($user->hasAnyRole(['admin_clinic', 'admin_pharmacy']) && $user->employee && $user->employee->organization_id) {
                    $accessibleBranchIds = Branch::where('organization_id', $user->employee->organization_id)->pluck('id')->toArray();
                    if (!in_array($validatedDrug['branch_id'], $accessibleBranchIds)) {
                        abort(403, 'Anda tidak memiliki izin untuk memperbarui obat ke cabang ini.');
                    }
                } else {
                    abort(403, 'Akses ditolak. Anda tidak memiliki cabang yang terhubung.');
                }
            }


            // Update Drug
            $drug->update($validatedDrug);

            // Update or Create DrugDetail
            $drug->drugDetail()->updateOrCreate(
                ['drug_id' => $drug->id], // Kriteria pencarian
                $validatedDrugDetail        // Data untuk update/create
            );

            // Note: Penanganan DrugStock di halaman edit akan dilakukan via AJAX terpisah
            //       untuk penambahan, perubahan, dan penghapusan per baris.
            //       Tidak perlu logic massal di method update ini untuk stocks.

            DB::commit();

            return redirect()->route('drugs.index')->with('success', 'Obat berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage()); // Untuk debugging
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui obat: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Drug $drug)
    {
        $this->authorize('delete', $drug);

        try {
            DB::beginTransaction();
            $drug->delete(); // Ini akan menghapus drugDetail dan drugStocks karena onDelete('cascade')
            DB::commit();
            return redirect()->route('drugs.index')->with('success', 'Obat berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus obat: ' . $e->getMessage());
        }
    }

    // =======================================================
    // Metode Khusus untuk Operasi AJAX DrugStock (Halaman Edit)
    // =======================================================

    /**
     * Store a new drug stock entry via AJAX.
     *
     * @param  \App\Models\Drug  $drug
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeDrugStock(Drug $drug, Request $request)
    {
        $this->authorize('update', $drug); // Otorisasi untuk mengupdate obat juga berlaku untuk stoknya

        $validator = Validator::make($request->all(), [
            'warehouse_id' => 'required|exists:warehouses,id',
            'location_id' => 'required|exists:locations,id',
            'stock_quantity' => 'required|integer|min:1',
            'unit_id' => 'required|exists:units,id',
            'batch_number' => 'required|string|max:255',
            'expiration_date' => 'required|date|after_or_equal:today',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Validasi tambahan: Pastikan gudang dan lokasi berada di cabang yang sama dengan obat
            $warehouse = Warehouse::find($request->warehouse_id);
            $location = Location::find($request->location_id);

            if (!$warehouse || $warehouse->branch_id !== $drug->branch_id) {
                return response()->json(['message' => 'Gudang tidak valid atau bukan milik cabang obat.'], 403);
            }
            if (!$location || $location->warehouse_id !== $warehouse->id) {
                return response()->json(['message' => 'Lokasi tidak valid atau bukan milik gudang terpilih.'], 403);
            }

            $stock = $drug->drugStocks()->create($request->all());
            $stock->load(['warehouse', 'location', 'unit']); // Load relasi untuk respon

            return response()->json([
                'message' => 'Stok obat berhasil ditambahkan.',
                'stock' => $stock
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menambahkan stok: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update a specific drug stock entry via AJAX.
     *
     * @param  \App\Models\Drug  $drug
     * @param  \App\Models\DrugStock  $drugStock
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateDrugStock(Drug $drug, DrugStock $drugStock, Request $request)
    {
        $this->authorize('update', $drug); // Otorisasi untuk mengupdate obat juga berlaku untuk stoknya

        // Pastikan drugStock memang milik drug ini
        if ($drugStock->drug_id !== $drug->id) {
            return response()->json(['message' => 'Stok tidak ditemukan untuk obat ini.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'warehouse_id' => 'required|exists:warehouses,id',
            'location_id' => 'required|exists:locations,id',
            'stock_quantity' => 'required|integer|min:0', // Stok bisa 0 saat update
            'unit_id' => 'required|exists:units,id',
            'batch_number' => 'required|string|max:255',
            'expiration_date' => 'required|date|after_or_equal:today',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Validasi tambahan: Pastikan gudang dan lokasi berada di cabang yang sama dengan obat
            $warehouse = Warehouse::find($request->warehouse_id);
            $location = Location::find($request->location_id);

            if (!$warehouse || $warehouse->branch_id !== $drug->branch_id) {
                return response()->json(['message' => 'Gudang tidak valid atau bukan milik cabang obat.'], 403);
            }
            if (!$location || $location->warehouse_id !== $warehouse->id) {
                return response()->json(['message' => 'Lokasi tidak valid atau bukan milik gudang terpilih.'], 403);
            }

            $drugStock->update($request->all());
            $drugStock->load(['warehouse', 'location', 'unit']); // Load relasi untuk respon

            return response()->json([
                'message' => 'Stok obat berhasil diperbarui.',
                'stock' => $drugStock
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal memperbarui stok: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete a specific drug stock entry via AJAX.
     *
     * @param  \App\Models\Drug  $drug
     * @param  \App\Models\DrugStock  $drugStock
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyDrugStock(Drug $drug, DrugStock $drugStock)
    {
        $this->authorize('update', $drug); // Otorisasi untuk mengupdate obat juga berlaku untuk stoknya

        // Pastikan drugStock memang milik drug ini
        if ($drugStock->drug_id !== $drug->id) {
            return response()->json(['message' => 'Stok tidak ditemukan untuk obat ini.'], 404);
        }

        try {
            $drugStock->delete();
            return response()->json(['message' => 'Stok obat berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus stok: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get locations by warehouse ID via AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLocationsByWarehouse(Request $request)
    {
        $warehouseId = $request->input('warehouse_id');
        $locations = [];

        if ($warehouseId) {
            $warehouse = Warehouse::find($warehouseId);
            $user = Auth::user();

            // Tambahkan otorisasi di sini untuk memastikan user bisa mengakses gudang ini
            // Berdasarkan WarehousePolicy view/update atau custom logic
            if ($warehouse) {
                if ($user->hasRole('super_owner')) {
                    // Super owner bisa melihat semua lokasi
                    $locations = $warehouse->locations()->select('id', 'name')->get();
                } elseif ($user->hasRole('organization_owner')) {
                    if ($user->ownedOrganization && $user->ownedOrganization->id === $warehouse->branch->organization_id) {
                        $locations = $warehouse->locations()->select('id', 'name')->get();
                    }
                } elseif ($user->hasAnyRole(['admin_clinic', 'admin_pharmacy'])) {
                    if ($user->employee && $user->employee->organization_id === $warehouse->branch->organization_id) {
                        $locations = $warehouse->locations()->select('id', 'name')->get();
                    }
                }
            }
        }

        return response()->json($locations);
    }
}
