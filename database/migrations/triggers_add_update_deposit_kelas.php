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
        CREATE TRIGGER `add_update_deposit_kelas` BEFORE INSERT ON `transaksi_depositk`
        FOR EACH ROW BEGIN
                   DECLARE deposit_kelas_row INT;
                   DECLARE temp_depositK INT;
                   DECLARE promo_kelas_row INT;
                   DECLARE promo_rec ROW TYPE OF promo_kelas;
                   
                   SELECT COUNT(*) INTO promo_kelas_row FROM promo_kelas;
                   
                   SET temp_depositK = NEW.jumlah_deposit;
                   
                   for_loop: FOR i IN 1..promo_kelas_row
                   DO
                       SELECT * INTO promo_rec FROM promo_kelas
                       WHERE id_promo_kelas = CONCAT('PRK00', promo_kelas_row - i + 1);
                       SET NEW.id_promo_kelas = promo_rec.id_promo_kelas;	
                       IF temp_depositK > promo_rec.syarat_bonus_kelas - 1 THEN
                           LEAVE for_loop;	
                       END IF;
                   END FOR for_loop;
                   
                   IF promo_rec.syarat_bonus_kelas = 10 THEN
                       SET NEW.tgl_kadaluarsa = DATE_ADD(NEW.tgl_transaksi, INTERVAL 2 MONTH);
                   ELSEIF promo_rec.syarat_bonus_kelas <= 5 THEN
                       SET NEW.tgl_kadaluarsa = DATE_ADD(NEW.tgl_transaksi, INTERVAL 1 MONTH);
                   END IF;
                           
                   SET NEW.jumlah_deposit = temp_depositK + promo_rec.bonus_kelas;
                   
                   SELECT COUNT(*) INTO deposit_kelas_row FROM deposit_kelas WHERE id_member = NEW.id_member AND id_kelas = NEW.id_kelas;
                   
                   IF deposit_kelas_row != 0 THEN
                       UPDATE deposit_kelas
                       SET
                           deposit_kelas = deposit_kelas + NEW.jumlah_deposit,
                           tgl_kadaluarsa = NEW.tgl_kadaluarsa
                       WHERE
                           id_member = NEW.id_member AND id_kelas = NEW.id_kelas;
                   ELSEIF deposit_kelas_row = 0 THEN
                       INSERT INTO deposit_kelas(id_member, id_kelas, deposit_kelas, tgl_kadaluarsa) VALUES
                           (NEW.id_member, NEW.id_kelas, NEW.jumlah_deposit, NEW.tgl_kadaluarsa);
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
        DB::unprepared('DROP TRIGGER `add_update_deposit_kelas`');
    }
};
