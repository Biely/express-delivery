<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskOrder extends Model
{
    protected $table = 'task_orders';

    public function adminuser(){
        return $this->hasOne(AdminUser::class,'name','sname');
    }
}
