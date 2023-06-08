<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_kelas';
    protected $keyType = 'string';
    protected $table = 'kelas';
    protected $fillable = [
        'nama_kelas',
        'harga',
        'kuota'
    ];

    public function jadwalUmum(){
        return $this->hasMany(Instruktur::class, 'id_kelas', 'id');
    }

    public function jadwalHarian(){
        return $this->hasMany(Instruktur::class, 'id_kelas','id');
    }

    public function transaksiDepoK(){
        return $this->hasMany(Instruktur::class, 'id_kelas','id');
    }
}
