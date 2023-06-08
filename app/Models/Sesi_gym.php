<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sesi_gym extends Model
{
    use HasFactory;

    protected $primaryKey = 'sesi';
    protected $keyType = 'string';
    protected $table = 'sesi_gym';
    protected $fillable = [
        'jam_mulai',
        'jam_selesai',
        'kuota'
    ];    

    public function bookingGym(){
        return $this->hasMany(Sesi_gym::class, 'sesi', 'id');
    }

    public function presensiGym(){
        return $this->hasMany(Sesi_gym::class, 'sesi', 'id');
    }
}
