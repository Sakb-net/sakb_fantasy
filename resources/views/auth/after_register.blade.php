@extends('site.layouts.app',['title' => 'Register-GAMEFANTASY'])
@section('content')
@php $empty=null @endphp
<div class="myinner-banner">
    <div class="opacity">
        <h2>{{trans('app.thank_you')}}</h2>
        <!--breadcrumbs-->
        <ul>
            <li><a href="{{ route('home') }}">{{trans('app.Home')}}</a></li>
            <li>/</li>
            <li>{{trans('app.thank_you')}}</li>
        </ul>
    </div>
</div>
<!-- //emann123 -->
<section class="section-padding wow fadeInUp">
    <div class="container">
        <div class="row">
            <div class="panel-sign">
                <div class="form-group">
                    <h2> {{trans('app.thank_you_subscribing_with_us')}}</h2>
                    <p>{{trans('app.receive_email_to_confirm')}}</p>
                </div>
                <div class="form-group">
                    <a href="{{ route('home') }}" class="butn">{{trans('app.goto_home')}}</a>
                    <a href="{{ route('game.index') }}" class="butn butn-bg">{{trans('app.goto_game')}}</a>
                </div>
            </div>
        </div>
    </div>
</section>
@include('site.home.sponsers')
@endsection
