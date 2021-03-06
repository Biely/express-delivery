<?php

namespace App\Admin\Controllers;

use App\Models\Task;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;

class DutyController extends Controller
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
            ->header('异常工单')
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
        return $content
            ->header('工单')
            ->description('详情')
            ->row($this->detail($id))
            ->row($this->form(route('duty.update',$id))->edit($id));
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

        $grid->model()->where('isok','=','2')->orWhere([['isok','=','1'],['deadline','<',time()]])->orWhere('times','>',1)->orderBy('id', 'desc');
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
        $grid->times('投诉次数')->sortable();
        $grid->content('内容')->display(function($content) {
            return str_limit($content, 30, '...');
        });
        $grid->reason('再次投诉原因')->display(function($reason) {
          return str_limit($reason, 30, '...');
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
        $states = [
            'on'  => ['value' => "1", 'text' => '是', 'color' => 'primary'],
            'off' => ['value' => "0", 'text' => '否', 'color' => 'default'],
        ];
        $grid->sname('负责客服');
        $grid->isduty('是否有责')->switch($states);
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableView();
            $actions->append("<a class='btn btn-xs btn-info' href=".route('duty.show',$actions->row->id).">查看详情</a>");
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
        $show->tel('手机号');
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
        $show->reason('再次投诉原因');
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
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form($action = null)
    {
        $form = new Form(new Task);
        if($action != null){
          $form->setAction($action);
        }
        $form->tools(function (Form\Tools $tools) {

            // 去掉`列表`按钮
            $tools->disableList();
        
            // 去掉`删除`按钮
            $tools->disableDelete();
        
            // 去掉`查看`按钮
            $tools->disableView();
        });
        $form->footer(function ($footer) {

            // 去掉`重置`按钮
            $footer->disableReset();
        
            // 去掉`查看`checkbox
            $footer->disableViewCheck();
        
            // 去掉`继续编辑`checkbox
            $footer->disableEditingCheck();
        
            // 去掉`继续创建`checkbox
            $footer->disableCreatingCheck();
        
        });
        $directors = [
            '1'  => "是",
            '0' => "否"
        ];
    
        $form->select('isduty', '是否有责')->options($directors);
        $form->saving(function (Form $form) {
            //...
          foreach (Admin::user()->roles as $key => $role) {
            # code...
            if($role->id == 8){
              if($form->isduty == 0){
                $error = new MessageBag([
                  'title'   => '您没有更高权限',
                  'message' => '不可多次修改工单状态',
              ]);
                return back()->with(compact('error'));
              }
            }
          }
        });
        $form->saved(function (Form $form) {
          //...
            admin_success('保存成功');
            return redirect(route('duty.index'));
        });
        return $form;
    }
}
