<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi_depou extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_transaksi_depou';
    protected $keyType = 'string';
    protected $table = 'transaksi_depou';
    protected $fillable = [
        'id_pegawai',
        'id_member',
        'id_promo',
        'sisa_deposit',
        'jumlah_depou',
        'tgl_transaksi'
    ];

    public function member(){
        return $this->belongsTo(Member::class, 'id_member');
    }

    public function pegawai(){
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

    public function promo_uang(){
        return $this->belongsTo(Promo_uang::class, 'id_promo');
    }
}
