<div class="col-md-8">
    <div class="card">
        <div class="card-header">工单详情</div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <fieldset disabled>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="disabledTextInput">快递单号</label>
                  <input type="text" id="disabledTextInput" class="form-control" value="{{ $task->eid }}">
                </div>
                <div class="form-group col-md-6">
                  <label for="disabledTextInput">快递商家</label>
                  <input type="text" id="disabledTextInput" class="form-control" value="{{ $task->etype }}">
                </div>
                <div class="form-group col-md-6">
                  <label for="disabledTextInput">问题类型</label>
                  <input type="text" id="disabledTextInput" class="form-control" value="{{ getQdata($task->qtype)['name'] }}">
                </div>
                <div class="form-group col-md-6">
                  <label for="disabledTextInput">投诉次数</label>
                  <input type="text" id="disabledTextInput" class="form-control" value="{{ $task->times }}">
                </div>
                <div class="form-group col-md-12">
                  <label for="disabledTextInput">问题内容</label>
                  <textarea id="disabledTextInput" class="form-control" value="">{{ $task->content }}</textarea>
                </div>
                <div class="form-group col-md-6">
                  <label for="disabledTextInput">投诉时间</label>
                  <input type="text" id="disabledTextInput" class="form-control" value="{{ $task->created_at }}">
                </div>
                <div class="form-group col-md-6">
                  <label for="disabledTextInput">处理期限</label>
                  <input type="text" id="disabledTextInput" class="form-control" value="{{ date('Y-m-d H:i:s',$task->deadline) }}">
                </div>
              </div>
            </fieldset>
            @if($task->isok == '0')
              <a href="{{ route('subtask.edit',$task->id) }}" class="btn btn-primary" role="button" aria-pressed="true" >修改</a>
            @elseif($task->isok > 1)
               <example-component v-bind:task="{{ $task }}" v-bind:turl="'{{ route('moretask',$task->id) }}'" v-bind:rurl="'{{ route('score',$task->id) }}'"></example-component>
            @endif
        </div>
    </div>
    <div class="m-3"></div>
    <div class="card">
      <div class="card-header">工单状态</div>
      <div class="card-body">
        <step-component v-bind:task="{{ $task }}"></step-component>
      </div>
    </div>
    <div class="m-3"></div>
    <div class="card">
      <div class="card-header">评论</div>
      <div class="card-body">
        <form method="post" action="{{ route('comment.store') }}">
          {{ csrf_field() }}
          <div class="form-group">
            <input type="hidden" name="task_id" value="{{ $task->id }}">
            <textarea type="text" class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}" id="comment" name="content" placeholder="输入评论..">{{ old('content') }}</textarea>
            @if ($errors->has('content'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('content') }}</strong>
                </span>
            @endif
          </div>
          <div class="form-group text-right">
            <button type="submit" class="btn btn-success">提交</button>
          </div>
        </form>
        <ul class="list-group list-group-flush">

          @foreach ($comments as $comment)
          <li class="list-group-item">
            <div class="media">
              <img class="mr-3 rounded-circle" src="{{ gravatar($comment->user_uuid, 'small') }}" alt="Generic placeholder image">
              <div class="media-body">
                <div class="row mt-0 mb-0">
                  <h6 class="mt-0 col-6">{{ $comment->formuser }}</h6><p class="col-6 text-right"><small>{{ $comment->created_at->diffForHumans() }}</small></p>
                </div>
                <p>{{ $comment->content }}</p>
              </div>
            </div>
          </li>
          @endforeach
        </ul>
        {{ $comments->links() }}
      </div>
    </div>
</div>
@section('js')
@endsection
