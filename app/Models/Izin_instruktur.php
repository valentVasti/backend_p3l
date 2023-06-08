<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Izin_instruktur extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_izin';
    protected $keyType = 'string';
    protected $table = 'izin_instruktur';
    protected $fillable = [
        'id_jadwal_harian',
        'id_instruktur',
        'id_instruktur_pengganti',
        'status_konfirmasi',
        'tgl_izin'
    ];


    // public function jadwalHarian(){
    //     return $this->belongsTo(Jadwal_harian::class, 'id_jadwal_harian');
    // }

    public function instruktur(){
        return $this->belongsTo(Instruktur::class, 'id_instruktur');
    }

    public function instrukturPengganti(){
        return $this->belongsTo(Instruktur::class, 'id_instruktur_pengganti');
    }
}
