@extends('site.layouts.app',['title' => '404'])
@section('content')

@php 
header("Location: ".route('home')); 
exit();
@endphp
<div class="myinner-banner">
    <div class="opacity">
        <h2>{{trans('app.Page')}} 404</h2>
    </div>
</div>
<section class="section-padding wow fadeInUp">
    <div class="container">
        <div class="row">
            <div class="panel-sign text-center">
                <div class="form-group">
                    <img class="center-block" src="{{ asset('images/icon/404.png') }}">
                </div>
                <div class="form-group">
                    <h2>{{trans('app.this_page_does_not_exist')}}</h2>
                </div>
                <div class="form-group">
                    <a href="{{ route('home') }}" class="butn butn-bg">{{trans('app.goto_home')}}</a>
                </div>
            </div>
        </div>
    </div>
</section>
@include('site.home.sponsers')
@endsection
@section('after_foot')
<script>
    $(document).ready(function () {
        $('body').find('.bottom-footer').addClass('footer_style');
        $('body').find('.bottom-footer').css({ "position": "static", "background": "#000" });;
    });
</script>
@stop  