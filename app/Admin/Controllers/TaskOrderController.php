<?php

namespace App\Admin\Controllers;

use Excel;
use Log;
use Event;
use App\Models\TaskOrder;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Encore\Admin\Show;
use Encore\Admin\Layout\Row;
use App\Events\UploadKf;

class TaskOrderController extends Controller
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
            ->header('客服分配')
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
            ->header('客服分配')
            ->description('详情')
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
            ->header('客服分配')
            ->description('编辑')
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
            ->header('导入文件')
            ->description('')
            ->row(function (Row $row) {

                $row->column(2,"");
            
                $row->column(8, "<a class='btn btn-info' href='http://mifengjf.com/uploads/files/templekf.csv' target='_blank'>下载模板</a><br>注意：1.文件必须是.csv格式；<br>2.文件第一行字段名称不可更改;<br>3.文件内容不可包含英文逗号(,)<br>");
            })
              ->row($this->uploadkf());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TaskOrder);

        $grid->id('ID');
        $grid->eid('快递单号');
        $grid->sname('客服名称');
        $grid->etype('快递公司');
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');

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
        $show = new Show(TaskOrder::findOrFail($id));

        $show->id('ID');
        $show->eid('快递单号');
        $show->sname('客服名称');
        $show->etype('快递公司');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new TaskOrder);

        $form->display('ID');
        $form->text('eid',"快递单号");
        $form->text('sname','客服名称');
        $form->select('etype','快递公司')->options(edatas());
        $form->display('Created at');
        $form->display('Updated at');

        return $form;
    }

    public function uploadkf(){
        $form = new \Encore\Admin\Widgets\Form();
  
        // $form->tools(function (Form\Tools $tools) {
        //     $tools->disableDelete();
        // });
        //$form->file('file','完结凭证')->uniqueName();
        //$form->disablePjax()
        $form->action(route('savekf'));
        //$form->file('file','导入任务');
        $form->largefile('file', '导入文件');
        return $form->render();
      }
  
      public function savekf(Request $request){
        $this->validate($request, [
          'file' => ['required', 'string', 'max:255']
        ]);
        $data=$request->input();
        $path = 'storage/app/aetherupload/'.$data['file'];
        //dump($path);
        //$excel = app()->make('excel');
        Excel::filter('chunk')->load($path)->chunk(250, function($results)
        {
          $head = ['快递单号'=>'eid','快递公司'=>'etype','客服名称'=>'sname'];
          Event::fire(new UploadKf($results,$head));
          //dump($head);
        });
        admin_toastr('导入成功', 'success');
        return redirect()->route('plan.index');
      }
}