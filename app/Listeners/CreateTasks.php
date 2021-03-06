<?php

namespace App\Listeners;

use App\Events\UploadDatas;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Encore\Admin\Facades\Admin;
use App\Models\TaskOrder;
use App\Models\Task;
use DB;
use Log;

class CreateTasks
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    protected $taskorders;
    protected $task;

    public function __construct()
    {
        //
        $this->taskorders = TaskOrder::all();
        $this->task = Task::get(['eid']);
    }

    /**
     * Handle the event.
     *
     * @param  UploadDatas  $event
     * @return void
     */
    public function handle(UploadDatas $event)
    {
        //
        $head = $event->head;
        $row=[];
        //Log::info($event->data);
        foreach ($event->data as $key => $d) {
            # code...
            $temp=[];
            //Log::info($d);
            foreach ($d as $k => $v) {
                # code...
                //Log::info($k);
                switch ($k) {
                    case '问题类型':
                    if($v==null){
                        break;
                    }
                    //Log::info($k);
                    $qdata = getQid($v);
                    //Log::info($qdata);
                    $now = date("Y-m-d H:i:s",time());
                    $temp[$head[$k]] = $qdata['id'];
                    $temp['deadline'] = getDeadline($qdata['seconds']);
                    $temp['user_uuid'] = Admin::user()->uuid;
                    
                    $temp['created_at'] = $now;
                    $temp['updated_at'] = $now;
                    break;  
                    case '快递单号':
                    if($v==null){
                        break;
                    }
                    $temp['eid'] = $v;
                    $ishave = $this->task->where('eid',$v)->first();
                    if(isset($ishave->eid)){
                        admin_error("导入失败", "该单号已存在,请删除该单号后重新导入.单号：".$v);
                            return redirect()->back();
                    }
                    $result = $this->taskorders->where('eid',$v)->first();
                    if(isset($result->eid)){
                        $adminuserdata = $result->adminuser;
                        if(isset($adminuserdata->uuid)){
                            $temp['sid'] = $adminuserdata->uuid;
                            $temp['sname'] = $adminuserdata->name;
                            $temp['sqq'] = $adminuserdata->qq;
                            $temp['isok'] = 1;
                        }else{
                            admin_error("导入失败", "该客服信息不存在，请检查客服名称是否有误：".$result->sname."单号：".$v);
                            return redirect()->back();
                        }
                    }else{
                        $temp['sid'] = null;
                        $temp['sname'] = null;
                        $temp['sqq'] = null;
                        $temp['isok'] = 0;
                    }
                    break;
                    default:
                    if($v==null){
                        break;
                    }
                    $temp[$head[$k]] = $v;
                    break;
                }
            }
            if(!empty($temp)){
                $row[]=$temp;
            }
        }
        
        //Log::info($row);
        DB::transaction(function () use ($row){
            DB::table('tasks')->insert($row);
        }, 5);
    }
}
