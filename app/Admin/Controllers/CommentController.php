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
        $grid->model()->orderBy('id', 'desc');
        $grid->id('Id');
        $grid->task_id('Task id');
        $grid->user_uuid('User uid');
        $grid->formuser('Formuser');
        $grid->touser('Touser');
        $grid->content('Content');
        $grid->created_at('Created at');

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
    protected function form($taskid = '')
    {
        $form = new Form(new Comment);

        $form->hidden('task_id', '任务id')->value($taskid);
        $form->hidden('user_uuid', '用户id')->value(Admin::user()->uuid);
        $form->hidden('formuser', '用户名')->value(Admin::user()->name);
        //$form->text('touser', 'Touser');
        $form->textarea('content', '评论内容');
        $form->saved(function (Form $form) {
            $taskid=$form->model()->task_id;
            // 跳转页面
            $success = new MessageBag([
               'title'   => '',
               'message' => '评论成功',
           ]);
            return redirect('/admin/hall/alltasks/'.$taskid)->with(compact('success'));
        });
        return $form;
    }
}
