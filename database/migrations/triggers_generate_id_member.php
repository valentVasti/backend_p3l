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
        CREATE TRIGGER `generate_id_member` BEFORE INSERT ON `member`
        FOR EACH ROW BEGIN
        DECLARE tgl_daftar_year CHAR(2);
        DECLARE tgl_daftar_month CHAR(2);
        DECLARE last_id_member INT;
    
        SET tgl_daftar_year = DATE_FORMAT(NEW.tgl_daftar, '%y');
        SET tgl_daftar_month = DATE_FORMAT(NEW.tgl_daftar, '%m');
    
        SELECT SUBSTRING_INDEX(id_member, '.', -1) INTO last_id_member
        FROM member
        ORDER BY id_member
        DESC LIMIT 1;

        IF last_id_member IS NULL THEN
            SET NEW.id_member = CONCAT(tgl_daftar_year, '.', tgl_daftar_month, '.001');
        ELSE
            SET NEW.id_member = CONCAT(tgl_daftar_year, '.', tgl_daftar_month, '.', LPAD(last_id_member + 1, 3, '0'));
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
        DB::unprepared('DROP TRIGGER `generate_id_member`');
    }
};
