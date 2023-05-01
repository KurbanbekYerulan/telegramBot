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
        return $this->hasMany(Question::class,'group_id','id')->orderBy('id');
    }

    public function schedule(){
        return $this->hasMany(Schedule::class,'group_id','id');
    }

    public function homeworks(){
        return $this->hasMany(Homeworks::class,'group_id','id');
    }
}
