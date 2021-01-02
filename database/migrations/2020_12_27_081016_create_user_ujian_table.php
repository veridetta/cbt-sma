<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserUjianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_ujian', function (Blueprint $table) {
            $table->id();
            $table->string('id_siswa');
            $table->string('id_paket');
            $table->string('id_soal');
            $table->datetime('mulai');
            $table->datetime('akhir');
            $table->string('status');
            $table->string('percobaan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_ujian');
    }
}
