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
        Schema::create('presensi_gym', function (Blueprint $table) {
            $table->string('id_presensi_gym', 5)->primary();
            $table->string('id_member', 10)->index('presensi_gym_ibfk_1');
            $table->integer('sesi')->index('sesi');
            $table->time('jam_presensi');
            $table->date('tgl_presensi_gym');
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
        Schema::dropIfExists('presensi_gym');
    }
};
