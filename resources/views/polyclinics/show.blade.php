@extends('layout.layout')

@php
    $title = 'Detail Poli Klinik';
    $subTitle = 'Informasi Poli Klinik';
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
                    <form class="row gy-3">

                        <div class="col-md-6">
                            <label for="code" class="form-label">Kode Poli</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="mdi:form-textbox"></iconify-icon></span>
                                <input type="text" id="code" name="code" class="form-control" value="{{ $polyclinic->code }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama Poli</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:hospital-line-duotone"></iconify-icon></span>
                                <input type="text" id="name" name="name" class="form-control" value="{{ $polyclinic->name }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="branch_name" class="form-label">Cabang</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="mdi:source-branch"></iconify-icon></span>
                                <input type="text" id="branch_name" name="branch_name" class="form-control" value="{{ $polyclinic->branch->name ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="physical_location_type" class="form-label">Tipe Fisik Lokasi (Nomor Ruangan)</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="mdi:office-building-marker"></iconify-icon></span>
                                <input type="text" id="physical_location_type" name="physical_location_type" class="form-control" value="{{ $polyclinic->physical_location_type ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="description" class="form-label">Keterangan Singkat</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:document-text-line-duotone"></iconify-icon></span>
                                <textarea id="description" name="description" class="form-control" readonly>{{ $polyclinic->description ?? '-' }}</textarea>
                            </div>
                        </div>

                        <div class="col-12">
                            @can('update', $polyclinic)
                                <a href="{{ route('polyclinics.edit', $polyclinic->id) }}" class="btn btn-warning-600">Edit Poli Klinik</a>
                            @endcan
                            <a href="{{ route('polyclinics.index') }}" class="btn btn-secondary-light ms-2">Kembali ke Daftar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
