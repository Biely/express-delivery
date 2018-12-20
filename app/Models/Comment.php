<?php

namespace App\Models;

use App\Models\User;
use App\Models\Adminuser;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
  public function task()
  {
      return $this->belongsTo(Task::class,'task_id','id');
  }

  public function user()
  {
      return $this->belongsTo(User::class,'touser','uuid');
  }

  public function adminuser()
  {
      return $this->belongsTo(Adminuser::class,'touser','uuid');
  }
}
