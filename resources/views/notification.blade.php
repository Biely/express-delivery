@extends('layouts.app')
@section('title', '消息中心')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">消息中心</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <ul class="list-group list-group-flush">
                    @foreach ($notifications as $notification)
                    <li class="list-group-item {{ ($notification->read_at==null) ? 'list-group-item-danger' : 'bg-light' }}">
                    <div class="media ">
                            <div class="infos">
                                <div class="media-heading">
                                    客服<a href="{{ route('subtask.show',$notification->data['task_id']) }}">{{ $notification->data['fromusername'] }}</a>
                                    评论了
                                    <a href="{{ route('subtask.show',$notification->data['task_id']) }}">你</a>
                                    单号为<a href="{{ route('subtask.show',$notification->data['task_id']) }}">{{ $notification->data['eid'] }}</a>投诉
                                    {{-- 回复删除按钮 --}}
                                    <span class="meta pull-right text-right" title="{{ $notification->created_at }}">
                                        <span class="glyphicon glyphicon-clock" aria-hidden="true"></span>
                                        <small>▪{{ $notification->created_at->diffForHumans() }}</small>
                                    </span>
                                </div>
                                <div class="reply-content">
                                        {!! str_limit($notification->data['content'],30, ' (...)') !!}
                                    </div>
                            </div>
                        </div>
                    </li>
                     @endforeach

                    {!! $notifications->render() !!}
                    </ul>
                </div>
            </div>
        </div>
        @include('compoment.rightslide')
    </div>
</div>
@endsection
