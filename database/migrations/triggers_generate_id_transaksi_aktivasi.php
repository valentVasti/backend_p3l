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
            CREATE TRIGGER `generate_id_transaksi_aktivasi` BEFORE INSERT ON `transaksi_aktivasi`
            FOR EACH ROW BEGIN
            DECLARE tgl_aktivasi_year CHAR(2);
            DECLARE tgl_aktivasi_month CHAR(2);
            DECLARE last_id_transaksi INT;
        
            SET tgl_aktivasi_year = DATE_FORMAT(NEW.tgl_aktivasi, '%y');
            SET tgl_aktivasi_month = DATE_FORMAT(NEW.tgl_aktivasi, '%m');
        
            SELECT SUBSTRING_INDEX(id_transaksi, '.', -1) INTO last_id_transaksi
            FROM transaksi_aktivasi
            WHERE DATE_FORMAT(tgl_aktivasi, '%y%m') = CONCAT(tgl_aktivasi_year, tgl_aktivasi_month)
            ORDER BY id_transaksi DESC
            LIMIT 1;
        
            IF last_id_transaksi IS NULL THEN
            SET NEW.id_transaksi = CONCAT(tgl_aktivasi_year, '.', tgl_aktivasi_month, '.001');
            ELSE
            SET NEW.id_transaksi = CONCAT(tgl_aktivasi_year, '.', tgl_aktivasi_month, '.', LPAD(last_id_transaksi + 1, 3, '0'));
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
        DB::unprepared('DROP TRIGGER `generate_id_transaksi_aktivasi`');
    }
};
