<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSesiSoalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sesi_soal', function (Blueprint $table) {
            $table->id();
            $table->string('id_paket_soal');
            $table->string('nama_sesi');
            $table->string('durasi');
            $table->string('urutan');
            $table->string('induk_sesi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sesi_soal');
    }
}
