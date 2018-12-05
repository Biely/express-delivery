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
        $this->middleware('auth');
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
        return view('home',compact('user','tasks','count'));
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
}
