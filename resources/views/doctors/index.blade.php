@extends('layout.layout') {{-- Sesuaikan dengan layout utama aplikasi Anda --}}

@php
    $title = 'Daftar Dokter';
    $subTitle = 'Manajemen Dokter';
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

                    {{-- Tombol Tambah Dokter Baru --}}
                    {{-- @can('create', App\Models\Doctor::class) --}} {{-- Aktifkan jika policy sudah dibuat --}}
                    <div class="mb-3">
                        <a href="{{ route('doctors.create') }}" class="btn btn-primary">Tambah Dokter Baru</a>
                    </div>
                    {{-- @endcan --}}

                    <div class="table-responsive">
                        <table class="table basic-table mb-0">
                            <thead>
                            <tr>
                                <th>S.L</th>
                                <th>NIK</th>
                                <th>Nama Dokter</th>
                                <th>Spesialisasi</th>
                                <th>Cabang</th>
                                <th>No. Telepon</th>
                                <th>STR</th>
                                <th>Tgl. Mulai</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($doctors as $doctor)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $doctor->nik }}</td>
                                    <td>{{ $doctor->name }}</td>
                                    <td>{{ $doctor->specialization ?? '-' }}</td>
                                    <td>{{ $doctor->branch->name ?? '-' }} ({{ $doctor->branch->organization->name ?? '-' }})</td>
                                    <td>{{ $doctor->phone_number ?? '-' }}</td>
                                    <td>{{ $doctor->str_number ?? '-' }}</td>
                                    <td>{{ $doctor->start_date->format('d-m-Y') }}</td>
                                    <td>
                                        {{-- @can('view', $doctor) --}}
                                        <a href="{{ route('doctors.show', $doctor->id) }}" class="btn btn-info btn-sm">Lihat</a>
                                        {{-- @endcan --}}
                                        {{-- @can('update', $doctor) --}}
                                        <a href="{{ route('doctors.edit', $doctor->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        {{-- @endcan --}}
                                        {{-- @can('delete', $doctor) --}}
                                        <form action="{{ route('doctors.destroy', $doctor->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data dokter {{ $doctor->name }}?')">Hapus</button>
                                        </form>
                                        {{-- @endcan --}}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada data dokter yang tersedia.</td>
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
