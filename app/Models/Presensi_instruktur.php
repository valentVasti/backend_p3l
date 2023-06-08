<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi_instruktur extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_presensi_instruktur';
    protected $keyType = 'string';
    protected $table = 'presensi_instruktur';
    protected $fillable = [
        'id_jadwal_harian',
        'keterlambatan',
        'update_jam_mulai'
    ];
}
