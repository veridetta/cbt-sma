<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatBintangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riwayat_bintang', function (Blueprint $table) {
            $table->id();
            $table->integer('id_users');
            $table->string('nominal');
            $table->string('status');
            $table->string('saldo');
            $table->datetime('tgl')->default(DB::raw('CURRENT_TIMESTAMP')->nullable()->change);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('riwayat_bintang');
    }
}
