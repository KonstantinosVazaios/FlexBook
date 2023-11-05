<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkHour extends Model
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
        'store_id',
        'day',
        'off_work',
        'start',
        'end',
    ];

    public function getDayLabelAttribute()
    {
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        return $days[$this->day];
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
