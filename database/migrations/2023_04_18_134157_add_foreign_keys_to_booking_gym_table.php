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
        Schema::table('booking_gym', function (Blueprint $table) {
            $table->foreign(['id_member'], 'booking_gym_ibfk_1')->references(['id_member'])->on('member');
            $table->foreign(['sesi'], 'booking_gym_ibfk_2')->references(['sesi'])->on('sesi_gym');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_gym', function (Blueprint $table) {
            $table->dropForeign('booking_gym_ibfk_1');
            $table->dropForeign('booking_gym_ibfk_2');
        });
    }
};
