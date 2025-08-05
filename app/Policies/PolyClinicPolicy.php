<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PolyClinic;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class PolyClinicPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models (list of polyclinics).
     */
    public function viewAny(User $user): bool
    {
        // Semua peran terkait klinik bisa melihat daftar poli
        return $user->hasAnyRole(['organization_owner', 'admin_clinic', 'receptionist', 'doctor', 'pharmacist']);
        // Menambahkan doctor dan pharmacist jika mereka juga perlu melihat daftar poli. Sesuaikan jika tidak.
    }

    /**
     * Determine whether the user can view the model (single polyclinic detail).
     */
    public function view(User $user, PolyClinic $polyclinic): bool
    {
        // organization_owner bisa melihat semua poli di organisasinya
        if ($user->hasRole('organization_owner')) {
            return $user->ownedOrganization && $user->ownedOrganization->id === $polyclinic->branch->organization_id;
        }

        // admin_clinic, receptionist, doctor, pharmacist bisa melihat poli di organisasi yang sama
        if ($user->hasAnyRole(['admin_clinic', 'receptionist', 'doctor', 'pharmacist'])) {
            if ($user->employee && $user->employee->organization_id === $polyclinic->branch->organization_id) {
                // Opsional: Jika Anda ingin membatasi hanya poli di cabang yang sama
                // return $user->employee->branch_id === $polyclinic->branch_id;
                return true; // Saat ini hanya membatasi di organisasi yang sama
            }
        }

        return false;
    }

    /**
     * Determine whether the user can create models (add new polyclinic).
     */
    public function create(User $user): bool
    {
        // organization_owner dan admin_clinic bisa membuat poli baru
        return $user->hasAnyRole(['organization_owner', 'admin_clinic']);
    }

    /**
     * Determine whether the user can update the model (edit polyclinic).
     */
    public function update(User $user, PolyClinic $polyclinic): bool
    {
        // organization_owner bisa update poli di organisasinya
        if ($user->hasRole('organization_owner')) {
            return $user->ownedOrganization && $user->ownedOrganization->id === $polyclinic->branch->organization_id;
        }

        // admin_clinic bisa update poli di organisasinya
        if ($user->hasRole('admin_clinic')) {
            if ($user->employee && $user->employee->organization_id === $polyclinic->branch->organization_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model (delete polyclinic).
     */
    public function delete(User $user, PolyClinic $polyclinic): bool
    {
        // organization_owner bisa menghapus poli di organisasinya
        if ($user->hasRole('organization_owner')) {
            return $user->ownedOrganization && $user->ownedOrganization->id === $polyclinic->branch->organization_id;
        }

        // admin_clinic bisa menghapus poli di organisasinya
        if ($user->hasRole('admin_clinic')) {
            if ($user->employee && $user->employee->organization_id === $polyclinic->branch->organization_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PolyClinic $polyclinic): bool
    {
        // Hanya owner/admin
        return $user->hasAnyRole(['organization_owner', 'admin_clinic']) && $user->ownedOrganization && $user->ownedOrganization->id === $polyclinic->branch->organization_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PolyClinic $polyclinic): bool
    {
        // Hanya owner/admin
        return $user->hasAnyRole(['organization_owner', 'admin_clinic']) && $user->ownedOrganization && $user->ownedOrganization->id === $polyclinic->branch->organization_id;
    }
}
