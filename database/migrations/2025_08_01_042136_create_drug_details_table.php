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
        Schema::create('drug_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drug_id')->unique()->constrained('drugs')->onDelete('cascade'); // Relasi ke Obat (one-to-one)
            $table->decimal('hna', 10, 2); // Harga Beli dari supplier
            $table->decimal('selling_price_1', 10, 2); // Harga Jual 1
            $table->decimal('discount_1', 5, 2)->nullable(); // Diskon Harga 1 (asumsi persentase, 5 digit total, 2 di belakang koma)
            $table->decimal('selling_price_2', 10, 2)->nullable(); // Harga Jual 2
            $table->decimal('discount_2', 5, 2)->nullable(); // Diskon Harga 2
            $table->decimal('selling_price_3', 10, 2)->nullable(); // Harga Jual 3
            $table->decimal('discount_3', 5, 2)->nullable(); // Diskon Harga 3
            $table->string('barcode')->nullable()->unique(); // Barcode Obat (Optional, Unique)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drug_details');
    }
};
