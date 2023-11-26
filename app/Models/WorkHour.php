<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkHour extends Model
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
        'store_id',
        'day',
        'off_work',
        'start',
        'end',
    ];

    public function getDayLabelAttribute()
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return $days[$this->day - 1];
    }

    public function getStoreNameAttribute()
    {
        return Store::find($this->store_id)->name;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
