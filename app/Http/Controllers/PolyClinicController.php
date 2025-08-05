<?php

namespace App\Http\Controllers;

use App\Models\PolyClinic;
use App\Models\Branch; // Import model Branch untuk dropdown
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PolyClinicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Memanggil policy 'viewAny'
        $this->authorize('viewAny', PolyClinic::class);

        $user = Auth::user();
        $polyclinics = collect();

        if ($user->hasRole('super_owner')) {
            // Super Owner bisa melihat semua poli
            $polyclinics = PolyClinic::with('branch')->get();
        } elseif ($user->hasRole('organization_owner')) {
            // Organization Owner hanya melihat poli di organisasinya
            if ($user->ownedOrganization) {
                $branchIds = $user->ownedOrganization->branches->pluck('id');
                $polyclinics = PolyClinic::whereIn('branch_id', $branchIds)
                    ->with('branch')
                    ->get();
            }
        } elseif ($user->hasAnyRole(['admin_clinic', 'receptionist', 'doctor', 'pharmacist'])) {
            // Peran lain melihat poli di organisasi yang sama
            if ($user->employee && $user->employee->organization_id) {
                $organizationId = $user->employee->organization_id;
                $branchIds = Branch::where('organization_id', $organizationId)->pluck('id');
                $polyclinics = PolyClinic::whereIn('branch_id', $branchIds)
                    ->with('branch')
                    ->get();
            }
        }

        return view('polyclinics.index', compact('polyclinics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Memanggil policy 'create'
        $this->authorize('create', PolyClinic::class);

        $user = Auth::user();
        $branches = collect();

        if ($user->hasRole('super_owner')) {
            // Super Owner bisa melihat semua cabang tipe 'clinic'
            $branches = Branch::where('type', 'clinic')->get(); // <--- Perubahan di sini
        } elseif ($user->hasRole('organization_owner')) {
            // Organization Owner hanya melihat cabang tipe 'clinic' di organisasinya
            if ($user->ownedOrganization) {
                $branches = $user->ownedOrganization->branches()->where('type', 'clinic')->get(); // <--- Perubahan di sini
            }
        } elseif ($user->hasRole('admin_clinic')) {
            // Admin Klinik melihat cabang tipe 'clinic' di organisasi yang sama
            if ($user->employee && $user->employee->organization_id) {
                $branches = Branch::where('organization_id', $user->employee->organization_id)
                    ->where('type', 'clinic') // <--- Perubahan di sini
                    ->get();
            }
        } else {
            abort(403, 'Anda tidak memiliki izin untuk menambah poli klinik.');
        }

        return view('polyclinics.create', compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Memanggil policy 'create'
        $this->authorize('create', PolyClinic::class);

        $request->validate([
            'code' => 'required|string|max:255|unique:polyclinics,code',
            'name' => 'required|string|max:255',
            'physical_location_type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $user = Auth::user();
        $selectedBranch = Branch::find($request->branch_id);

        // Validasi tambahan: Pastikan user hanya bisa menambah poli di cabang yang terkait organisasinya
        if ($user->hasRole('organization_owner') && $user->ownedOrganization->id !== $selectedBranch->organization_id) {
            abort(403, 'Anda tidak memiliki izin untuk menambah poli di cabang ini.');
        }
        if ($user->hasRole('admin_clinic') && ($user->employee && $user->employee->organization_id !== $selectedBranch->organization_id)) {
            abort(403, 'Anda tidak memiliki izin untuk menambah poli di cabang ini.');
        }

        PolyClinic::create($request->all());

        return redirect()->route('polyclinics.index')->with('success', 'Poli klinik berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PolyClinic $polyclinic)
    {
        // Memanggil policy 'view'
        $this->authorize('view', $polyclinic);

        return view('polyclinics.show', compact('polyclinic'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PolyClinic $polyclinic)
    {
        // Memanggil policy 'update'
        $this->authorize('update', $polyclinic);

        $user = Auth::user();
        $branches = collect();

        if ($user->hasRole('super_owner')) {
            // Super Owner bisa melihat semua cabang tipe 'clinic'
            $branches = Branch::where('type', 'clinic')->get(); // <--- Perubahan di sini
        } elseif ($user->hasRole('organization_owner')) {
            // Organization Owner hanya melihat cabang tipe 'clinic' di organisasinya
            if ($user->ownedOrganization) {
                $branches = $user->ownedOrganization->branches()->where('type', 'clinic')->get(); // <--- Perubahan di sini
            }
        } elseif ($user->hasRole('admin_clinic')) {
            // Admin Klinik melihat cabang tipe 'clinic' di organisasi yang sama
            if ($user->employee && $user->employee->organization_id) {
                $branches = Branch::where('organization_id', $user->employee->organization_id)
                    ->where('type', 'clinic') // <--- Perubahan di sini
                    ->get();
            }
        } else {
            abort(403, 'Anda tidak memiliki izin untuk mengedit poli klinik.');
        }

        return view('polyclinics.edit', compact('polyclinic', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PolyClinic $polyclinic)
    {
        // Memanggil policy 'update'
        $this->authorize('update', $polyclinic);

        $request->validate([
            'code' => ['required', 'string', 'max:255', Rule::unique('polyclinics', 'code')->ignore($polyclinic->id)],
            'name' => 'required|string|max:255',
            'physical_location_type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $user = Auth::user();
        $selectedBranch = Branch::find($request->branch_id);

        // Validasi tambahan: Pastikan user hanya bisa mengupdate poli di cabang yang terkait organisasinya
        if ($user->hasRole('organization_owner') && $user->ownedOrganization->id !== $selectedBranch->organization_id) {
            abort(403, 'Anda tidak memiliki izin untuk mengupdate poli di cabang ini.');
        }
        if ($user->hasRole('admin_clinic') && ($user->employee && $user->employee->organization_id !== $selectedBranch->organization_id)) {
            abort(403, 'Anda tidak memiliki izin untuk mengupdate poli di cabang ini.');
        }

        $polyclinic->update($request->all());

        return redirect()->route('polyclinics.index')->with('success', 'Poli klinik berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PolyClinic $polyclinic)
    {
        // Memanggil policy 'delete'
        $this->authorize('delete', $polyclinic);

        $polyclinic->delete();

        return redirect()->route('polyclinics.index')->with('success', 'Poli klinik berhasil dihapus.');
    }
}
