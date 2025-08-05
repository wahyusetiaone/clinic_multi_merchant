<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="{{ route('index') }}" class="sidebar-logo">
            <img src="{{ asset('assets/images/logo.png') }}" alt="site logo" class="light-logo">
            <img src="{{ asset('assets/images/logo-light.png') }}" alt="site logo" class="dark-logo">
            <img src="{{ asset('assets/images/logo-icon.png') }}" alt="site logo" class="logo-icon">
        </a>
    </div>
    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">
            <li>
                <a  href="{{ route('index') }}">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sidebar-menu-group-title">Application</li>
            @can('viewAny', App\Models\Employee::class)
                <li>
                    <a href="{{ route('employees.index') }}">
                        <iconify-icon icon="ph:users" class="menu-icon"></iconify-icon>
                        <span>Employees</span>
                    </a>
                </li>
            @endcan

            {{-- Menu Poly Clinics --}}
            @can('viewAny', App\Models\PolyClinic::class)
                <li>
                    <a href="{{ route('polyclinics.index') }}">
                        <iconify-icon icon="mdi:hospital-building" class="menu-icon"></iconify-icon>
                        <span>Poly Clinics</span>
                    </a>
                </li>
            @endcan

            {{-- Menu Dokter --}}
            @can('viewAny', App\Models\Doctor::class)
                <li>
                    <a href="{{ route('doctors.index') }}" >
                        <iconify-icon icon="mdi:stethoscope" class="menu-icon"></iconify-icon>
                        <span class="sidebar-submenu-text">Dokter</span>
                    </a>
                </li>
            @endcan
            {{-- Menu Petugas Medis --}}
            @can('viewAny', App\Models\MedicalPersonnel::class)
                <li>
                    <a href="{{ route('medical_personnel.index') }}">
                        <iconify-icon icon="mdi:account-heart" class="menu-icon"></iconify-icon>
                        <span class="sidebar-submenu-text">Petugas Medis</span>
                    </a>
                </li>
            @endcan
            {{-- Tambahan untuk Master Data Obat --}}
            <li class="sidebar-menu-group-title">Master Data Obat</li>
            @can('viewAny', App\Models\Manufacturer::class) {{-- Asumsi nama modelnya Manufacturer --}}
            <li>
                <a href="{{ route('manufacturers.index') }}"> {{-- Asumsi rutenya manufacturers.index --}}
                    <iconify-icon icon="mdi:factory" class="menu-icon"></iconify-icon>
                    <span>Pabrik</span>
                </a>
            </li>
            @endcan
            @can('viewAny', App\Models\DrugGroup::class) {{-- Asumsi nama modelnya DrugGroup --}}
            <li>
                <a href="{{ route('drug_groups.index') }}"> {{-- Asumsi rutenya drug_groups.index --}}
                    <iconify-icon icon="solar:box-minimalistic-line-duotone" class="menu-icon"></iconify-icon>
                    <span>Group Obat</span>
                </a>
            </li>
            @endcan
            @can('viewAny', App\Models\DrugCategory::class) {{-- Asumsi nama modelnya DrugCategory --}}
            <li>
                <a href="{{ route('drug_categories.index') }}"> {{-- Asumsi rutenya drug_categories.index --}}
                    <iconify-icon icon="solar:tag-outline" class="menu-icon"></iconify-icon>
                    <span>Kategori Obat</span>
                </a>
            </li>
            @endcan
            @can('viewAny', App\Models\UnitLabel::class) {{-- Asumsi nama modelnya UnitLabel --}}
            <li>
                <a href="{{ route('unit_labels.index') }}"> {{-- Asumsi rutenya unit_labels.index --}}
                    <iconify-icon icon="solar:tag-horizontal-line-duotone" class="menu-icon"></iconify-icon>
                    <span>Unit Label</span>
                </a>
            </li>
            @endcan
            {{-- Tambahkan ini untuk Gudang --}}
            @can('viewAny', App\Models\Warehouse::class)
                <li>
                    <a href="{{ route('warehouses.index') }}">
                        <iconify-icon icon="mdi:warehouse" class="menu-icon"></iconify-icon>
                        <span>Gudang</span>
                    </a>
                </li>
            @endcan
            @can('viewAny', App\Models\Drug::class)
                <li>
                    <a href="{{ route('drugs.index') }}">
                        <iconify-icon icon="solar:pill-broken" class="menu-icon"></iconify-icon>
                        <span>Obat</span>
                    </a>
                </li>
            @endcan
            <li>
                  <a href="{{ route('signup') }}">
                    <iconify-icon icon="mage:email" class="menu-icon"></iconify-icon>
                    <span>Email</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
