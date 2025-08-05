@extends('layout.layout')

@php
    $title = 'Edit Kategori Obat';
    $subTitle = 'Form Kategori Obat';
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
                    <form action="{{ route('drug_categories.update', $drugCategory->id) }}" method="POST" class="row gy-3 needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="col-md-12">
                            <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                            <div class="icon-field has-validation">
                                <span class="icon"><iconify-icon icon="solar:tag-outline" class="menu-icon"></iconify-icon></span>
                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Kategori Obat" value="{{ old('name', $drugCategory->name) }}" required>
                                <div class="invalid-feedback">
                                    Mohon masukkan nama kategori obat.
                                </div>
                                @error('name')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary-600" type="submit">Update Kategori</button>
                            <a href="{{ route('drug_categories.index') }}" class="btn btn-secondary-light ms-2">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
