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
</div>
