@extends('layouts.app')
@section('title', '工单详情')
@section('css')
<!-- <link href="{{ asset('raty-master/lib/jquery.raty.css') }}" rel="stylesheet">
<script src="{{ asset('raty-master/vendor/jquery.js') }}" type="text/javascript"></script>
<script src="{{ asset('raty-master/lib/jquery.raty.js') }}"></script> -->
@endsection
@section('content')
<div class="container">
    @include('compoment.bread')
    <div class="row justify-content-center">
      @if($action == 'detil')
        @include('compoment.detil')
      @else
        @include('compoment.edittask')
      @endif
        @include('compoment.rightslide')
        <div class="col-md-8">

        </div>
    </div>
</div>
@endsection
