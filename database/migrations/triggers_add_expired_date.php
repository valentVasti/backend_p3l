<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
        CREATE TRIGGER `add_expired_date` BEFORE INSERT ON `transaksi_aktivasi`
        FOR EACH ROW SET NEW.tgl_kadaluarsa = DATE_ADD(NEW.tgl_aktivasi, INTERVAL 1 YEAR)
        ");
    }
 

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `add_expired_date`');
    }
};
