<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Adminuser;

class TaskOrder extends Model
{
    protected $table = 'task_orders';

    public function adminuser(){
        return $this->hasOne(Adminuser::class,'name','sname');
    }
}
