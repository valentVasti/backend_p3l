<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_member';
    protected $keyType = 'string';
    protected $table = 'member';
    protected $fillable = [
        'nama',
        'no_telp',
        'tgl_lahir',
        'status',
        'email',
        'password',
        'tgl_daftar',
        'deposit_uang',
        'tgl_kadaluarsa'
    ];

    public function transaksiAktivasi(){
        return $this->hasMany(Member::class, 'id_member', 'id');
    }

    public function transaksiDepoU(){
        return $this->hasMany(Member::class, 'id_member', 'id');
    }

    public function transaksiDepoK(){
        return $this->hasMany(Member::class, 'id_member', 'id');
    }

    public function bookingGym(){
        return $this->hasMany(Member::class, 'id_member', 'id');
    }

    public function presensiGym(){
        return $this->hasMany(Member::class, 'id_member', 'id');
    }

    public function presensiKelas(){
        return $this->hasMany(Member::class, 'id_member', 'id');
    }

    public function bookingKelas(){
        return $this->hasMany(Member::class, 'id_member', 'id');
    }
}

