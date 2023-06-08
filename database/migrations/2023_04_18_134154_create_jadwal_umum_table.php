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
        Schema::create('jadwal_umum', function (Blueprint $table) {
            $table->string('id_jadwal_umum', 5)->primary();
            $table->string('hari_kelas_umum');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('id_kelas', 5)->index('id_kelas');
            $table->string('id_instruktur', 5)->nullable();
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
        Schema::dropIfExists('jadwal_umum');
    }
};
