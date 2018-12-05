<?php
namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\BatchAction;

class TaskGet extends BatchAction{
  protected $id;
  protected $sid;
  protected $sname;

  public function __construct($id,$sid,$sname){
    $this->id = $id;
    $this->sid = $sid;
    $this->sname = $sname;
  }

  public function script(){
    return <<<SCRIPT

$('.grid-check-row').on('click', function () {

    // Your code.
    console.log($(this).data('id'));
    $.ajax({
        method: 'post',
        url: '/admin/hall/alltasks/taskget',
        data: {
            _token:LA.token,
            id: $(this).data('id'),
            sid: {$this->sid},
            sname: {$this->sname}
        },
        success: function () {
            $.pjax.reload('#pjax-container');
            toastr.success('领取成功');
        }
    });

});

SCRIPT;
  }

  protected function render()
  {
      Admin::script($this->script());

      return "<a class='btn btn-xs btn-success grid-check-row' data-id='{$this->id}'>领取任务</a>";
  }

  public function __toString()
  {
      return $this->render();
  }
}
