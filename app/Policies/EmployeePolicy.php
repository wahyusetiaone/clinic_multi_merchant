<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Employee;

class EmployeePolicy
{
    /**
     * Determine whether the user can view any models.
     * (Untuk daftar semua pegawai)
     */
    public function viewAny(User $user): bool
    {
        // organization_owner, admin_clinic, dan receptionist bisa melihat daftar pegawai
        return $user->hasAnyRole(['organization_owner', 'admin_clinic', 'receptionist']);
    }

    /**
     * Determine whether the user can view the model.
     * (Untuk melihat detail satu pegawai)
     */
    public function view(User $user, Employee $employee): bool
    {
        // organization_owner bisa melihat semua pegawai di organisasinya
        if ($user->hasRole('organization_owner')) {
            return $user->ownedOrganization && $user->ownedOrganization->id === $employee->organization_id;
        }

        // admin_clinic dan receptionist bisa melihat pegawai di organisasi yang sama
        // Dan, jika pegawai tersebut terasosiasi dengan cabang yang sama (opsional, tergantung kebutuhan)
        if ($user->hasAnyRole(['admin_clinic', 'receptionist'])) {
            // Asumsi user admin/resepsionis juga terhubung ke employee dan organisasi
            if ($user->employee && $user->employee->organization_id === $employee->organization_id) {
                // Opsional: Jika Anda ingin membatasi hanya pegawai di cabang yang sama
                // return $user->employee->branch_id === $employee->branch_id;
                return true; // Saat ini hanya membatasi di organisasi yang sama
            }
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     * (Hanya owner/admin yang bisa menambah pegawai baru)
     */
    public function create(User $user): bool
    {
        // organization_owner dan admin_clinic bisa membuat pegawai baru
        return $user->hasAnyRole(['organization_owner', 'admin_clinic']);
    }

    /**
     * Determine whether the user can update the model.
     * (Hanya owner/admin yang bisa mengupdate pegawai)
     */
    public function update(User $user, Employee $employee): bool
    {
        // organization_owner bisa update pegawai di organisasinya
        if ($user->hasRole('organization_owner')) {
            return $user->ownedOrganization && $user->ownedOrganization->id === $employee->organization_id;
        }

        // admin_clinic bisa update pegawai di organisasinya (dan mungkin cabang yang sama)
        if ($user->hasRole('admin_clinic')) {
            if ($user->employee && $user->employee->organization_id === $employee->organization_id) {
                // Opsional: Jika Anda ingin membatasi hanya pegawai di cabang yang sama untuk admin_clinic
                // return $user->employee->branch_id === $employee->branch_id;
                return true; // Saat ini hanya membatasi di organisasi yang sama
            }
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     * (Hanya owner/admin yang bisa menghapus pegawai)
     */
    public function delete(User $user, Employee $employee): bool
    {
        // organization_owner bisa menghapus pegawai di organisasinya
        if ($user->hasRole('organization_owner')) {
            return $user->ownedOrganization && $user->ownedOrganization->id === $employee->organization_id;
        }

        // admin_clinic bisa menghapus pegawai di organisasinya (dan mungkin cabang yang sama)
        if ($user->hasRole('admin_clinic')) {
            if ($user->employee && $user->employee->organization_id === $employee->organization_id) {
                // Opsional: Jika Anda ingin membatasi hanya pegawai di cabang yang sama untuk admin_clinic
                // return $user->employee->branch_id === $employee->branch_id;
                return true; // Saat ini hanya membatasi di organisasi yang sama
            }
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model. (Opsional, jika ada soft delete)
     */
    public function restore(User $user, Employee $employee): bool
    {
        // Hanya owner/admin
        return $user->hasAnyRole(['organization_owner', 'admin_clinic']) && $user->ownedOrganization && $user->ownedOrganization->id === $employee->organization_id;
    }

    /**
     * Determine whether the user can permanently delete the model. (Opsional)
     */
    public function forceDelete(User $user, Employee $employee): bool
    {
        // Hanya owner/admin
        return $user->hasAnyRole(['organization_owner', 'admin_clinic']) && $user->ownedOrganization && $user->ownedOrganization->id === $employee->organization_id;
    }
}
