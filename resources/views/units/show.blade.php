@extends('layout.layout')

@php
    $title = 'Detail Satuan Obat';
    $subTitle = 'Informasi Satuan Obat';
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
                        <div class="col-md-12">
                            <label for="name" class="form-label">Nama Satuan</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:box-minimalistic-line-duotone"></iconify-icon></span>
                                <input type="text" id="name" name="name" class="form-control" value="{{ $unit->name }}" readonly>
                            </div>
                        </div>
                        <div class="col-12">
                            @can('update', $unit)
                                <a href="{{ route('units.edit', $unit->id) }}" class="btn btn-warning-600">Edit Satuan</a>
                            @endcan
                            <a href="{{ route('units.index') }}" class="btn btn-secondary-light ms-2">Kembali ke Daftar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
