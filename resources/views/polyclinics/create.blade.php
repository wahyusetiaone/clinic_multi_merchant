@extends('layout.layout')

@php
    $title = 'Tambah Poli Klinik Baru';
    $subTitle = 'Form Poli Klinik';
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
                    <form action="{{ route('polyclinics.store') }}" method="POST" class="row gy-3 needs-validation" novalidate>
                        @csrf

                        <div class="col-md-6">
                            <label for="code" class="form-label">Kode Poli</label>
                            <div class="icon-field has-validation">
                                <span class="icon"><iconify-icon icon="mdi:form-textbox"></iconify-icon></span>
                                <input type="text" id="code" name="code" class="form-control" value="{{ old('code') }}" placeholder="Contoh: G, UM, ANAK" required>
                                <div class="invalid-feedback">
                                    Mohon berikan kode poli.
                                </div>
                                @error('code')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama Poli</label>
                            <div class="icon-field has-validation">
                                <span class="icon"><iconify-icon icon="solar:hospital-line-duotone"></iconify-icon></span>
                                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" placeholder="Contoh: Poli Gigi, Poli Umum" required>
                                <div class="invalid-feedback">
                                    Mohon berikan nama poli.
                                </div>
                                @error('name')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="branch_id" class="form-label">Cabang</label>
                            <div class="icon-field has-validation">
                                <select id="branch_id" name="branch_id" class="form-select" required>
                                    <option value="">Pilih Cabang</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }} ({{ $branch->organization->name ?? '' }})</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Mohon pilih cabang.
                                </div>
                                @error('branch_id')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="physical_location_type" class="form-label">Tipe Fisik Lokasi (Nomor Ruangan)</label>
                            <div class="icon-field has-validation">
                                <span class="icon"><iconify-icon icon="mdi:office-building-marker"></iconify-icon></span>
                                <input type="text" id="physical_location_type" name="physical_location_type" class="form-control" value="{{ old('physical_location_type') }}" placeholder="Contoh: Ruang 101, Gedung B">
                                <div class="invalid-feedback">
                                    Mohon berikan lokasi fisik.
                                </div>
                                @error('physical_location_type')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="description" class="form-label">Keterangan Singkat</label>
                            <div class="icon-field has-validation">
                                <span class="icon"><iconify-icon icon="solar:document-text-line-duotone"></iconify-icon></span>
                                <textarea id="description" name="description" class="form-control" placeholder="Keterangan singkat tentang poli ini">{{ old('description') }}</textarea>
                                <div class="invalid-feedback">
                                    Mohon berikan keterangan.
                                </div>
                                @error('description')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <button class="btn btn-primary-600" type="submit">Simpan Poli Klinik</button>
                            <a href="{{ route('polyclinics.index') }}" class="btn btn-secondary-light ms-2">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
