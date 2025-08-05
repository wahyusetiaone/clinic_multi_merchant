<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Manufacturer;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class ManufacturerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Organization Owner, Admin Clinic, dan Admin Pharmacy bisa melihat daftar pabrik
        return $user->hasAnyRole(['organization_owner', 'admin_clinic', 'admin_pharmacy']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Manufacturer $manufacturer): bool
    {
        // Organization Owner, Admin Clinic, dan Admin Pharmacy bisa melihat detail pabrik
        return $user->hasAnyRole(['organization_owner', 'admin_clinic', 'admin_pharmacy']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Organization Owner, Admin Clinic, dan Admin Pharmacy bisa membuat pabrik baru
        return $user->hasAnyRole(['organization_owner', 'admin_clinic', 'admin_pharmacy']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Manufacturer $manufacturer): bool
    {
        // Organization Owner, Admin Clinic, dan Admin Pharmacy bisa mengupdate pabrik
        return $user->hasAnyRole(['organization_owner', 'admin_clinic', 'admin_pharmacy']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Manufacturer $manufacturer): bool
    {
        // Organization Owner, Admin Clinic, dan Admin Pharmacy bisa menghapus pabrik
        return $user->hasAnyRole(['organization_owner', 'admin_clinic', 'admin_pharmacy']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Manufacturer $manufacturer): bool
    {
        return false; // Umumnya tidak diperlukan untuk master data sederhana
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Manufacturer $manufacturer): bool
    {
        return false; // Umumnya tidak diperlukan untuk master data sederhana
    }
}
