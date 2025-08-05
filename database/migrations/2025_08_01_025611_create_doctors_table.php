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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique(); // NIK Dokter Anda
            $table->string('satusehat_id')->nullable(); // ID Satu Sehat (opsional)
            $table->string('name'); // Nama Dokter
            $table->string('specialization')->nullable(); // Spesialis / Keahlian
            $table->text('address')->nullable(); // Alamat Tinggal
            $table->string('phone_number')->nullable(); // Nomor Telepon
            $table->string('str_number')->nullable(); // Nomor Surat Tanda Registrasi (STR)
            $table->string('username')->unique()->nullable(); // Username untuk login portal dokter (Optional)
            $table->date('start_date'); // Tanggal Mulai Bertugas
            $table->string('photo')->nullable(); // Path/Nama file Foto Dokter
            $table->string('signature')->nullable(); // Path/Nama file Tanda Tangan Dokter
            $table->string('stamp')->nullable(); // Path/Nama file Stempel Dokter

            // Relasi ke tabel branches
            $table->foreignId('branch_id')->constrained('branches')->onDelete('set null'); // Dokter bisa tidak terhubung ke cabang jika cabang dihapus

            // Kolom untuk menghubungkan dengan tabel users jika username digunakan untuk login
            // Asumsi tabel users sudah ada dan field username-nya sama
            // $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Opsional jika dokter memiliki akun user terpisah

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
