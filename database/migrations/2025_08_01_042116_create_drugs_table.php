<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('drugs', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Kode Obat (Otomatis dibuat nanti di Model/Controller)
            $table->string('name'); // Nama Obat
            $table->foreignId('manufacturer_id')->nullable()->constrained('manufacturers')->onDelete('set null'); // Pabrik (Optional)
            $table->foreignId('group_id')->nullable()->constrained('drug_groups')->onDelete('set null'); // Golongan (Optional)
            $table->foreignId('category_id')->nullable()->constrained('drug_categories')->onDelete('set null'); // Kategori (Optional)
            $table->enum('drug_type', ['Non Konsinyasi', 'Konsinyasi']); // Jenis Obat
            $table->integer('min_stock')->nullable(); // Minimal Stok
            $table->text('description')->nullable(); // Deskripsi
            $table->text('indication')->nullable(); // Indikasi
            $table->text('content')->nullable(); // Kandungan
            $table->text('dosage')->nullable(); // Dosis
            $table->string('packaging')->nullable(); // Kemasan / Sediaan
            $table->text('side_effects')->nullable(); // Efek Samping
            $table->string('precursor_active_ingredient')->nullable(); // Zat Aktif Prekursor
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade'); // Relasi ke Branch
            $table->foreignId('label_id')->nullable()->constrained('labels')->onDelete('set null'); // Aturan pakai/Etiket (Optional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drugs');
    }
};
