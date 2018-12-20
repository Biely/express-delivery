<?php

namespace App\Admin\Extensions\Nav;
use Encore\Admin\Facades\Admin;
use App\Models\Adminuser;

class notifications
{
    protected $user;
    protected $notifications;
    protected $url;

    public function __construct()
    {
        if(isset(Admin::user()->id)){
            $this->user = Adminuser::find(Admin::user()->id);
            $this->notifications = $this->user->unreadNotifications()->count();
            $this->url = route('notifi.index');
        }else{
            $this->notifications = 0;
            $this->url = "#";
        }
        
    }

    public function __toString()
    {
        return <<<HTML

<li>
    <a href={$this->url}>
      <i class="fa fa-envelope-o"></i>
      <span class="label label-warning">{$this->notifications}</span>
    </a>
</li>

HTML;
    }
}