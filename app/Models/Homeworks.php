<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homeworks extends Model
{
    use HasFactory;

    protected $fillable = [
      'description',
      'group_id'
    ];

    public function group(){
        return $this->belongsTo(Groups::class,'group_id','id');
    }
}
