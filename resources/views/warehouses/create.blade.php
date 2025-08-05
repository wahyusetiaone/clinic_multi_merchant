@extends('layout.layout')

@php
    $title='Tambah Gudang & Rak (Wizard)';
    $subTitle = 'Form Wizard Gudang';
    $script = ' <script>
        // =============================== Wizard Step Js Start ================================
        $(document).ready(function() {
            // Fungsi untuk validasi langkah
            function validateStep(fieldset) {
                let isValid = true;

                // Validasi input standar di fieldset
                fieldset.find(".wizard-required").each(function(){
                    if ($(this).val() === "" || ($(this).is("select") && $(this).val() === "")) { // Added validation for select
                        $(this).siblings(".wizard-form-error").show();
                        isValid = false;
                    } else {
                        $(this).siblings(".wizard-form-error").hide();
                    }
                });

                // Validasi input di dalam setiap entri rak yang dinamis (jika ada di fieldset ini)
                if (fieldset.find("#rack-entries").length) { // Hanya jalankan jika ada div rack-entries
                    if (fieldset.find(".rack-entry").length === 0) {
                        // Jika tidak ada rak sama sekali di langkah ini dan harus ada minimal 1
                        alert("Mohon tambahkan setidaknya satu Rak."); // Atau tampilkan pesan error yang lebih halus
                        isValid = false;
                    } else {
                        fieldset.find(".rack-entry").each(function() {
                            $(this).find(".rack-required").each(function() {
                                if ($(this).val() === "") {
                                    $(this).siblings(".wizard-form-error").show();
                                    isValid = false;
                                } else {
                                    $(this).siblings(".wizard-form-error").hide();
                                }
                            });
                        });
                    }
                }
                return isValid;
            }

            // Inisialisasi counter untuk rak
            let rackCounter = 0;

            // Fungsi untuk menambahkan entri rak baru
            function addRackEntry() {
                const template = `
                    <div class="card mb-3 rack-entry" id="rack-entry-${rackCounter}">
                        <div class="card-body">
                            <h6 class="mb-3">Rak Baru #${rackCounter + 1}</h6>
                            <div class="row gy-3">
                                <div class="col-md-12">
                                    <label for="racks_${rackCounter}_name" class="form-label">Nama Rak / Kode Rak*</label>
                                    <div class="position-relative">
                                        <input type="text" id="racks_${rackCounter}_name" name="racks[${rackCounter}][name]" class="form-control rack-required" placeholder="Cth: A1 atau Rak Obat" required>
                                        <div class="wizard-form-error">
                                            Mohon masukkan nama atau kode rak.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <button type="button" class="btn btn-danger btn-sm remove-rack-entry" data-id="${rackCounter}">Hapus Rak</button>
                            </div>
                        </div>
                    </div>
                `;
                $("#rack-entries").append(template);
                rackCounter++;
            }

            // Event handler untuk tombol "Tambah Rak"
            $(document).on("click", "#add-rack-btn", function() {
                addRackEntry();
            });

            // Event handler untuk tombol "Hapus Rak" (menggunakan delegasi event)
            $(document).on("click", ".remove-rack-entry", function() {
                const idToRemove = $(this).data("id");
                $("#rack-entry-" + idToRemove).remove();
            });

            // click on next button
            $(".form-wizard-next-btn").on("click", function() {
                var parentFieldset = $(this).parents(".wizard-fieldset");
                var currentActiveStep = $(this).parents(".form-wizard").find(".form-wizard-list__item.active");
                var next = $(this);

                if (!validateStep(parentFieldset)) {
                    return false; // Hentikan jika validasi gagal
                }

                next.parents(".wizard-fieldset").removeClass("show");
                currentActiveStep.removeClass("active").addClass("activated").next().addClass("active");
                next.parents(".wizard-fieldset").next(".wizard-fieldset").addClass("show");

                // Update progress bar/step indicator
                $(document).find(".form-wizard-list__item").each(function(){
                    if($(this).hasClass("active")){
                        var innerWidth = $(this).innerWidth();
                        var position = $(this).position();
                        $(document).find(".form-wizard-step-move").css({"left": position.left, "width": innerWidth});
                    }
                });
            });

            //click on previous button
            $(".form-wizard-previous-btn").on("click",function() {
                var prev =$(this);
                var currentActiveStep = $(this).parents(".form-wizard").find(".form-wizard-list__item.active");
                prev.parents(".wizard-fieldset").removeClass("show");
                prev.parents(".wizard-fieldset").prev(".wizard-fieldset").addClass("show");
                currentActiveStep.removeClass("active").prev().removeClass("activated").addClass("active");

                // Update progress bar/step indicator
                $(document).find(".form-wizard-list__item").each(function(){
                    if($(this).hasClass("show")){
                        var formAtrr = $(this).attr("data-tab-content");
                        $(document).find(".form-wizard-list__item").each(function(){
                            if($(this).attr("data-attr") == formAtrr){
                                $(this).addClass("active");
                                var innerWidth = $(this).innerWidth();
                                var position = $(this).position();
                                $(document).find(".form-wizard-step-move").css({"left": position.left, "width": innerWidth});
                            }else{
                                $(this).removeClass("active");
                            }
                        });
                    }
                    if($(this).hasClass("active")){
                        var innerWidth = $(this).innerWidth();
                        var position = $(this).position();
                        $(document).find(".form-wizard-step-move").css({"left": position.left, "width": innerWidth});
                    }
                });
            });

            // click on form submit button
            $(document).on("click",".form-wizard .form-wizard-submit" , function(){
                var parentFieldset = $(this).parents(".wizard-fieldset");
                if (!validateStep(parentFieldset)) {
                    return false; // Hentikan jika validasi gagal
                }
                // Jika validasi berhasil, submit form
                $("#warehouseWizardForm").submit();
            });

            // focus on input field check empty or not
            $(".form-control").on("focus", function(){
                var tmpThis = $(this).val();
                if(tmpThis == "" ) {
                    $(this).parent().addClass("focus-input");
                }
                else if(tmpThis !="" ){
                    $(this).parent().addClass("focus-input");
                }
            }).on("blur", function(){
                var tmpThis = $(this).val();
                if(tmpThis == "" ) {
                    $(this).parent().removeClass("focus-input");
                }
                else if(tmpThis !="" ){
                    $(this).parent().addClass("focus-input");
                    $(this).siblings(".wizard-form-error").hide();
                }
            });

            // Inisialisasi posisi progress bar saat halaman dimuat
            var initialActiveStep = $(".form-wizard-list__item.active");
            if (initialActiveStep.length > 0) {
                var initialWidth = initialActiveStep.innerWidth();
                var initialPosition = initialActiveStep.position();
                $(document).find(".form-wizard-step-move").css({"left": initialPosition.left, "width": initialWidth});
            }
        });
        // =============================== Wizard Step Js End ================================
    </script>';

