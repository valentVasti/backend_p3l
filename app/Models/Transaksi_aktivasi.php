<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi_aktivasi extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_transaksi';
    protected $keyType = 'string';
    protected $table = 'transaksi_aktivasi';
    protected $fillable = [
        'id_pegawai',
        'id_member',
        'tgl_kadaluarsa',
        'tgl_aktivasi'
    ];

    public function member(){
        return $this->belongsTo(Member::class, 'id_member');
    }

    public function pegawai(){
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
}
