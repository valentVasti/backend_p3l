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
        Schema::table('presensi_gym', function (Blueprint $table) {
            $table->foreign(['id_member'], 'presensi_gym_ibfk_1')->references(['id_member'])->on('booking_gym');
            $table->foreign(['sesi'], 'presensi_gym_ibfk_2')->references(['sesi'])->on('booking_gym');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('presensi_gym', function (Blueprint $table) {
            $table->dropForeign('presensi_gym_ibfk_1');
            $table->dropForeign('presensi_gym_ibfk_2');
        });
    }
};
