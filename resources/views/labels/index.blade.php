@extends('layout.layout')

@php
    $title = 'Daftar Etiket/Aturan Pakai Obat';
    $subTitle = 'Manajemen Data Master';
@endphp

@section('content')
    <div class="row gy-4">
        <div class="col-lg-12">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ $title }}</h5>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @can('create', App\Models\Label::class)
                        <div class="mb-3">
                            <a href="{{ route('labels.create') }}" class="btn btn-primary">Tambah Etiket Baru</a>
                        </div>
                    @endcan

                    <div class="table-responsive">
                        <table class="table basic-table mb-0">
                            <thead>
                            <tr>
                                <th>S.L</th>
                                <th>Nama Etiket</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($labels as $label)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $label->name }}</td>
                                    <td>{{ $label->description ?? '-' }}</td>
                                    <td>
                                        @can('view', $label)
                                            <a href="{{ route('labels.show', $label->id) }}" class="btn btn-info btn-sm">Lihat</a>
                                        @endcan
                                        @can('update', $label)
                                            <a href="{{ route('labels.edit', $label->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        @endcan
                                        @can('delete', $label)
                                            <form action="{{ route('labels.destroy', $label->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus etiket {{ $label->name }}?')">Hapus</button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data etiket/aturan pakai yang tersedia.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
