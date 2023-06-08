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
        CREATE TRIGGER `generate_id_promo_uang` BEFORE INSERT ON `promo_uang`
        FOR EACH ROW BEGIN
        DECLARE id INT;
    
        SELECT COUNT(*) INTO id FROM promo_uang;
        
        IF id < 9 THEN
            SET NEW.id_promo = CONCAT('PRU00', id+1);
        ELSEIF id > 8 THEN
            SET NEW.id_promo = CONCAT('PRU0', id+1);
        ELSEIF id > 98 THEN
            SET NEW.id_promo = CONCAT('PRU', id+1);
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
        DB::unprepared('DROP TRIGGER `generate_id_promo_kelas`');
    }
};
