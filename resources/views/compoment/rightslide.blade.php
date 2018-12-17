<div class="col-md-4">
  <div class="card">
    <div class="card-header">
      任务中心
    </div>
    <div class="card-body">
      <!-- <div class="center-block text-center">
        <img src="{{ gravatar(Auth::user()->email, '100') }}" class="rounded-circle" alt="Cinque Terre">
      </div> -->
      <ul class="list-group list-group-flush">
        <a href="{{ route('home') }}" class="list-group-item d-flex justify-content-between align-items-center list-group-item-action">
          全部工单
          <span class="badge badge-danger badge-pill">{{ isset($count['all']) ? $count['all'] : "" }}</span>
        </a>
        <a href="{{ route('waitodo') }}" class="list-group-item d-flex justify-content-between align-items-center list-group-item-action">
          待处理
          <span class="badge badge-danger badge-pill">{{ isset($count[0]) ? $count[0] : "" }}</span>
        </a>
        <a href="{{ route('hasget') }}" class="list-group-item d-flex justify-content-between align-items-center list-group-item-action">
          已接单
          <span class="badge badge-info badge-pill">{{ isset($count[1]) ? $count[1] : "" }}</span>
        </a>
        <a href="{{ route('isok') }}" class="list-group-item d-flex justify-content-between align-items-center list-group-item-action">
          已完结
          <span class="badge badge-success badge-pill">{{ isset($count[2]) ? $count[2] : "" }}</span>
        </a>
      </ul>
    </div>
  </div>
  <div class="card mt-2">
      <div class="card-header">
          我的通知
        </div>
    <div class="card-body">
      <ul class="list-group list-group-flush">
        <a href="{{ route('notifications.index') }}" class="list-group-item d-flex justify-content-between align-items-center list-group-item-action ">
          最新评论
          <span class="badge badge-danger badge-pill">{{ Auth::user()->notification_count }}</span>
        </a>
      </ul>
    </div>
  </div>
  <div class="card mt-2">
    <div class="card-header">
        日常查询
      </div>
  <div class="card-body">
    <ul class="list-group list-group-flush">
      <a href="http://wpa.qq.com/msgrd?v=3&uin=2797714406&site=qq&menu=yes" target="_blank" class="list-group-item d-flex justify-content-between align-items-center list-group-item-action ">
        QQ:2797714406
        <span class="badge badge-success badge-pill">通道1</span>
      </a>
      <a href="http://wpa.qq.com/msgrd?v=3&uin=3119154902&site=qq&menu=yes" target="_blank" class="list-group-item d-flex justify-content-between align-items-center list-group-item-action ">
        QQ:3119154902
        <span class="badge badge-success badge-pill">通道2</span>
      </a>
      <a href="http://wpa.qq.com/msgrd?v=3&uin=2049187382&site=qq&menu=yes" target="_blank" class="list-group-item d-flex justify-content-between align-items-center list-group-item-action ">
        QQ:204918738
        <span class="badge badge-success badge-pill">通道3</span>
      </a>
    </ul>
  </div>
</div>
</div>
