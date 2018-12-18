<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Log;

class ApiController extends Controller{


    public function updatascore(){
        
        
        DB::transaction(function () {
            DB::table('admin_users')->update(['score' => 100,'sumscore'=>0,'ts'=>0]);
            Log::info('客服评分自动更新成功'.Carbon::now());
            //return 'ok';
        });
    }
}