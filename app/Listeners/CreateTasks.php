<?php

namespace App\Listeners;

use App\Events\UploadDatas;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Encore\Admin\Facades\Admin;
use Log;

class CreateTasks
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
                switch ($k) {
                    case '问题类型':
                        $qdata = getQid($k);
                        $temp[$head[$k]] = $qdata['id'];
                        $temp['deadline'] = getDeadline($qdata['seconds']);
                        $temp['user_uuid'] = Admin::user()->uuid;
                        break;
                    default:
                        $temp[$head[$k]] = $v;
                        break;
                }
            }
            $row[]=$temp;
        }
        Log::info($row);
    }
}
