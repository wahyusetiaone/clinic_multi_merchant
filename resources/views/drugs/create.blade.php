@extends('layout.layout')

@php
    $title = 'Tambah Obat Baru (Wizard)';
    $subTitle = 'Form Wizard Obat';
    $script = '<script>
        // =============================== Wizard Step Js Start ================================
        $(document).ready(function() {
            // Fungsi untuk validasi langkah
            function validateStep(fieldset) {
                let isValid = true;
                fieldset.find(".wizard-required").each(function(){
                    if ($(this).val() === "" || ($(this).is("select") && $(this).val() === "")) {
                        $(this).siblings(".wizard-form-error").show();
                        isValid = false;
                    } else {
                        $(this).siblings(".wizard-form-error").hide();
                    }
                });
                return isValid;
            }

            // Fungsi untuk menambahkan entri stok baru
            window.addStockEntry = function() {
                const stockEntryTemplate = `
                    <div class="stock-entry row mb-3 p-3 border rounded shadow-sm position-relative">
                        <button type="button" class="btn-close remove-stock-entry position-absolute top-0 end-0 m-2" aria-label="Close"></button>
                        <div class="col-md-6 mb-3">
                            <label for="warehouse_id" class="form-label">Gudang</label>
                            <div class="icon-field has-validation">
                                <span class="icon"><iconify-icon icon="solar:warehouse-broken"></iconify-icon></span>
                                <select name="stocks[\${Date.now()}][warehouse_id]" class="form-control wizard-required warehouse-select">
                                    <option value="">Pilih Gudang</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }} ({{ $warehouse->branch->name ?? "N/A" }}")</option>
                                    @endforeach
                                </select>
                                <div class="wizard-form-error text-danger">Mohon pilih gudang.</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="location_id" class="form-label">Lokasi (Rak)</label>
                            <div class="icon-field has-validation">
                                <span class="icon"><iconify-icon icon="mdi:box-cutter-off"></iconify-icon></span>
                                <select name="stocks[\${Date.now()}][location_id]" class="form-control wizard-required location-select">
                                    <option value="">Pilih Lokasi</option>
                                </select>
                                <div class="wizard-form-error text-danger">Mohon pilih lokasi.</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="stock_quantity" class="form-label">Jumlah Stok</label>
                            <div class="icon-field has-validation">
                                <span class="icon"><iconify-icon icon="solar:box-minimalistic-line-duotone"></iconify-icon></span>
                                <input type="number" name="stocks[\${Date.now()}][stock_quantity]" class="form-control wizard-required" placeholder="Jumlah Stok" min="0">
                                <div class="wizard-form-error text-danger">Mohon masukkan jumlah stok.</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="unit_id" class="form-label">Satuan Stok</label>
                            <div class="icon-field has-validation">
                                <span class="icon"><iconify-icon icon="solar:box-minimalistic-line-duotone"></iconify-icon></span>
                                <select name="stocks[\${Date.now()}][unit_id]" class="form-control wizard-required">
                                    <option value="">Pilih Satuan</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                                <div class="wizard-form-error text-danger">Mohon pilih satuan.</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="batch_number" class="form-label">Nomor Batch</label>
                            <div class="icon-field has-validation">
                                <span class="icon"><iconify-icon icon="solar:tag-horizontal-line-duotone"></iconify-icon></span>
                                <input type="text" name="stocks[\${Date.now()}][batch_number]" class="form-control wizard-required" placeholder="Nomor Batch">
                                <div class="wizard-form-error text-danger">Mohon masukkan nomor batch.</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="expiration_date" class="form-label">Tanggal Kedaluwarsa</label>
                            <div class="icon-field has-validation">
                                <span class="icon"><iconify-icon icon="solar:calendar-date-line-duotone"></iconify-icon></span>
                                <input type="date" name="stocks[\${Date.now()}][expiration_date]" class="form-control wizard-required">
                                <div class="wizard-form-error text-danger">Mohon masukkan tanggal kedaluwarsa.</div>
                            </div>
                        </div>
                    </div>
                `;
                $("#stock-entries").append(stockEntryTemplate);
            };

            // Menghapus entri stok
            $(document).on("click", ".remove-stock-entry", function() {
                $(this).closest(".stock-entry").remove();
                if ($("#stock-entries .stock-entry").length === 0) {
                     // Optionally add a message if no entries
                }
            });

            // AJAX untuk memuat lokasi berdasarkan gudang
            $(document).on("change", ".warehouse-select", function() {
                const warehouseId = $(this).val();
                const locationSelect = $(this).closest(".stock-entry").find(".location-select");
                locationSelect.empty().append("<option value=\"\">Memuat Lokasi...</option>");

                if (warehouseId) {
                    $.ajax({
                        url: "'.route("get.locations.by.warehouse").'",
                        type: "GET",
                        data: { warehouse_id: warehouseId },
                        success: function(data) {
                            locationSelect.empty().append("<option value=\"\">Pilih Lokasi</option>");
                            if (data.length > 0) {
                                $.each(data, function(key, value) {
                                    locationSelect.append("<option value=\"" + value.id + "\">" + value.name + "</option>");
                                });
                            } else {
                                locationSelect.append("<option value=\"\">Tidak ada lokasi</option>");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching locations:", error);
                            locationSelect.empty().append("<option value=\"\">Gagal memuat lokasi</option>");
                        }
                    });
                } else {
                    locationSelect.empty().append("<option value=\"\">Pilih Lokasi</option>");
                }
            });

            // Wizard Navigation
            $(".form-wizard-next-btn").on("click", function() {
                var parentFieldset = $(this).parents(".wizard-fieldset");
                if (validateStep(parentFieldset)) {
                    var currentActiveStep = $(this).parents(".form-wizard").find(".form-wizard-list .active");
                    currentActiveStep.removeClass("active").addClass("activated").next().addClass("active");
                    parentFieldset.removeClass("show").next(".wizard-fieldset").addClass("show");
                }
            });

            $(".form-wizard-previous-btn").on("click", function() {
                var parentFieldset = $(this).parents(".wizard-fieldset");
                var currentActiveStep = $(this).parents(".form-wizard").find(".form-wizard-list .active");
                currentActiveStep.removeClass("active").prev().removeClass("activated").addClass("active");
                parentFieldset.removeClass("show").prev(".wizard-fieldset").addClass("show");
            });

            // Form Submit (last step)
            $(".form-wizard-submit").on("click", function(e) {
                e.preventDefault();
                var parentFieldset = $(this).parents(".wizard-fieldset");
                if (validateStep(parentFieldset)) {
                     // Add an explicit check for at least one stock entry if on the stock tab
                    if (parentFieldset.data("tab-content") === "stok" && $("#stock-entries .stock-entry").length === 0) {
                        alert("Mohon tambahkan setidaknya satu entri stok.");
                        return;
                    }
                    $("#drug-form").submit();
                }
            });
        });
    </script>';
@endphp

@section('content')
    <div class="row gy-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ $title }}</h5>
                    <p class="text-neutral-500">Lengkapi detail obat, harga dan stok.</p>
                </div>
                <div class="card-body">
                    <div class="form-wizard">
                        <div class="form-wizard-header overflow-x-auto scroll-sm pb-8 my-32">
                            <ul class="list-unstyled form-wizard-list style-two">
                                <li class="form-wizard-list__item active" data-tab-content="info-dasar">
                                    <div class="form-wizard-list__line">
                                        <span class="count">1</span>
                                    </div>
                                    <span class="text text-xs fw-semibold">Info Dasar</span>
                                </li>
                                <li class="form-wizard-list__item" data-tab-content="detail-harga">
                                    <div class="form-wizard-list__line">
                                        <span class="count">2</span>
                                    </div>
                                    <span class="text text-xs fw-semibold">Detail Harga</span>
                                </li>
                                <li class="form-wizard-list__item" data-tab-content="stok">
                                    <div class="form-wizard-list__line">
                                        <span class="count">3</span>
                                    </div>
                                    <span class="text text-xs fw-semibold">Stok & Lokasi</span>
                                </li>
                                <li class="form-wizard-list__item" data-tab-content="selesai">
                                    <div class="form-wizard-list__line">
                                        <span class="count">4</span>
                                    </div>
                                    <span class="text text-xs fw-semibold">Selesai</span>
                                </li>
                            </ul>
                            <div class="form-wizard-step-move"></div>
                        </div>
                        <form action="{{ route('drugs.store') }}" method="POST" id="drug-form" class="form-wizard-form">
                            @csrf

                            <fieldset class="wizard-fieldset show" data-tab-content="info-dasar">
                                <h6 class="mb-3">Informasi Dasar Obat</h6>
                                <div class="row gy-3">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Nama Obat</label>
                                        <div class="icon-field has-validation">
                                            <span class="icon"><iconify-icon icon="solar:pill-broken"></iconify-icon></span>
                                            <input type="text" id="name" name="name" class="form-control wizard-required" value="{{ old('name') }}" placeholder="Nama Obat" required>
                                            <div class="wizard-form-error text-danger">Mohon masukkan nama obat.</div>
                                            @error('name')<div class="error-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="branch_id" class="form-label">Cabang</label>
                                        <div class="icon-field has-validation">
                                            <span class="icon"><iconify-icon icon="solar:building-3-line-duotone"></iconify-icon></span>
                                            <select id="branch_id" name="branch_id" class="form-control wizard-required" required>
                                                <option value="">Pilih Cabang</option>
                                                @foreach($branches as $branch)
                                                    <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="wizard-form-error text-danger">Mohon pilih cabang.</div>
                                            @error('branch_id')<div class="error-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="manufacturer_id" class="form-label">Pabrik (Optional)</label>
                                        <div class="icon-field">
                                            <span class="icon"><iconify-icon icon="solar:factory-broken"></iconify-icon></span>
                                            <select id="manufacturer_id" name="manufacturer_id" class="form-control">
                                                <option value="">Pilih Pabrik</option>
                                                @foreach($manufacturers as $manufacturer)
                                                    <option value="{{ $manufacturer->id }}" {{ old('manufacturer_id') == $manufacturer->id ? 'selected' : '' }}>{{ $manufacturer->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('manufacturer_id')<div class="error-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="group_id" class="form-label">Golongan (Optional)</label>
                                        <div class="icon-field">
                                            <span class="icon"><iconify-icon icon="solar:tag-broken"></iconify-icon></span>
                                            <select id="group_id" name="group_id" class="form-control">
                                                <option value="">Pilih Golongan</option>
                                                @foreach($drugGroups as $group)
                                                    <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('group_id')<div class="error-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="category_id" class="form-label">Kategori (Optional)</label>
                                        <div class="icon-field">
                                            <span class="icon"><iconify-icon icon="solar:folder-broken"></iconify-icon></span>
                                            <select id="category_id" name="category_id" class="form-control">
                                                <option value="">Pilih Kategori</option>
                                                @foreach($drugCategories as $category)
                                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('category_id')<div class="error-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="drug_type" class="form-label">Jenis Obat</label>
                                        <div class="icon-field has-validation">
                                            <span class="icon"><iconify-icon icon="solar:medicine-broken"></iconify-icon></span>
                                            <select id="drug_type" name="drug_type" class="form-control wizard-required" required>
                                                <option value="">Pilih Jenis</option>
                                                <option value="Non Konsinyasi" {{ old('drug_type') == 'Non Konsinyasi' ? 'selected' : '' }}>Non Konsinyasi</option>
                                                <option value="Konsinyasi" {{ old('drug_type') == 'Konsinyasi' ? 'selected' : '' }}>Konsinyasi</option>
                                            </select>
                                            <div class="wizard-form-error text-danger">Mohon pilih jenis obat.</div>
                                            @error('drug_type')<div class="error-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="min_stock" class="form-label">Minimal Stok (Optional)</label>
                                        <div class="icon-field">
                                            <span class="icon"><iconify-icon icon="solar:box-minimalistic-line-duotone"></iconify-icon></span>
                                            <input type="number" id="min_stock" name="min_stock" class="form-control" value="{{ old('min_stock') }}" placeholder="Minimal Stok" min="0">
                                            @error('min_stock')<div class="error-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="packaging" class="form-label">Kemasan / Sediaan (Optional)</label>
                                        <div class="icon-field">
                                            <span class="icon"><iconify-icon icon="solar:box-broken"></iconify-icon></span>
                                            <input type="text" id="packaging" name="packaging" class="form-control" value="{{ old('packaging') }}" placeholder="Contoh: Box, Strip, Botol">
                                            @error('packaging')<div class="error-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="precursor_active_ingredient" class="form-label">Zat Aktif Prekursor (Optional)</label>
                                        <div class="icon-field">
                                            <span class="icon"><iconify-icon icon="solar:chemical-weapon-broken"></iconify-icon></span>
                                            <input type="text" id="precursor_active_ingredient" name="precursor_active_ingredient" class="form-control" value="{{ old('precursor_active_ingredient') }}" placeholder="Zat Aktif Prekursor (jika ada)">
                                            @error('precursor_active_ingredient')<div class="error-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="label_id" class="form-label">Aturan Pakai / Etiket (Optional)</label>
                                        <div class="icon-field">
                                            <span class="icon"><iconify-icon icon="solar:notebook-broken"></iconify-icon></span>
                                            <select id="label_id" name="label_id" class="form-control">
                                                <option value="">Pilih Etiket</option>
                                                @foreach($labels as $label)
                                                    <option value="{{ $label->id }}" {{ old('label_id') == $label->id ? 'selected' : '' }}>{{ $label->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('label_id')<div class="error-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="description" class="form-label">Deskripsi (Optional)</label>
                                        <div class="icon-field">
                                            <span class="icon"><iconify-icon icon="solar:document-text-line-duotone"></iconify-icon></span>
                                            <textarea id="description" name="description" class="form-control" placeholder="Deskripsi obat">{{ old('description') }}</textarea>
                                            @error('description')<div class="error-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="indication" class="form-label">Indikasi (Optional)</label>
                                        <div class="icon-field">
                                            <span class="icon"><iconify-icon icon="solar:heart-line-duotone"></iconify-icon></span>
                                            <textarea id="indication" name="indication" class="form-control" placeholder="Indikasi penggunaan obat">{{ old('indication') }}</textarea>
                                            @error('indication')<div class="error-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="content" class="form-label">Kandungan (Optional)</label>
                                        <div class="icon-field">
                                            <span class="icon"><iconify-icon icon="solar:flask-minimalistic-broken"></iconify-icon></span>
                                            <textarea id="content" name="content" class="form-control" placeholder="Kandungan atau komposisi obat">{{ old('content') }}</textarea>
                                            @error('content')<div class="error-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="dosage" class="form-label">Dosis (Optional)</label>
                                        <div class="icon-field">
                                            <span class="icon"><iconify-icon icon="solar:syringe-broken"></iconify-icon></span>
                                            <textarea id="dosage" name="dosage" class="form-control" placeholder="Dosis penggunaan">{{ old('dosage') }}</textarea>
                                            @error('dosage')<div class="error-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="side_effects" class="form-label">Efek Samping (Optional)</label>
                                        <div class="icon-field">
                                            <span class="icon"><iconify-icon icon="solar:danger-triangle-broken"></iconify-icon></span>
                                            <textarea id="side_effects" name="side_effects" class="form-control" placeholder="Efek samping yang mungkin terjadi">{{ old('side_effects') }}</textarea>
                                            @error('side_effects')<div class="error-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group d-flex align-items-center justify-content-end gap-8 mt-4">
                                    <button type="button" class="form-wizard-next-btn btn btn-primary-600 px-32">Selanjutnya</button>
                                </div>
                            </fieldset>

                            <fieldset class="wizard-fieldset" data-tab-content="detail-harga">
                                <h6 class="mb-3">Detail Harga Obat</h6>
                                <div class="row gy-3">
                                    <div class="col-md-6 mb-3">
                                        <label for="hna" class="form-label">Harga Beli (HNA)</label>
                                        <div class="icon-field has-validation">
                                            <span class="icon"><iconify-icon icon="solar:dollar-broken"></iconify-icon></span>
                                            <input type="number" step="0.01" id="hna" name="hna" class="form-control wizard-required" value="{{ old('hna') }}" placeholder="Harga Beli (HNA)" min="0" required>
                                            <div class="wizard-form-error text-danger">Mohon masukkan harga beli.</div>
                                            @error('hna')<div class="error-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="selling_price_1" class="form-label">Harga Jual 1</label>
                                        <div class="icon-field has-validation">
                                            <span class="icon"><iconify-icon icon="solar:tag-price-broken"></iconify-icon></span>
                                            <input type="number" step="0.01" id="selling_price_1" name="selling_price_1" class="form-control wizard-required" value="{{ old('selling_price_1') }}" placeholder="Harga Jual 1" min="0" required>
                                            <div class="wizard-form-error text-danger">Mohon masukkan harga jual 1.</div>
                                            @error('selling_price_1')<div class="error-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="discount_1" class="form-label">Diskon Harga 1 (%) (Optional)</label>
                                        <div class="icon-field">
                                            <span class="icon"><iconify-icon icon="solar:sale-tag-broken"></iconify-icon></span>
                                            <input type="number" step="0.01" id="discount_1" name="discount_1" class="form-control" value="{{ old('discount_1') }}" placeholder="Diskon Harga 1" min="0" max="100">
                                            @error('discount_1')<div class="error-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="selling_price_2" class="form-label">Harga Jual 2 (Optional)</label>
                                        <div class="icon-field">
                                            <span class="icon"><iconify-icon icon="solar:tag-price-line-duotone"></iconify-icon></span>
                                            <input type="number" step="0.01" id="selling_price_2" name="selling_price_2" class="form-control" value="{{ old('selling_price_2') }}" placeholder="Harga Jual 2" min="0">
                                            @error('selling_price_2')<div class="error-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="discount_2" class="form-label">Diskon Harga 2 (%) (Optional)</label>
                                        <div class="icon-field">
                                            <span class="icon"><iconify-icon icon="solar:sale-tag-line-duotone"></iconify-icon></span>
                                            <input type="number" step="0.01" id="discount_2" name="discount_2" class="form-control" value="{{ old('discount_2') }}" placeholder="Diskon Harga 2" min="0" max="100">
                                            @error('discount_2')<div class="error-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="selling_price_3" class="form-label">Harga Jual 3 (Optional)</label>
                                        <div class="icon-field">
                                            <span class="icon"><iconify-icon icon="solar:tag-price-outline"></iconify-icon></span>
                                            <input type="number" step="0.01" id="selling_price_3" name="selling_price_3" class="form-control" value="{{ old('selling_price_3') }}" placeholder="Harga Jual 3" min="0">
                                            @error('selling_price_3')<div class="error-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="discount_3" class="form-label">Diskon Harga 3 (%) (Optional)</label>
                                        <div class="icon-field">
                                            <span class="icon"><iconify-icon icon="solar:sale-tag-outline"></iconify-icon></span>
                                            <input type="number" step="0.01" id="discount_3" name="discount_3" class="form-control" value="{{ old('discount_3') }}" placeholder="Diskon Harga 3" min="0" max="100">
                                            @error('discount_3')<div class="error-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="barcode" class="form-label">Barcode (Optional)</label>
                                        <div class="icon-field">
                                            <span class="icon"><iconify-icon icon="solar:barcode-broken"></iconify-icon></span>
                                            <input type="text" id="barcode" name="barcode" class="form-control" value="{{ old('barcode') }}" placeholder="Barcode Obat">
                                            @error('barcode')<div class="error-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group d-flex align-items-center justify-content-end gap-8 mt-4">
                                    <button type="button" class="form-wizard-previous-btn btn btn-neutral-500 border-neutral-100 px-32">Sebelumnya</button>
                                    <button type="button" class="form-wizard-next-btn btn btn-primary-600 px-32">Selanjutnya</button>
                                </div>
                            </fieldset>

                            <fieldset class="wizard-fieldset" data-tab-content="stok">
                                <h6 class="mb-3">Informasi Stok Obat</h6>
                                <p class="text-neutral-400 text-sm mb-3">Tambahkan setidaknya satu lokasi stok untuk obat ini.</p>
                                <div class="row gy-3">
                                    <div class="col-12">
                                        <div id="stock-entries"></div>
                                        <div class="d-flex justify-content-center mb-4">
                                            <button type="button" class="btn btn-secondary" onclick="addStockEntry()">
                                                <iconify-icon icon="ic:round-add"></iconify-icon> Tambah Entri Stok
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group d-flex align-items-center justify-content-end gap-8 mt-4">
                                    <button type="button" class="form-wizard-previous-btn btn btn-neutral-500 border-neutral-100 px-32">Sebelumnya</button>
                                    <button type="button" class="form-wizard-next-btn btn btn-primary-600 px-32">Selanjutnya</button>
                                </div>
                            </fieldset>

                            <fieldset class="wizard-fieldset" data-tab-content="selesai">
                                <div class="text-center mb-40">
                                    <img src="{{ asset('assets/images/gif/success-img3.gif') }}" alt="" class="gif-image mb-24">
                                    <h6 class="text-md text-neutral-600">Form Hampir Selesai!</h6>
                                    <p class="text-neutral-400 text-sm mb-0">Pastikan semua data sudah benar sebelum menyimpan.</p>
                                </div>
                                <div class="form-group d-flex align-items-center justify-content-end gap-8">
                                    <button type="button" class="form-wizard-previous-btn btn btn-neutral-500 border-neutral-100 px-32">Sebelumnya</button>
                                    <button type="button" class="form-wizard-submit btn btn-primary-600 px-32">Simpan Obat</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
