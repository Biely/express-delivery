<div class="col-md-8">
    <div class="card">
        <div class="card-header">修改工单</div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <form method="POST" action="{{ route('subtask.update',$task->id) }}">
                @csrf

                <div class="form-group">
                    <label for="eid">{{ __('快递单号') }}</label>
                    <input id="eid" type="text" class="form-control{{ $errors->has('eid') ? ' is-invalid' : '' }}" name="eid" value="{{ old('eid',$task->eid) }}" required autofocus>

                    @if ($errors->has('eid'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('eid') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="qtype">{{ __('问题类型') }}</label>
                      <select id="qtype" class="form-control{{ $errors->has('qtype') ? ' is-invalid' : '' }}" name="qtype">
                        @foreach ($qtypes as $qtype)
                          <option value="{{ $qtype['id'] }}" {{ old('qtype',$task->qtype) == $qtype['id'] ? 'selected' : '' }}>{{ $qtype['name'] }}</option>
                        @endforeach
                      </select>
                        @if ($errors->has('qtype'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('qtype') }}</strong>
                            </span>
                        @endif
                </div>

                <div class="form-group">
                  <label for="content">问题描述</label>
                  <textarea id="content" class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}" name="content" value="" required autofocus>{{ old('content',$task->content) }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">
                    {{ __('保存') }}
                </button>
            </form>
        </div>
    </div>
</div>
