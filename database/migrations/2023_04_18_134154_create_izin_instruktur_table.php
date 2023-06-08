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
        Schema::create('izin_instruktur', function (Blueprint $table) {
            $table->string('id_izin', 10)->primary();
            $table->string('id_jadwal_harian', 5)->index('id_jadwal_harian');
            $table->string('id_instruktur', 5)->index('id_instruktur');
            $table->string('id_instruktur_pengganti', 5)->index('id_instruktur_pengganti')->nullable();
            $table->boolean('status_konfirmasi');
            $table->date('tgl_izin');
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
        Schema::dropIfExists('izin_instruktur');
    }
};
