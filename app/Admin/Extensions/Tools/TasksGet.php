<?php

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Grid\Tools\BatchAction;

class TasksGet extends BatchAction{
    protected $action;
    protected $sname;
    public function __construct($action = 1,$sname = "")
    {
        $this->action = $action;
        $this->sname = $sname;
    }

    public function script()
    {
        return <<<EOT

$('{$this->getElementClass()}').on('click', function() {

    $.ajax({
        method: 'post',
        url: '/admin/hall/alltasks/tasksget',
        data: {
            _token:LA.token,
            ids: selectedRows(),
            action: {$this->action},
            sname: {$this->sname}
        },
        success: function () {
            $.pjax.reload('#pjax-container');
            toastr.success('操作成功');
        }
    });
});

EOT;

    }
}
