@extends('site.layouts.app')
@section('content')
@include('site.draft.menu')
    <section class="section-padding mainSection">
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

    <section class="section-padding wow fadeInUp joinDraftSection">
        <div class="container">
            <div class="row">
                <div class="col-md-10">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3>{{trans('app.joinDraft')}}</h3>
                        </div>

                        <div id='formErorr' class='alert alert-danger text-center mb-10' style="display:none;">
                        <p id="errorText"></p>
                        </div>

                        <div class="panel-body">
                            <form id="joinDraft" method="post">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{trans('app.teamName')}}<span> ({{trans('app.limitAlphabets')}})</span></label>
                                        <input name="teamName" type="text" maxlength="20" class="form-control" required placeholder="{{trans('app.teamName')}}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{trans('app.gameNotifications')}}:</label>
                                        <p class="font-normal">{{trans('app.draftRecieveNotification')}}</p>
                                        <div class="team-checkbox">
                                            <input type="checkbox" name="followed" id="followed">
                                            <label for="followed" class="checkbox-text">{{trans('app.yesRecieveNotificationByEmail')}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{trans('app.leagueSize')}}:</label>
                                        <select class="form-control" name="dawrySize">
                                            <option value="4">4</option>
                                            <option value="6">6</option>
                                            <option value="8">8</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="team-checkbox">
                                            <input type="checkbox" name="condition" id="condition">
                                            <label for="condition" class="checkbox-text">{{trans('app.alreadyRead')}} <a href="">{{trans('app.terms_condition')}}</a></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="submit" class="butn butn-bg m0" value="{{trans('app.joinDraft')}}">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>     
@endsection
@section('after_head')

@stop  

@section('after_foot')

<script src="{{ asset('js/site/draft/createDraft.js?v='.config('version.version_script')) }}') }}"></script>

@stop