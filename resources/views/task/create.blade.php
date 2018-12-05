@extends('layouts.app')
@section('title', '发布工单')
@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            发布工单
          </div>
          <div class="card-body">
            @include('compoment._errors')
            <form method="POST" action="{{ route('subtask.store') }}">
              {{ csrf_field() }}
              <div class="form-group">
                <label for="eid">快递单号</label>
                <input type="text" class="form-control" id="eid" name="eid" placeholder="">
              </div>
              <div class="form-group">
                <label for="qtype">问题类型</label>
                <select class="form-control" id="qtype" name="qtype">
                  @foreach ($qtypes as $qtype)
                    <option value="{{ $qtype['id'] }}">{{ $qtype['name'] }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="content">问题描述</label>
                <textarea class="form-control" id="content" name="content" rows="3"></textarea>
              </div>
              <button type="submit" class="btn btn-primary">提交</button>
            </form>
          </div>
        </div>
      </div>
      @include('compoment.rightslide')
  </div>
</div>
@endsection
