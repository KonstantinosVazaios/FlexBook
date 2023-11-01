<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreHoliday extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'is_open',
        'open',
        'close'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
