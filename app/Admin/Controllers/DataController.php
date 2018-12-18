<?php
/**数据管理控制器 */
namespace App\Admin\Controllers;

use App\Models\Task;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Illuminate\Http\Request;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Form;
use DB;
use Log;

class DataController extends Controller
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
            ->header('工单清理')
            ->description('删除工单')
            ->row("")
            ->row($this->deleteform());
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

        $grid->id('Id');
        $grid->eid('Eid');
        $grid->store('Store');
        $grid->etype('Etype');
        $grid->user_uuid('User uuid');
        $grid->uname('Uname');
        $grid->qq('Qq');
        $grid->tel('Tel');
        $grid->qtype('Qtype');
        $grid->times('Times');
        $grid->isduty('Isduty');
        $grid->content('Content');
        $grid->deadline('Deadline');
        $grid->file('File');
        $grid->bz('Bz');
        $grid->isok('Isok');
        $grid->sid('Sid');
        $grid->sname('Sname');
        $grid->sqq('Sqq');
        $grid->reason('Reason');
        $grid->score('Score');
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');
        $grid->deleted_at('Deleted at');

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

        $show->id('Id');
        $show->eid('Eid');
        $show->store('Store');
        $show->etype('Etype');
        $show->user_uuid('User uuid');
        $show->uname('Uname');
        $show->qq('Qq');
        $show->tel('Tel');
        $show->qtype('Qtype');
        $show->times('Times');
        $show->isduty('Isduty');
        $show->content('Content');
        $show->deadline('Deadline');
        $show->file('File');
        $show->bz('Bz');
        $show->isok('Isok');
        $show->sid('Sid');
        $show->sname('Sname');
        $show->sqq('Sqq');
        $show->reason('Reason');
        $show->score('Score');
        $show->created_at('Created at');
        $show->updated_at('Updated at');
        $show->deleted_at('Deleted at');

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
        $form->text('user_uuid', 'User uuid');
        $form->text('uname', 'Uname');
        $form->text('qq', 'Qq');
        $form->text('tel', 'Tel');
        $form->number('qtype', 'Qtype');
        $form->number('times', 'Times')->default(1);
        $form->number('isduty', 'Isduty');
        $form->textarea('content', 'Content');
        $form->text('deadline', 'Deadline');
        $form->file('file', 'File');
        $form->textarea('bz', 'Bz');
        $form->number('isok', 'Isok');
        $form->text('sid', 'Sid');
        $form->text('sname', 'Sname');
        $form->text('sqq', 'Sqq');
        $form->text('reason', 'Reason');
        $form->text('score', 'Score');

        return $form;
    }

    public function deleteform(){
        $form = new Form();
        $form->action(route('tasksdata.index'));
        $form->datetimeRange('start_at', 'end_at','时间区间')->help('请谨慎操作，数据一旦删除便无法恢复')->rules('required');
        return $form->render();
    }

    public function doform(Request $request){
        $this->validate($request, [
            'start_at' => 'required',
            'end_at' => 'required'
        ]);
        $data = $request->all();
        DB::beginTransaction();
        try {
            DB::table("tasks")->whereBetween('created_at', [$data['start_at'],$data['end_at']])->delete();
            DB::commit();
            admin_success('工单数据删除事务运行成功,时间范围：'.$data['start_at']."-".$data['end_at']);
            Log::info('工单数据删除事务运行成功,时间范围：'.$data['start_at']."-".$data['end_at']);
        } catch (\Exception $e) {
            DB::rollback();//事务回滚
            admin_warning('工单数据删除事务运行失败');
            Log::info($e);
        }
        //dump($data);
        
    }
}
