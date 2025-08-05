@extends('layout.layout')

@php
    $title = 'Edit Gudang';
    $subTitle = 'Form Gudang';
    $script = '<script>
                        (() => {
                            "use strict"
                            const forms = document.querySelectorAll(".needs-validation")
                            Array.from(forms).forEach(form => {
                                form.addEventListener("submit", event => {
                                    if (!form.checkValidity()) {
                                        event.preventDefault()
                                        event.stopPropagation()
                                    }
                                    form.classList.add("was-validated")
                                }, false)
                            })
                        })()
            </script>';
@endphp

@section('content')

    <div class="row gy-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ $title }}</h5>
                </div>
                <div class="card-body">
                    <div id="alert-placeholder"></div>

                    <form action="{{ route('warehouses.update', $warehouse->id) }}" method="POST" class="row gy-3 needs-validation mb-5" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama Gudang</label>
                            <div class="icon-field has-validation">
                                <span class="icon"><iconify-icon icon="solar:warehouse-broken"></iconify-icon></span>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Nama Gudang" value="{{ old('name', $warehouse->name) }}" required>
                                <div class="invalid-feedback">
                                    Mohon masukkan nama gudang.
                                </div>
                                @error('name')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="branch_id" class="form-label">Cabang</label>
                            <div class="icon-field has-validation">
                                <span class="icon"><iconify-icon icon="solar:building-3-line-duotone"></iconify-icon></span>
                                <select class="form-select" id="branch_id" name="branch_id" required>
                                    <option value="">Pilih Cabang</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id', $warehouse->branch_id) == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Mohon pilih cabang.
                                </div>
                                @error('branch_id')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <button class="btn btn-primary-600" type="submit">Update Gudang</button>
                            <a href="{{ route('warehouses.index') }}" class="btn btn-secondary-light ms-2">Batal</a>
                        </div>
                    </form>

                    <hr class="my-4">

                    <h6 class="mb-3">Daftar Lokasi Penyimpanan:</h6>
                    @can('update', $warehouse) {{-- Hanya jika user bisa mengupdate gudang, maka bisa menambah/mengedit lokasi --}}
                    <div class="mb-3">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLocationModal">
                            Tambah Lokasi Baru
                        </button>
                    </div>
                    @endcan

                    <div class="table-responsive">
                        <table class="table basic-table mb-0" id="locations-table">
                            <thead>
                            <tr>
                                <th>S.L</th>
                                <th>Nama Lokasi</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($warehouse->locations as $location)
                                <tr id="location-row-{{ $location->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $location->name }}</td>
                                    <td>
                                        @can('update', $warehouse) {{-- Izin dikontrol oleh WarehousePolicy update --}}
                                        <button type="button" class="btn btn-warning btn-sm edit-location-btn"
                                                data-id="{{ $location->id }}"
                                                data-name="{{ $location->name }}">
                                            Edit
                                        </button>
                                        @endcan
                                        @can('update', $warehouse) {{-- Izin dikontrol oleh WarehousePolicy update --}}
                                        <button type="button" class="btn btn-danger btn-sm delete-location-btn"
                                                data-id="{{ $location->id }}"
                                                data-name="{{ $location->name }}">
                                            Hapus
                                        </button>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr id="no-locations-row">
                                    <td colspan="4" class="text-center">Belum ada lokasi penyimpanan untuk gudang ini.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addLocationModal" tabindex="-1" aria-labelledby="addLocationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addLocationModalLabel">Tambah Lokasi Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="add-location-form" class="needs-validation" novalidate>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="warehouse_id" value="{{ $warehouse->id }}">
                        <div class="mb-3">
                            <label for="new_location_name" class="form-label">Nama Lokasi</label>
                            <input type="text" class="form-control" id="new_location_name" name="name" required>
                            <div class="invalid-feedback" id="new_location_name_error">
                                Mohon masukkan nama lokasi.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Lokasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editLocationModal" tabindex="-1" aria-labelledby="editLocationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editLocationModalLabel">Edit Lokasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit-location-form" class="needs-validation" novalidate>
                    <div class="modal-body">
                        @csrf
                        @method('PUT') {{-- Penting untuk metode PUT --}}
                        <input type="hidden" id="edit_location_id" name="id">
                        <div class="mb-3">
                            <label for="edit_location_name" class="form-label">Nama Lokasi</label>
                            <input type="text" class="form-control" id="edit_location_name" name="name" required>
                            <div class="invalid-feedback" id="edit_location_name_error">
                                Mohon masukkan nama lokasi.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update Lokasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Fungsi untuk menampilkan pesan sukses atau error
        function showToast(message, type = 'success') {
            const alertPlaceholder = document.getElementById('alert-placeholder') || document.createElement('div');
            alertPlaceholder.id = 'alert-placeholder';
            alertPlaceholder.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
            document.querySelector('.card-body').prepend(alertPlaceholder);
            setTimeout(() => {
                alertPlaceholder.innerHTML = '';
            }, 5000);
        }

        // Fungsi untuk merender lokasi ke tabel
        function renderLocationRow(location, index) {
            return `
            <tr id="location-row-${location.id}">
                <td>${index}</td>
                <td>${location.name}</td>
                <td>
                    {{-- Izin dikontrol oleh WarehousePolicy update --}}
            @can('update', $warehouse)
            <button type="button" class="btn btn-warning btn-sm edit-location-btn"
                    data-id="${location.id}"
                            data-name="${location.name}">
                        Edit
                    </button>
                    @endcan
            @can('update', $warehouse) {{-- Izin dikontrol oleh WarehousePolicy update --}}
            <button type="button" class="btn btn-danger btn-sm delete-location-btn"
                    data-id="${location.id}"
                            data-name="${location.name}">
                        Hapus
                    </button>
                    @endcan
            </td>
        </tr>
`;
        }

        // Fungsi untuk mengupdate nomor SL pada tabel
        function updateTableSlNumbers() {
            const tableBody = document.querySelector('#locations-table tbody');
            Array.from(tableBody.rows).forEach((row, index) => {
                if (row.id !== 'no-locations-row') {
                    row.cells[0].textContent = index + 1;
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const warehouseId = "{{ $warehouse->id }}"; // Dapatkan ID gudang dari Blade

            // Tangani submit form Tambah Lokasi (AJAX)
            document.getElementById('add-location-form').addEventListener('submit', async function (e) {
                e.preventDefault();
                e.stopPropagation();

                document.getElementById('new_location_name_error').textContent = '';
                this.classList.remove("was-validated");

                if (!this.checkValidity()) {
                    this.classList.add("was-validated");
                    return;
                }

                const formData = new FormData(this);

                try {
                    const response = await fetch(`/warehouses/${warehouseId}/locations`, { // URL baru
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok) {
                        showToast(data.message, 'success');
                        const newLocation = data.location;

                        const noLocationsRow = document.getElementById('no-locations-row');
                        if (noLocationsRow) {
                            noLocationsRow.remove();
                        }

                        const tableBody = document.querySelector('#locations-table tbody');
                        const newRow = document.createElement('tr');
                        newRow.id = `location-row-${newLocation.id}`;
                        const newIndex = tableBody.children.length + 1;
                        newRow.innerHTML = renderLocationRow(newLocation, newIndex);
                        tableBody.appendChild(newRow);

                        updateTableSlNumbers();

                        this.reset();
                        this.classList.remove("was-validated");
                        const addLocationModal = bootstrap.Modal.getInstance(document.getElementById('addLocationModal'));
                        addLocationModal.hide();

                    } else {
                        let errorMessage = 'Gagal menambah lokasi.';
                        if (data.errors) {
                            if (data.errors.name) {
                                document.getElementById('new_location_name_error').textContent = data.errors.name[0];
                            }
                            errorMessage += '<br>' + Object.values(data.errors).flat().join('<br>');
                        } else if (data.message) {
                            errorMessage = data.message; // Tangani pesan error dari controller (ex: 403)
                        }
                        showToast(errorMessage, 'danger');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan saat berkomunikasi dengan server.', 'danger');
                }
            });

            // Tangani klik tombol Edit Lokasi (membuka modal edit)
            document.getElementById('locations-table').addEventListener('click', function (e) {
                if (e.target.classList.contains('edit-location-btn')) {
                    const id = e.target.dataset.id;
                    const name = e.target.dataset.name;

                    document.getElementById('edit_location_id').value = id;
                    document.getElementById('edit_location_name').value = name;

                    const editLocationModal = new bootstrap.Modal(document.getElementById('editLocationModal'));
                    editLocationModal.show();
                }
            });

            // Tangani submit form Edit Lokasi (AJAX)
            document.getElementById('edit-location-form').addEventListener('submit', async function (e) {
                e.preventDefault();
                e.stopPropagation();

                document.getElementById('edit_location_name_error').textContent = '';
                this.classList.remove("was-validated");

                if (!this.checkValidity()) {
                    this.classList.add("was-validated");
                    return;
                }

                const locationId = document.getElementById('edit_location_id').value;
                const formData = new FormData(this);
                const actionUrl = `/warehouses/${warehouseId}/locations/${locationId}`; // URL baru

                try {
                    const response = await fetch(actionUrl, {
                        method: 'POST', // Menggunakan POST untuk PUT/PATCH via _method
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok) {
                        showToast(data.message, 'success');
                        const updatedLocation = data.location;

                        const row = document.getElementById(`location-row-${updatedLocation.id}`);
                        if (row) {
                            const slNumber = row.cells[0].textContent;
                            row.innerHTML = renderLocationRow(updatedLocation, slNumber);
                            row.cells[0].textContent = slNumber;
                        }
                        updateTableSlNumbers();

                        const editLocationModal = bootstrap.Modal.getInstance(document.getElementById('editLocationModal'));
                        editLocationModal.hide();

                    } else {
                        let errorMessage = 'Gagal mengupdate lokasi.';
                        if (data.errors) {
                            if (data.errors.name) {
                                document.getElementById('edit_location_name_error').textContent = data.errors.name[0];
                            }
                            errorMessage += '<br>' + Object.values(data.errors).flat().join('<br>');
                        } else if (data.message) {
                            errorMessage = data.message; // Tangani pesan error dari controller (ex: 403)
                        }
                        showToast(errorMessage, 'danger');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan saat berkomunikasi dengan server.', 'danger');
                }
            });

            // Tangani klik tombol Hapus Lokasi (AJAX)
            document.getElementById('locations-table').addEventListener('click', async function (e) {
                if (e.target.classList.contains('delete-location-btn')) {
                    const id = e.target.dataset.id;
                    const name = e.target.dataset.name;

                    if (confirm(`Apakah Anda yakin ingin menghapus lokasi ${name}?`)) {
                        try {
                            const formData = new FormData();
                            formData.append('_method', 'DELETE');
                            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                            const response = await fetch(`/warehouses/${warehouseId}/locations/${id}`, { // URL baru
                                method: 'POST', // Menggunakan POST untuk DELETE via _method
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: formData
                            });

                            const data = await response.json();

                            if (response.ok) {
                                showToast(data.message, 'success');
                                document.getElementById(`location-row-${id}`).remove();
                                updateTableSlNumbers();

                                const tableBody = document.querySelector('#locations-table tbody');
                                if (tableBody.children.length === 0) {
                                    const noLocationsRow = document.createElement('tr');
                                    noLocationsRow.id = 'no-locations-row';
                                    noLocationsRow.innerHTML = '<td colspan="4" class="text-center">Belum ada lokasi penyimpanan untuk gudang ini.</td>';
                                    tableBody.appendChild(noLocationsRow);
                                }

                            } else {
                                let errorMessage = 'Gagal menghapus lokasi.';
                                if (data.errors) {
                                    errorMessage += '<br>' + Object.values(data.errors).flat().join('<br>');
                                } else if (data.message) {
                                    errorMessage = data.message; // Tangani pesan error dari controller (ex: 403)
                                }
                                showToast(errorMessage, 'danger');
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            showToast('Terjadi kesalahan saat berkomunikasi dengan server.', 'danger');
                        }
                    }
                }
            });
        });
    </script>
@endpush
