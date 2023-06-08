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
            CREATE TRIGGER `deactivate_after_delete` AFTER DELETE ON `transaksi_aktivasi`
            FOR EACH ROW UPDATE member
            SET
                status = 0,
                tgl_kadaluarsa = null
            WHERE
                id_member = OLD.id_member
        ");
    }
 

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `deactivate_after_delete`');
    }
};
