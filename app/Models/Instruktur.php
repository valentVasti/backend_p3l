<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instruktur extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_instruktur';
    protected $keyType = 'string';
    protected $table = 'instruktur';
    protected $fillable = [
        'nama',
        'no_telp',
        'tgl_lahir',
        'email',
        'password'
    ];


    public function jadwalUmum(){
        return $this->hasMany(instruktur::class, 'id_instruktur', 'id');
    }

    public function jadwalHarian(){
        return $this->hasMany(instruktur::class, 'id_instruktur','id');
    }

    public function izinInstruktur(){
        return $this->hasMany(instruktur::class, 'id_instruktur','id');
    }

    // public function izinInstrukturPengganti(){
    //     return $this->hasMany(Instruktur::class, 'id_instruktur_pengganti','id');
    // }
}


