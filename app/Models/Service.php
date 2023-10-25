<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'default_price',
        'default_duration', // minutes
        'active'
    ];

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'service_store');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('override_price', 'override_duration');
    }
}
