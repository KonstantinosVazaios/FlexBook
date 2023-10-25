<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreHour extends Model
{
    use HasFactory;

    // 0 SUNDAY
    // 1 MONDAY
    // 2 TUESDAY
    // 3 WEDNESDAY
    // 4 THURSDAY
    // 5 FRIDAY
    // 6 SATURDAY

    protected $fillable = [
        'day',
        'open',
        'close',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
