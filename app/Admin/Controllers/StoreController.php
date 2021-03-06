<?php
/**快递网点控制器 */
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
use App\Admin\Extensions\Tools\TasksGet;
use App\Admin\Extensions\Tools\TaskGet;
use Illuminate\Support\Facades\Storage;
use Log;

class StoreController extends Controller
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
            ->header(Admin::user()->name)
            ->description('工单列表')
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
            ->header(Admin::user()->name)
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
            ->description('发布工单')
            ->body($this->dataform());
    }

    public function store()
    {
        //
        return $this->dataform()->store();
    }
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Task);
        $grid->model()->where('store',Admin::user()->name)->orderBy('id', 'desc');

        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->equal('eid','快递单号')->integer();
            $filter->equal('store','快递网点');
            $filter->equal('etype','快递类型')->select(edatas());
            $filter->equal('qtype','问题类型')->select(qdataArry());
            $filter->equal('istag','标记状态')->select(['1'=> '已标记','0'=>'未标记']);
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
                $sname = "'".Admin::user()->username."'";
                $batch->disableDelete();
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
          return $w;
        });
        $states = [
          'on'  => ['value' => "1", 'text' => '是', 'color' => 'primary'],
          'off' => ['value' => "0", 'text' => '否', 'color' => 'default'],
        ];
        $grid->istag('标记跟踪')->switch($states);
        $grid->actions(function ($actions) {
          //Log::info($actions->row);
          $actions->disableEdit();
          $actions->disableView();
           $actions->disableDelete();
           $actions->append("<a class='btn btn-xs btn-info' href=".route('store.show',$actions->row->id).">查看详情</a>");
           if($actions->row->isok == 0){
             $actions->disableEdit();
             $sid = "'".Admin::user()->uuid."'";
             $sname = "'".Admin::user()->username."'";
             $actions->append(new TaskGet($actions->getKey(),$sid,$sname));
           }else if(($actions->row->isok == 1 && $actions->row->sid == Admin::user()->uuid)){
            $actions->append("<a class='btn btn-xs btn-warning' href=".route('store.edit',$actions->row->id).">处理工单</a>");
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
       $show->qq('QQ')->unescape()->as(function($qq){
        if($qq!=null){
          $w = $qq.'<a href="http://wpa.qq.com/msgrd?v=3&uin='.$qq.'&site=qq&menu=yes" target="_blank" class="btn btn-xs btn-info">发起聊天</a>';
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
                $html .= "<a href='$src' target='_blank'><img src='$src' style='max-width:200px;max-height:200px' class='img' /></a>";
            }
          }
        }
        return $html;
      });
       $show->divider();
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
    protected function dataform()
    {
        $form = new Form(new Task);
        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
        });
        $form->text('eid', '快递单号')->rules('required');
        $form->hidden('store', 'Store')->value(Admin::user()->name);
        $form->hidden('etype','快递类型')->value(Admin::user()->etype);
        $form->hidden('user_uuid','用户id')->value(Admin::user()->uuid);
        $form->hidden('uname', '用户名')->value(Admin::user()->name);
        $form->hidden('qq', 'QQ')->value(Admin::user()->qq);
        $form->hidden('tel', '联系方式')->value(Admin::user()->tel);
        $form->hidden('deadline', '完结期限')->value();
        $form->select('qtype', '问题类型')->options(qdataArry())->rules('required');
        $form->textarea('content', '问题描述')->rules('required');
        $form->saving(function (Form $form) {
          Log::info($form->qtype);
          Log::info(getQdata($form->qtype));
          $form->deadline = getDeadline(getQdata($form->qtype)['seconds']);
        });

        return $form;
    }

    protected function form(){
      $form = new Form(new Task);
      $form->tools(function (Form\Tools $tools) {
          $tools->disableDelete();
      });
      $form->hidden('deadline','完结期限');
      $form->hidden('isok','是否完结')->value();
      $form->multipleFile('file','完结凭证')->removable()->uniqueName()->rules('required');
        //$form->file('file','完结凭证');
      $form->textarea('bz', '备注');
      $form->select('istag','是否标记')->options([0 => '否', 1 => '是']);
      $form->saving(function (Form $form) {
        if($form->deadline<time()){
          $form->isok=2;
        }else{
          $form->isok=3;
        }
      });
      return $form;
    }
}
