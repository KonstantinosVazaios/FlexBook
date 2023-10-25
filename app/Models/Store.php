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

    public function hours()
    {
        return $this->hasMany(StoreHour::class);
    }

    // Used to get Admins & Staff Users
    public function users()
    {
        return $this->belongsToMany(User::class, 'store_user');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_store');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
