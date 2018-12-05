@extends('layouts.app')
@section('title', '个人中心')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">个人中心</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if($action == 'index')
                     @include('compoment.persondisform')
                    @else
                     @include('compoment.personform')
                    @endif
                </div>
            </div>
        </div>
        @include('compoment.rightslide')
    </div>
</div>
@endsection
