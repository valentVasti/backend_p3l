<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi_gym extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_presensi_gym';
    protected $keyType = 'string';
    protected $table = 'presensi_gym';
    protected $fillable = [
        'id_member',
        'sesi',
        'jam_presensi',
        'tgl_presensi_gym'
    ];

    public function member(){
        return $this->belongsTo(Member::class, 'id_member');
    }

    public function sesiGym(){
        return $this->belongsTo(Sesi_gym::class, 'sesi');
    }
}
