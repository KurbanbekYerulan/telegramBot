<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function questions()
    {
        return $this->hasMany(Question::class, 'group_id', 'id')->orderBy('id');
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class, 'group_id', 'id');
    }

    public function material()
    {
        return $this->hasMany(Material::class, 'group_id', 'id');
    }

    public function homeworks()
    {
        return $this->hasMany(Homeworks::class, 'group_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($group) {
            $group->questions()->delete();
        });

        static::deleting(function ($group) {
            $group->schedule()->delete();
        });

        static::deleting(function ($group) {
            $group->homeworks()->delete();
        });

        static::deleting(function ($group) {
            $group->material()->delete();
        });
    }
}
