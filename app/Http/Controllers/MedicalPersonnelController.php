<?php

namespace App\Http\Controllers;

use App\Models\MedicalPersonnel;
use App\Models\Branch; // Jangan lupa import model Branch
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth; // Untuk otorisasi berdasarkan user

class MedicalPersonnelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // TODO: Implement MedicalPersonnelPolicy::viewAny untuk otorisasi
        // $this->authorize('viewAny', MedicalPersonnel::class);

        $user = Auth::user();
        $medicalPersonnel = collect(); // Inisialisasi koleksi kosong

        if ($user->hasRole('super_owner')) {
            $medicalPersonnel = MedicalPersonnel::with('branch')->get();
        } elseif ($user->hasRole('organization_owner')) {
            if ($user->ownedOrganization) {
                $branchIds = $user->ownedOrganization->branches->pluck('id');
                $medicalPersonnel = MedicalPersonnel::whereIn('branch_id', $branchIds)
                    ->with('branch')
                    ->get();
            }
        } elseif ($user->hasAnyRole(['admin_clinic', 'receptionist'])) { // Asumsi peran ini bisa melihat daftar petugas medis
            if ($user->employee && $user->employee->organization_id) {
                $organizationId = $user->employee->organization_id;
                $branchIds = Branch::where('organization_id', $organizationId)->pluck('id');
                $medicalPersonnel = MedicalPersonnel::whereIn('branch_id', $branchIds)
                    ->with('branch')
                    ->get();
            }
        } else {
            abort(403, 'Anda tidak memiliki izin untuk melihat daftar petugas medis.');
        }

        return view('medical_personnel.index', compact('medicalPersonnel'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // TODO: Implement MedicalPersonnelPolicy::create untuk otorisasi
        // $this->authorize('create', MedicalPersonnel::class);

        $user = Auth::user();
        $branches = collect();

        if ($user->hasRole('super_owner')) {
            $branches = Branch::where('type', 'clinic')->get();
        } elseif ($user->hasRole('organization_owner')) {
            if ($user->ownedOrganization) {
                $branches = $user->ownedOrganization->branches()->where('type', 'clinic')->get();
            }
        } elseif ($user->hasRole('admin_clinic')) {
            if ($user->employee && $user->employee->organization_id) {
                $branches = Branch::where('organization_id', $user->employee->organization_id)
                    ->where('type', 'clinic')
                    ->get();
            }
        } else {
            abort(403, 'Anda tidak memiliki izin untuk menambah petugas medis.');
        }

        return view('medical_personnel.create', compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: Implement MedicalPersonnelPolicy::create untuk otorisasi
        // $this->authorize('create', MedicalPersonnel::class);

        $request->validate([
            'category' => 'required|in:perawat,petugas', // Validasi kategori
            'nik' => 'required|string|max:255|unique:medical_personnel,nik',
            'satusehat_id' => 'nullable|string|max:255|unique:medical_personnel,satusehat_id',
            'name' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:medical_personnel,email',
            'start_date' => 'required|date',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $user = Auth::user();
        $selectedBranch = Branch::find($request->branch_id);

        // Validasi tambahan untuk branch_id berdasarkan role
        if ($user->hasRole('organization_owner') && $user->ownedOrganization->id !== $selectedBranch->organization_id) {
            abort(403, 'Anda tidak memiliki izin untuk menambah petugas medis di cabang ini.');
        }
        if ($user->hasRole('admin_clinic') && ($user->employee && $user->employee->organization_id !== $selectedBranch->organization_id)) {
            abort(403, 'Anda tidak memiliki izin untuk menambah petugas medis di cabang ini.');
        }

        MedicalPersonnel::create($request->all());

        return redirect()->route('medical_personnel.index')->with('success', 'Petugas medis berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MedicalPersonnel $medicalPersonnel)
    {
        // TODO: Implement MedicalPersonnelPolicy::view untuk otorisasi
        // $this->authorize('view', $medicalPersonnel);

        // Pastikan petugas medis yang diakses sesuai dengan organisasi/cabang user yang login
        $user = Auth::user();
        if ($user->hasRole('organization_owner') && $user->ownedOrganization->id !== $medicalPersonnel->branch->organization_id) {
            abort(403, 'Anda tidak memiliki izin untuk melihat detail petugas medis ini.');
        }
        if ($user->hasRole('admin_clinic') && ($user->employee && $user->employee->organization_id !== $medicalPersonnel->branch->organization_id)) {
            abort(403, 'Anda tidak memiliki izin untuk melihat detail petugas medis ini.');
        }

        return view('medical_personnel.show', compact('medicalPersonnel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MedicalPersonnel $medicalPersonnel)
    {
        // TODO: Implement MedicalPersonnelPolicy::update untuk otorisasi
        // $this->authorize('update', $medicalPersonnel);

        // Pastikan petugas medis yang diakses sesuai dengan organisasi/cabang user yang login
        $user = Auth::user();
        if ($user->hasRole('organization_owner') && $user->ownedOrganization->id !== $medicalPersonnel->branch->organization_id) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit petugas medis ini.');
        }
        if ($user->hasRole('admin_clinic') && ($user->employee && $user->employee->organization_id !== $medicalPersonnel->branch->organization_id)) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit petugas medis ini.');
        }

        $branches = collect();
        if ($user->hasRole('super_owner')) {
            $branches = Branch::where('type', 'clinic')->get();
        } elseif ($user->hasRole('organization_owner')) {
            if ($user->ownedOrganization) {
                $branches = $user->ownedOrganization->branches()->where('type', 'clinic')->get();
            }
        } elseif ($user->hasRole('admin_clinic')) {
            if ($user->employee && $user->employee->organization_id) {
                $branches = Branch::where('organization_id', $user->employee->organization_id)
                    ->where('type', 'clinic')
                    ->get();
            }
        } else {
            abort(403, 'Anda tidak memiliki izin untuk mengedit petugas medis.');
        }

        return view('medical_personnel.edit', compact('medicalPersonnel', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MedicalPersonnel $medicalPersonnel)
    {
        // TODO: Implement MedicalPersonnelPolicy::update untuk otorisasi
        // $this->authorize('update', $medicalPersonnel);

        $request->validate([
            'category' => 'required|in:perawat,petugas',
            'nik' => ['required', 'string', 'max:255', Rule::unique('medical_personnel', 'nik')->ignore($medicalPersonnel->id)],
            'satusehat_id' => ['nullable', 'string', 'max:255', Rule::unique('medical_personnel', 'satusehat_id')->ignore($medicalPersonnel->id)],
            'name' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:255',
            'email' => ['nullable', 'email', 'max:255', Rule::unique('medical_personnel', 'email')->ignore($medicalPersonnel->id)],
            'start_date' => 'required|date',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $user = Auth::user();
        $selectedBranch = Branch::find($request->branch_id);

        // Validasi tambahan untuk branch_id
        if ($user->hasRole('organization_owner') && $user->ownedOrganization->id !== $selectedBranch->organization_id) {
            abort(403, 'Anda tidak memiliki izin untuk mengupdate petugas medis di cabang ini.');
        }
        if ($user->hasRole('admin_clinic') && ($user->employee && $user->employee->organization_id !== $selectedBranch->organization_id)) {
            abort(403, 'Anda tidak memiliki izin untuk mengupdate petugas medis di cabang ini.');
        }

        $medicalPersonnel->update($request->all());

        return redirect()->route('medical_personnel.index')->with('success', 'Data petugas medis berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedicalPersonnel $medicalPersonnel)
    {
        // TODO: Implement MedicalPersonnelPolicy::delete untuk otorisasi
        // $this->authorize('delete', $medicalPersonnel);

        // Pastikan petugas medis yang diakses sesuai dengan organisasi/cabang user yang login
        $user = Auth::user();
        if ($user->hasRole('organization_owner') && $user->ownedOrganization->id !== $medicalPersonnel->branch->organization_id) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus petugas medis ini.');
        }
        if ($user->hasRole('admin_clinic') && ($user->employee && $user->employee->organization_id !== $medicalPersonnel->branch->organization_id)) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus petugas medis ini.');
        }

        $medicalPersonnel->delete();

        return redirect()->route('medical_personnel.index')->with('success', 'Petugas medis berhasil dihapus.');
    }
}
