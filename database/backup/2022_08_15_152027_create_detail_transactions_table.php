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
        Schema::create('detail_transactions', function (Blueprint $table) {
            $table->integer('detail_transaksi_id');
            $table->integer('detail_transaksi_transaksi');
            $table->integer('detail_transaksi_user');
            $table->date('detail_transaksi_tgl_pengembalian');
            $table->integer('detail_transaksi_denda');
            $table->timestamps();
        });

        Schema::table('detail_transactions', function (Blueprint $table) {
            $table->foreign('detail_transaksi_transaksi')->references('transaksi_id')->on('transactions');
        });

        Schema::table('detail_transactions', function (Blueprint $table) {
            $table->foreign('detail_transaksi_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_transactions');
    }
};
