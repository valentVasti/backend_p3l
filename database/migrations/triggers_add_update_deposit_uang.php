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
        CREATE TRIGGER `add_update_deposit_uang` BEFORE INSERT ON `transaksi_depou`
        FOR EACH ROW BEGIN
               DECLARE temp_depositU FLOAT;
               DECLARE temp_sisa_deposit FLOAT;
               DECLARE promo_uang_row INT;
               DECLARE promo_rec ROW TYPE OF promo_uang;
               
               SELECT COUNT(*) INTO promo_uang_row FROM promo_uang;
               
               SET temp_depositU = NEW.jumlah_depou;
               
               for_loop: FOR i IN 1..promo_uang_row
               DO
                   SELECT * INTO promo_rec FROM promo_uang
                   WHERE id_promo = CONCAT('PRU00', promo_uang_row - i + 1);
                   SET NEW.id_promo = promo_rec.id_promo;
                   
                   IF temp_depositU >= promo_rec.syarat_bonus_uang THEN
                       LEAVE for_loop;	
                   END IF;
                       
               END FOR for_loop;
               
               
               SELECT deposit_uang INTO temp_sisa_deposit FROM member WHERE id_member = NEW.id_member;
       
               IF temp_sisa_deposit >= 0 THEN
                   SET NEW.jumlah_depou = temp_depositU + promo_rec.bonus_uang;
             ELSE
               SET NEW.jumlah_depou = temp_depositU;
             END IF;
       
               SET NEW.sisa_deposit = temp_sisa_deposit;
               
               IF temp_depositU != 0 THEN
                   UPDATE member
                   SET
                       deposit_uang = deposit_uang + NEW.jumlah_depou
                   WHERE
                       id_member = NEW.id_member;
               ELSEIF temp_depositU = 0 THEN
                   UPDATE member
                   SET
                       deposit_uang = NEW.jumlah_depou
                   WHERE
                       id_member = NEW.id_member;
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
        DB::unprepared('DROP TRIGGER `add_update_deposit_uang`');
    }
};
