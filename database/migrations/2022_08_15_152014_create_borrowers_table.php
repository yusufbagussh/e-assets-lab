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
        Schema::create('borrowers', function (Blueprint $table) {
            $table->id('peminjam_id');
            $table->unsignedBigInteger('peminjam_no_identitas');
            $table->string('peminjam_nama');
            $table->unsignedBigInteger('peminjam_jurusan');
            $table->string('peminjam_email');
            $table->unsignedBigInteger('peminjam_no_wa');
            $table->string('peminjam_status');
            $table->timestamps();
        });

        Schema::table('borrowers', function (Blueprint $table) {
            $table->foreign('peminjam_jurusan')->references('jurusan_id')->on('majors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('borrowers');
    }
};
