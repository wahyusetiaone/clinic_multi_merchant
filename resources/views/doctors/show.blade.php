@extends('layout.layout') {{-- Sesuaikan dengan layout utama aplikasi Anda --}}

@php
    $title = 'Detail Dokter';
    $subTitle = 'Informasi Dokter';
@endphp

@section('content')

    <div class="row gy-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ $title }}</h5>
                </div>
                <div class="card-body">
                    <form class="row gy-3">
                        <div class="col-md-6">
                            <label for="nik" class="form-label">NIK</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="mdi:card-account-details-outline"></iconify-icon></span>
                                <input type="text" id="nik" name="nik" class="form-control" value="{{ $doctor->nik }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="satusehat_id" class="form-label">ID Satu Sehat</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:cloud-file-bold"></iconify-icon></span>
                                <input type="text" id="satusehat_id" name="satusehat_id" class="form-control" value="{{ $doctor->satusehat_id ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:user-bold"></iconify-icon></span>
                                <input type="text" id="name" name="name" class="form-control" value="{{ $doctor->name }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="specialization" class="form-label">Spesialis</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:syringe-bold-duotone"></iconify-icon></span>
                                <input type="text" id="specialization" name="specialization" class="form-control" value="{{ $doctor->specialization ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="address" class="form-label">Alamat</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:home-2-bold"></iconify-icon></span>
                                <textarea id="address" name="address" class="form-control" readonly>{{ $doctor->address ?? '-' }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="phone_number" class="form-label">No. Telepon</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:phone-calling-rounded-bold"></iconify-icon></span>
                                <input type="text" id="phone_number" name="phone_number" class="form-control" value="{{ $doctor->phone_number ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="str_number" class="form-label">No. STR</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:documents-bold"></iconify-icon></span>
                                <input type="text" id="str_number" name="str_number" class="form-control" value="{{ $doctor->str_number ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="username" class="form-label">Username</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:user-circle-bold"></iconify-icon></span>
                                <input type="text" id="username" name="username" class="form-control" value="{{ $doctor->username ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Tanggal Mulai Bertugas</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:calendar-bold-duotone"></iconify-icon></span>
                                <input type="text" id="start_date" name="start_date" class="form-control" value="{{ $doctor->start_date->format('d-m-Y') }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Foto Dokter</label>
                            @if ($doctor->photo)
                                <div class="border p-2 rounded text-center">
                                    <img src="{{ Storage::url($doctor->photo) }}" alt="Foto Dokter" class="img-fluid" style="max-height: 200px;">
                                </div>
                            @else
                                <p class="text-muted">Tidak ada foto.</p>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tanda Tangan Dokter</label>
                            @if ($doctor->signature)
                                <div class="border p-2 rounded text-center">
                                    <img src="{{ Storage::url($doctor->signature) }}" alt="Tanda Tangan Dokter" class="img-fluid" style="max-height: 200px;">
                                </div>
                            @else
                                <p class="text-muted">Tidak ada tanda tangan.</p>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Stempel Dokter</label>
                            @if ($doctor->stamp)
                                <div class="border p-2 rounded text-center">
                                    <img src="{{ Storage::url($doctor->stamp) }}" alt="Stempel Dokter" class="img-fluid" style="max-height: 200px;">
                                </div>
                            @else
                                <p class="text-muted">Tidak ada stempel.</p>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label for="branch" class="form-label">Cabang</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:hospital-line-duotone"></iconify-icon></span>
                                <input type="text" id="branch" name="branch" class="form-control" value="{{ $doctor->branch->name ?? '-' }} ({{ $doctor->branch->organization->name ?? '-' }})" readonly>
                            </div>
                        </div>

                        <div class="col-12">
                            {{-- @can('update', $doctor) --}}
                            <a href="{{ route('doctors.edit', $doctor->id) }}" class="btn btn-warning-600">Edit Dokter</a>
                            {{-- @endcan --}}
                            <a href="{{ route('doctors.index') }}" class="btn btn-secondary-light ms-2">Kembali ke Daftar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
