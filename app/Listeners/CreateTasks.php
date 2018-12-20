<?php

namespace App\Listeners;

use App\Events\UploadDatas;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Encore\Admin\Facades\Admin;
use App\Models\TaskOrder;
use App\Models\Adminuser;
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
    protected $adminuser;

    public function __construct()
    {
        //
        $this->taskorders = TaskOrder::all();
        $this->adminuser = Adminuser::all();
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
        foreach ($event->data as $key => $d) {
            # code...
            $temp=[];
            foreach ($d as $k => $v) {
                # code...
                //Log::info($k);
                switch ($k) {
                    case '问题类型':
                        $qdata = getQid($v);
                        $now = date("Y-m-d H:i:s",time());
                        $temp[$head[$k]] = $qdata['id'];
                        $temp['deadline'] = getDeadline($qdata['seconds']);
                        $temp['user_uuid'] = Admin::user()->uuid;
                        
                        $temp['created_at'] = $now;
                        $temp['updated_at'] = $now;
                        break;
                    // case " ":
                    //     $temp[$head[$k]] = " ";
                    break;
                case '快递单号':
                    $temp['eid'] = $v;
                    $result = $this->taskorders->where('eid',$v)->first();
                    Log::info('单号：'.$v.$result);
                    if(!empty($result)){
                        $audata = $this->adminuser->where('name',$result->sname)->first();
                        if(!empty($audata)){
                            Log::info('客服信息：'.$audata);
                            $temp['sid'] = $audata->uuid;
                            $temp['sname'] = $audata->name;
                            $temp['sqq'] = $audata->qq;
                            $temp['isok'] = 1;
                        }
                    }
                    break;
                    default:
                        $temp[$head[$k]] = $v;
                        break;
                }
            }
            $row[]=$temp;
        }
        
        //Log::info($row);
        DB::transaction(function () use ($row){
            DB::table('tasks')->insert($row);
        }, 5);
    }
}
