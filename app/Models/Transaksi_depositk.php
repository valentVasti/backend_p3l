<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi_depositk extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_transaksi_depok';
    protected $keyType = 'string';
    protected $table = 'transaksi_depositk';
    protected $fillable = [
        'id_pegawai',
        'id_member',
        'id_kelas',
        'id_promo_kelas',
        'jumlah_bayar',
        'jumlah_deposit',
        'tgl_kadaluarsa',
        'tgl_transaksi'
    ];

    public function member(){
        return $this->belongsTo(Member::class, 'id_member');
    }

    public function pegawai(){
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

    public function kelas(){
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function promo_kelas(){
        return $this->belongsTo(Promo_kelas::class, 'id_promo_kelas');
    }
}
