<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['text', 'points', 'group_id'];

    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_id', 'id');
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($group) {
            $group->answers()->delete();
        });
    }
    public function group()
    {
        return $this->belongsTo(Groups::class, 'group_id', 'id');
    }

}
