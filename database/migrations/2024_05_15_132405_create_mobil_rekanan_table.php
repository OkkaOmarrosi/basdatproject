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
        Schema::create('mobil_rekanan', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('idrekanan_mitra')->constrained('rekanan_mitra')->cascadeOnDelete();
            $table->unsignedBigInteger('idrekanan_mitra');
            $table->string('nopol', 50)->nullable();
            $table->string('merk', 50)->nullable();
            $table->string('tipe', 50)->nullable();
            $table->string('tahun', 4)->nullable();
            $table->string('warna', 50)->nullable();
            $table->string('no_rangka', 50)->nullable();
            $table->string('no_mesin', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobil_rekanan');
    }
};
