<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal_umum extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_jadwal_umum';
    protected $keyType = 'string';
    protected $table = 'jadwal_umum';
    protected $fillable = [
        'id_instruktur',
        'id_kelas',
        'hari_kelas_umum',
        'jam_mulai',
        'jam_selesai'
    ];

    
    public function kelas(){
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function instruktur(){
        return $this->belongsTo(Instruktur::class, 'id_instruktur');
    }

}
