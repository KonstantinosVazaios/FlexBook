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
        'price',
        'sort_index'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
