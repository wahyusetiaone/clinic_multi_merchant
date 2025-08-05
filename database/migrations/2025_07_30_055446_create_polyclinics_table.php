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
        Schema::create('polyclinics', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Kode Poli (misal: G, UM, ANAK)
            $table->string('name'); // Nama Poli
            $table->string('physical_location_type')->nullable(); // Tipe Fisik Lokasi (misal: Nomor Ruangan)
            $table->text('description')->nullable(); // Keterangan singkat tentang poli
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade'); // <--- Tambahan ini
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polyclinics');
    }
};
