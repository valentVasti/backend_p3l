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
        Schema::create('member', function (Blueprint $table) {
            $table->string('id_member', 10)->primary();
            $table->string('nama');
            $table->string('no_telp', 15);
            $table->date('tgl_lahir');
            $table->boolean('status');
            $table->string('email');
            $table->string('password');
            $table->date('tgl_daftar');
            $table->float('deposit_uang', 10, 0);
            $table->date('tgl_kadaluarsa')->nullable();
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
        Schema::dropIfExists('member');
    }
};
