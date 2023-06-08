<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo_uang extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_promo';
    protected $keyType = 'string';
    protected $table = 'promo_uang';
    protected $fillable = [
        'syarat_bonus_uang',
        'bonus_uang',
    ];

    public function transaksiDepoU(){
        return $this->hasMany(Promo_uang::class, 'id_promo', 'id');
    }
}
