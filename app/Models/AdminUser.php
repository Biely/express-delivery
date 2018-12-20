<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Log;
use App\Models\TaskOrder;

class AdminUser extends Model
{
    //
    use Notifiable {
        notify as protected laravelNotify;
    }

    protected $table = 'admin_users';

    protected $fillable = ['id','uuid', 'username', 'name' , 'qq' , 'tel', 'etype','notification_count','score','ts','sumscore'];
    
    public function taskorder()
    {
        return $this->belongsTo(TaskOrder::class,'sname','name');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }  
    
    public function tasks(){
        return $this->hasMany(Task::class,'sid','uuid');
    }

    public function notify($instance)
    {
        // 如果要通知的人是当前用户，就不必通知了！
        // if ($this->id == Auth::id()) {
        //     return;
        // }
        $this->increment('notification_count');
        $this->laravelNotify($instance);
        //Log::info((string)$instance);
    }

    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }

    public function updateScore($step){
        $this->increment('sumscore',$step*20);
        $this->increment('ts');
        $this->save();
        $this->score=$this->sumscore/$this->ts;
        $this->save();
        return $this;
    }
}
