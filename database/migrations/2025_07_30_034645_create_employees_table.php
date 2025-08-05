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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('nip')->nullable()->unique();
            $table->foreignId('position_id')->nullable()->constrained('positions')->onDelete('set null');
            $table->enum('type', ['medical', 'non-medical', 'doctor', 'nurse', 'pharmacist', 'admin_staff'])->default('non-medical');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('path_photo')->default('https://placehold.co/400x400/EEE/31343C');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
