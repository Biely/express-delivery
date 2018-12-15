<?php
namespace App\Observers;

use App\Notifications\TaskNews;
use App\Models\Comment;
use Log;

class CommentsObserver{
    public function created(Comment $comment)
    {
        if($comment->usertype==0){
            $user = $comment->adminuser;
            if($comment->touser!=null){
                $user->notify(new TaskNews($comment));
            }
        }else{
            $user = $comment->user;
            $user->notify(new TaskNews($comment));
        }
        
        //Log::info(new TaskNews($comment));
        // 通知作者话题被回复了
        
    }
}