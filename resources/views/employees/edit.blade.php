@extends('layout.layout')

@php
    $title = 'Edit Pegawai';
    $subTitle = 'Form Pegawai';
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
                    <form action="{{ route('employees.update', $employee->id) }}" method="POST" class="row gy-3 needs-validation" novalidate>
                        @csrf
                        @method('PUT') {{-- Penting untuk update --}}

                        {{-- Bagian Informasi Dasar Pegawai --}}
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama Pegawai</label>
                            <div class="icon-field has-validation">
                                <span class="icon"><iconify-icon icon="f7:person"></iconify-icon></span>
                                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $employee->name) }}" placeholder="Masukkan Nama Lengkap" required>
                                <div class="invalid-feedback">
                                    Mohon berikan nama pegawai.
                                </div>
                                @error('name')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="organization_id" class="form-label">Organisasi</label>
                            <div class="icon-field has-validation">
                                <select id="organization_id" name="organization_id" class="form-select" required>
                                    <option value="">Pilih Organisasi</option>
                                    @foreach ($organizations as $org)
                                        <option value="{{ $org->id }}" {{ old('organization_id', $employee->organization_id) == $org->id ? 'selected' : '' }}>{{ $org->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Mohon pilih organisasi.
                                </div>
                                @error('organization_id')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="branch_id" class="form-label">Cabang (Opsional)</label>
                            <div class="icon-field has-validation">
                                <select id="branch_id" name="branch_id" class="form-select">
                                    <option value="">Tidak Terikat Cabang</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id', $employee->branch_id) == $branch->id ? 'selected' : '' }}>{{ $branch->name }} ({{ $branch->type }})</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Mohon pilih cabang yang valid.
                                </div>
                                @error('branch_id')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="position_id" class="form-label">Jabatan</label>
                            <div class="icon-field has-validation">
                                <select id="position_id" name="position_id" class="form-select" required>
                                    <option value="">Pilih Jabatan</option>
                                    @foreach ($positions as $pos)
                                        <option value="{{ $pos->id }}" {{ old('position_id', $employee->position_id) == $pos->id ? 'selected' : '' }}>{{ $pos->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Mohon pilih jabatan.
                                </div>
                                @error('position_id')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="nip" class="form-label">NIP (Nomor Induk Pegawai - Opsional)</label>
                            <div class="icon-field has-validation">
                                <span class="icon"><iconify-icon icon="mdi:card-account-details-outline"></iconify-icon></span>
                                <input type="text" id="nip" name="nip" class="form-control" value="{{ old('nip', $employee->nip) }}" placeholder="Contoh: 199001012020121001">
                                <div class="invalid-feedback">
                                    Mohon berikan NIP yang valid.
                                </div>
                                @error('nip')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">Telepon (Opsional)</label>
                            <div class="icon-field has-validation">
                                <span class="icon"><iconify-icon icon="solar:phone-calling-linear"></iconify-icon></span>
                                <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $employee->phone) }}" placeholder="+62 812-3456-7890">
                                <div class="invalid-feedback">
                                    Mohon berikan nomor telepon yang valid.
                                </div>
                                @error('phone')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="address" class="form-label">Alamat (Opsional)</label>
                            <div class="icon-field has-validation">
                                <span class="icon"><iconify-icon icon="solar:home-smile-outline"></iconify-icon></span>
                                <textarea id="address" name="address" class="form-control" placeholder="Alamat Lengkap">{{ old('address', $employee->address) }}</textarea>
                                <div class="invalid-feedback">
                                    Mohon berikan alamat.
                                </div>
                                @error('address')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5 class="card-title mb-3">Akun Pengguna (Opsional - Jika Pegawai Membutuhkan Akses Login)</h5>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Akun (Opsional)</label>
                            <div class="icon-field has-validation">
                                <span class="icon"><iconify-icon icon="mage:email"></iconify-icon></span>
                                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $employee->user->email ?? '') }}" placeholder="email@example.com">
                                <div class="invalid-feedback">
                                    Mohon berikan alamat email yang valid.
                                </div>
                                @error('email')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="role" class="form-label">Peran Akun (Opsional - Jika membuat akun pengguna)</label>
                            <div class="icon-field has-validation">
                                <span class="icon"><iconify-icon icon="solar:user-tag-outline"></iconify-icon></span>
                                <select id="role" name="role" class="form-select">
                                    <option value="">Tidak Ada Peran</option>
                                    @foreach (\Spatie\Permission\Models\Role::where('name', '!=', 'super_owner')->get() as $role)
                                        <option value="{{ $role->name }}" {{ old('role', $employee->user ? $employee->user->roles->first()->name : '') == $role->name ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $role->name)) }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Mohon pilih peran yang valid.
                                </div>
                                @error('role')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label">Password Akun (Kosongkan jika tidak ingin mengubah)</label>
                            <div class="icon-field has-validation">
                                <span class="icon"><iconify-icon icon="solar:lock-password-outline"></iconify-icon></span>
                                <input type="password" id="password" name="password" class="form-control" placeholder="********">
                                <div class="invalid-feedback">
                                    Mohon berikan password (minimal 8 karakter).
                                </div>
                                @error('password')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <div class="icon-field has-validation">
                                <span class="icon"><iconify-icon icon="solar:lock-password-outline"></iconify-icon></span>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="********">
                                <div class="invalid-feedback">
                                    Konfirmasi password tidak cocok.
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <button class="btn btn-primary-600" type="submit">Update Pegawai</button>
                            <a href="{{ route('employees.index') }}" class="btn btn-secondary-light ms-2">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
