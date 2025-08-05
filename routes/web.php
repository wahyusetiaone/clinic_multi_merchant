<?php

use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DrugCategoryController;
use App\Http\Controllers\DrugController;
use App\Http\Controllers\DrugGroupController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\MedicalPersonnelController;
use App\Http\Controllers\PolyClinicController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth; // Penting: untuk Auth::user()

// Rute untuk Tamu (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/signin', [AuthController::class, 'showLoginForm'])->name('signin');
    Route::post('/signin', [AuthController::class, 'login']);

    Route::get('/signup', [AuthController::class, 'showRegistrationForm'])->name('signup');
    Route::post('/signup', [AuthController::class, 'register']);
});

// Rute untuk Pengguna Terotentikasi
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard Utama yang akan mengalihkan berdasarkan peran
    Route::get('/dashboard', function () {
        // Logika pengalihan ke dashboard spesifik berdasarkan peran
        $user = Auth::user();

        if ($user->hasRole('super_owner')) {
            return redirect()->route('super_owner.dashboard');
        } elseif ($user->hasRole('organization_owner')) {
            return redirect()->route('organization_owner.dashboard');
        } elseif ($user->hasRole('admin_clinic')) {
            return redirect()->route('admin_clinic.dashboard');
        } elseif ($user->hasRole('admin_pharmacy')) {
            return redirect()->route('admin_pharmacy.dashboard');
        } elseif ($user->hasRole('doctor')) {
            return redirect()->route('doctor.dashboard');
        } elseif ($user->hasRole('pharmacist')) {
            return redirect()->route('pharmacist.dashboard');
        } elseif ($user->hasRole('receptionist')) {
            return redirect()->route('receptionist.dashboard');
        } else {
            // Default fallback jika tidak ada peran yang cocok
            return view('404');
        }
    })->name('dashboard');

    // Rute-rute Dashboard Spesifik Berdasarkan Peran
    // Super Owner
    Route::middleware(['role:super_owner'])->group(function () {
        Route::get('/super-owner/dashboard', function () {
            return view('super_owner.dashboard.index');
        })->name('super_owner.dashboard')->middleware('permission:view dashboards');
        // Tambahkan rute manajemen organisasi di sini
        // Route::resource('organizations', OrganizationController::class);
    });

    // Organization Owner
    Route::middleware('role:organization_owner')->group(function () {
        Route::get('/organization/dashboard', function () {
            return view('organization_owner.dashboard.index');
        })->name('organization_owner.dashboard');
        // Rute untuk mengelola cabang, pegawai, dll. di bawah organisasi mereka
        // Route::resource('branches', BranchController::class);
        // Route::resource('employees', EmployeeController::class);
    });

    // Admin Klinik
    Route::middleware('role:admin_clinic')->group(function () {
        Route::get('/clinic/dashboard', function () {
            return view('admin_clinic.dashboard.index');
        })->name('admin_clinic.dashboard');
        // Rute manajemen janji temu, pasien klinik
    });

    // Admin Apotek
    Route::middleware('role:admin_pharmacy')->group(function () {
        Route::get('/pharmacy/dashboard', function () {
            return view('admin_pharmacy.dashboard.index');
        })->name('admin_pharmacy.dashboard');
        // Rute manajemen inventaris apotek, penjualan
    });

    // Dokter
    Route::middleware('role:doctor')->group(function () {
        Route::get('/doctor/dashboard', function () {
            return view('doctor.dashboard.index');
        })->name('doctor.dashboard');
        // Rute melihat rekam medis, membuat resep
    });

    // Apoteker
    Route::middleware('role:pharmacist')->group(function () {
        Route::get('/pharmacist/dashboard', function () {
            return view('pharmacist.dashboard.index');
        })->name('pharmacist.dashboard');
        // Rute untuk melayani resep, mengelola stok obat
    });

    // Resepsionis
    Route::middleware('role:receptionist')->group(function () {
        Route::get('/receptionist/dashboard', function () {
            return view('receptionist.dashboard.index');
        })->name('receptionist.dashboard');
        // Rute untuk pendaftaran pasien, penjadwalan janji temu
    });

    // --- Rute CRUD Employee ---
    // Gunakan middleware role_or_permission untuk memberi akses dasar ke rute
    // organization_owner, admin_clinic, dan receptionist
    Route::middleware('role_or_permission:organization_owner|admin_clinic|receptionist')->group(function () {
        // Resource controller untuk Employee
        Route::resource('employees', EmployeeController::class);
    });

    // --- Rute CRUD Poly Clinic ---
    // organization_owner, admin_clinic, receptionist, doctor, pharmacist
    Route::middleware('role_or_permission:organization_owner|admin_clinic|receptionist|doctor|pharmacist')->group(function () {
        Route::resource('polyclinics', PolyClinicController::class);
    });

    // --- Rute CRUD Doctor ---
    // organization_owner, admin_clinic
    Route::middleware('role_or_permission:organization_owner|admin_clinic')->group(function () {
        Route::resource('doctors', DoctorController::class);
    });

    // --- Rute CRUD Medical Personnel ---
    // organization_owner, admin_clinic, dan receptionist (untuk melihat)
    Route::middleware('role_or_permission:organization_owner|admin_clinic|receptionist')->group(function () {
        Route::resource('medical_personnel', MedicalPersonnelController::class);
    });

    // --- Rute CRUD Master Data Obat (Pabrik) ---
    // organization_owner, admin_clinic, dan admin_pharmacy
    Route::middleware('role_or_permission:organization_owner|admin_clinic|admin_pharmacy')->group(function () {
        Route::resource('manufacturers', ManufacturerController::class);
    });

    // --- Rute CRUD Master Data Obat (Golongan) ---
    // organization_owner, admin_clinic, dan admin_pharmacy
    Route::middleware('role_or_permission:organization_owner|admin_clinic|admin_pharmacy')->group(function () {
        Route::resource('drug_groups', DrugGroupController::class);
    });

    // --- Rute CRUD Master Data Obat (Kategori) ---
    // organization_owner, admin_clinic, dan admin_pharmacy
    Route::middleware('role_or_permission:organization_owner|admin_clinic|admin_pharmacy')->group(function () {
        Route::resource('drug_categories', DrugCategoryController::class);
    });

    // --- Rute CRUD Master Data Obat (Satuan) ---
    // organization_owner, admin_clinic, dan admin_pharmacy
    Route::middleware('role_or_permission:organization_owner|admin_clinic|admin_pharmacy')->group(function () {
        Route::resource('units', UnitController::class);
    });

    // --- Rute CRUD Master Data Obat (Etiket/Aturan Pakai) ---
    // organization_owner, admin_clinic, dan admin_pharmacy
    Route::middleware('role_or_permission:organization_owner|admin_clinic|admin_pharmacy')->group(function () {
        Route::resource('labels', LabelController::class);
    });

    // --- Rute CRUD Warehouse dan Lokasinya ---
    // organization_owner, admin_clinic, dan admin_pharmacy
    Route::middleware('role_or_permission:super_owner|organization_owner|admin_clinic|admin_pharmacy')->group(function () {
        Route::resource('warehouses', WarehouseController::class);
        // Rute khusus untuk langkah kedua wizard (view)
        Route::get('/warehouses/{warehouse}/add-locations', [WarehouseController::class, 'addLocationsWizard'])->name('warehouses.addLocationsWizard');

        // Rute untuk operasi CRUD Lokasi (melalui WarehouseController)
        Route::post('/warehouses/{warehouse}/locations', [WarehouseController::class, 'storeLocation'])->name('warehouses.locations.store');
        Route::put('/warehouses/{warehouse}/locations/{location}', [WarehouseController::class, 'updateLocation'])->name('warehouses.locations.update');
        Route::delete('/warehouses/{warehouse}/locations/{location}', [WarehouseController::class, 'destroyLocation'])->name('warehouses.locations.destroy');
    });

    // --- Rute CRUD Obat (Drugs) ---
    // organization_owner, admin_clinic, dan admin_pharmacy
    Route::middleware('role_or_permission:super_owner|organization_owner|admin_clinic|admin_pharmacy')->group(function () {
        Route::resource('drugs', DrugController::class);
        // Rute untuk mendapatkan lokasi berdasarkan gudang (AJAX)
        Route::get('/get-locations-by-warehouse', [DrugController::class, 'getLocationsByWarehouse'])->name('get.locations.by.warehouse');
        Route::post('/drugs/{drug}/drug-stocks', [DrugController::class, 'storeDrugStock'])->name('drugs.drug-stocks.store');
        Route::put('/drugs/{drug}/drug-stocks/{drugStock}', [DrugController::class, 'updateDrugStock'])->name('drugs.drug-stocks.update');
        Route::delete('/drugs/{drug}/drug-stocks/{drugStock}', [DrugController::class, 'destroyDrugStock'])->name('drugs.drug-stocks.destroy');
    });
});

// Halaman utama
Route::get('/', function () {
    return view('welcome');
})->name('index');
