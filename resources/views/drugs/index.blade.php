@extends('layout.layout')

@php
    $title = 'Daftar Obat';
    $subTitle = 'Manajemen Obat';
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
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @can('create', App\Models\Drug::class)
                        <div class="mb-3">
                            <a href="{{ route('drugs.create') }}" class="btn btn-primary">Tambah Obat Baru</a>
                        </div>
                    @endcan

                    <div class="table-responsive">
                        <table class="table basic-table mb-0">
                            <thead>
                            <tr>
                                <th>S.L</th>
                                <th>Kode Obat</th>
                                <th>Nama Obat</th>
                                <th>Jenis</th>
                                <th>Pabrik</th>
                                <th>Golongan</th>
                                <th>Kategori</th>
                                <th>Stok Min.</th>
                                <th>Cabang</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($drugs as $drug)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $drug->code }}</td>
                                    <td>{{ $drug->name }}</td>
                                    <td>{{ $drug->drug_type }}</td>
                                    <td>{{ $drug->manufacturer->name ?? '-' }}</td>
                                    <td>{{ $drug->drugGroup->name ?? '-' }}</td>
                                    <td>{{ $drug->drugCategory->name ?? '-' }}</td>
                                    <td>{{ $drug->min_stock ?? '-' }}</td>
                                    <td>{{ $drug->branch->name ?? '-' }}</td>
                                    <td>
                                        @can('view', $drug)
                                            <a href="{{ route('drugs.show', $drug->id) }}" class="btn btn-info btn-sm">Lihat</a>
                                        @endcan
                                        @can('update', $drug)
                                            <a href="{{ route('drugs.edit', $drug->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        @endcan
                                        @can('delete', $drug)
                                            <form action="{{ route('drugs.destroy', $drug->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus obat ini?')">Hapus</button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">Tidak ada data obat yang tersedia.</td>
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
