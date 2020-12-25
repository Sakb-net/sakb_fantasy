@extends('site.layouts.app',['title' => 'Login-GAMEFANTASY'])
@section('content')
<div class="myinner-banner">
    <div class="opacity">
        <h2>{{trans('app.Login')}}</h2>
        <!--breadcrumbs-->
        <ul>
            <li><a href="{{ route('home') }}">{{trans('app.Home')}}</a></li>
            <li>/</li>
            <li>{{trans('app.Login')}}</li>
        </ul>
    </div>
    <!-- /.opacity -->
</div>
<section class="section-padding wow fadeInUp">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
               @include('auth.login_form')
            </div>
            <!--regesiter -->
            <div class="col-md-5 col-md-offset-1">
                <div class="login-well text-center">
                    <h2>{{trans('app.not_have_account')}}</h2>
                    <p>{{trans('app.subscribe_get_features')}}:</p>
                    <ul>
                        <li>{{trans('app.subscribe_game')}}</li>
                        <li>{{trans('app.follow_saudi_league')}}</li>
                        <li>{{trans('app.watch_latest_video_league')}}</li>
                    </ul>
                    <a href="{{ route('register') }}" class="butn butn-bg">{{trans('app.create_new_account')}}</a>
                </div>
            </div>
        </div>
    </div>
</section>
@include('site.home.sponsers')
@endsection
