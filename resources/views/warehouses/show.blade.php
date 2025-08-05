@extends('layout.layout')

@php
    $title = 'Detail Gudang';
    $subTitle = 'Informasi Gudang';
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

                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama Gudang</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:warehouse-broken"></iconify-icon></span>
                                <input type="text" id="name" name="name" class="form-control" value="{{ $warehouse->name }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="branch_name" class="form-label">Cabang</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:building-3-line-duotone"></iconify-icon></span>
                                <input type="text" id="branch_name" name="branch_name" class="form-control" value="{{ $warehouse->branch->name ?? 'N/A' }}" readonly>
                            </div>
                        </div>

                        <div class="col-12">
                            @can('update', $warehouse)
                                <a href="{{ route('warehouses.edit', $warehouse->id) }}" class="btn btn-warning-600">Edit Gudang</a>
                            @endcan
                            <a href="{{ route('warehouses.index') }}" class="btn btn-secondary-light ms-2">Kembali ke Daftar</a>
                        </div>
                    </form>

                    <hr class="my-4">

                    <h6 class="mb-3">Daftar Lokasi Penyimpanan:</h6>
                    <div class="table-responsive">
                        <table class="table basic-table mb-0">
                            <thead>
                            <tr>
                                <th>S.L</th>
                                <th>Nama Lokasi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($warehouse->locations as $location)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $location->name }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Belum ada lokasi penyimpanan untuk gudang ini.</td>
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
