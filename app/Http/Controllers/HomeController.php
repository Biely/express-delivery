<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function __construct()
    {
      $this->middleware(['auth','canuse']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $tasks = Task::where('user_uuid',$user->uuid)->paginate(10);
        //dump($tasks);
        $count = taskcount($user->uuid);
        $this->isshangban($request);
        //dump(Auth::user()->storeinfo);
        return view('home',compact('user','tasks','count'));
    }

    public function news(){

    }

    public function waitodo(Request $request){
      $user = Auth::user();
      $tasks = Task::where('user_uuid',$user->uuid)->where('isok','0')->paginate(10);
      $s = '待处理';
      $this->isshangban($request);
      $count = taskcount($user->uuid);
      return view('home',compact('user','tasks','s','count'));
    }

    public function hasget(Request $request){
      $user = Auth::user();
      $tasks = Task::where('user_uuid',$user->uuid)->where('isok','1')->paginate(10);
      $s = '已接单';
      $this->isshangban($request);
      $count = taskcount($user->uuid);
      return view('home',compact('user','tasks','s','count'));
    }

    public function isok(Request $request){
      $user = Auth::user();
      $tasks = Task::where('user_uuid',$user->uuid)->where('isok','>','1')->paginate(10);
      $s = '已完结';
      $this->isshangban($request);
      $count = taskcount($user->uuid);
      return view('home',compact('user','tasks','s','count'));
    }

    public function isshangban($request){
      $hour = date('H',time());
      $inthour = (int)$hour;
      if($inthour<=8||$inthour>=18){
        $request->session()->flash('status', '客服上班时间：8:10-18:00，下班时间工单无法及时处理，敬请谅解!');
      }
    }

    public function taskcount(){
      $user = Auth::user();
      $tasks = Task::where('user_uuid',$user->uuid);
      $isok['0'] = $tasks->where('isok','0')->count();
      $isok['1'] = $tasks->where('isok','1')->count();
      $isok['2'] = $tasks->where('isok','>','1')->count();
      return $isok;
    }

    public function search(Request $request, Task $task){
      $user = Auth::user();
      $count = taskcount($user->uuid);
      $this->isshangban($request);
      $this->validate($request, [
        'eid' => ['required', 'string', 'max:255']
      ]);
      $data = $request->all();
      $tasks = $task->where('eid',$data['eid'])->where('user_uuid',$user->uuid)->paginate(10);
      if($tasks->count()==0){
        $request->session()->flash('error', '查找不到工单');
        return view('home',compact('user','tasks','s','count'));
      }else{
        return view('home',compact('user','tasks','s','count'));
      }
    }

    public function notif(Request $request)
    {
        // 获取登录用户的所有通知
        $user = User::find(Auth::user()->id);
        $count = taskcount(Auth::user()->uuid);
        $this->isshangban($request);
        $data =  $request->all();
        $notifications = $user->unreadNotifications()->paginate(20);
        if($notifications->count()==0){
          $notifications = $user->notifications()->paginate(20);
        }else{
          $user->markAsRead();
        }
        return view('notification', compact('notifications','count','user'));
    }
}
