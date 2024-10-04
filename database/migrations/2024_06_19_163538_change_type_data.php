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
        Schema::table('pesanan_pembayaran', function (Blueprint $table) {
            $table->unsignedBigInteger('header_id')->nullable()->change();
        });

        Schema::table('pesanan_pengeluaran', function (Blueprint $table) {
            $table->unsignedBigInteger('header_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
