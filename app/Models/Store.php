<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Store extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
