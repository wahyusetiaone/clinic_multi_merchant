<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MedicalPersonnel; // Import model MedicalPersonnel
use App\Models\Branch; // Import model Branch untuk relasi
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class MedicalPersonnelPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models (list of medical personnel).
     */
    public function viewAny(User $user): bool
    {
        // Super Owner selalu bisa melihat semua
        if ($user->hasRole('super_owner')) {
            return true;
        }

        // organization_owner, admin_clinic, dan receptionist bisa melihat daftar petugas medis
        return $user->hasAnyRole(['organization_owner', 'admin_clinic', 'receptionist']);
    }

    /**
     * Determine whether the user can view the model (single medical personnel detail).
     */
    public function view(User $user, MedicalPersonnel $medicalPersonnel): bool
    {
        // Super Owner selalu bisa melihat
        if ($user->hasRole('super_owner')) {
            return true;
        }

        // organization_owner bisa melihat petugas medis di organisasinya
        if ($user->hasRole('organization_owner')) {
            return $user->ownedOrganization && $user->ownedOrganization->id === $medicalPersonnel->branch->organization_id;
        }

        // admin_clinic dan receptionist bisa melihat petugas medis di organisasi yang sama
        if ($user->hasAnyRole(['admin_clinic', 'receptionist'])) {
            if ($user->employee && $user->employee->organization_id === $medicalPersonnel->branch->organization_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can create models (create a new medical personnel).
     */
    public function create(User $user): bool
    {
        // Super Owner, organization_owner, dan admin_clinic bisa membuat petugas medis baru
        return $user->hasAnyRole(['super_owner', 'organization_owner', 'admin_clinic']);
    }

    /**
     * Determine whether the user can update the model (update a medical personnel).
     */
    public function update(User $user, MedicalPersonnel $medicalPersonnel): bool
    {
        // Super Owner selalu bisa mengupdate
        if ($user->hasRole('super_owner')) {
            return true;
        }

        // organization_owner bisa mengupdate petugas medis di organisasinya
        if ($user->hasRole('organization_owner')) {
            return $user->ownedOrganization && $user->ownedOrganization->id === $medicalPersonnel->branch->organization_id;
        }

        // admin_clinic bisa mengupdate petugas medis di organisasinya
        if ($user->hasRole('admin_clinic')) {
            if ($user->employee && $user->employee->organization_id === $medicalPersonnel->branch->organization_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model (delete a medical personnel).
     */
    public function delete(User $user, MedicalPersonnel $medicalPersonnel): bool
    {
        // Super Owner selalu bisa menghapus
        if ($user->hasRole('super_owner')) {
            return true;
        }

        // organization_owner bisa menghapus petugas medis di organisasinya
        if ($user->hasRole('organization_owner')) {
            return $user->ownedOrganization && $user->ownedOrganization->id === $medicalPersonnel->branch->organization_id;
        }

        // admin_clinic bisa menghapus petugas medis di organisasinya
        if ($user->hasRole('admin_clinic')) {
            if ($user->employee && $user->employee->organization_id === $medicalPersonnel->branch->organization_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MedicalPersonnel $medicalPersonnel): bool
    {
        // Asumsi restore hanya untuk owner/admin pada organisasinya
        if ($user->hasRole('super_owner')) {
            return true;
        }
        return $user->hasAnyRole(['organization_owner', 'admin_clinic']) && $user->ownedOrganization && $user->ownedOrganization->id === $medicalPersonnel->branch->organization_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MedicalPersonnel $medicalPersonnel): bool
    {
        // Asumsi force delete hanya untuk owner/admin pada organisasinya
        if ($user->hasRole('super_owner')) {
            return true;
        }
        return $user->hasAnyRole(['organization_owner', 'admin_clinic']) && $user->ownedOrganization && $user->ownedOrganization->id === $medicalPersonnel->branch->organization_id;
    }
}
