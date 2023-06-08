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
            CREATE TRIGGER `activate_member` AFTER INSERT ON `transaksi_aktivasi`
            FOR EACH ROW UPDATE member
            SET
                status = 1
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
        DB::unprepared('DROP TRIGGER `activate_member`');
    }
};
