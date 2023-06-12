<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking_kelas extends Model
{
    use HasFactory;

    protected $primaryKey = null;
    public $incrementing = false;
    protected $table = 'booking_kelas';
    protected $fillable = [
        'id_jadwal_harian',
        'id_member',
        'tgl_booking_kelas',
        'status',
    ];

    public function jadwal_harian(){
        return $this->belongsTo(Jadwal_harian::class, 'id_jadwal_harian');
    }

    public function member(){
        return $this->belongsTo(Member::class, 'id_member');
    }
}
