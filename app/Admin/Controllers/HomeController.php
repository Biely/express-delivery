<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use App\Models\Task;
use App\Models\User;
use App\Models\Comment;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        
        return $content
            ->header('首页')
            ->description('')
            ->row(function (Row $row){
                $tasks = Task::all();
                $waittodo = $tasks->where('isok','<','2')->count();
                $waittoget = $tasks->where('isok','<','1')->count();
                $nodo = $tasks->where('isok','<','2')->where('deadline','<',time())->count();
                $row->column(3, function (Column $column) use($tasks){
                    
                    $column->append('<div class="small-box bg-aqua">
                    <div class="inner">
                      <h3>'.$tasks->count().'</h3>
        
                      <p>累计工单</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="#" class="small-box-footer">更多信息 <i class="fa fa-arrow-circle-right"></i></a>
                  <iframe id="tmp_downloadhelper_iframe" style="display: none;"></iframe></div>');
                });

                $row->column(3, function (Column $column) use($waittodo){
                    $column->append('<div class="small-box bg-green">
                    <div class="inner">
                      <h3>'.$waittodo.'</h3>
        
                      <p>待完成工单</p>
                    <iframe id="tmp_downloadhelper_iframe" style="display: none;"></iframe></div>
                    <div class="icon">
                      <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="#" class="small-box-footer">更多信息 <i class="fa fa-arrow-circle-right"></i></a>
                  </div>');
                });
                $row->column(3, function (Column $column) use($waittoget){
                    $column->append('<div class="small-box bg-yellow">
                    <div class="inner">
                      <h3>'.$waittoget.'</h3>
        
                      <p>待领取工单</p>
                    <iframe id="tmp_downloadhelper_iframe" style="display: none;"></iframe></div>
                    <div class="icon">
                      <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="#" class="small-box-footer">更多信息 <i class="fa fa-arrow-circle-right"></i></a>
                  </div>');
                });
                $row->column(3, function (Column $column) use($nodo){
                    $column->append('<div class="small-box bg-red">
                    <div class="inner">
                      <h3>'.$nodo.'</h3>
        
                      <p>已超时工单</p>
                    <iframe id="tmp_downloadhelper_iframe" style="display: none;"></iframe></div>
                    <div class="icon">
                      <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="#" class="small-box-footer">更多信息 <i class="fa fa-arrow-circle-right"></i></a>
                  </div>');
                });
            });
    }
}
