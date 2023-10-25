<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationService extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'staff_id',
        'service_id',
        'service_name',
        'duration',
        'price',
        'start_time',
        'end_time',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
