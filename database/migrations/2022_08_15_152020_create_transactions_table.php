<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('transaksi_id');
            $table->unsignedBigInteger('transaksi_item');
            $table->unsignedBigInteger('transaksi_peminjam');
            $table->date('transaksi_tgl_pinjam');
            $table->date('transaksi_tgl_kembali');
            $table->timestamps();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('transaksi_item')->references('item_id')->on('items');
        });
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('transaksi_peminjam')->references('peminjam_id')->on('borrowers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
