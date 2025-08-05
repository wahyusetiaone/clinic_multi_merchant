@extends('layout.layout') {{-- Sesuaikan dengan layout utama aplikasi Anda --}}

@php
    $title = 'Daftar Petugas Medis';
    $subTitle = 'Manajemen Petugas Medis';
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

                    {{-- Tombol Tambah Petugas Medis Baru --}}
                    @can('create', App\Models\MedicalPersonnel::class) {{-- Aktifkan jika policy sudah dibuat --}}
                    <div class="mb-3">
                        <a href="{{ route('medical_personnel.create') }}" class="btn btn-primary">Tambah Petugas Medis Baru</a>
                    </div>
                    @endcan

                    <div class="table-responsive">
                        <table class="table basic-table mb-0">
                            <thead>
                            <tr>
                                <th>S.L</th>
                                <th>Kategori</th>
                                <th>NIK</th>
                                <th>Nama Petugas</th>
                                <th>Bagian / Spesialis</th>
                                <th>Cabang</th>
                                <th>No. Telepon</th>
                                <th>Email</th>
                                <th>Tgl. Mulai</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($medicalPersonnel as $personnel)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ ucfirst($personnel->category) }}</td>
                                    <td>{{ $personnel->nik }}</td>
                                    <td>{{ $personnel->name }}</td>
                                    <td>{{ $personnel->specialization ?? '-' }}</td>
                                    <td>{{ $personnel->branch->name ?? '-' }} ({{ $personnel->branch->organization->name ?? '-' }})</td>
                                    <td>{{ $personnel->phone_number ?? '-' }}</td>
                                    <td>{{ $personnel->email ?? '-' }}</td>
                                    <td>{{ $personnel->start_date->format('d-m-Y') }}</td>
                                    <td>
                                        @can('view', $personnel)
                                            <a href="{{ route('medical_personnel.show', $personnel->id) }}" class="btn btn-info btn-sm">Lihat</a>
                                        @endcan
                                        @can('update', $personnel)
                                            <a href="{{ route('medical_personnel.edit', $personnel->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        @endcan
                                        @can('delete', $personnel)
                                            <form action="{{ route('medical_personnel.destroy', $personnel->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data petugas medis {{ $personnel->name }}?')">Hapus</button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">Tidak ada data petugas medis yang tersedia.</td>
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
