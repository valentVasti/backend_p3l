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
        Schema::create('presensi_instruktur', function (Blueprint $table) {
            $table->string('id_presensi_instruktur', 5)->primary();
            $table->string('id_jadwal_harian', 5)->index('id_jadwal_harian');
            $table->time('keterlambatan');
            $table->time('update_jam_mulai');
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
        Schema::dropIfExists('presensi_instruktur');
    }
};
