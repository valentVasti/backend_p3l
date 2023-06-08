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
        Schema::create('presensi_kelas', function (Blueprint $table) {
            $table->string('id_presensi_kelas', 5)->primary();
            $table->string('id_jadwal_harian', 5)->index('presensi_kelas_ibfk_1');
            $table->string('id_member', 10)->index('presensi_kelas_ibfk_2');
            $table->date('tgl_presensi_kelas');
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
        Schema::dropIfExists('presensi_kelas');
    }
};
