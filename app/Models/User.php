<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return str_ends_with($this->email, '@flexbook.gr');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function hasRole($role)
    {
        $this->roles()->where('name', $role)->get()->isNotEmpty();
    }

    public function hasPermission($permission)
    {
        return 
        $this->role->permissions()->where('name', $permission)->get()->isNotEmpty() ||
        $this->permissions()->where('name', $permission)->get()->isNotEmpty();
    }

    public function hours()
    {
        return $this->hasMany(WorkHour::class);
    }

    public function leaves()
    {
        return $this->hasMany(WorkLeaves::class);
    }

    // Staff Users have many services
    public function services()
    {
        return $this->belongsToMany(Service::class)
            ->withPivot('override_price', 'override_duration');
    }

    // Client Users have many reservation
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
