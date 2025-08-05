<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Doctor; // Import model Doctor
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class DoctorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models (list of doctors).
     */
    public function viewAny(User $user): bool
    {
        // Super Owner selalu bisa melihat semua
        if ($user->hasRole('super_owner')) {
            return true;
        }

        // organization_owner, admin_clinic, dan receptionist bisa melihat daftar dokter
        // Anda bisa menambahkan 'doctor' jika dokter juga perlu melihat daftar dokter lain
        return $user->hasAnyRole(['organization_owner', 'admin_clinic', 'receptionist']);
    }

    /**
     * Determine whether the user can view the model (single doctor detail).
     */
    public function view(User $user, Doctor $doctor): bool
    {
        // Super Owner selalu bisa melihat
        if ($user->hasRole('super_owner')) {
            return true;
        }

        // organization_owner bisa melihat dokter di organisasinya
        if ($user->hasRole('organization_owner')) {
            return $user->ownedOrganization && $user->ownedOrganization->id === $doctor->branch->organization_id;
        }

        // admin_clinic dan receptionist bisa melihat dokter di organisasi yang sama
        if ($user->hasAnyRole(['admin_clinic', 'receptionist'])) {
            if ($user->employee && $user->employee->organization_id === $doctor->branch->organization_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can create models (create a new doctor).
     */
    public function create(User $user): bool
    {
        // Super Owner, organization_owner, dan admin_clinic bisa membuat dokter baru
        return $user->hasAnyRole(['super_owner', 'organization_owner', 'admin_clinic']);
    }

    /**
     * Determine whether the user can update the model (update a doctor).
     */
    public function update(User $user, Doctor $doctor): bool
    {
        // Super Owner selalu bisa mengupdate
        if ($user->hasRole('super_owner')) {
            return true;
        }

        // organization_owner bisa mengupdate dokter di organisasinya
        if ($user->hasRole('organization_owner')) {
            return $user->ownedOrganization && $user->ownedOrganization->id === $doctor->branch->organization_id;
        }

        // admin_clinic bisa mengupdate dokter di organisasinya
        if ($user->hasRole('admin_clinic')) {
            if ($user->employee && $user->employee->organization_id === $doctor->branch->organization_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model (delete a doctor).
     */
    public function delete(User $user, Doctor $doctor): bool
    {
        // Super Owner selalu bisa menghapus
        if ($user->hasRole('super_owner')) {
            return true;
        }

        // organization_owner bisa menghapus dokter di organisasinya
        if ($user->hasRole('organization_owner')) {
            return $user->ownedOrganization && $user->ownedOrganization->id === $doctor->branch->organization_id;
        }

        // admin_clinic bisa menghapus dokter di organisasinya
        if ($user->hasRole('admin_clinic')) {
            if ($user->employee && $user->employee->organization_id === $doctor->branch->organization_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Doctor $doctor): bool
    {
        // Asumsi restore hanya untuk owner/admin pada organisasinya
        if ($user->hasRole('super_owner')) {
            return true;
        }
        return $user->hasAnyRole(['organization_owner', 'admin_clinic']) && $user->ownedOrganization && $user->ownedOrganization->id === $doctor->branch->organization_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Doctor $doctor): bool
    {
        // Asumsi force delete hanya untuk owner/admin pada organisasinya
        if ($user->hasRole('super_owner')) {
            return true;
        }
        return $user->hasAnyRole(['organization_owner', 'admin_clinic']) && $user->ownedOrganization && $user->ownedOrganization->id === $doctor->branch->organization_id;
    }
}
