<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Branch; // Jangan lupa import model Branch
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Untuk upload file
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth; // Untuk otorisasi berdasarkan user

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // TODO: Implement DoctorPolicy::viewAny untuk otorisasi
        // $this->authorize('viewAny', Doctor::class);

        $user = Auth::user();
        $doctors = collect();

        if ($user->hasRole('super_owner')) {
            $doctors = Doctor::with('branch')->get();
        } elseif ($user->hasRole('organization_owner')) {
            if ($user->ownedOrganization) {
                $branchIds = $user->ownedOrganization->branches->pluck('id');
                $doctors = Doctor::whereIn('branch_id', $branchIds)
                    ->with('branch')
                    ->get();
            }
        } elseif ($user->hasAnyRole(['admin_clinic', 'receptionist'])) { // Asumsi peran ini bisa melihat daftar dokter
            if ($user->employee && $user->employee->organization_id) {
                $organizationId = $user->employee->organization_id;
                $branchIds = Branch::where('organization_id', $organizationId)->pluck('id');
                $doctors = Doctor::whereIn('branch_id', $branchIds)
                    ->with('branch')
                    ->get();
            }
        } else {
            abort(403, 'Anda tidak memiliki izin untuk melihat daftar dokter.');
        }


        return view('doctors.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // TODO: Implement DoctorPolicy::create untuk otorisasi
        // $this->authorize('create', Doctor::class);

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
            abort(403, 'Anda tidak memiliki izin untuk menambah dokter.');
        }

        return view('doctors.create', compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: Implement DoctorPolicy::create untuk otorisasi
        // $this->authorize('create', Doctor::class);

        $request->validate([
            'nik' => 'required|string|max:255|unique:doctors,nik',
            'satusehat_id' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:255',
            'str_number' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255|unique:doctors,username',
            'start_date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'stamp' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $data = $request->except(['photo', 'signature', 'stamp']); // Ambil semua data kecuali file

        // Handle file uploads
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('doctors/photos', 'public');
        }
        if ($request->hasFile('signature')) {
            $data['signature'] = $request->file('signature')->store('doctors/signatures', 'public');
        }
        if ($request->hasFile('stamp')) {
            $data['stamp'] = $request->file('stamp')->store('doctors/stamps', 'public');
        }

        $user = Auth::user();
        $selectedBranch = Branch::find($request->branch_id);

        // Validasi tambahan untuk branch_id
        if ($user->hasRole('organization_owner') && $user->ownedOrganization->id !== $selectedBranch->organization_id) {
            abort(403, 'Anda tidak memiliki izin untuk menambah dokter di cabang ini.');
        }
        if ($user->hasRole('admin_clinic') && ($user->employee && $user->employee->organization_id !== $selectedBranch->organization_id)) {
            abort(403, 'Anda tidak memiliki izin untuk menambah dokter di cabang ini.');
        }

        Doctor::create($data);

        return redirect()->route('doctors.index')->with('success', 'Dokter berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        // TODO: Implement DoctorPolicy::view untuk otorisasi
        // $this->authorize('view', $doctor);

        // Pastikan dokter yang diakses sesuai dengan organisasi/cabang user yang login
        $user = Auth::user();
        if ($user->hasRole('organization_owner') && $user->ownedOrganization->id !== $doctor->branch->organization_id) {
            abort(403, 'Anda tidak memiliki izin untuk melihat detail dokter ini.');
        }
        if ($user->hasRole('admin_clinic') && ($user->employee && $user->employee->organization_id !== $doctor->branch->organization_id)) {
            abort(403, 'Anda tidak memiliki izin untuk melihat detail dokter ini.');
        }

        return view('doctors.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor)
    {
        // TODO: Implement DoctorPolicy::update untuk otorisasi
        // $this->authorize('update', $doctor);

        // Pastikan dokter yang diakses sesuai dengan organisasi/cabang user yang login
        $user = Auth::user();
        if ($user->hasRole('organization_owner') && $user->ownedOrganization->id !== $doctor->branch->organization_id) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit dokter ini.');
        }
        if ($user->hasRole('admin_clinic') && ($user->employee && $user->employee->organization_id !== $doctor->branch->organization_id)) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit dokter ini.');
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
            abort(403, 'Anda tidak memiliki izin untuk mengedit dokter.');
        }

        return view('doctors.edit', compact('doctor', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {
        // TODO: Implement DoctorPolicy::update untuk otorisasi
        // $this->authorize('update', $doctor);

        $request->validate([
            'nik' => ['required', 'string', 'max:255', Rule::unique('doctors', 'nik')->ignore($doctor->id)],
            'satusehat_id' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:255',
            'str_number' => 'nullable|string|max:255',
            'username' => ['nullable', 'string', 'max:255', Rule::unique('doctors', 'username')->ignore($doctor->id)],
            'start_date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'stamp' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $data = $request->except(['photo', 'signature', 'stamp']); // Ambil semua data kecuali file

        // Handle file uploads (dan hapus yang lama jika ada)
        if ($request->hasFile('photo')) {
            if ($doctor->photo) {
                Storage::disk('public')->delete($doctor->photo);
            }
            $data['photo'] = $request->file('photo')->store('doctors/photos', 'public');
        }
        if ($request->hasFile('signature')) {
            if ($doctor->signature) {
                Storage::disk('public')->delete($doctor->signature);
            }
            $data['signature'] = $request->file('signature')->store('doctors/signatures', 'public');
        }
        if ($request->hasFile('stamp')) {
            if ($doctor->stamp) {
                Storage::disk('public')->delete($doctor->stamp);
            }
            $data['stamp'] = $request->file('stamp')->store('doctors/stamps', 'public');
        }

        $user = Auth::user();
        $selectedBranch = Branch::find($request->branch_id);

        // Validasi tambahan untuk branch_id
        if ($user->hasRole('organization_owner') && $user->ownedOrganization->id !== $selectedBranch->organization_id) {
            abort(403, 'Anda tidak memiliki izin untuk mengupdate dokter di cabang ini.');
        }
        if ($user->hasRole('admin_clinic') && ($user->employee && $user->employee->organization_id !== $selectedBranch->organization_id)) {
            abort(403, 'Anda tidak memiliki izin untuk mengupdate dokter di cabang ini.');
        }

        $doctor->update($data);

        return redirect()->route('doctors.index')->with('success', 'Data dokter berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        // TODO: Implement DoctorPolicy::delete untuk otorisasi
        // $this->authorize('delete', $doctor);

        // Pastikan dokter yang diakses sesuai dengan organisasi/cabang user yang login
        $user = Auth::user();
        if ($user->hasRole('organization_owner') && $user->ownedOrganization->id !== $doctor->branch->organization_id) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus dokter ini.');
        }
        if ($user->hasRole('admin_clinic') && ($user->employee && $user->employee->organization_id !== $doctor->branch->organization_id)) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus dokter ini.');
        }


        // Hapus file terkait jika ada
        if ($doctor->photo) {
            Storage::disk('public')->delete($doctor->photo);
        }
        if ($doctor->signature) {
            Storage::disk('public')->delete($doctor->signature);
        }
        if ($doctor->stamp) {
            Storage::disk('public')->delete($doctor->stamp);
        }

        $doctor->delete();

        return redirect()->route('doctors.index')->with('success', 'Dokter berhasil dihapus.');
    }
}
