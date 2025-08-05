<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Warehouse;
use App\Models\Branch; // Import model Branch
use Illuminate\Auth\Access\HandlesAuthorization;

class WarehousePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models (list of warehouses).
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super_owner', 'organization_owner', 'admin_clinic', 'admin_pharmacy']);
    }

    /**
     * Determine whether the user can view the model (single warehouse detail).
     */
    public function view(User $user, Warehouse $warehouse): bool
    {
        // Super Owner bisa melihat gudang manapun
        if ($user->hasRole('super_owner')) {
            return true;
        }

        // Organization Owner bisa melihat gudang di organisasinya
        if ($user->hasRole('organization_owner')) {
            return $user->ownedOrganization && $user->ownedOrganization->id === $warehouse->branch->organization_id;
        }

        // Admin Klinik/Farmasi bisa melihat gudang di organisasi yang sama
        if ($user->hasAnyRole(['admin_clinic', 'admin_pharmacy'])) {
            if ($user->employee && $user->employee->organization_id) {
                $organizationId = $user->employee->organization_id;
                return $warehouse->branch->organization_id === $organizationId;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can create models (new warehouse).
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super_owner', 'organization_owner', 'admin_clinic', 'admin_pharmacy']);
    }

    /**
     * Determine whether the user can update the model (edit warehouse, and manage its locations).
     */
    public function update(User $user, Warehouse $warehouse): bool
    {
        // Super Owner bisa update gudang manapun
        if ($user->hasRole('super_owner')) {
            return true;
        }

        // Organization Owner bisa update gudang di organisasinya
        if ($user->hasRole('organization_owner')) {
            return $user->ownedOrganization && $user->ownedOrganization->id === $warehouse->branch->organization_id;
        }

        // Admin Klinik/Farmasi bisa update gudang di organisasi yang sama
        if ($user->hasAnyRole(['admin_clinic', 'admin_pharmacy'])) {
            if ($user->employee && $user->employee->organization_id) {
                $organizationId = $user->employee->organization_id;
                return $warehouse->branch->organization_id === $organizationId;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model (delete warehouse, which also deletes its locations).
     */
    public function delete(User $user, Warehouse $warehouse): bool
    {
        // Super Owner bisa menghapus gudang manapun
        if ($user->hasRole('super_owner')) {
            return true;
        }

        // Organization Owner bisa menghapus gudang di organisasinya
        if ($user->hasRole('organization_owner')) {
            return $user->ownedOrganization && $user->ownedOrganization->id === $warehouse->branch->organization_id;
        }

        // Admin Klinik/Farmasi bisa menghapus gudang di organisasi yang sama
        if ($user->hasAnyRole(['admin_clinic', 'admin_pharmacy'])) {
            if ($user->employee && $user->employee->organization_id) {
                $organizationId = $user->employee->organization_id;
                return $warehouse->branch->organization_id === $organizationId;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Warehouse $warehouse): bool
    {
        // Implementasi jika Anda menggunakan soft deletes
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Warehouse $warehouse): bool
    {
        // Implementasi jika Anda menggunakan soft deletes
        return false;
    }
}
