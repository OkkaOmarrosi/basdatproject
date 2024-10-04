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
        Schema::create('pesanan_pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('header_id');
            $table->unsignedInteger('iditem_order_pengeluaran');
            $table->double('jumlah');
            $table->unsignedInteger('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_pengeluaran');
    }
};
