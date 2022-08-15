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
        Schema::create('items', function (Blueprint $table) {
            $table->id('item_id');
            $table->unsignedBigInteger('item_lokasi');
            $table->string('item_nama');
            $table->unsignedBigInteger('item_jumlah');
            $table->string('item_gambar');
            $table->string('item_kondisi');
            $table->string('item_status');
            $table->text('item_spesifikasi');
            $table->timestamps();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->foreign('item_lokasi')->references('lokasi_id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
};
