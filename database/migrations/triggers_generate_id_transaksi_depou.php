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
            CREATE TRIGGER `generate_id_transaksi_depou` BEFORE INSERT ON `transaksi_depou`
            FOR EACH ROW BEGIN
            DECLARE tgl_transaksi_year CHAR(2);
            DECLARE tgl_transaksi_month CHAR(2);
            DECLARE last_id_transaksi INT;
        
            SET tgl_transaksi_year = DATE_FORMAT(NEW.tgl_transaksi, '%y');
            SET tgl_transaksi_month = DATE_FORMAT(NEW.tgl_transaksi, '%m');
        
            SELECT SUBSTRING_INDEX(id_transaksi_depou, '.', -1) INTO last_id_transaksi
            FROM transaksi_depou
            WHERE DATE_FORMAT(tgl_transaksi, '%y%m') = CONCAT(tgl_transaksi_year, tgl_transaksi_month)
            ORDER BY id_transaksi_depou DESC
            LIMIT 1;
        
            IF last_id_transaksi IS NULL THEN
            SET NEW.id_transaksi_depou = CONCAT(tgl_transaksi_year, '.', tgl_transaksi_month, '.001');
            ELSE
            SET NEW.id_transaksi_depou = CONCAT(tgl_transaksi_year, '.', tgl_transaksi_month, '.', LPAD(last_id_transaksi + 1, 3, '0'));
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
        DB::unprepared('DROP TRIGGER `generate_id_transaksi_depou`');
    }
};
