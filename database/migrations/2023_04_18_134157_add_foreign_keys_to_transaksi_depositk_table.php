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
        Schema::table('transaksi_depositk', function (Blueprint $table) {
            $table->foreign(['id_promo_kelas'], 'transaksi_depositk_ibfk_4')->references(['id_promo_kelas'])->on('promo_kelas');
            $table->foreign(['id_kelas'], 'transaksi_depositk_ibfk_1')->references(['id_kelas'])->on('kelas');
            $table->foreign(['id_pegawai'], 'transaksi_depositk_ibfk_3')->references(['id_pegawai'])->on('pegawai');
            $table->foreign(['id_member'], 'transaksi_depositk_ibfk_2')->references(['id_member'])->on('member');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaksi_depositk', function (Blueprint $table) {
            $table->dropForeign('transaksi_depositk_ibfk_4');
            $table->dropForeign('transaksi_depositk_ibfk_1');
            $table->dropForeign('transaksi_depositk_ibfk_3');
            $table->dropForeign('transaksi_depositk_ibfk_2');
        });
    }
};
