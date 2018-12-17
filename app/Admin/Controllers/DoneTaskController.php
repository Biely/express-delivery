<?php

namespace App\Admin\Controllers;

use App\Models\Task;
use App\Http\Controllers\Controller;
use App\Admin\Controllers\CommentController;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Facades\Storage;

class DoneTaskController extends Controller
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
            ->header('已完结')
            ->description('列表')
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
      $task=Task::findOrFail($id);
      $commentctr = new CommentController;
        return $content
            ->header('详情')
            ->description('任务详情')
            // ->body($this->detail($id));
            ->row($this->detail($id))
            ->row($commentctr->form($id,$task->user_uuid,route('comments.store')));
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
            ->header('编辑')
            ->description('内容编辑')
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
            ->header('创建')
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
       $grid->model()->where('sid',Admin::user()->uuid)->where('isok','>','1')->orderBy('id', 'desc');
       $grid->filter(function($filter){
           $filter->disableIdFilter();
           $filter->equal('eid','快递单号')->integer();
           $filter->equal('store','快递网点');
           $filter->equal('etype','快递类型')->select(edatas());
           $filter->equal('qtype','问题类型')->select(qdataArry());
           $filter->between('created_at', '投诉时间')->datetime();
           $filter->between('updated_at', '完成时间')->datetime();
       });
       $grid->disableCreateButton();
       $grid->id('工单ID')->sortable();
       $grid->eid('快递单号');
       $grid->etype('快递类型')->sortable();
       $grid->store('快递网点')->sortable();
       $grid->qtype('问题类型')->sortable()->display(function ($qtype) {
           $data = getQdata($qtype);
           return $data['name'];
       });
       $grid->uname('投诉人');
       $grid->qq('QQ')->display(function($qq){
        if($qq!=null){
          $w = $qq.'<a href="http://wpa.qq.com/msgrd?v=3&uin='.$qq.'&site=qq&menu=yes" target="_blank" class="btn btn-xs btn-info">发起聊天</a>';
        }else{
          $w = '无';
        }
        return $w;
      });
       $grid->times('投诉次数')->sortable();
       $grid->content('内容')->display(function($content) {
           return str_limit($content, 30, '...');
       });
       $grid->created_at('投诉时间')->sortable();
       $grid->deadline('完结期限')->sortable()->display(function ($deadline) {
           //
           return date("Y-m-d H:i:s",$deadline);
       })->badge();
       $grid->isok('状态')->sortable()->display(function($isok){
         if($isok==2){
           $d ="<span style='color:red'>（已超时）</span>";
           $w = "<span style='color:green'>已完结</span>".$d;
         }
         if($isok==3){
           $w = "<span style='color:green'>已完结</span>";
         }
         return $w;
       });
       $grid->updated_at('完结时间');
       $grid->actions(function ($actions) {
          $actions->disableDelete();
          $actions->disableEdit();
          $actions->disableView();
          $actions->append("<a class='btn btn-xs btn-info' href=".route('donetasks.show',$actions->row->id).">查看详情</a>");
       });
       $grid->tools(function ($tools) {
           $tools->batch(function ($batch) {
               $batch->disableDelete();
           });
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
      $show->panel()
    ->tools(function ($tools) {
        $tools->disableEdit();
        $tools->disableDelete();
    });
      $show->id('工单ID');
      $show->eid('快递单号');
      $show->etype('快递类型');
      $show->qtype('问题类型')->as(function ($qtype) {
        $data = getQdata($qtype);
        return $data['name'];
      });
      $show->content('问题描述');
      $show->uname('投诉人');
      $show->qq('QQ')->unescape()->as(function($qq){
        if($qq!=null){
          $w = $qq.'-<a href="http://wpa.qq.com/msgrd?v=3&uin='.$qq.'&site=qq&menu=yes" target="_blank" class="btn btn-xs btn-info">发起聊天</a>';
        }else{
          $w = '无';
        }
        return $w;
      });
      $show->tel('联系方式');
      $show->times('投诉次数');
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
      $show->bz('客服备注');
      $show->file('处理凭证')->unescape()->as(function ($file) {
        $html ="";
        if(is_array($file)){
          foreach ($file as $key => $f) {
            # code...
            $disk = config('admin.upload.disk');
            if (config("filesystems.disks.{$disk}")) {
                $src = Storage::disk($disk)->url($f);
                $html .= "<img src='$src' style='max-width:200px;max-height:200px' class='img' />";
            }
          }
        }
        
        return $html;
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

        $form->text('eid', 'Eid');
        $form->text('store', 'Store');
        $form->text('etype', 'Etype');
        $form->text('uid', 'Uid');
        $form->text('uname', 'Uname');
        $form->text('qq', 'Qq');
        $form->number('qtype', 'Qtype');
        $form->number('times', 'Times')->default(1);
        $form->textarea('content', 'Content');
        $form->text('deadline', 'Deadline');
        $form->file('file', 'File');
        $form->number('isok', 'Isok');
        $form->text('sid', 'Sid');
        $form->text('sname', 'Sname');
        $form->text('score', 'Score');

        return $form;
    }
}
