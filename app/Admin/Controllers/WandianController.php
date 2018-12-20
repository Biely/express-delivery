<?php

namespace App\Admin\Controllers;

use App\Models\Store;
use App\Models\Adminuser;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use App\Admin\Extensions\Tools\KillStore;
use App\Admin\Extensions\Tools\PassStore;

class WandianController extends Controller
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
            ->header('网点管理')
            ->description('网点列表')
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
            ->header('查看网点')
            ->description('网点信息')
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
            ->header('编辑网点')
            ->description('网点信息')
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
            ->header('新建网点')
            ->description('网点信息')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Store);

        $grid->id('网点ID');
        $grid->name('网点名称');
        $grid->etype('快递公司');
        $grid->content('备注');
        $grid->status('网点状态')->display(function($status) {
            if($status==0){
                return "正常使用";
            }else{
                return "账号冻结";
            }
        });
        $grid->actions(function ($actions) {
            //Log::info($actions->row);
            // $actions->disableEdit();
            //  $actions->disableDelete();
            //  $actions->disableView();
            //  $actions->append("<a class='btn btn-xs btn-info' href=".route('alltasks.show',$actions->row->id).">查看详情</a>");
            if($actions->row->status == 0){
              $actions->append(new KillStore($actions->row->id));
            }else{
                $actions->append(new PassStore($actions->row->id));
            }
  
          });
        $grid->created_at('创建时间');
        $grid->updated_at('更新时间');

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
        $show = new Show(Store::findOrFail($id));

        $show->id('网点ID');
        $show->name('网点名称');
        $show->etype('快递公司');
        $show->content('备注');
        $show->status('网点状态')->as(function ($status) {
           if($status == 0){
               return "正常使用";
           }else{
               return "账号冻结";
           }
        });
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
        $form = new Form(new Store);

        $form->text('name','网点名称');
        $form->select('etype','快递公司')->options(edatas());
        $form->textarea('content','备注')->rows(2);
        $form->select('status','是否封禁')->options([0 => '否',1 => '是']);
        return $form;
    }

    public function kill(Request $request){
        $store = Store::find($request->input('id'));
        $store->status = $request->input('status');
        $admin = Adminuser::where('name',$store->name)->first();
        if(!empty($admin)){
            $admin->password = bcrypt('mifengjf');
            $admin->save();
        }
        if($store->save()){
            return 'success';
        }
    }
}
