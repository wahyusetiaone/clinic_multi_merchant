<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Organization;
use App\Models\Branch;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Memanggil policy 'viewAny'
        $this->authorize('viewAny', Employee::class);

        // Filter pegawai berdasarkan organisasi user yang login
        $user = Auth::user();
        $employees = collect(); // Default empty collection

        if ($user->hasRole('super_owner')) {
            // Super Owner bisa melihat semua pegawai
            $employees = Employee::with(['organization', 'branch', 'position', 'user'])->get();
        } elseif ($user->hasRole('organization_owner')) {
            // Organization Owner hanya melihat pegawai di organisasinya
            if ($user->ownedOrganization) {
                $employees = $user->ownedOrganization->employees()->with(['organization', 'branch', 'position', 'user'])->get();
            }
        } elseif ($user->hasAnyRole(['admin_clinic', 'receptionist'])) {
            // Admin Klinik/Resepsionis melihat pegawai di organisasi yang sama
            if ($user->employee && $user->employee->organization_id) {
                $employees = Employee::where('organization_id', $user->employee->organization_id)
                    ->with(['organization', 'branch', 'position', 'user'])
                    ->get();
            }
        }

        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Memanggil policy 'create'
        $this->authorize('create', Employee::class);

        // Filter organisasi dan cabang yang bisa dipilih berdasarkan peran user
        $user = Auth::user();
        $organizations = collect();
        $branches = collect();
        $positions = Position::all(); // Semua posisi, nanti di view bisa difilter JS jika perlu

        if ($user->hasRole('super_owner')) {
            $organizations = Organization::all();
            $branches = Branch::all();
        } elseif ($user->hasRole('organization_owner')) {
            if ($user->ownedOrganization) {
                $organizations = collect([$user->ownedOrganization]);
                $branches = $user->ownedOrganization->branches;
            }
        } elseif ($user->hasRole('admin_clinic')) {
            // Admin clinic hanya bisa create employee di organisasinya dan mungkin di cabangnya
            if ($user->employee && $user->employee->organization_id) {
                $organizations = collect([$user->employee->organization]);
                $branches = $user->employee->organization->branches; // Atau hanya $user->employee->branch jika terbatas ke satu cabang
            }
        } else {
            // Jika tidak ada peran yang diizinkan, ini akan di-block oleh Policy
            abort(403, 'Anda tidak memiliki izin untuk menambah pegawai.');
        }

        return view('employees.create', compact('organizations', 'branches', 'positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Memanggil policy 'create'
        $this->authorize('create', Employee::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'organization_id' => 'required|exists:organizations,id',
            'branch_id' => 'nullable|exists:branches,id',
            'position_id' => 'required|exists:positions,id',
            'nip' => 'nullable|string|max:255|unique:employees,nip',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'email' => 'nullable|string|email|max:255|unique:users,email', // Email untuk user account
            'password' => 'nullable|string|min:8|confirmed', // Password untuk user account
            'role' => 'nullable|string|exists:roles,name', // Role Spatie
        ]);

        $user = Auth::user();
        $organizationId = $request->organization_id;

        // Validasi tambahan: Pastikan user hanya bisa menambah pegawai di organisasinya sendiri
        if ($user->hasRole('organization_owner') && $user->ownedOrganization->id !== (int)$organizationId) {
            abort(403, 'Anda tidak memiliki izin untuk menambah pegawai di organisasi ini.');
        }
        if ($user->hasRole('admin_clinic') && $user->employee->organization_id !== (int)$organizationId) {
            abort(403, 'Anda tidak memiliki izin untuk menambah pegawai di organisasi ini.');
        }

        $newUser = null;
        if ($request->filled('email') && $request->filled('password')) {
            $newUser = User::create([
                'name' => $request->name, // Bisa pakai nama pegawai
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            if ($request->filled('role')) {
                $newUser->assignRole($request->role);
            }
        }

        Employee::create([
            'name' => $request->name,
            'organization_id' => $organizationId,
            'branch_id' => $request->branch_id,
            'user_id' => $newUser ? $newUser->id : null,
            'position_id' => $request->position_id,
            'nip' => $request->nip,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('employees.index')->with('success', 'Pegawai berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        // Memanggil policy 'view'
        $this->authorize('view', $employee);

        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        // Memanggil policy 'update'
        $this->authorize('update', $employee);

        $user = Auth::user();
        $organizations = collect();
        $branches = collect();
        $positions = Position::all(); // Semua posisi

        if ($user->hasRole('super_owner')) {
            $organizations = Organization::all();
            $branches = Branch::all();
        } elseif ($user->hasRole('organization_owner')) {
            if ($user->ownedOrganization) {
                $organizations = collect([$user->ownedOrganization]);
                $branches = $user->ownedOrganization->branches;
            }
        } elseif ($user->hasRole('admin_clinic')) {
            if ($user->employee && $user->employee->organization_id) {
                $organizations = collect([$user->employee->organization]);
                $branches = $user->employee->organization->branches;
            }
        } else {
            // Jika tidak ada peran yang diizinkan, ini akan di-block oleh Policy
            abort(403, 'Anda tidak memiliki izin untuk mengedit pegawai.');
        }

        return view('employees.edit', compact('employee', 'organizations', 'branches', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        // Memanggil policy 'update'
        $this->authorize('update', $employee);

        $request->validate([
            'name' => 'required|string|max:255',
            'organization_id' => 'required|exists:organizations,id',
            'branch_id' => 'nullable|exists:branches,id',
            'position_id' => 'required|exists:positions,id',
            'nip' => ['nullable', 'string', 'max:255', Rule::unique('employees', 'nip')->ignore($employee->id)],
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($employee->user_id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'nullable|string|exists:roles,name',
        ]);

        $user = Auth::user();
        $organizationId = $request->organization_id;

        // Validasi tambahan: Pastikan user hanya bisa mengupdate pegawai di organisasinya sendiri
        if ($user->hasRole('organization_owner') && $user->ownedOrganization->id !== (int)$organizationId) {
            abort(403, 'Anda tidak memiliki izin untuk mengupdate pegawai di organisasi ini.');
        }
        if ($user->hasRole('admin_clinic') && $user->employee->organization_id !== (int)$organizationId) {
            abort(403, 'Anda tidak memiliki izin untuk mengupdate pegawai di organisasi ini.');
        }

        $employee->update([
            'name' => $request->name,
            'organization_id' => $organizationId,
            'branch_id' => $request->branch_id,
            'position_id' => $request->position_id,
            'nip' => $request->nip,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Update atau buat User account jika email/password diisi
        if ($request->filled('email')) {
            $userAccount = $employee->user;
            if (!$userAccount) {
                $userAccount = new User();
                $userAccount->name = $request->name;
            }
            $userAccount->email = $request->email;
            if ($request->filled('password')) {
                $userAccount->password = Hash::make($request->password);
            }
            $userAccount->save();

            // Assign/Sync role
            if ($request->filled('role')) {
                $userAccount->syncRoles([$request->role]);
            } else {
                $userAccount->syncRoles([]); // Remove all roles if no role selected
            }

            $employee->user_id = $userAccount->id;
            $employee->save();
        } else {
            // Jika email dikosongkan, hapus user account dan roles jika ada
            if ($employee->user) {
                $employee->user->syncRoles([]); // Hapus semua peran
                $employee->user->delete(); // Hapus user account
                $employee->user_id = null;
                $employee->save();
            }
        }


        return redirect()->route('employees.index')->with('success', 'Pegawai berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        // Memanggil policy 'delete'
        $this->authorize('delete', $employee);

        // Hapus juga user account yang terkait jika ada
        if ($employee->user) {
            $employee->user->syncRoles([]); // Hapus semua peran terkait user
            $employee->user->delete();
        }

        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Pegawai berhasil dihapus.');
    }
}
