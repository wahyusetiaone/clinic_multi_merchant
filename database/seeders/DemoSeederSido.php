<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Organization;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DemoSeederSido extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // --- 1. Create Permissions (Izin) ---
        $permissions = [
            'manage organizations',
            'view organizations',
            'manage branches',
            'view branches',
            'manage employees',
            'view employees',
            'manage positions',
            'view positions',
            'manage users',
            'view users',
            'manage clinic appointments',
            'view clinic appointments',
            'manage pharmacy inventory',
            'view pharmacy inventory',
            'create prescriptions',
            'view medical records',
            'dispense medicine',
            'register patients',
            'view dashboards',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // --- 2. Create Roles (Peran) and Assign Permissions ---

        // Role: Super Owner
        $superOwnerRole = Role::firstOrCreate(['name' => 'super_owner']);
        $superOwnerRole->givePermissionTo(Permission::all()); // Super owner gets all permissions

        // Role: Organization Owner
        $orgOwnerRole = Role::firstOrCreate(['name' => 'organization_owner']);
        $orgOwnerRole->givePermissionTo([
            'view dashboards',
            'manage branches',
            'view branches',
            'manage employees',
            'view employees',
            'manage positions',
            'view positions',
            'view clinic appointments',
            'view pharmacy inventory',
        ]);

        // Role: Admin Klinik
        $adminClinicRole = Role::firstOrCreate(['name' => 'admin_clinic']);
        $adminClinicRole->givePermissionTo([
            'view dashboards',
            'manage clinic appointments',
            'view clinic appointments',
            'manage employees',
            'view employees',
            'register patients',
        ]);

        // Role: Admin Apotek
        $adminPharmacyRole = Role::firstOrCreate(['name' => 'admin_pharmacy']);
        $adminPharmacyRole->givePermissionTo([
            'view dashboards',
            'manage pharmacy inventory',
            'view pharmacy inventory',
            'manage employees',
            'view employees',
            'dispense medicine',
        ]);

        // Role: Dokter
        $doctorRole = Role::firstOrCreate(['name' => 'doctor']);
        $doctorRole->givePermissionTo([
            'view dashboards',
            'view medical records',
            'create prescriptions',
            'view clinic appointments',
        ]);

        // Role: Apoteker
        $pharmacistRole = Role::firstOrCreate(['name' => 'pharmacist']);
        $pharmacistRole->givePermissionTo([
            'view dashboards',
            'dispense medicine',
            'view pharmacy inventory',
        ]);

        // Role: Resepsionis
        $receptionistRole = Role::firstOrCreate(['name' => 'receptionist']);
        $receptionistRole->givePermissionTo([
            'view dashboards',
            'view clinic appointments',
            'register patients',
        ]);

        // --- 3. Create Positions ---
        // Solusi: Cari berdasarkan 'name' yang unik, lalu update/set valid_branch_types
        $posisiDokterUmum = Position::firstOrCreate(
            ['name' => 'Dokter Umum'], // Hanya cari berdasarkan nama
            ['description' => 'Bertanggung jawab atas pemeriksaan dan diagnosis umum.']
        );
        $posisiDokterUmum->valid_branch_types = ['clinic']; // Tetapkan nilai setelahnya
        $posisiDokterUmum->save(); // Simpan perubahan

        $posisiApoteker = Position::firstOrCreate(
            ['name' => 'Apoteker'],
            ['description' => 'Bertanggung jawab atas pengelolaan obat dan pelayanan farmasi.']
        );
        $posisiApoteker->valid_branch_types = ['pharmacy'];
        $posisiApoteker->save();

        $posisiResepsionisKlinik = Position::firstOrCreate(
            ['name' => 'Resepsionis Klinik'],
            ['description' => 'Melayani pendaftaran pasien dan janji temu di klinik.']
        );
        $posisiResepsionisKlinik->valid_branch_types = ['clinic'];
        $posisiResepsionisKlinik->save();

        $posisiResepsionisApotek = Position::firstOrCreate(
            ['name' => 'Resepsionis Apotek'],
            ['description' => 'Melayani pelanggan di apotek.']
        );
        $posisiResepsionisApotek->valid_branch_types = ['pharmacy'];
        $posisiResepsionisApotek->save();

        $posisiAdminOrg = Position::firstOrCreate(
            ['name' => 'Admin Organisasi'],
            ['description' => 'Mengelola operasional tingkat organisasi.']
        );
        $posisiAdminOrg->valid_branch_types = ['clinic', 'pharmacy'];
        $posisiAdminOrg->save();

        // --- 4. Create Super Owner User ---
        $superOwnerUser = User::firstOrCreate(
            ['email' => 'superowner@example.com'],
            [
                'name' => 'Super Owner Demo',
                'password' => Hash::make('password'),
            ]
        );
        $superOwnerUser->assignRole('super_owner');

        // --- 5. Create Organizations, Branches, Users, and Employees ---

        // --- ORGANISASI SIDO MAJU ---
        $orgSidoMajuOwner = User::firstOrCreate(
            ['email' => 'owner.sidomaju@example.com'],
            [
                'name' => 'Owner Sido Maju',
                'password' => Hash::make('password'),
            ]
        );
        $orgSidoMajuOwner->assignRole('organization_owner');

        $orgSidoMaju = Organization::firstOrCreate(
            ['name' => 'Sido Maju'],
            [
                'address' => 'Jl. Merdeka No. 1, Jakarta',
                'phone' => '021-111222',
                'owner_id' => $orgSidoMajuOwner->id,
            ]
        );

        // Cabang Klinik Sido Maju
        $clinicSidoMaju = Branch::firstOrCreate(
            ['name' => 'Klinik Sido Maju Pusat'],
            [
                'organization_id' => $orgSidoMaju->id,
                'type' => 'clinic',
                'address' => 'Jl. Merdeka No. 1A, Jakarta',
                'phone' => '021-111223',
            ]
        );

        // Cabang Apotek Sido Maju
        $pharmacySidoMaju = Branch::firstOrCreate(
            ['name' => 'Apotek Sido Maju Sehat'],
            [
                'organization_id' => $orgSidoMaju->id,
                'type' => 'pharmacy',
                'address' => 'Jl. Merdeka No. 1B, Jakarta',
                'phone' => '021-111224',
            ]
        );

        // Pegawai & User untuk Sido Maju
        // Dokter Sido Maju
        $userDokterSidoMaju = User::firstOrCreate(
            ['email' => 'dokter.sidomaju@example.com'],
            [
                'name' => 'Dr. Budi Sido Maju',
                'password' => Hash::make('password'),
            ]
        );
        $userDokterSidoMaju->assignRole('doctor');
        Employee::firstOrCreate(
            ['user_id' => $userDokterSidoMaju->id],
            [
                'organization_id' => $orgSidoMaju->id,
                'branch_id' => $clinicSidoMaju->id,
                'name' => 'Dr. Budi Sido Maju',
                'nip' => 'D001SM',
                'position_id' => $posisiDokterUmum->id,
                'phone' => '081234567890',
            ]
        );

        // Apoteker Sido Maju
        $userApotekerSidoMaju = User::firstOrCreate(
            ['email' => 'apoteker.sidomaju@example.com'],
            [
                'name' => 'Apt. Siti Sido Maju',
                'password' => Hash::make('password'),
            ]
        );
        $userApotekerSidoMaju->assignRole('pharmacist');
        Employee::firstOrCreate(
            ['user_id' => $userApotekerSidoMaju->id],
            [
                'organization_id' => $orgSidoMaju->id,
                'branch_id' => $pharmacySidoMaju->id,
                'name' => 'Apt. Siti Sido Maju',
                'nip' => 'A001SM',
                'position_id' => $posisiApoteker->id,
                'phone' => '081234567891',
            ]
        );

        // Resepsionis Klinik Sido Maju
        $userResepsionisKlinikSM = User::firstOrCreate(
            ['email' => 'resepsionis.kliniksm@example.com'],
            [
                'name' => 'Rina Resepsionis Klinik SM',
                'password' => Hash::make('password'),
            ]
        );
        $userResepsionisKlinikSM->assignRole('receptionist');
        Employee::firstOrCreate(
            ['user_id' => $userResepsionisKlinikSM->id],
            [
                'organization_id' => $orgSidoMaju->id,
                'branch_id' => $clinicSidoMaju->id,
                'name' => 'Rina Resepsionis Klinik SM',
                'nip' => 'R001SM',
                'position_id' => $posisiResepsionisKlinik->id,
                'phone' => '081234567892',
            ]
        );

        // --- ORGANISASI SIDO DADI ---
        $orgSidoDadiOwner = User::firstOrCreate(
            ['email' => 'owner.sidodadi@example.com'],
            [
                'name' => 'Owner Sido Dadi',
                'password' => Hash::make('password'),
            ]
        );
        $orgSidoDadiOwner->assignRole('organization_owner');

        $orgSidoDadi = Organization::firstOrCreate(
            ['name' => 'Sido Dadi'],
            [
                'address' => 'Jl. Sudirman No. 2, Surabaya',
                'phone' => '031-333444',
                'owner_id' => $orgSidoDadiOwner->id,
            ]
        );

        // Cabang Klinik Sido Dadi
        $clinicSidoDadi = Branch::firstOrCreate(
            ['name' => 'Klinik Sido Dadi Selatan'],
            [
                'organization_id' => $orgSidoDadi->id,
                'type' => 'clinic',
                'address' => 'Jl. Sudirman No. 2A, Surabaya',
                'phone' => '031-333445',
            ]
        );

        // Cabang Apotek Sido Dadi
        $pharmacySidoDadi = Branch::firstOrCreate(
            ['name' => 'Apotek Sido Dadi Mandiri'],
            [
                'organization_id' => $orgSidoDadi->id,
                'type' => 'pharmacy',
                'address' => 'Jl. Sudirman No. 2B, Surabaya',
                'phone' => '031-333446',
            ]
        );

        // Pegawai & User untuk Sido Dadi
        // Dokter Sido Dadi
        $userDokterSidoDadi = User::firstOrCreate(
            ['email' => 'dokter.sidodadi@example.com'],
            [
                'name' => 'Dr. Ayu Sido Dadi',
                'password' => Hash::make('password'),
            ]
        );
        $userDokterSidoDadi->assignRole('doctor');
        Employee::firstOrCreate(
            ['user_id' => $userDokterSidoDadi->id],
            [
                'organization_id' => $orgSidoDadi->id,
                'branch_id' => $clinicSidoDadi->id,
                'name' => 'Dr. Ayu Sido Dadi',
                'nip' => 'D001SD',
                'position_id' => $posisiDokterUmum->id,
                'phone' => '081234567893',
            ]
        );

        // Apoteker Sido Dadi
        $userApotekerSidoDadi = User::firstOrCreate(
            ['email' => 'apoteker.sidodadi@example.com'],
            [
                'name' => 'Apt. Bayu Sido Dadi',
                'password' => Hash::make('password'),
            ]
        );
        $userApotekerSidoDadi->assignRole('pharmacist');
        Employee::firstOrCreate(
            ['user_id' => $userApotekerSidoDadi->id],
            [
                'organization_id' => $orgSidoDadi->id,
                'branch_id' => $pharmacySidoDadi->id,
                'name' => 'Apt. Bayu Sido Dadi',
                'nip' => 'A001SD',
                'position_id' => $posisiApoteker->id,
                'phone' => '081234567894',
            ]
        );

        // Admin Organisasi Sido Dadi (contoh user yang tidak terikat cabang spesifik, tapi ke organisasi)
        $userAdminOrgSD = User::firstOrCreate(
            ['email' => 'admin.sidodadi@example.com'],
            [
                'name' => 'Admin Org Sido Dadi',
                'password' => Hash::make('password'),
            ]
        );
        // Karena ini admin organisasi, dia mungkin punya peran khusus, atau dia bisa mendapatkan peran organization_owner
        // Mari kita beri dia peran organization_owner agar bisa mengelola semua di bawah Sido Dadi
        $userAdminOrgSD->assignRole('organization_owner');
        Employee::firstOrCreate(
            ['user_id' => $userAdminOrgSD->id],
            [
                'organization_id' => $orgSidoDadi->id,
                'branch_id' => null, // Tidak terikat ke cabang spesifik
                'name' => 'Admin Organisasi Sido Dadi',
                'nip' => 'ADM001SD',
                'position_id' => $posisiAdminOrg->id,
                'phone' => '081234567895',
            ]
        );


        $this->command->info('Demo data for Sido Maju and Sido Dadi organizations, branches, positions, users, and employees seeded successfully!');
    }
}
