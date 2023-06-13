<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi_kelas extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_presensi_kelas';
    protected $keyType = 'string';
    protected $table = 'presensi_kelas';
    protected $fillable = [
        'id_jadwal_harian',
        'id_member',
        'tgl_presensi_kelas',
        'status_kehadiran'
    ];

    public function member(){
        return $this->belongsTo(Member::class, 'id_member');
    }

    public function jadwal_harian(){
        return $this->belongsTo(Jadwal_harian::class, 'id_jadwal_harian');
    }
}
