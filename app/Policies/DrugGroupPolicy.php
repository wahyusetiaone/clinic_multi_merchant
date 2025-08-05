<?php

namespace App\Policies;

use App\Models\User;
use App\Models\DrugGroup;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class DrugGroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Organization Owner, Admin Clinic, dan Admin Pharmacy bisa melihat daftar golongan obat
        return $user->hasAnyRole(['organization_owner', 'admin_clinic', 'admin_pharmacy']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DrugGroup $drugGroup): bool
    {
        // Organization Owner, Admin Clinic, dan Admin Pharmacy bisa melihat detail golongan obat
        return $user->hasAnyRole(['organization_owner', 'admin_clinic', 'admin_pharmacy']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Organization Owner, Admin Clinic, dan Admin Pharmacy bisa membuat golongan obat baru
        return $user->hasAnyRole(['organization_owner', 'admin_clinic', 'admin_pharmacy']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DrugGroup $drugGroup): bool
    {
        // Organization Owner, Admin Clinic, dan Admin Pharmacy bisa mengupdate golongan obat
        return $user->hasAnyRole(['organization_owner', 'admin_clinic', 'admin_pharmacy']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DrugGroup $drugGroup): bool
    {
        // Organization Owner, Admin Clinic, dan Admin Pharmacy bisa menghapus golongan obat
        return $user->hasAnyRole(['organization_owner', 'admin_clinic', 'admin_pharmacy']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DrugGroup $drugGroup): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DrugGroup $drugGroup): bool
    {
        return false;
    }
}
