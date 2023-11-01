<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'address',
        'telephone',
        'email',
        'active'
    ];

    public function storeHolidays()
    {
        return $this->hasMany(StoreHoliday::class);
    }

    public function storeHours()
    {
        return $this->hasMany(StoreHour::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'store_user')->withPivot('id', 'role_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_store')
            ->withPivot('override_price', 'override_duration');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
