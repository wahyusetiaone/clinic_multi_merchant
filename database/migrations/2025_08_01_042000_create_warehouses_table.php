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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Gudang
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade'); // Relasi ke Branch
            $table->boolean('is_default')->default(false); // Apakah ini gudang default untuk cabang ini?
            $table->timestamps();

            // Tambahkan unique constraint agar setiap cabang hanya memiliki satu gudang default
            $table->unique(['branch_id', 'is_default']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
