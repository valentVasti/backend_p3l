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
        Schema::create('transaksi_depositk', function (Blueprint $table) {
            $table->string('id_transaksi_depok', 10)->primary();
            $table->string('id_pegawai', 5)->index('id_pegawai');
            $table->string('id_member', 10)->index('id_member');
            $table->string('id_kelas', 5)->index('id_kelas');
            $table->string('id_promo_kelas', 10)->index('id_promo_kelas');
            $table->float('jumlah_bayar', 10, 0);
            $table->integer('jumlah_deposit');
            $table->date('tgl_kadaluarsa');
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
        Schema::dropIfExists('transaksi_depositk');
    }
};
