<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qtype extends Model
{
    public function setKeeptimeAttribute($value)
    {
        $this->attributes['keeptime'] = $value;
        $this->attributes['seconds'] = 3600*$value;
    }
}
