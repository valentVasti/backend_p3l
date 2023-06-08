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
        Schema::table('transaksi_depou', function (Blueprint $table) {
            $table->foreign(['id_pegawai'], 'transaksi_depou_ibfk_1')->references(['id_pegawai'])->on('pegawai');
            $table->foreign(['id_promo'], 'transaksi_depou_ibfk_3')->references(['id_promo'])->on('promo_uang');
            $table->foreign(['id_member'], 'transaksi_depou_ibfk_2')->references(['id_member'])->on('member');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaksi_depou', function (Blueprint $table) {
            $table->dropForeign('transaksi_depou_ibfk_1');
            $table->dropForeign('transaksi_depou_ibfk_3');
            $table->dropForeign('transaksi_depou_ibfk_2');
        });
    }
};
