@extends('layout.layout') {{-- Sesuaikan dengan layout utama aplikasi Anda --}}

@php
    $title = 'Edit Petugas Medis';
    $subTitle = 'Form Petugas Medis';
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
                    <form action="{{ route('medical_personnel.update', $medicalPersonnel->id) }}" method="POST" class="row gy-3 needs-validation" novalidate>
                        @csrf
                        @method('PUT') {{-- Penting untuk metode UPDATE --}}

                        <div class="col-md-6">
                            <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <div class="icon-field has-validation">
                                <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="perawat" {{ (old('category', $medicalPersonnel->category) == 'perawat') ? 'selected' : '' }}>Perawat</option>
                                    <option value="petugas" {{ (old('category', $medicalPersonnel->category) == 'petugas') ? 'selected' : '' }}>Petugas</option>
                                </select>
                                <div class="invalid-feedback">
                                    Mohon pilih kategori petugas medis.
                                </div>
                                @error('category')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="nik" class="form-label">NIK <span class="text-danger">*</span></label>
                            <div class="icon-field has-validation">
                                <span class="icon"><iconify-icon icon="mdi:card-account-details-outline"></iconify-icon></span>
                                <input type="text" id="nik" name="nik" class="form-control @error('nik') is-invalid @enderror" placeholder="NIK Petugas Medis Anda" value="{{ old('nik', $medicalPersonnel->nik) }}" required>
                                <div class="invalid-feedback">
                                    Mohon masukkan NIK Petugas Medis.
                                </div>
                                @error('nik')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="satusehat_id" class="form-label">ID Satu Sehat (Opsional)</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:cloud-file-bold"></iconify-icon></span>
                                <input type="text" id="satusehat_id" name="satusehat_id" class="form-control @error('satusehat_id') is-invalid @enderror" placeholder="ID Satu Sehat (Jika terhubung)" value="{{ old('satusehat_id', $medicalPersonnel->satusehat_id) }}">
                                <div class="invalid-feedback">
                                    Mohon masukkan ID Satu Sehat.
                                </div>
                                @error('satusehat_id')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                            <div class="icon-field has-validation">
                                <span class="icon"><iconify-icon icon="solar:user-bold"></iconify-icon></span>
                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Petugas Medis" value="{{ old('name', $medicalPersonnel->name) }}" required>
                                <div class="invalid-feedback">
                                    Mohon masukkan Nama Petugas Medis.
                                </div>
                                @error('name')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="specialization" class="form-label">Bagian / Spesialis (Opsional)</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:syringe-bold-duotone"></iconify-icon></span>
                                <input type="text" id="specialization" name="specialization" class="form-control @error('specialization') is-invalid @enderror" placeholder="Bagian pekerjaan atau spesialisasi" value="{{ old('specialization', $medicalPersonnel->specialization) }}">
                                <div class="invalid-feedback">
                                    Mohon masukkan bagian / spesialisasi.
                                </div>
                                @error('specialization')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="phone_number" class="form-label">No. Telepon (Opsional)</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:phone-calling-rounded-bold"></iconify-icon></span>
                                <input type="text" id="phone_number" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" placeholder="Nomor telepon yang bisa dihubungi" value="{{ old('phone_number', $medicalPersonnel->phone_number) }}">
                                <div class="invalid-feedback">
                                    Mohon masukkan nomor telepon.
                                </div>
                                @error('phone_number')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="address" class="form-label">Alamat (Opsional)</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:map-point-square-bold"></iconify-icon></span>
                                <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" placeholder="Alamat tinggal petugas medis Anda">{{ old('address', $medicalPersonnel->address) }}</textarea>
                                <div class="invalid-feedback">
                                    Mohon masukkan alamat.
                                </div>
                                @error('address')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email (Opsional)</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:inbox-line-duotone"></iconify-icon></span>
                                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email aktif petugas medis" value="{{ old('email', $medicalPersonnel->email) }}">
                                <div class="invalid-feedback">
                                    Mohon masukkan email yang valid.
                                </div>
                                @error('email')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Tanggal Mulai Bertugas <span class="text-danger">*</span></label>
                            <div class="icon-field has-validation">
                                <span class="icon"><iconify-icon icon="solar:calendar-bold-duotone"></iconify-icon></span>
                                <input type="date" id="start_date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $medicalPersonnel->start_date->format('Y-m-d')) }}" required>
                                <div class="invalid-feedback">
                                    Mohon pilih tanggal mulai bertugas.
                                </div>
                                @error('start_date')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="branch_id" class="form-label">Cabang <span class="text-danger">*</span></label>
                            <div class="icon-field has-validation">
                                <select class="form-select @error('branch_id') is-invalid @enderror" id="branch_id" name="branch_id" required>
                                    <option value="">Pilih Cabang</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ (old('branch_id', $medicalPersonnel->branch_id) == $branch->id) ? 'selected' : '' }}>
                                            {{ $branch->name }} ({{ $branch->organization->name ?? 'Tidak ada Organisasi' }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Mohon pilih cabang.
                                </div>
                                @error('branch_id')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <button class="btn btn-primary-600" type="submit">Update Petugas Medis</button>
                            <a href="{{ route('medical_personnel.index') }}" class="btn btn-secondary-light ms-2">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
