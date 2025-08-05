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
        Schema::create('drug_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drug_id')->constrained('drugs')->onDelete('cascade'); // Relasi ke Obat
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade'); // Gudang
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade'); // Lokasi di gudang
            $table->integer('stock_quantity'); // Jumlah Stok
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade'); // Satuan / Kemasan Stok
            $table->string('batch_number'); // No Batch
            $table->date('expiration_date'); // Tanggal Expired
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drug_stocks');
    }
};
