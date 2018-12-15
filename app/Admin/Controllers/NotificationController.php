<?php

namespace App\Admin\Controllers;

use App\Models\AdminUser;
use App\Models\Task;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Widgets\Table;

class NotificationController extends Controller
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
            ->header('消息中心')
            ->description('我的评论')
            ->body($this->table());
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
    public function table(){
        $headers = ['评论人', '工单号', '内容', '评论时间','状态','操作'];
        $rows = [];
        $user=AdminUser::find(Admin::user()->id);
        $notifications = $user->unreadNotifications()->paginate(20);
        if($notifications->count()==0){
          $notifications = $user->notifications()->paginate(20);
        }else{
          $user->markAsRead();
        }
        foreach ($notifications as $key => $n) {
            # code...
            $temp[]=$n->data['fromusername'];
            $temp[]=$n->data['eid'];
            $temp[]=$n->data['content'];
            $temp[]=$n->created_at->diffForHumans();
            if($n->read_at==null){
                $temp[]="<span class='badge bg-red'>未读</span>";
            }else{
                $temp[]="<span class='badge bg-success'>已读</span>";
            }
            if(Admin::user()->roles[0]->id == "7"){
                $temp[] = "<a class='btn btn-xs btn-info' href=".route('mytasks.show',$n->data['task_id']).">查看详情</a>";
            }else if(Admin::user()->roles[0]->id == "10"){
                $temp[] = "<a class='btn btn-xs btn-info' href=".route('store.show',$n->data['task_id']).">查看详情</a>";
            }else{
                $temp[] = "<a class='btn btn-xs btn-info' href=".route('alltasks.show',$n->data['task_id']).">查看详情</a>";
            }
            
            $rows[] =$temp;
        }
        $table = new Table($headers, $rows);
        return $table->render();
    }

    protected function grid()
    {
        $grid = new Grid(new AdminUser);
        $grid->model()->where('id',Admin::user()->id);
        $grid->id('Id');
        $grid->unreadNotifications('我的消息')->display(function ($comments) {
            $count = count($comments);
            return "<span class='label label-warning'>{$count}</span>";
        });
    
        $grid->uuid('Uuid');
        $grid->username('Username');
        $grid->password('Password');
        $grid->name('Name');
        $grid->qq('Qq');
        $grid->tel('Tel');
        $grid->etype('Etype');
        $grid->avatar('Avatar');
        $grid->notification_count('Notification count');
        $grid->remember_token('Remember token');
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
        $show = new Show(AdminUser::findOrFail($id));

        $show->id('Id');
        $show->uuid('Uuid');
        $show->username('Username');
        $show->password('Password');
        $show->name('Name');
        $show->qq('Qq');
        $show->tel('Tel');
        $show->etype('Etype');
        $show->avatar('Avatar');
        $show->notification_count('Notification count');
        $show->remember_token('Remember token');
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
        $form = new Form(new AdminUser);

        $form->text('uuid', 'Uuid');
        $form->text('username', 'Username');
        $form->password('password', 'Password');
        $form->text('name', 'Name');
        $form->text('qq', 'Qq');
        $form->text('tel', 'Tel');
        $form->text('etype', 'Etype');
        $form->image('avatar', 'Avatar');
        $form->number('notification_count', 'Notification count');
        $form->text('remember_token', 'Remember token');

        return $form;
    }
}
