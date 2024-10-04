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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('nama_pemesan')->nullable();
            $table->string('nomor_pemesan')->nullable();
            $table->string('nama_tamu')->nullable();
            $table->string('nomor_tamu')->nullable();
            $table->string('paket')->nullable();
            $table->tinyInteger('is_pesanan_tamu')->default(0);
            $table->unsignedBigInteger('idtipe_mobil');
            $table->unsignedBigInteger('idmobil')->nullable();
            $table->unsignedBigInteger('layanan_id');
            $table->unsignedBigInteger('idmitra');
            $table->unsignedBigInteger('idpool');
            $table->dateTime('tgl_mulai');
            $table->dateTime('tgl_selesai');
            $table->string('penjemputan');
            $table->string('tujuan');
            $table->json('track_mobil')->nullable();
            $table->string('estimasi_km')->nullable();
            $table->string('estimasi_bbm')->nullable();
            $table->string('estimasi_tol_parkir')->nullable();
            $table->double('harga')->nullable();
            $table->unsignedBigInteger('idrekanan')->nullable();
            $table->string('mobil_rekan')->nullable();
            $table->unsignedBigInteger('create_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
