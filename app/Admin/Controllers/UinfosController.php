<?php

namespace App\Admin\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class UinfosController extends Controller
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
        $grid = new Grid(new User);

        $grid->id('用户Id');
        //$grid->uuid('Uuid');
        $grid->name('用户名');
        $grid->email('邮箱');
        $grid->store('网点');
        $grid->etype('快递商家');
        $grid->qq('QQ');
        $grid->tasks('投诉次数')->display(function ($tasks) {
            //$count = count($tasks);
            return "<span class='label label-warning'>{$tasks}</span>";
        });
        // $grid->email_verified_at('Email verified at');
        // $grid->password('Password');
        // $grid->remember_token('Remember token');
        $grid->created_at('注册日期');
        //$grid->updated_at('Updated at');
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableEdit();
        });
        $grid->disableCreateButton();
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
        $show = new Show(User::findOrFail($id));

        $show->id('用户Id');
        //$show->uuid('Uuid');
        $show->name('用户名');
        $show->email('邮箱');
        $show->store('网点');
        $show->etype('快递商家');
        $show->qq('QQ');
        $show->uptasks('投诉次数');
        // $show->email_verified_at('Email verified at');
        // $show->password('Password');
        // $show->remember_token('Remember token');
        $show->created_at('注册时间');
        //$show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User);

        $form->text('uuid', 'Uuid');
        $form->text('name', 'Name');
        $form->email('email', 'Email');
        $form->text('store', 'Store');
        $form->text('etype', 'Etype');
        $form->text('qq', 'Qq');
        $form->number('uptasks', 'Uptasks');
        $form->datetime('email_verified_at', 'Email verified at')->default(date('Y-m-d H:i:s'));
        $form->password('password', 'Password');
        $form->text('remember_token', 'Remember token');

        return $form;
    }
}
