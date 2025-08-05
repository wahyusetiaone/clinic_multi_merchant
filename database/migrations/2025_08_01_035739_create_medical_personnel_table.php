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
        Schema::create('medical_personnel', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // Kategori: perawat atau petugas
            $table->string('nik')->unique(); // NIK Petugas Medis
            $table->string('satusehat_id')->nullable()->unique(); // ID Satu Sehat (Opsional)
            $table->string('name'); // Nama Petugas Medis
            $table->string('specialization')->nullable(); // Bagian / Spesialis
            $table->text('address')->nullable(); // Alamat
            $table->string('phone_number')->nullable(); // No. Telepon
            $table->string('email')->nullable()->unique(); // Email (Opsional)
            $table->date('start_date'); // Tanggal Mulai Bertugas

            // Foreign key ke tabel branches
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_personnel');
    }
};
