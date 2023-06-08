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
        CREATE TRIGGER `add_date_from_booking` BEFORE INSERT ON `presensi_gym`
        FOR EACH ROW BEGIN
       
        DECLARE booking_date DATE;
        
        SELECT tgl_booking 
        INTO booking_date 
        FROM booking_gym 
        WHERE id_member = NEW.id_member AND sesi = NEW.sesi;
        
        SET NEW.tgl_presensi_gym = booking_date;
        
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
        DB::unprepared('DROP TRIGGER `add_date_from_booking`');
    }
};
