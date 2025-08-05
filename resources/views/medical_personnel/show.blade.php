@extends('layout.layout') {{-- Sesuaikan dengan layout utama aplikasi Anda --}}

@php
    $title = 'Detail Petugas Medis';
    $subTitle = 'Informasi Petugas Medis';
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
                            <label for="category" class="form-label">Kategori</label>
                            <div class="icon-field">
                                <input type="text" id="category" name="category" class="form-control" value="{{ ucfirst($medicalPersonnel->category) }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <span class="icon"><iconify-icon icon="solar:id-card-line-duotone"></iconify-icon></span>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="mdi:card-account-details-outline"></iconify-icon></span>
                                <input type="text" id="nik" name="nik" class="form-control" value="{{ $medicalPersonnel->nik }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="satusehat_id" class="form-label">ID Satu Sehat</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:cloud-file-bold"></iconify-icon></span>
                                <input type="text" id="satusehat_id" name="satusehat_id" class="form-control" value="{{ $medicalPersonnel->satusehat_id ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:user-bold"></iconify-icon></span>
                                <input type="text" id="name" name="name" class="form-control" value="{{ $medicalPersonnel->name }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="specialization" class="form-label">Bagian / Spesialis</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:syringe-bold-duotone"></iconify-icon></span>
                                <input type="text" id="specialization" name="specialization" class="form-control" value="{{ $medicalPersonnel->specialization ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="phone_number" class="form-label">No. Telepon</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:phone-calling-rounded-bold"></iconify-icon></span>
                                <input type="text" id="phone_number" name="phone_number" class="form-control" value="{{ $medicalPersonnel->phone_number ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="address" class="form-label">Alamat</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:map-point-square-bold"></iconify-icon></span>
                                <textarea id="address" name="address" class="form-control" readonly>{{ $medicalPersonnel->address ?? '-' }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:inbox-line-duotone"></iconify-icon></span>
                                <input type="email" id="email" name="email" class="form-control" value="{{ $medicalPersonnel->email ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Tanggal Mulai Bertugas</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:calendar-bold-duotone"></iconify-icon></span>
                                <input type="text" id="start_date" name="start_date" class="form-control" value="{{ $medicalPersonnel->start_date->format('d-m-Y') }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="branch" class="form-label">Cabang</label>
                            <div class="icon-field">
                                <input type="text" id="branch" name="branch" class="form-control" value="{{ $medicalPersonnel->branch->name ?? '-' }} ({{ $medicalPersonnel->branch->organization->name ?? '-' }})" readonly>
                            </div>
                        </div>

                        <div class="col-12">
                            @can('update', $medicalPersonnel)
                                <a href="{{ route('medical_personnel.edit', $medicalPersonnel->id) }}" class="btn btn-warning-600">Edit Petugas Medis</a>
                            @endcan
                            <a href="{{ route('medical_personnel.index') }}" class="btn btn-secondary-light ms-2">Kembali ke Daftar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
