<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal_harian extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_jadwal_harian';
    protected $keyType = 'string';
    protected $table = 'jadwal_harian';
    protected $fillable = [
        'id_instruktur',
        'id_kelas',
        'hari_kelas_harian',
        'jam_mulai',
        'jam_selesai',
        'kuota',
        'keterangan',
        'tgl_kelas'
    ];

    // public function izinInstruktur(){
    //     return $this->hasMany(Jadwal_harian::class, 'id_jadwal_harian','id');
    // }

    public function kelas(){
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function instruktur(){
        return $this->belongsTo(Instruktur::class, 'id_instruktur');
    }

    public function presensiKelas(){
        return $this->hasMany(Jadwal_harian::class, 'id_member', 'id');
    }

}
