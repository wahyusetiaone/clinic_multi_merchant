<?php

namespace App\Providers;

use App\Models\Doctor;
use App\Models\Drug;
use App\Models\DrugCategory;
use App\Models\DrugGroup;
use App\Models\Employee;
use App\Models\Label;
use App\Models\Manufacturer;
use App\Models\MedicalPersonnel;
use App\Models\PolyClinic;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Policies\DoctorPolicy;
use App\Policies\DrugCategoryPolicy;
use App\Policies\DrugGroupPolicy;
use App\Policies\DrugPolicy;
use App\Policies\EmployeePolicy;
use App\Policies\LabelPolicy;
use App\Policies\ManufacturerPolicy;
use App\Policies\MedicalPersonnelPolicy;
use App\Policies\PolyClinicPolicy;
use App\Policies\UnitPolicy;
use App\Policies\WarehousePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Employee::class => EmployeePolicy::class,
        PolyClinic::class => PolyClinicPolicy::class,
        Doctor::class => DoctorPolicy::class,
        MedicalPersonnel::class => MedicalPersonnelPolicy::class,
        Manufacturer::class => ManufacturerPolicy::class,
        DrugGroup::class => DrugGroupPolicy::class,
        DrugCategory::class => DrugCategoryPolicy::class,
        Unit::class => UnitPolicy::class,
        Label::class => LabelPolicy::class,
        Warehouse::class => WarehousePolicy::class,
        Drug::class => DrugPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
