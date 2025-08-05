@extends('layout.layout') {{-- Sesuaikan dengan layout utama aplikasi Anda --}}

@php
    $title = 'Tambah Dokter Baru';
    $subTitle = 'Form Dokter';
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
                    <form action="{{ route('doctors.store') }}" method="POST" enctype="multipart/form-data" class="row gy-3 needs-validation" novalidate>
                        @csrf

                        <div class="col-md-6">
                            <label for="nik" class="form-label"> <span class="text-danger">*</span></label>
                            <div class="icon-field has-NIKvalidation">
                                <span class="icon"><iconify-icon icon="mdi:card-account-details-outline"></iconify-icon></span>
                                <input type="text" id="nik" name="nik" class="form-control @error('nik') is-invalid @enderror" placeholder="NIK Dokter Anda" value="{{ old('nik') }}" required>
                                <div class="invalid-feedback">
                                    Mohon masukkan NIK Dokter.
                                </div>
                                @error('nik')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="satusehat_id" class="form-label">ID Satu Sehat (Opsional)</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:cloud-file-bold"></iconify-icon></span>
                                <input type="text" id="satusehat_id" name="satusehat_id" class="form-control @error('satusehat_id') is-invalid @enderror" placeholder="ID Satu Sehat (Jika terhubung)" value="{{ old('satusehat_id') }}">
                                <div class="invalid-feedback">
                                    Mohon masukkan ID Satu Sehat.
                                </div>
                                @error('satusehat_id')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                            {{-- Anda bisa menambahkan tombol "Cari" di sini dengan JS untuk integrasi Satu Sehat --}}
                            {{-- <button type="button" class="btn btn-sm btn-outline-info mt-1">Cari ID Satu Sehat</button> --}}
                        </div>

                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                            <div class="icon-field has-validation">
                                <span class="icon"><iconify-icon icon="solar:user-bold"></iconify-icon></span>
                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Dokter" value="{{ old('name') }}" required>
                                <div class="invalid-feedback">
                                    Mohon masukkan Nama Dokter.
                                </div>
                                @error('name')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="specialization" class="form-label">Spesialis (Opsional)</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:syringe-bold-duotone"></iconify-icon></span>
                                <input type="text" id="specialization" name="specialization" class="form-control @error('specialization') is-invalid @enderror" placeholder="Spesialis / Keahlian Dokter" value="{{ old('specialization') }}">
                                <div class="invalid-feedback">
                                    Mohon masukkan spesialisasi.
                                </div>
                                @error('specialization')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="address" class="form-label">Alamat (Opsional)</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:home-2-bold"></iconify-icon></span>
                                <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" placeholder="Alamat tinggal dokter Anda">{{ old('address') }}</textarea>
                                <div class="invalid-feedback">
                                    Mohon masukkan alamat.
                                </div>
                                @error('address')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="phone_number" class="form-label">No. Telepon (Opsional)</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:phone-calling-rounded-bold"></iconify-icon></span>
                                <input type="text" id="phone_number" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" placeholder="Nomor telepon yang bisa dihubungi" value="{{ old('phone_number') }}">
                                <div class="invalid-feedback">
                                    Mohon masukkan nomor telepon.
                                </div>
                                @error('phone_number')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="str_number" class="form-label">No. STR (Opsional)</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:documents-bold"></iconify-icon></span>
                                <input type="text" id="str_number" name="str_number" class="form-control @error('str_number') is-invalid @enderror" placeholder="Nomor Surat Tanda Registrasi" value="{{ old('str_number') }}">
                                <div class="invalid-feedback">
                                    Mohon masukkan nomor STR.
                                </div>
                                @error('str_number')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="username" class="form-label">Username (Opsional untuk login portal dokter)</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:user-circle-bold"></iconify-icon></span>
                                <input type="text" id="username" name="username" class="form-control @error('username') is-invalid @enderror" placeholder="Username untuk login dokter" value="{{ old('username') }}">
                                <div class="invalid-feedback">
                                    Mohon masukkan username.
                                </div>
                                @error('username')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Tanggal Mulai Bertugas <span class="text-danger">*</span></label>
                            <div class="icon-field has-validation">
                                <span class="icon"><iconify-icon icon="solar:calendar-bold-duotone"></iconify-icon></span>
                                <input type="date" id="start_date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                                <div class="invalid-feedback">
                                    Mohon pilih tanggal mulai bertugas.
                                </div>
                                @error('start_date')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="photo" class="form-label">Foto Dokter (Opsional)</label>
                            <input class="form-control @error('photo') is-invalid @enderror" type="file" id="photo" name="photo" accept="image/*">
                            <div class="invalid-feedback">
                                Mohon unggah foto dokter.
                            </div>
                            @error('photo')<div class="error-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="signature" class="form-label">Tanda Tangan Dokter (Opsional)</label>
                            <input class="form-control @error('signature') is-invalid @enderror" type="file" id="signature" name="signature" accept="image/*">
                            <div class="invalid-feedback">
                                Mohon unggah tanda tangan dokter.
                            </div>
                            @error('signature')<div class="error-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="stamp" class="form-label">Stempel Dokter (Opsional)</label>
                            <input class="form-control @error('stamp') is-invalid @enderror" type="file" id="stamp" name="stamp" accept="image/*">
                            <div class="invalid-feedback">
                                Mohon unggah stempel dokter.
                            </div>
                            @error('stamp')<div class="error-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="branch_id" class="form-label">Cabang <span class="text-danger">*</span></label>
                            <div class="icon-field has-validation">
                                <select class="form-select @error('branch_id') is-invalid @enderror" id="branch_id" name="branch_id" required>
                                    <option value="">Pilih Cabang</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
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
                            <button class="btn btn-primary-600" type="submit">Simpan Dokter</button>
                            <a href="{{ route('doctors.index') }}" class="btn btn-secondary-light ms-2">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
