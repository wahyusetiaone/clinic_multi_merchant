<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Drug;
use App\Models\Branch; // Import model Branch
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class DrugPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models (list of drugs).
     */
    public function viewAny(User $user): bool
    {
        // Super Owner, Organization Owner, Admin Klinik, dan Admin Farmasi bisa melihat daftar obat
        return $user->hasAnyRole(['super_owner', 'organization_owner', 'admin_clinic', 'admin_pharmacy']);
    }

    /**
     * Determine whether the user can view the model (single drug detail).
     */
    public function view(User $user, Drug $drug): bool
    {
        // Super Owner bisa melihat obat manapun
        if ($user->hasRole('super_owner')) {
            return true;
        }

        // Organization Owner bisa melihat obat di organisasinya
        if ($user->hasRole('organization_owner')) {
            return $user->ownedOrganization && $user->ownedOrganization->id === $drug->branch->organization_id;
        }

        // Admin Klinik/Farmasi bisa melihat obat di organisasi yang sama
        if ($user->hasAnyRole(['admin_clinic', 'admin_pharmacy'])) {
            if ($user->employee && $user->employee->organization_id) {
                $organizationId = $user->employee->organization_id;
                return $drug->branch->organization_id === $organizationId;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can create models (create a new drug).
     */
    public function create(User $user): bool
    {
        // Hanya Organization Owner, Admin Klinik, dan Admin Farmasi yang bisa membuat obat
        return $user->hasAnyRole(['organization_owner', 'admin_clinic', 'admin_pharmacy']);
    }

    /**
     * Determine whether the user can update the model (edit a drug).
     */
    public function update(User $user, Drug $drug): bool
    {
        // Super Owner bisa mengupdate obat manapun
        if ($user->hasRole('super_owner')) {
            return true;
        }

        // Organization Owner bisa mengupdate obat di organisasinya
        if ($user->hasRole('organization_owner')) {
            return $user->ownedOrganization && $user->ownedOrganization->id === $drug->branch->organization_id;
        }

        // Admin Klinik/Farmasi bisa mengupdate obat di organisasi yang sama
        if ($user->hasAnyRole(['admin_clinic', 'admin_pharmacy'])) {
            if ($user->employee && $user->employee->organization_id) {
                $organizationId = $user->employee->organization_id;
                return $drug->branch->organization_id === $organizationId;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model (delete a drug).
     */
    public function delete(User $user, Drug $drug): bool
    {
        // Super Owner bisa menghapus obat manapun
        if ($user->hasRole('super_owner')) {
            return true;
        }

        // Organization Owner bisa menghapus obat di organisasinya
        if ($user->hasRole('organization_owner')) {
            return $user->ownedOrganization && $user->ownedOrganization->id === $drug->branch->organization_id;
        }

        // Admin Klinik/Farmasi bisa menghapus obat di organisasi yang sama
        if ($user->hasAnyRole(['admin_clinic', 'admin_pharmacy'])) {
            if ($user->employee && $user->employee->organization_id) {
                $organizationId = $user->employee->organization_id;
                return $drug->branch->organization_id === $organizationId;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     * (Biasanya untuk soft deletes, jika tidak digunakan, bisa biarkan false)
     */
    public function restore(User $user, Drug $drug): bool
    {
        return false; // Sesuaikan jika Anda mengimplementasikan soft deletes
    }

    /**
     * Determine whether the user can permanently delete the model.
     * (Biasanya untuk soft deletes, jika tidak digunakan, bisa biarkan false)
     */
    public function forceDelete(User $user, Drug $drug): bool
    {
        return false; // Sesuaikan jika Anda mengimplementasikan soft deletes
    }
}
