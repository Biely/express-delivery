<?php
/**所有任务控制器 */
namespace App\Admin\Controllers;

use Excel;
use Log;
use Event;
use App\Models\Task;
use App\Models\Comment;
use App\Http\Controllers\Controller;
use App\Admin\Controllers\CommentController;
use App\Admin\Extensions\Tools\TasksGet;
use App\Admin\Extensions\Tools\TaskGet;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Row;
use Carbon\Carbon;
use App\Admin\Extensions\ExcelExpoter;
use App\Events\UploadDatas;
use Illuminate\Support\Facades\Storage;

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
            ->header('任务中心')
            ->description('所有任务')
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
            ->header('导入数据')
            ->description('上传文件')
            ->row(function (Row $row) {

              $row->column(2,"");
          
              $row->column(8, "<a class='btn btn-info' href='http://mifengjf.com/uploads/files/temple.csv' target='_blank'>下载模板</a><br>注意：1.文件必须是.csv格式；<br>2.文件第一行字段名称不可更改;<br>3.文件内容不可包含英文逗号(,)<br>");
          })
            ->row($this->uploadtask());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {

        $grid = new Grid(new Task);
        if(Admin::user()->roles[0]->id == "7"){
          $grid->model()->where('isok','<','1')->where('etype',Admin::user()->etype)->orderBy('id', 'desc');
        }else{
          $grid->model()->orderBy('id', 'desc');
        }
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->equal('eid','快递单号')->integer();
            $filter->equal('store','快递网点');
            $filter->equal('etype','快递类型')->select(edatas());
            $filter->equal('qtype','问题类型')->select(qdataArry());
            $filter->equal('sname','负责客服');
            $filter->between('created_at', '投诉时间')->datetime();
            $filter->where(function ($query) {
              switch ($this->input) {
                  case '0':
                      // custom complex query if the 'yes' option is selected
                      $query->where('isok',0);
                      break;
                  case '1':
                      $query->where('isok',1);
                      break;
                  case '2':
                      $query->where('isok',2);
                      break;
                  case '3':
                      $query->whereRaw("`isok` <= 2 AND `deadline` <".time());
                      break;
                  case '4':
                      $query->whereRaw("`isok` = 0 AND `deadline` <".time());
                      break;
                  case '5':
                      $query->whereRaw("`isok` = 1 AND `deadline` <".time());
                      break;
                  case '6':
                      $query->whereRaw("`isok` = 2 AND `deadline` <".time());
                      break;
                  case '7':
                      $query->where('isok',3);
                      break;
              }
          }, '工单状态', 'status')->select([
              '0' => '待接单',
              '1' => '处理中',
              '2' => '已处理',
              '3' => '已超时',
              '4' => '待接单（已超时）',
              '5' => '处理中（已超时）',
              '6' => '已处理（已超时）',
              '7' => '正常完成'
          ]);
        });
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $sid = "'".Admin::user()->uuid."'";
                $sname = "'".Admin::user()->name."'";
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
        $grid->qq('QQ')->display(function($qq){
          if($qq!=null){
            $w = $qq.'<a href="http://wpa.qq.com/msgrd?v=3&uin='.$qq.'&site=qq&menu=yes" target="_blank" class="btn btn-xs btn-info">发起聊天</a>';
          }else{
            $w = '无';
          }
          return $w;
        });
        $grid->tel('联系方式');
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
          $this->deadline = $w;
          return $w;
        });
        $grid->isduty("是否有责")->display(function($isduty){
          if($isduty == 1) {
            return "是";
          }else{
            return "否";
          }
        });
        $grid->actions(function ($actions) {
          //Log::info($actions->row);
          $actions->disableEdit();
           $actions->disableDelete();
           $actions->disableView();
           $actions->append("<a class='btn btn-xs btn-info' href=".route('alltasks.show',$actions->row->id).">查看详情</a>");
          if($actions->row->isok == 0){
            $sid = "'".Admin::user()->uuid."'";
            $sname = "'".Admin::user()->name."'";
            $actions->append(new TaskGet($actions->getKey(),$sid,$sname));
          }

        });
        $excel = new ExcelExpoter();
        $excel->setAttr(['工单id', '快递单号','快递类型','网点','客户','QQ','联系方式','投诉次数','问题类型','内容','完结期限','状态','负责客服','评价','发布时间','最后更新时间'], ['id', 'eid','etype','store','uname','qq','tel','times','qtype','content','deadline','isok','sname','score','created_at','updated_at']);
        $grid->exporter($excel);
        //$grid->exporter(new ExcelExpoter());
        return $grid;
    }

    public function store()
    {
        //
        return $this->form()->store();
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
        $show->isduty("是否有责")->as(function ($isduty){
          if($isduty == 1) {
            return "是";
          }else{
            return "否";
          }
        });
        $show->content('问题描述');
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
                  $html .= "<a href='$src' target='_blank'><img src='$src' style='max-width:200px;max-height:200px' class='img' /></a>";
              }
            }
          }
          return $html;
        });
        $show->comments('评论', function ($comments) {
          $comments->resource('/admin/comments');
          $comments->model()->orderBy('id', 'desc')->paginate(5);
          //$comments->id();
          $comments->formuser('评论人');
          $comments->content('内容')->limit(100);
          //$comments->created_at("评论时间");
          //$comments->updated_at();
          $comments->created_at('发布时间')->display(function ($created_at){
            return  Carbon::parse($created_at)->diffForHumans();
          });
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
            $post->sqq = Admin::user()->qq;
            $post->isok = 1;
            $post->save();
        }
        return "success";
    }

    public function taskget(Request $request){
      $post=Task::find($request->input('id'));
      $post->sid = $request->input('sid');
      $post->sname = $request->input('sname');
      $post->sqq = Admin::user()->qq;
      $post->isok = 1;
      $post->save();
      return "success";
    }

    public function comment($task_id){
      $form = new Form(new Comment);
      $form->tools(function (Form\Tools $tools) {
          $tools->disableDelete();
      });
      $form->hidden('task_id','任务id')->value($task_id);
      $form->hidden('user_uuid','用户id')->value(Admin::user()->uuid);
      $form->hidden('formuser','用户名')->value(Admin::user()->name);
      $form->textarea('content','内容')->rules('required|max:255')->rows(2);
      $form->file('file','完结凭证')->uniqueName();
      return $form;
    }

    public function uploadtask(){
      $form = new \Encore\Admin\Widgets\Form();

      // $form->tools(function (Form\Tools $tools) {
      //     $tools->disableDelete();
      // });
      //$form->file('file','完结凭证')->uniqueName();
      //$form->disablePjax()
      $form->action(route('savetask'));
      //$form->file('file','导入任务');
      $form->largefile('file', '导入任务');
      return $form->render();
    }

    public function savetask(Request $request){
      $this->validate($request, [
        'file' => ['required', 'string', 'max:255']
      ]);
      $data=$request->input();
      $path = 'storage/app/aetherupload/'.$data['file'];
      //dump($path);
      //$excel = app()->make('excel');
      Excel::filter('chunk')->load($path)->chunk(250, function($results)
      {
        $head = ['快递单号'=>'eid','快递类型'=>'etype','网点'=>'store','客户'=>'uname','内容'=>'content','问题类型'=>'qtype'];
        Event::fire(new UploadDatas($results,$head));
        //dump($head);
      });
      admin_toastr('导入成功', 'success');
      return redirect()->route('alltasks.index');
    }
}
