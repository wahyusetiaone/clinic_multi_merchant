<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Label;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class LabelPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Organization Owner, Admin Clinic, dan Admin Pharmacy bisa melihat daftar etiket
        return $user->hasAnyRole(['organization_owner', 'admin_clinic', 'admin_pharmacy']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Label $label): bool
    {
        // Organization Owner, Admin Clinic, dan Admin Pharmacy bisa melihat detail etiket
        return $user->hasAnyRole(['organization_owner', 'admin_clinic', 'admin_pharmacy']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Organization Owner, Admin Clinic, dan Admin Pharmacy bisa membuat etiket baru
        return $user->hasAnyRole(['organization_owner', 'admin_clinic', 'admin_pharmacy']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Label $label): bool
    {
        // Organization Owner, Admin Clinic, dan Admin Pharmacy bisa mengupdate etiket
        return $user->hasAnyRole(['organization_owner', 'admin_clinic', 'admin_pharmacy']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Label $label): bool
    {
        // Organization Owner, Admin Clinic, dan Admin Pharmacy bisa menghapus etiket
        return $user->hasAnyRole(['organization_owner', 'admin_clinic', 'admin_pharmacy']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Label $label): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Label $label): bool
    {
        return false;
    }
}
