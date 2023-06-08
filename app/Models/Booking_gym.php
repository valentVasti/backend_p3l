<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking_gym extends Model
{
    use HasFactory;

    protected $primaryKey = null;
    public $incrementing = false;
    protected $table = 'booking_gym';
    protected $fillable = [
        'id_member',
        'sesi',
        'tgl_booking',
        'status',
    ];

    public function member(){
        return $this->belongsTo(Member::class, 'id_member');
    }

    public function sesiGym(){
        return $this->belongsTo(Sesi_gym::class, 'sesi');
    }
}
