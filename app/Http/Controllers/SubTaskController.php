<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Qtype;
use App\Models\Task;
use App\Models\TaskOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Log;

class SubTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
       $this->middleware('auth');
    }

    public function index()
    {
        //
        $data = getQtypes();
        return abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $user = Auth::user();
        $qtypes = collect(getQtypes());
        $count = taskcount($user->uuid);
        return view('task.create',compact('qtypes','count'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'eid' => 'required|max:50',
            'qtype' => 'required',
            'content' => 'required|max:255'
        ]);
        $data = $request->all();
        $qdata = getQdata($data['qtype']);
        $deadline = getDeadline($qdata['seconds']);
        $user = Auth::user();
        $task = new Task;
        $edata = TaskOrder::where('eid',$data['eid'])->first();
        if(!isset($edata->id)){
          $sdata = $edata->adminuser;
          if(!$sdata->isEmpty()){
            $task->sid = $sdata->uuid;
            $task->sname = $sdata->name;
            $task->isok = 1;
          }
        }
        $task->eid = $data['eid'];
        $task->qtype = $data['qtype'];
        $task->etype = $user->etype;
        $task->store = $user->store;
        $task->qq = $user->qq;
        $task->tel = $user->tel;
        $task->uname = $user->name;
        $task->user_uuid = $user->uuid;
        $task->deadline = $deadline;
        $task->content = e($data['content']);
        if($task->save()){
          return redirect()->route('home')->with('status', '提交成功。');
        }else{
          return back()->withErrors(['提交失败。']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $user = Auth::user();
        $task = Task::findOrFail($id);
        $comments = $task->comments()->orderBy('created_at','desc')->paginate(5);
        $action = 'detil';
        $count = taskcount($user->uuid);
        //dump($isok);
        return view('taskdetil',compact('task','action','comments','count'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $user = Auth::user();
      $task = Task::findOrFail($id);
      $action = 'edit';
      $qtypes = collect(getQtypes());
      $count = taskcount($user->uuid);
      return view('taskdetil',compact('task','action','qtypes','count'));
    }

    protected function detail($id){

    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task, $id)
    {
        //
        $this->validate($request, [
             'eid' => ['required', 'string', 'max:255'],
             'qtype' => ['required', 'string', 'max:255'],
             'content' => ['required', 'string', 'max:255']
         ]);
         $data = $request->all();
         $qdata = getQdata($data['qtype']);
         $task=$task->findOrFail($id);
         $task->eid = $data['eid'];
         $task->qtype = $data['qtype'];
         $task->content = $data['content'];
         $task->deadline = getDeadline($qdata['seconds']);
         if($task->save()){
           return redirect()->route('subtask.show',$task->id)->with('status', '修改成功，您的问题处理期限将根据最新修改时间重新计算。');
         }else{
           return back()->withErrors(['保存失败。']);
         }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function score(Request $request, Task $task,$id){
      $task=$task->findOrFail($id);
      $data = $request->all();
      Log::info($data);
      $task->score = $data['score'];
      if($task->save()){
        return ['status' => 'success'];
      }else{
        return ['status' => 'fail'];
      }
    }

    public function moretask(Request $request, Task $task,$id){
      $task=$task->findOrFail($id);
      $data = $request->all();
      $qdata = getQdata($task->qtype);
      $task->score = null;
      $task->times = $task->times+1;
      $task->deadline = getDeadline($qdata['seconds']);
      $task->file = null;
      $task->isok = 0;
      if($task->save()){
        return ['status' => 'success'];
      }else{
        return ['status' => 'fail'];
      }
    }

}
