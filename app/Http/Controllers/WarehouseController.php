<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\Branch; // Pastikan ini diimport
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Warehouse::class);

        $user = Auth::user();
        $warehouses = collect();

        if ($user->hasRole('super_owner')) {
            $warehouses = Warehouse::with(['branch', 'locations'])->get();
        } elseif ($user->hasRole('organization_owner')) {
            if ($user->ownedOrganization) {
                $branchIds = $user->ownedOrganization->branches->pluck('id');
                $warehouses = Warehouse::whereIn('branch_id', $branchIds)
                    ->with(['branch', 'locations'])
                    ->get();
            }
        } elseif ($user->hasAnyRole(['admin_clinic', 'admin_pharmacy'])) {
            if ($user->employee && $user->employee->organization_id) {
                $organizationId = $user->employee->organization_id;
                $branchIds = Branch::where('organization_id', $organizationId)->pluck('id');
                $warehouses = Warehouse::whereIn('branch_id', $branchIds)
                    ->with(['branch', 'locations'])
                    ->get();
            }
        }
        // Tambahkan kondisi atau penanganan lain jika ada peran lain yang perlu melihat gudang
        // Misalnya, jika 'doctor' atau 'pharmacist' hanya bisa melihat gudang di cabang mereka

        return view('warehouses.index', compact('warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Warehouse::class);

        $user = Auth::user();
        $branches = collect(); // Inisialisasi collection kosong

        // Logika untuk mengambil cabang berdasarkan peran pengguna
        if ($user->hasRole('super_owner')) {
            $branches = Branch::all();
        } elseif ($user->hasRole('organization_owner')) {
            if ($user->ownedOrganization) {
                $branches = $user->ownedOrganization->branches;
            }
        } elseif ($user->hasAnyRole(['admin_clinic', 'admin_pharmacy'])) {
            if ($user->employee && $user->employee->organization_id) {
                $organizationId = $user->employee->organization_id;
                $branches = Branch::where('organization_id', $organizationId)->get();
            }
        }
        // Jika ada peran lain yang perlu mengakses form ini, tambahkan logika di sini.

        return view('warehouses.create', compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Warehouse::class);

        $validatedData = $request->validate([
            // 'code' telah dihapus dari form dan model, jadi dihapus juga dari validasi
            'name' => 'required|string|max:255',
            // 'description' telah dihapus dari form dan model, jadi dihapus juga dari validasi
            'branch_id' => 'required|exists:branches,id',
            'is_default' => 'boolean',
            'racks' => 'array',
            'racks.*.name' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $warehouse = Warehouse::create([
                // 'code' tidak lagi ada di sini
                'name' => $validatedData['name'],
                // 'description' tidak lagi ada di sini
                'branch_id' => $validatedData['branch_id'],
                'is_default' => $validatedData['is_default'] ?? false,
            ]);

            if (isset($validatedData['racks']) && is_array($validatedData['racks'])) {
                foreach ($validatedData['racks'] as $rackData) {
                    $warehouse->locations()->create([
                        'name' => $rackData['name'],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('warehouses.index')->with('success', 'Gudang dan Rak berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan gudang dan rak: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Warehouse $warehouse)
    {
        $this->authorize('view', $warehouse);

        $warehouse->load(['branch', 'locations']);
        return view('warehouses.show', compact('warehouse'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warehouse $warehouse)
    {
        $this->authorize('update', $warehouse);

        $user = Auth::user();
        $branches = collect();

        if ($user->hasRole('super_owner')) {
            $branches = Branch::all();
        } elseif ($user->hasRole('organization_owner')) {
            if ($user->ownedOrganization) {
                $branches = $user->ownedOrganization->branches;
            }
        } elseif ($user->hasAnyRole(['admin_clinic', 'admin_pharmacy'])) {
            if ($user->employee && $user->employee->organization_id) {
                $organizationId = $user->employee->organization_id;
                $branches = Branch::where('organization_id', $organizationId)->get();
            }
        }

        return view('warehouses.edit', compact('warehouse', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        $this->authorize('update', $warehouse);

        $validatedData = $request->validate([
            // 'code' telah dihapus
            'name' => 'required|string|max:255',
            // 'description' telah dihapus
            'branch_id' => 'required|exists:branches,id',
            'is_default' => 'boolean',
            // Racks tidak langsung diupdate di sini, mereka memiliki endpoint sendiri
        ]);

        DB::beginTransaction();
        try {
            $warehouse->update([
                // 'code' tidak lagi diupdate
                'name' => $validatedData['name'],
                // 'description' tidak lagi diupdate
                'branch_id' => $validatedData['branch_id'],
                'is_default' => $validatedData['is_default'] ?? false,
            ]);

            DB::commit();
            return redirect()->route('warehouses.index')->with('success', 'Gudang berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui gudang: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        $this->authorize('delete', $warehouse);

        DB::beginTransaction();
        try {
            $warehouse->delete();
            DB::commit();
            return redirect()->route('warehouses.index')->with('success', 'Gudang berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus gudang: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created location for the specified warehouse via AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeLocation(Request $request, Warehouse $warehouse)
    {
        // Otorisasi melalui WarehousePolicy::update pada warehouse parent
        $this->authorize('update', $warehouse);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $location = $warehouse->locations()->create([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Lokasi penyimpanan berhasil ditambahkan.',
            'location' => $location
        ]);
    }

    /**
     * Update the specified location for the specified warehouse via AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Warehouse  $warehouse
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateLocation(Request $request, Warehouse $warehouse, Location $location)
    {
        // Otorisasi melalui WarehousePolicy::update pada warehouse parent
        $this->authorize('update', $warehouse);

        // Pastikan lokasi ini memang milik gudang yang dimaksud
        if ($location->warehouse_id !== $warehouse->id) {
            return response()->json(['message' => 'Lokasi tidak ditemukan di gudang ini.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $location->update(['name' => $request->name]); // Hanya update 'name'

        return response()->json([
            'message' => 'Lokasi penyimpanan berhasil diperbarui.',
            'location' => $location
        ]);
    }

    /**
     * Remove the specified location from the specified warehouse via AJAX.
     *
     * @param  \App\Models\Warehouse  $warehouse
     * @param  \App\\Models\\Location  $location
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyLocation(Warehouse $warehouse, Location $location)
    {
        // Otorisasi melalui WarehousePolicy::update pada warehouse parent
        $this->authorize('update', $warehouse);

        // Pastikan lokasi ini memang milik gudang yang dimaksud
        if ($location->warehouse_id !== $warehouse->id) {
            return response()->json(['message' => 'Lokasi tidak ditemukan di gudang ini.'], 404);
        }

        $location->delete();

        return response()->json(['message' => 'Lokasi penyimpanan berhasil dihapus.']);
    }
}
