@extends('layout.layout') {{-- Pastikan ini sesuai dengan layout utama Anda --}}

@php
    $title = 'Daftar Pegawai';
    $subTitle = 'Manajemen Pegawai';
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

                    @can('create', App\Models\Employee::class)
                        <div class="mb-3">
                            <a href="{{ route('employees.create') }}" class="btn btn-primary">Tambah Pegawai Baru</a>
                        </div>
                    @endcan

                    <div class="table-responsive">
                        <table class="table basic-table mb-0">
                            <thead>
                            <tr>
                                <th>S.L</th>
                                <th>Nama</th>
                                <th>Organisasi</th>
                                <th>Cabang</th>
                                <th>Jabatan</th>
                                <th>Akun Pengguna</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($employees as $employee)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $employee->name }}</td>
                                    <td>{{ $employee->organization->name ?? '-' }}</td>
                                    <td>{{ $employee->branch->name ?? '-' }}</td>
                                    <td>{{ $employee->position->name ?? '-' }}</td>
                                    <td>{{ $employee->user->email ?? 'Tidak Ada Akun' }}</td>
                                    <td class="action-links">
                                        @can('view', $employee)
                                            <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-info btn-sm">Lihat</a>
                                        @endcan
                                        @can('update', $employee)
                                            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        @endcan
                                        @can('delete', $employee)
                                            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pegawai ini?')">Hapus</button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data pegawai yang tersedia.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div></div>
    </div>

@endsection
