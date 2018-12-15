<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminUser extends Model
{
    //
    protected $table = 'admin_users';

    protected $fillable = ['id','uuid', 'username', 'name' , 'qq' , 'tel', 'etype'];
    
    public function taskorder()
    {
        return $this->belongsTo(TaskOrder::class);
    }
}
