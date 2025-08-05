@extends('layout.layout')

@php
    $title = 'Daftar Poli Klinik';
    $subTitle = 'Manajemen Poli';
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

                    @can('create', App\Models\PolyClinic::class)
                        <div class="mb-3">
                            <a href="{{ route('polyclinics.create') }}" class="btn btn-primary">Tambah Poli Klinik Baru</a>
                        </div>
                    @endcan

                    <div class="table-responsive">
                        <table class="table basic-table mb-0">
                            <thead>
                            <tr>
                                <th>S.L</th>
                                <th>Kode Poli</th>
                                <th>Nama Poli</th>
                                <th>Cabang</th>
                                <th>Lokasi Fisik</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($polyclinics as $polyclinic)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $polyclinic->code }}</td>
                                    <td>{{ $polyclinic->name }}</td>
                                    <td>{{ $polyclinic->branch->name ?? '-' }}</td>
                                    <td>{{ $polyclinic->physical_location_type ?? '-' }}</td>
                                    <td>{{ Str::limit($polyclinic->description, 50) ?? '-' }}</td>
                                    <td class="action-links">
                                        @can('view', $polyclinic)
                                            <a href="{{ route('polyclinics.show', $polyclinic->id) }}" class="btn btn-info btn-sm">Lihat</a>
                                        @endcan
                                        @can('update', $polyclinic)
                                            <a href="{{ route('polyclinics.edit', $polyclinic->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        @endcan
                                        @can('delete', $polyclinic)
                                            <form action="{{ route('polyclinics.destroy', $polyclinic->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus poli ini?')">Hapus</button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data poli klinik yang tersedia.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div></div>
    </div>

@endsection
