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
        Schema::create('transaksi_aktivasi', function (Blueprint $table) {
            $table->string('id_transaksi', 10)->primary();
            $table->string('id_pegawai', 5)->index('id_pegawai');
            $table->string('id_member', 10)->index('id_member');
            $table->date('tgl_kadaluarsa');
            $table->date('tgl_aktivasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi_aktivasi');
    }
};
