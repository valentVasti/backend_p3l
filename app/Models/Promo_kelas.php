<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo_kelas extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_promo_kelas';
    protected $keyType = 'string';
    protected $table = 'promo_kelas';
    protected $fillable = [
        'syarat_bonus_kelas',
        'bonus_kelas',
    ];

    public function transaksiDepoK(){
        return $this->hasMany(Promo_kelas::class, 'id_promo_kelas', 'id');
    }
}
