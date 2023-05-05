<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'phone',
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

    public function schedule()
    {
        return $this->hasMany(Schedule::class, 'user_id', 'id');
    }

    public function material()
    {
        return $this->hasMany(Material::class, 'user_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($group) {
            $group->schedule()->delete();
        });

        static::deleting(function ($group) {
            $group->material()->delete();
        });
    }
}
