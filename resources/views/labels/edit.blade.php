@extends('layout.layout')

@php
    $title = 'Edit Etiket/Aturan Pakai';
    $subTitle = 'Form Etiket';
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
                    <form action="{{ route('labels.update', $label->id) }}" method="POST" class="row gy-3 needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="col-md-12">
                            <label for="name" class="form-label">Nama Etiket <span class="text-danger">*</span></label>
                            <div class="icon-field has-validation">
                                <span class="icon"><iconify-icon icon="solar:document-text-line-duotone"></iconify-icon></span>
                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Etiket (contoh: 3x sehari setelah makan)" value="{{ old('name', $label->name) }}" required>
                                <div class="invalid-feedback">
                                    Mohon masukkan nama etiket.
                                </div>
                                @error('name')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="description" class="form-label">Deskripsi (Opsional)</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:document-add-line-duotone"></iconify-icon></span>
                                <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" placeholder="Deskripsi singkat etiket">{{ old('description', $label->description) }}</textarea>
                                <div class="invalid-feedback">
                                    Mohon masukkan deskripsi.
                                </div>
                                @error('description')<div class="error-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary-600" type="submit">Update Etiket</button>
                            <a href="{{ route('labels.index') }}" class="btn btn-secondary-light ms-2">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
