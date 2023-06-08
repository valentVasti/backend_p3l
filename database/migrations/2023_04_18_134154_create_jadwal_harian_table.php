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
        Schema::create('jadwal_harian', function (Blueprint $table) {
            $table->string('id_jadwal_harian', 5)->primary();
            $table->string('id_instruktur', 5)->index('id_instruktur');
            $table->string('id_kelas', 5)->index('id_kelas');
            $table->string('hari_kelas_harian');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->integer('kuota');
            $table->string('keterangan');
            $table->date('tgl_kelas')->nullable();
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
        Schema::dropIfExists('jadwal_harian');
    }
};
