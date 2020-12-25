@extends('site.layouts.app',['title' => 'Register-GAMEFANTASY'])
@section('content')
<div class="myinner-banner">
    <div class="opacity">
        <h2>{{trans('app.create_new_account')}}</h2>
        <!--breadcrumbs-->
        <ul>
            <li><a href="{{ route('home') }}">{{trans('app.Home')}}</a></li>
            <li>/</li>
            <li><a href="{{ route('login') }}">{{trans('app.Login')}}</a></li>
            <li>/</li>
            <li>{{trans('app.create_new_account')}}</li>
        </ul>
    </div>
    <!-- /.opacity -->
</div>
<section class="section-padding wow fadeInUp">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form data-validate="parsley" id="form" class="panel-sign" role="form" method="POST" action="{{ route('register') }}">

                    <!-- progressbar -->
                    <ul id="progressbar">
                        <li class="active" id="my-data"><strong>{{trans('app.personal_data')}}</strong></li>
                        <li id="my-fav"><strong>{{trans('app.favorite_team')}}</strong></li>
                        <li id="my-follow"><strong>{{trans('app.following_news')}}</strong></li>
                    </ul>
                    <!-- end progress -->

                    {{ csrf_field() }}

                <div class="firstPage">
                <div id='formErorr' class='alert alert-danger text-center mb-10' style="display:none;">
                <p id="errorText"></p>
                </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{trans('app.email')}}
                                <!-- <span>*</span> -->
                            </label>
                            <input type="email"  data-rangelength="[3,250]" name="email" id="email" class="form-control"  placeholder="{{trans('app.enter_your_email')}}" />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{trans('app.mobile')}} 
                                <span>*</span>
                            </label>
                            <input id="phone" type="tel" name="phone" class="form-control" placeholder="{{trans('app.mobile')}}" />
                            <select name="address" id="address-country" style="display: none;"></select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{trans('app.user_name')}}<span>*</span></label>
                            <input id="name" type="text" name="name" class="form-control" placeholder="{{trans('app.name')}}"/>
                        </div>
                    </div>

                    <div class="col-md-6" id="countryField">
                        <div class="form-group">
                            <label>{{trans('app.choose_city')}}<span>*</span></label>
                            {!!city_select()!!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{trans('app.password')}} <span>*</span></label>
                            <input id="password" type="password" name="password" class="form-control"  placeholder="{{trans('app.password')}}" />

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group ">
                            <label> {{trans('app.confirm')}} {{trans('app.password')}}<span>*</span></label>
                            <input id="password-confirm" type="password" name="password_confirmation" class="form-control" placeholder="{{trans('app.confirm')}} {{trans('app.password')}}" />
                        </div>
                    </div>

                    <!-- <div class="col-md-12">
                        <div class="form-group">
                            <label>{{trans('app.choose_fav_club')}}:</label>
                            @php $data_teams=App\Models\Team::getTeam_data(1)@endphp
                            <select name="best_team" class="form-control">
                                @foreach ($data_teams as $key_team => $team)
                                    <option value="{!!$team['link']!!}">{!!$team['name']!!}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> -->

                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="checkbox" class="" name="approve" id="approve" placeholder="">
                            <label for="approve" >{{trans('app.I_have_read')}} <a href="{{ route('terms') }}" target="_blank">{{trans('app.terms_condition')}}</a></label>
                        </div>
                    </div>

                    <!-- <div class="col-md-12">
                        <div class="form-group">
                            <div class="text-center">
                                <input type="button" name="login-submit" id="login-submit" class="butn butn-bg showSecondPage" value="{{trans('app.register')}}">
                            </div>
                        </div>
                    </div> -->

                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="text-center">
                                <input type="button" class="butn butn-bg showSecondPage" value="{{trans('app.next_step')}}">
                            </div>
                        </div>
                    </div>

                </div>



                <div class="secondPage">
                         <div class="choose-fav">
                                    <h2>{{trans('app.favorite_team')}}</h2>
                                    <p>{{trans('app.choose_only_team')}}</p>
                                    <div class="radio-container">
                                        <div class="radio-tile-group row">

                            @php $data_teams=App\Models\Team::getAll_Teamdata()

                            @endphp
                           
                            @foreach ($data_teams as $key_team => $team)
                                <div class="col-md-3">
                                    <!-- start input-container-->
                                    <div class="input-container">
                                        <input class="radio-button" type="radio" name="best_team" value="{!!$team['name']!!}-{!!$team['link']!!}-{!!finalvaluebylang($team->image,'','en') !!}">
                                        <div class="radio-tile">
                                            <label for="1" class="radio-tile-label">{!!$team['name']!!}</label>
                                            <img src="{!!finalvaluebylang($team->image,'','en') !!}" alt="club-logo">

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="choose-teams">
                                    <h2>{{trans('app.ask_follow_question')}}</h2>
                                    @foreach ($data_teams as $key_team => $team)
                                    <div class="team-checkbox col-sm-3 col-xs-6">
                                        <input type="checkbox" name="followed[]" id="{!!$team['link']!!}" value="{!!$team['name']!!}-{!!$team['link']!!}-{!!finalvaluebylang($team->image,'','en') !!}" class="check-button">
                                        <label for="{!!$team['link']!!}" class="checkbox-text">{!!$team['name']!!}</label>
                                    </div>
                                    @endforeach
                                   
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="text-center">
                                        <input type="button"  class="butn backFirstPage" style="display: inline;" value="{{trans('app.previous_step')}}">
                                        
                                            <input type="button" class="butn butn-bg showThirdPage" value="{{trans('app.next_step')}}">
                                        </div>
                                    </div>
                                </div>
                </div>

                <div class="thirdPage">
                <div class="col-md-12">
                                    <div class="choose-fav">
                                        <h2>{{trans('app.following_news')}}</h2>
                                        <p class="email-sms">{{trans('app.choose_following_news')}}</p>
                                        <p class="noTeams">{{trans('app.no_choosen_follow_news')}}</p>
                                        <div class="email-sms">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td></td>
                                                        <td>
                                                        {{trans('app.send_news_notifications')}}<br>Notifications
                                                        </td>
                                                        <td>
                                                        {{trans('app.send_news_emails')}} <br>Emails
                                                        </td>
                                                    </tr>


                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="text-center">
                                        <input type="button"  class="butn backSecondPage" style="display: inline;" value="{{trans('app.previous_step')}}">
                                            <input type="submit" class="butn butn-bg" value="{{trans('app.create_account')}}">
                                        </div>
                                    </div>
                                </div>
                </div>
                    <!--@include('auth.social_login')-->
                </form>
            </div>
        </div>
    </div>
</section>
@include('site.home.sponsers')
@endsection
@section('after_foot')


@if(App::getLocale() == 'ar')
<link rel="stylesheet" type="text/css" href="{{ asset('js/site/js/country-sync/css/intlTelInput.css') }}">
@else
<link rel="stylesheet" type="text/css" href="{{ asset('js/site/js/country-sync/css/intlTelInput-en.css') }}">
@endif

<!-- <script src="js/plugins.js"></script> -->
<script src="{{ asset('js/site/js/country-sync/js/intlTelInput.js') }}"></script>
<script src="{{ asset('js/site/js/country-sync/js/countrySync.js') }}"></script>
<!-- <script src="js/theme.js"></script> -->
<script src="{{ asset('js/site/register.js') }}"></script>
@endsection

