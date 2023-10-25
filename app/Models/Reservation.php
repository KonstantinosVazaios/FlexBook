<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'user_id',
        'name',
        'telephone',
        'reservation_date',
    ];

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
