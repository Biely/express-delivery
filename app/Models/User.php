<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Log;

class User extends Model
{
    //use SoftDeletes;
    //protected $guarded = ['password'];
    //protected $fillable = ['uuid','name',];
    use Notifiable {
        notify as protected laravelNotify;
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class,'user_uuid', 'uuid');
    }

    public function notify($instance)
    {
        // 如果要通知的人是当前用户，就不必通知了！
        // if ($this->id == Auth::id()) {
        //     return;
        // }
        $this->increment('notification_count');
        $this->laravelNotify($instance);
        //dump($instance);
    }

    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }
}
