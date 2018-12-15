<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
  public function task()
  {
      return $this->belongsTo(Task::class,'task_id','id');
  }

  public function user()
  {
      return $this->belongsTo(User::class);
  }
}
