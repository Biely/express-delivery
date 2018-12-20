<?php

namespace App\Models;

use App\Models\Adminuser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    //use SoftDeletes;

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function adminuser()
    {
        return $this->belongsTo(Adminuser::class,'sid','uuid');
    }

    public function setFileAttribute($image)
    {
        if (is_array($image)) {
            $this->attributes['file'] = json_encode($image);
        }
    }

    public function getFileAttribute($image)
    {
        return json_decode($image, true);
    }
}
