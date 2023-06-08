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
        Schema::table('deposit_kelas', function (Blueprint $table) {
            $table->foreign(['id_member'], 'deposit_kelas_ibfk_1')->references(['id_member'])->on('member');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deposit_kelas', function (Blueprint $table) {
            $table->dropForeign('deposit_kelas_ibfk_1');
        });
    }
};
