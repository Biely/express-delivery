<?php
namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\BatchAction;

class PassStore extends BatchAction{
  protected $id;

  public function __construct($id){
    $this->id = $id;
  }

  public function script(){
    return <<<SCRIPT

$('.grid-check-row').on('click', function () {

    // Your code.
    console.log($(this).data('id'));
    $.ajax({
        method: 'post',
        url: '/admin/passstore',
        data: {
            _token:LA.token,
            id: $(this).data('id'),
            status: 0
        },
        success: function () {
            $.pjax.reload('#pjax-container');
            toastr.success('解冻成功');
        }
    });

});

SCRIPT;
  }

  protected function render()
  {
      Admin::script($this->script());

      return "<a class='btn btn-xs btn-success grid-check-row' data-id='{$this->id}'>解冻网点</a>";
  }

  public function __toString()
  {
      return $this->render();
  }
}
