<?php

namespace App\Admin\Controllers;

use App\Models\Comment;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Carbon\Carbon;
//use App\Admin\Controllers\TaskController;

class CommentController extends Controller
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
            ->header('用户评论')
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
    public function show($id, Content $content, Request $request)
    {
      $data = $request->input();
        return $content
            ->header('详情')
            ->description('评论详情')
            ->row($this->detail($id))
            ->row($this->form($data['task_id'],route('comments.store')));
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
      //$taskctr = new TaskController;
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    protected function taskdetail($id)
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
          $comments->model()->orderBy('id', 'desc')->paginate(5);
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
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content,Request $request)
    {
      $taskid = $request->input('task_id');
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form($taskid));
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Comment);
        $grid->actions(function ($actions) {
            $actions->disableEdit();
            $actions->disableView();
            $actions->append('<a href="'.route('comments.show', $actions->getKey()).'?task_id='.$actions->row->task_id.'"><i class="fa fa-eye"></i></a>');
        });
        $grid->disableCreateButton();
        $grid->model()->orderBy('id', 'desc');
        // $grid->id('Id')->sortable();
        $grid->task_id('工单id')->sortable();
        $grid->formuser('评论人');
        $grid->content('内容');
        $grid->created_at('发布时间')->display(function ($created_at){
          return  Carbon::parse($created_at)->diffForHumans();
        })->sortable();

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
        $show = new Show(Comment::findOrFail($id));

        //$show->id('Id');
        $show->task('工单详情',function ($task){
          $task->setResource(route('alltasks.index'));
          $task->id();
          $task->id('工单ID');
          $task->eid('快递单号');
          $task->etype('快递类型');
          $task->qtype('问题类型')->as(function ($qtype) {
            $data = getQdata($qtype);
            return $data['name'];
          });
          $task->content('问题描述');
          $task->uname('投诉人');
          $task->qq('QQ');
          $task->tel('联系方式');
          $task->times('投诉次数');
          $task->sname('负责客服')->as(function ($sname) {
            if($sname==null){
              return '无';
            }else{
              return $sname;
            }
          });
          $task->created_at('发布时间');
          $task->deadline('完结期限')->as(function ($deadline) {
            return date("Y-m-d H:i:s",$deadline);
          })->badge();
          $task->isok('状态')->unescape()->as(function ($isok){
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
          $task->file('处理凭证')->file();
          $task->score('评价')->unescape()->as(function ($score) {
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
        });
        $show->task_id('工单id');
        //$show->user_uid('User uid');
        $show->formuser('评论人');
        //$show->touser('Touser');
        $show->content('内容');
        //$show->created_at('评论时间');
        $show->updated_at('评论时间');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form($taskid = '',$action = null)
    {
        $task = Comment::findOrFail($taskid);
        $form = new Form(new Comment);
        if($action != null){
          $form->setAction($action);
        }
        $form->setTitle('回复评论');
        $form->hidden('task_id', '任务id')->value($taskid);
        $form->hidden('user_uuid','用户id')->value(Admin::user()->uuid);
        $form->hidden('formuser','用户名')->value(Admin::user()->name);
        $form->hidden('touser','回复人')->value($task->user_uuid);
        $form->textarea('content','内容')->rules('required|max:255')->rows(1);
        $form->file('file','附件')->uniqueName();
        $form->saved(function (Form $form) {
            $taskid=$form->model()->task_id;
            // 跳转页面
            $success = new MessageBag([
               'title'   => '',
               'message' => '评论成功',
           ]);
            return back()->with(compact('success'));
        });
        return $form;
    }
}
