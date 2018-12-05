@extends('layouts.app')
@section('title', '首页')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">我的工单{{ isset($s) ? "-".$s : '' }}</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @include('compoment.taskslist')
                </div>
            </div>
        </div>
        @include('compoment.rightslide')
    </div>
</div>
@endsection
