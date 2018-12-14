<?php

namespace App\Listeners;

use App\Events\UploadKf;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Encore\Admin\Facades\Admin;
use DB;
use Log;

class CreateOrders
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
     * @param  UploadKf  $event
     * @return void
     */
    public function handle(UploadKf $event)
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
                // switch ($k) {
                //     case " ":
                //         $temp[$head[$k]] = "无";
                //         break;
                //     // case '快递单号':
                //     //     $temp['eid'] = time();
                //     //     break;
                //     default:
                //         $temp[$head[$k]] = $v;
                //         break;
                // }
                $temp[$head[$k]] = $v;
            }
            $row[]=$temp;
        }
        
        //Log::info($row);
        DB::transaction(function () use ($row){
            DB::table('task_orders')->insert($row);
        }, 5);
    }
}
