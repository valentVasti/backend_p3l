<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit_kelas extends Model
{
    use HasFactory;
    use HasFactory;

    protected $primaryKey = null;
    public $incrementing = false;
    protected $table = 'deposit_kelas';
    protected $fillable = [
        'id_member',
        'id_kelas',
        'deposit_kelas',
        'tgl_kadaluarsa',
    ];


    public function kelas(){
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function jadwalHarian(){
        return $this->hasMany(instruktur::class, 'id_instruktur','id');
    }
}
