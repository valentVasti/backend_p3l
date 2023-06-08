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
        Schema::create('transaksi_depou', function (Blueprint $table) {
            $table->string('id_transaksi_depou', 10)->primary();
            $table->string('id_pegawai', 5)->index('id_pegawai');
            $table->string('id_member', 10)->index('id_member');
            $table->string('id_promo', 10)->index('id_promo');
            $table->float('sisa_deposit', 10, 0);
            $table->float('jumlah_depou', 10, 0);
            $table->date('tgl_transaksi');
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
        Schema::dropIfExists('transaksi_depou');
    }
};
