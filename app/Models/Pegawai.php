<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pegawai';
    protected $keyType = 'string';
    protected $table = 'pegawai';
    protected $fillable = [
        'id_role',
        'nama_pegawai',
        'no_telp_pegawai',
        'tgl_lahir_pegawai',
        'email',
        'password'
    ];

    public function transaksiAktivasi(){
        return $this->hasMany(Pegawai::class, 'id_pegawai', 'id');
    }

    public function transaksiDepoU(){
        return $this->hasMany(Pegawai::class, 'id_pegawai', 'id');
    }

    public function transaksiDepoK(){
        return $this->hasMany(Pegawai::class, 'id_pegawai', 'id');
    }
}
