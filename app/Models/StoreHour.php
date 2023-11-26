<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreHour extends Model
{
    use HasFactory;

    // 1 MONDAY
    // 2 TUESDAY
    // 3 WEDNESDAY
    // 4 THURSDAY
    // 5 FRIDAY
    // 6 SATURDAY
    // 7 SUNDAY

    protected $fillable = [
        'day',
        'is_open',
        'open',
        'close',
    ];

    public function getDayLabelAttribute()
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return $days[$this->day - 1];
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
