@extends('layout.layout')

@php
    $title = 'Detail Etiket/Aturan Pakai Obat';
    $subTitle = 'Informasi Etiket';
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
                            <label for="name" class="form-label">Nama Etiket</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:document-text-line-duotone"></iconify-icon></span>
                                <input type="text" id="name" name="name" class="form-control" value="{{ $label->name }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="description" class="form-label">Deskripsi</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:document-add-line-duotone"></iconify-icon></span>
                                <textarea id="description" name="description" class="form-control" readonly>{{ $label->description ?? '-' }}</textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            @can('update', $label)
                                <a href="{{ route('labels.edit', $label->id) }}" class="btn btn-warning-600">Edit Etiket</a>
                            @endcan
                            <a href="{{ route('labels.index') }}" class="btn btn-secondary-light ms-2">Kembali ke Daftar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
