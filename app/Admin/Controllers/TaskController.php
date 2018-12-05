<?php

namespace App\Admin\Controllers;

use App\Models\Task;
use App\Http\Controllers\Controller;
use App\Admin\Extensions\Tools\TasksGet;
use App\Admin\Extensions\Tools\TaskGet;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Encore\Admin\Facades\Admin;
use Log;

class TaskController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {

        $grid = new Grid(new Task);
        $grid->model()->orderBy('id', 'desc');
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->equal('eid','快递单号')->integer();
            $filter->equal('store','快递网点');
            $filter->equal('etype','快递类型')->select(etype());
            $filter->equal('qtype','问题类型')->select(qdataArry());
            $filter->between('created_at', '投诉时间')->datetime();
        });
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $sid = "'".Admin::user()->uuid."'";
                $sname = "'".Admin::user()->username."'";
                //$batch->disableDelete();
                $batch->add('领取任务', new TasksGet($sid,$sname));
            });
        });
        $grid->id('工单ID')->sortable();
        $grid->eid('快递单号');
        $grid->etype('快递类型')->sortable();
        $grid->store('快递网点')->sortable();
        $grid->qtype('问题类型')->sortable()->display(function ($qtype) {
            $data = getQdata($qtype);
            return $data['name'];
        });
        $grid->uname('投诉人');
        $grid->qq('联系方式');
        $grid->times('投诉次数')->sortable();
        $grid->content('内容')->display(function($content) {
          return str_limit($content, 30, '...');
      });

        //$grid->updated_at('Updated at');
        $grid->sname('负责客服')->sortable()->display(function($sname){
          if($sname==null){
            $w = "<span style='color:#f39c12'>无</span>";
          }else{
            $w = "<span style='color:green'>$sname</span>";
          }
          return $w;
        });
        $grid->created_at('投诉时间')->sortable();
        $grid->deadline('完结期限')->sortable()->display(function ($deadline) {
            //
            return date("Y-m-d H:i:s",$deadline);
        })->badge();
        $grid->isok('状态')->sortable()->display(function($isok){
          if($isok==0){
            $d = "";
            if($this->deadline<time()){
              $d ="<span style='color:red'>（已超时）</span>";
            }
            $w = "<span style='color:#f39c12'>待处理</span>".$d;
          }
          if($isok==1){
            $d = "";
            if($this->deadline<time()){
              $d ="<span style='color:red'>（已超时）</span>";
            }
            $w = "<span style='color:green'>正在处理</span>".$d;
          }
          if($isok==2){
            $d ="<span style='color:red'>（已超时）</span>";
            $w = "<span style='color:green'>已完结</span>".$d;
          }
          if($isok==3){
            $w = "<span style='color:green'>已完结</span>";
          }
          return $w;
        });
        $grid->actions(function ($actions) {
          //Log::info($actions->row);
          $actions->disableEdit();
           $actions->disableDelete();
          if($actions->row->isok == 0){
            $sid = "'".Admin::user()->uuid."'";
            $sname = "'".Admin::user()->username."'";
            $actions->append(new TaskGet($actions->getKey(),$sid,$sname));
          }
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Task::findOrFail($id));

        $show->id('工单ID');
        $show->eid('快递单号');
        $show->etype('快递类型');
        $show->qtype('问题类型')->as(function ($qtype) {
          $data = getQdata($qtype);
          return $data['name'];
        });
        $show->content('问题描述');
        $show->uname('投诉人');
        $show->qq('联系方式');
        $show->times('投诉次数');
        $show->sname('负责客服')->as(function ($sname) {
          if($sname==null){
            return '无';
          }else{
            return $sname;
          }
        });
        $show->created_at('发布时间');
        $show->deadline('完结期限')->as(function ($deadline) {
          return date("Y-m-d H:i:s",$deadline);
        })->badge();
        $show->isok('状态')->unescape()->as(function ($isok){
          if($isok==0){
            $d = "";
            if($this->deadline<time()){
              $d ="<span style='color:red'>（已超时）</span>";
            }
            $w = "<span style='color:#f39c12'>待处理</span>".$d;
          }
          if($isok==1){
            $d = "";
            if($this->deadline<time()){
              $d ="<span style='color:red'>（已超时）</span>";
            }
            $w = "<span style='color:green'>正在处理</span>".$d;
          }
          if($isok==2){
            $d ="<span style='color:red'>（已超时）</span>";
            $w = "<span style='color:green'>已完结</span>".$d;
          }
          if($isok==3){
            $w = "<span style='color:green'>已完结</span>";
          }
          return $w;
        });
        $show->file('处理凭证')->file();
        $show->score('评价')->unescape()->as(function ($score) {
          if($score == null){
            return '无';
          }else{
            $str = "";
            $str1='<i class="fa fa-star fa-lg " style="color:#ffc107" aria-hidden="true"></i>';
            for($i=0;$i<$score;$i++){
              $str .= $str1;
            }
            return $str;
          }
        });
        $show->comments('评论', function ($comments) {

          $comments->resource('/admin/comments');
          $comments->model()->orderBy('id', 'desc');
          //$comments->id();
          $comments->formuser('评论人');
          $comments->content('内容')->limit(100);
          $comments->created_at("评论时间");
          //$comments->updated_at();

          $comments->filter(function ($filter) {
              $filter->like('content');
          });
      });
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
      $form = new Form(new Task);

      $form->tools(function (Form\Tools $tools) {
          $tools->disableDelete();
      });
      $form->hidden('isok','是否完结')->value();
      $form->hidden('deadline','完结时间')->value();
      $form->file('file','完结凭证')->uniqueName();
      $form->saving(function (Form $form) {
        if($form->deadline<time()){
          $form->isok=2;
        }else{
          $form->isok=3;
        }
      });
      return $form;
    }
    //批量领取任务
    public function tasksget(Request $request){
        foreach (Task::find($request->input('ids')) as $post) {
            $post->sid = $request->input('action');
            $post->sname = $request->input('sname');
            $post->isok = 1;
            $post->save();
        }
        return "success";
    }

    public function taskget(Request $request){
            $post=Task::find($request->input('id'));
            $post->sid = $request->input('sid');
            $post->sname = $request->input('sname');
            $post->isok = 1;
            $post->save();
            return "success";
    }
}