@endphp

@section('content')

    <div class="row gy-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-4 text-xl">Buat Gudang dan Rak Anda</h6>
                    <p class="text-neutral-500">Lengkapi detail gudang dan rak.</p>

                    <div class="form-wizard">
                        <form action="{{ route('warehouses.store') }}" method="POST" id="warehouseWizardForm">
                            @csrf
                            <div class="form-wizard-header overflow-x-auto scroll-sm pb-8 my-32">
                                <ul class="list-unstyled form-wizard-list style-two">
                                    <li class="form-wizard-list__item active" data-tab-content="data-gudang">
                                        <div class="form-wizard-list__line">
                                            <span class="count">1</span>
                                        </div>
                                        <span class="text text-xs fw-semibold">Data Gudang</span>
                                    </li>
                                    <li class="form-wizard-list__item" data-tab-content="data-rak">
                                        <div class="form-wizard-list__line">
                                            <span class="count">2</span>
                                        </div>
                                        <span class="text text-xs fw-semibold">Data Rak</span>
                                    </li>
                                    <li class="form-wizard-list__item" data-tab-content="selesai">
                                        <div class="form-wizard-list__line">
                                            <span class="count">3</span>
                                        </div>
                                        <span class="text text-xs fw-semibold">Selesai</span>
                                    </li>
                                </ul>
                                <div class="form-wizard-step-move"></div>
                            </div>

                            <fieldset class="wizard-fieldset show" data-tab-content="data-gudang">
                                <h6 class="text-md text-neutral-500">Informasi Dasar Gudang</h6>
                                <div class="row gy-3">
                                    {{-- Kode Gudang dan Keterangan Singkat dihapus --}}
                                    <div class="col-sm-6">
                                        <label class="form-label">Nama Gudang*</label>
                                        <div class="position-relative">
                                            <input type="text" id="name" name="name" class="form-control wizard-required @error('name') is-invalid @enderror" placeholder="Cth: Gudang Utama" value="{{ old('name') }}" required>
                                            <div class="wizard-form-error">
                                                Mohon masukkan nama gudang.
                                            </div>
                                            @error('name')<div class="error-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="branch_id" class="form-label">Cabang*</label>
                                        <div class="position-relative">
                                            <select id="branch_id" name="branch_id" class="form-select wizard-required @error('branch_id') is-invalid @enderror" required>
                                                <option value="">Pilih Cabang</option>
                                                {{-- Anda perlu memastikan variabel $branches tersedia dari controller --}}
                                                @isset($branches)
                                                    @foreach($branches as $branch)
                                                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                                    @endforeach
                                                @else
                                                    {{-- Fallback jika $branches tidak tersedia (misal saat pengembangan awal) --}}
                                                    <option value="1">Cabang Default (ID: 1)</option>
                                                    {{-- Hapus ini di produksi --}}
                                                @endisset
                                            </select>
                                            <div class="wizard-form-error">
                                                Mohon pilih cabang.
                                            </div>
                                            @error('branch_id')<div class="error-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check mt-3">
                                            <input class="form-check-input" type="checkbox" id="is_default" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_default">
                                                Atur sebagai gudang utama (default)
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group text-end">
                                        <button type="button" class="form-wizard-next-btn btn btn-primary-600 px-32">Selanjutnya</button>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="wizard-fieldset" data-tab-content="data-rak">
                                <h6 class="text-md text-neutral-500 mb-4">Tambahkan data rak untuk gudang ini.</h6>
                                <div id="rack-entries">
                                    {{-- Rak akan ditambahkan di sini oleh JavaScript --}}
                                </div>
                                <div class="d-flex justify-content-center mb-4">
                                    <button type="button" id="add-rack-btn" class="btn btn-secondary-light btn-sm">
                                        <iconify-icon icon="ic:round-add"></iconify-icon> Tambah Rak Baru
                                    </button>
                                </div>
                                <div class="form-group d-flex align-items-center justify-content-end gap-8">
                                    <button type="button" class="form-wizard-previous-btn btn btn-neutral-500 border-neutral-100 px-32">Sebelumnya</button>
                                    <button type="button" class="form-wizard-next-btn btn btn-primary-600 px-32" onclick="if ($('#rack-entries .rack-entry').length === 0) { addRackEntry(); return false; }">Selanjutnya</button>
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
                                    <button type="button" class="form-wizard-submit btn btn-primary-600 px-32">Simpan Gudang & Rak</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
