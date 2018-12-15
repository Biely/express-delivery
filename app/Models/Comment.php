<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
  public function task()
  {
      return $this->belongsTo(Task::class,'id','task_id');
  }

  public function user()
  {
      return $this->belongsTo(User::class);
  }
}
