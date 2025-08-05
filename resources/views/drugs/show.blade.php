@extends('layout.layout')

@php
    $title = 'Detail Obat';
    $subTitle = 'Informasi Obat';
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
                            <label for="code" class="form-label">Kode Obat</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="mdi:form-textbox"></iconify-icon></span>
                                <input type="text" id="code" name="code" class="form-control" value="{{ $drug->code }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama Obat</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:pill-broken"></iconify-icon></span>
                                <input type="text" id="name" name="name" class="form-control" value="{{ $drug->name }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="branch_name" class="form-label">Cabang</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:building-3-line-duotone"></iconify-icon></span>
                                <input type="text" id="branch_name" name="branch_name" class="form-control" value="{{ $drug->branch->name ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="manufacturer_name" class="form-label">Pabrik</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:factory-broken"></iconify-icon></span>
                                <input type="text" id="manufacturer_name" name="manufacturer_name" class="form-control" value="{{ $drug->manufacturer->name ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="group_name" class="form-label">Golongan</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:tag-broken"></iconify-icon></span>
                                <input type="text" id="group_name" name="group_name" class="form-control" value="{{ $drug->drugGroup->name ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="category_name" class="form-label">Kategori</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:folder-broken"></iconify-icon></span>
                                <input type="text" id="category_name" name="category_name" class="form-control" value="{{ $drug->drugCategory->name ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="drug_type" class="form-label">Jenis Obat</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:medicine-broken"></iconify-icon></span>
                                <input type="text" id="drug_type" name="drug_type" class="form-control" value="{{ $drug->drug_type }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="min_stock" class="form-label">Minimal Stok</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:box-minimalistic-line-duotone"></iconify-icon></span>
                                <input type="text" id="min_stock" name="min_stock" class="form-control" value="{{ $drug->min_stock ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="packaging" class="form-label">Kemasan / Sediaan</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:box-broken"></iconify-icon></span>
                                <input type="text" id="packaging" name="packaging" class="form-control" value="{{ $drug->packaging ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="precursor_active_ingredient" class="form-label">Zat Aktif Prekursor</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:chemical-weapon-broken"></iconify-icon></span>
                                <input type="text" id="precursor_active_ingredient" name="precursor_active_ingredient" class="form-control" value="{{ $drug->precursor_active_ingredient ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="label_name" class="form-label">Aturan Pakai / Etiket</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:notebook-broken"></iconify-icon></span>
                                <input type="text" id="label_name" name="label_name" class="form-control" value="{{ $drug->label->name ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="description" class="form-label">Deskripsi</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:document-text-line-duotone"></iconify-icon></span>
                                <textarea id="description" name="description" class="form-control" readonly>{{ $drug->description ?? '-' }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="indication" class="form-label">Indikasi</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:heart-line-duotone"></iconify-icon></span>
                                <textarea id="indication" name="indication" class="form-control" readonly>{{ $drug->indication ?? '-' }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="content" class="form-label">Kandungan</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:flask-minimalistic-broken"></iconify-icon></span>
                                <textarea id="content" name="content" class="form-control" readonly>{{ $drug->content ?? '-' }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="dosage" class="form-label">Dosis</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:syringe-broken"></iconify-icon></span>
                                <textarea id="dosage" name="dosage" class="form-control" readonly>{{ $drug->dosage ?? '-' }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="side_effects" class="form-label">Efek Samping</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:danger-triangle-broken"></iconify-icon></span>
                                <textarea id="side_effects" name="side_effects" class="form-control" readonly>{{ $drug->side_effects ?? '-' }}</textarea>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6 class="mb-3">Detail Harga Obat:</h6>
                        <div class="col-md-6">
                            <label for="hna" class="form-label">Harga Beli (HNA)</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:dollar-broken"></iconify-icon></span>
                                <input type="text" id="hna" name="hna" class="form-control" value="{{ number_format($drug->drugDetail->hna ?? 0, 2) }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="selling_price_1" class="form-label">Harga Jual 1</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:tag-price-broken"></iconify-icon></span>
                                <input type="text" id="selling_price_1" name="selling_price_1" class="form-control" value="{{ number_format($drug->drugDetail->selling_price_1 ?? 0, 2) }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="discount_1" class="form-label">Diskon Harga 1 (%)</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:sale-tag-broken"></iconify-icon></span>
                                <input type="text" id="discount_1" name="discount_1" class="form-control" value="{{ number_format($drug->drugDetail->discount_1 ?? 0, 2) }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="selling_price_2" class="form-label">Harga Jual 2</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:tag-price-line-duotone"></iconify-icon></span>
                                <input type="text" id="selling_price_2" name="selling_price_2" class="form-control" value="{{ number_format($drug->drugDetail->selling_price_2 ?? 0, 2) }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="discount_2" class="form-label">Diskon Harga 2 (%)</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:sale-tag-line-duotone"></iconify-icon></span>
                                <input type="text" id="discount_2" name="discount_2" class="form-control" value="{{ number_format($drug->drugDetail->discount_2 ?? 0, 2) }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="selling_price_3" class="form-label">Harga Jual 3</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:tag-price-outline"></iconify-icon></span>
                                <input type="text" id="selling_price_3" name="selling_price_3" class="form-control" value="{{ number_format($drug->drugDetail->selling_price_3 ?? 0, 2) }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="discount_3" class="form-label">Diskon Harga 3 (%)</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:sale-tag-outline"></iconify-icon></span>
                                <input type="text" id="discount_3" name="discount_3" class="form-control" value="{{ number_format($drug->drugDetail->discount_3 ?? 0, 2) }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="barcode" class="form-label">Barcode</label>
                            <div class="icon-field">
                                <span class="icon"><iconify-icon icon="solar:barcode-broken"></iconify-icon></span>
                                <input type="text" id="barcode" name="barcode" class="form-control" value="{{ $drug->drugDetail->barcode ?? '-' }}" readonly>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6 class="mb-3">Daftar Stok Obat:</h6>
                        <div class="table-responsive">
                            <table class="table basic-table mb-0">
                                <thead>
                                <tr>
                                    <th>S.L</th>
                                    <th>Gudang</th>
                                    <th>Lokasi</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                    <th>No. Batch</th>
                                    <th>Tanggal Kedaluwarsa</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($drug->drugStocks as $stock)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $stock->warehouse->name ?? '-' }} ({{ $stock->warehouse->branch->name ?? '-' }})</td>
                                        <td>{{ $stock->location->name ?? '-' }}</td>
                                        <td>{{ $stock->stock_quantity }}</td>
                                        <td>{{ $stock->unit->name ?? '-' }}</td>
                                        <td>{{ $stock->batch_number }}</td>
                                        <td>{{ \Carbon\Carbon::parse($stock->expiration_date)->format('d-m-Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada stok obat untuk obat ini.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="col-12 mt-4">
                            @can('update', $drug)
                                <a href="{{ route('drugs.edit', $drug->id) }}" class="btn btn-warning-600">Edit Obat</a>
                            @endcan
                            <a href="{{ route('drugs.index') }}" class="btn btn-secondary-light ms-2">Kembali ke Daftar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
