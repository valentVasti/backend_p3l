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
        CREATE TRIGGER `generate_id_presensi_kelas` BEFORE INSERT ON `presensi_kelas`
        FOR EACH ROW BEGIN
        DECLARE id INT;
        
        SELECT COUNT(*) INTO id FROM presensi_instruktur;
        
        IF id < 9 THEN
            SET NEW.id_presensi_kelas = CONCAT('PK0', id+1);
        ELSEIF id > 8 THEN
            SET NEW.id_presensi_kelas = CONCAT('PK', id+1);
        END IF;
        
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
        DB::unprepared('DROP TRIGGER `generate_id_presensi_kelas`');
    }
};
