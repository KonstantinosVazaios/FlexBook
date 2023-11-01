<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'default_price',
        'default_duration', // minutes
        'active'
    ];

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'service_store')
            ->withPivot('override_price', 'override_duration');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('override_price', 'override_duration');
    }
}
