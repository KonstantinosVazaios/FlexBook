<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'store_id',
        'user_id',
        'name',
        'telephone',
        "start_date",
        "end_date"
    ];

    public function services()
    {
        return $this->hasMany(ReservationService::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
