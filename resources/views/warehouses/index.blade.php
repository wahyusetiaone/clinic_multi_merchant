@extends('layout.layout')

@php
    $title = 'Daftar Gudang';
    $subTitle = 'Manajemen Gudang';
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

                    @can('create', App\Models\Warehouse::class)
                        <div class="mb-3">
                            <a href="{{ route('warehouses.create') }}" class="btn btn-primary">Tambah Gudang Baru</a>
                        </div>
                    @endcan

                    <div class="table-responsive">
                        <table class="table basic-table mb-0">
                            <thead>
                            <tr>
                                <th>S.L</th>
                                <th>Nama Gudang</th>
                                <th>Cabang</th>
                                <th>Jumlah Lokasi</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($warehouses as $warehouse)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $warehouse->name }}</td>
                                    <td>{{ $warehouse->branch->name ?? 'N/A' }}</td>
                                    <td>{{ $warehouse->locations->count() }}</td>
                                    <td>
                                        @can('view', $warehouse)
                                            <a href="{{ route('warehouses.show', $warehouse->id) }}" class="btn btn-info btn-sm">Lihat</a>
                                        @endcan
                                        @can('update', $warehouse)
                                            <a href="{{ route('warehouses.edit', $warehouse->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        @endcan
                                        @can('delete', $warehouse)
                                            <form action="{{ route('warehouses.destroy', $warehouse->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus gudang ini beserta semua lokasinya?')">Hapus</button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data gudang yang tersedia.</td>
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
