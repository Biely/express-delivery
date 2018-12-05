<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    //use SoftDeletes;
    //protected $guarded = ['password'];
    //protected $fillable = ['uuid','name',];
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class,'user_uuid', 'uuid');
    }
}
