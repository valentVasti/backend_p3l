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
        CREATE TRIGGER `add_keterlambatan` BEFORE INSERT ON `presensi_instruktur`
        FOR EACH ROW BEGIN
        DECLARE temp_jam_mulai TIME;
        
        SELECT jam_mulai INTO temp_jam_mulai FROM jadwal_harian
        WHERE id_jadwal_harian = NEW.id_jadwal_harian;
        
        SET temp_jam_mulai = NEW.update_jam_mulai - temp_jam_mulai;
        SET NEW.keterlambatan = temp_jam_mulai;
        END
        ");
    }
 

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `add_keterlambatan`');
    }
};
