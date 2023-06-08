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
        CREATE TRIGGER `generate_member_default_password` BEFORE INSERT ON `member`
        FOR EACH ROW BEGIN
            DECLARE tgl_lahir_year CHAR(2);
            DECLARE tgl_lahir_month CHAR(2);
		    DECLARE tgl_lahir_day CHAR(2);
        
            SET tgl_lahir_year = DATE_FORMAT(NEW.tgl_lahir, '%y');
            SET tgl_lahir_month = DATE_FORMAT(NEW.tgl_lahir, '%m');
            SET tgl_lahir_day = DATE_FORMAT(NEW.tgl_lahir, '%d');
           
            SET NEW.password = CONCAT(tgl_lahir_day, tgl_lahir_month, tgl_lahir_year);
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
        DB::unprepared('DROP TRIGGER `generate_member_default_password`');
    }
};
