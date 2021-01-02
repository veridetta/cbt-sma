<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserJawabanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_jawaban', function (Blueprint $table) {
            $table->id();
            $table->string('id_siswa');
            $table->string('id_paket');
            $table->string('nomor_soal');
            $table->string('kunci');
            $table->string('jawabanSiswa');
            $table->string('id_soal');
            $table->string('id_sesi');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_jawaban');
    }
}
