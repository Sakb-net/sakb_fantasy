@extends('site.layouts.app')
@section('content')
@include('site.draft.menu')
<section class="section-padding wow fadeInUp mainSection">
    <div class="container">
        <p>{{trans('app.loginByUserName')}}  {{auth::user()->name}}.</p>
        <div class="row">
            <div class="col-md-4">
                <div class="draft-home">
                    <div class="draft-step" style="background-image: url ('/images/draft/home-step-1.jpg');"></div>
                    <div class="draft-step-content">
                        <h2>{{trans('app.createOrJoinLeague')}}</h2>
                        <p>{{trans('app.competeWithFriendsOrFamily')}}</p>
                        <a id="showStartPage">{{trans('app.playNow')}}</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="draft-home">
                    <div class="draft-step" style="background-image: url('/images/draft/home-step-2.jpg');"></div>
                    
                    <div class="draft-step-content">
                        <h2>{{trans('app.makeYourScouts')}}</h2>
                        <p>{{trans('app.buildWatchListOfPlayes')}}</p>
                        <a id="showStartPage1">{{trans('app.playNow')}}</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="draft-home">
                    <div class="draft-step" style="background-image: url('/images/draft/home-step-3.jpg');"></div>
                    <div class="draft-step-content">
                        <h2>{{trans('app.joinDraft')}}</h2>
                        <p>{{trans('app.takeYourTurnToChoose')}}</p>
                        <a id="showStartPage2">{{trans('app.playNow')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-padding startSection">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <div class="draft-start">
                    <h2>{{trans('app.joinOrCreateLeague')}}</h2>
                    <p>{{trans('app.createNewPrivateLeagueAndInviteFriends')}}</p>
                    <a href="{{route('draft.joinLeaugeDraft')}}" class="butn butn-bg">{{trans('app.join_a_league')}}</a>
                    <a href="{{route('draft.createLeaugeDraft')}}" class="butn butn-bg">{{trans('app.create_a_league')}}</a>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="draft-start">
                    <h2>{{trans('app.newSALeagueDraft')}}</h2>
                    <p>{{trans('app.whyNotPracticeFakeDraft')}}</p>
                    <a href="{{route('draft.createDraft')}}" class="butn butn-bg">{{trans('app.joinDraft')}}</a>
                </div>
            </div>
        </div>
    </div>
</section>        
@endsection
@section('after_head')

@stop  

@section('after_foot')
<script src="{{ asset('js/site/draft/home.js?v='.config('version.version_script')) }}') }}"></script>
@include('site.draft.pusher')
@stop