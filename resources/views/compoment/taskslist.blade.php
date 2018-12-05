<ul class="list-group list-group-flush">

  @foreach( $tasks as $task)
  <li class="list-group-item list-group-item-action">
    <div class="row justify-content-between">
      <p class="col-8"><small>快递单号：{{ $task->eid }}</small></p>
      <p class="col-4 text-right text-info"><small>{{ getQdata($task->qtype)['name'] }}</small></p>
      <p class="col-12"><small>投诉内容：{{ str_limit($task->content, 50, ' (...)') }}</small></p>
      <p class="col-6"><small>投诉时间：{{ $task->created_at }}</small></p><p class="col-6 text-right"><small><em>处理期限：{{ date("Y-m-d H:i:s",$task->deadline) }}</em>&nbsp;&nbsp;&nbsp;&nbsp;<span class="badge badge-primary">{{ taskstatus($task->deadline,$task->isok) }}</span></small></p>
      <p class="col-12 text-right"><a role="button" aria-pressed="true" href="{{ route('subtask.show',$task->id) }}" class="btn btn-outline-info btn-sm">详情</a></p>
    </div>
  </li>
  @endforeach

</ul>
  {{ $tasks->links() }}
