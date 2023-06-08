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
        Schema::create('booking_kelas', function (Blueprint $table) {
            $table->string('id_jadwal_harian', 5)->index('id_jadwal_harian');
            $table->string('id_member', 10)->index('id_member');
            $table->date('tgl_booking_kelas');
            $table->string('status', 50)->default('ONGOING');
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
        Schema::dropIfExists('booking_kelas');
    }
};
