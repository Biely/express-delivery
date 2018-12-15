<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;

class NotificationsController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $requset)
    {
        // 获取登录用户的所有通知
        $user = User::find(Auth::user()->id);
        $data =  $requset->all();
        if(!isset($data['view'])){
            $notifications = $user->unreadNotifications()->paginate(20);
            //$notifications->markAsRead();
            //dump($notifications);
        }else{
            $notifications = $user->notifications()->paginate(20);
        }
        $user->markAsRead();
        return view('notification', compact('notifications'));
    }
}
