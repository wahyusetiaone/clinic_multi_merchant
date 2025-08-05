@extends('layout.layout')

@php
    $title = 'Detail Kategori Obat';
    $subTitle = 'Informasi Kategori Obat';
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
                            <label for="name" class="form-label">Nama Kategori</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:tag-outline" class="menu-icon"></iconify-icon></span>
                                <input type="text" id="name" name="name" class="form-control" value="{{ $drugCategory->name }}" readonly>
                            </div>
                        </div>
                        <div class="col-12">
                            @can('update', $drugCategory)
                                <a href="{{ route('drug_categories.edit', $drugCategory->id) }}" class="btn btn-warning-600">Edit Kategori</a>
                            @endcan
                            <a href="{{ route('drug_categories.index') }}" class="btn btn-secondary-light ms-2">Kembali ke Daftar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
