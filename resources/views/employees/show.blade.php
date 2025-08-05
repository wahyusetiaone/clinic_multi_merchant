@extends('layout.layout')

@php
    $title = 'Detail Pegawai';
    $subTitle = 'Informasi Pegawai';
    // Tidak perlu script validasi karena form readonly
@endphp

@section('content')

    <div class="row gy-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ $title }}</h5>
                </div>
                <div class="card-body">
                    <form class="row gy-3"> {{-- Tidak perlu novalidate dan needs-validation jika readonly --}}

                        {{-- Bagian Informasi Dasar Pegawai --}}
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama Pegawai</label>
                            <div class="icon-field"> {{-- Hapus has-validation --}}
                                <span class="icon"><iconify-icon icon="f7:person"></iconify-icon></span>
                                <input type="text" id="name" name="name" class="form-control" value="{{ $employee->name }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="organization_name" class="form-label">Organisasi</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="clarity:organization-solid"></iconify-icon></span>
                                <input type="text" id="organization_name" name="organization_name" class="form-control" value="{{ $employee->organization->name ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="branch_name" class="form-label">Cabang</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="mdi:source-branch"></iconify-icon></span>
                                <input type="text" id="branch_name" name="branch_name" class="form-control" value="{{ $employee->branch->name ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="position_name" class="form-label">Jabatan</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="mdi:briefcase-outline"></iconify-icon></span>
                                <input type="text" id="position_name" name="position_name" class="form-control" value="{{ $employee->position->name ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="nip" class="form-label">NIP</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="mdi:card-account-details-outline"></iconify-icon></span>
                                <input type="text" id="nip" name="nip" class="form-control" value="{{ $employee->nip ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">Telepon</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:phone-calling-linear"></iconify-icon></span>
                                <input type="text" id="phone" name="phone" class="form-control" value="{{ $employee->phone ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="address" class="form-label">Alamat</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:home-smile-outline"></iconify-icon></span>
                                <textarea id="address" name="address" class="form-control" readonly>{{ $employee->address ?? '-' }}</textarea>
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5 class="card-title mb-3">Informasi Akun Pengguna</h5>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Akun</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="mage:email"></iconify-icon></span>
                                <input type="email" id="email" name="email" class="form-control" value="{{ $employee->user->email ?? 'Tidak Ada Akun' }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="role" class="form-label">Peran Akun</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:user-tag-outline"></iconify-icon></span>
                                <input type="text" id="role" name="role" class="form-control" value="{{ $employee->user ? (ucfirst(str_replace('_', ' ', $employee->user->roles->first()->name))) : 'Tidak Ada' }}" readonly>
                            </div>
                        </div>

                        <div class="col-12">
                            @can('update', $employee)
                                <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning-600">Edit Pegawai</a>
                            @endcan
                            <a href="{{ route('employees.index') }}" class="btn btn-secondary-light ms-2">Kembali ke Daftar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
