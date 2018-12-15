<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Comment;
use Log;

class TaskNews extends Notification
{
    use Queueable;

    public $comment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        //
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // 开启通知的频道
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $task = $this->comment->task;
        //$link =  $topic->link(['#reply' . $this->reply->id]);
        //$notifiable->id = $notifiable->uuid;
        // 存入数据库里的数据
        return [
            'task_id' => $this->comment->task_id,
            'touser' => $this->comment->touser,
            'fromusername' => $this->comment->formuser,
            'eid' => $task->eid,
            'content' => $this->comment->content
        ];
    }
}
