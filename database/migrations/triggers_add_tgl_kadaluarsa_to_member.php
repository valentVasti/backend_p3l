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
            CREATE TRIGGER `add_tgl_kadaluarsa_to_member` AFTER INSERT ON `transaksi_aktivasi`
            FOR EACH ROW UPDATE member
            SET
                tgl_kadaluarsa = NEW.tgl_kadaluarsa
            WHERE
                id_member = NEW.id_member
        ");
    }
 

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `add_tgl_kadaluarsa_to_member`');
    }
};
