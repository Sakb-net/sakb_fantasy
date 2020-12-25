<!-- <section class="section-padding wow fadeInUp gray-bg">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="block-title">
                    <span>{{trans('app.last_videos')}}</span>
                    <a class="read-more" href="{{ route('videos.index') }}">{{trans('app.more_more')}}</a>
                </h3>
                <div id="videos-slider" class="owl-carousel">
                    @foreach($videos as $keyvideos=>$val_videos)
                    <div class="post-slide">
                        <div class="post-img">
                            <a href="{{ route('videos.single',$val_videos->link) }}">
                                @if(!empty($val_videos->image))
                                <img  src="{{ $val_videos->image }}" alt="">
                                @else
                                <img  src="{{ asset('images/news-img/1.jpg') }}" alt="">
                                @endif
                                <div class="video-icon"><i class="fa fa-play"></i></div>
                            </a>
                            <div class="video-icon"><i class="fa fa-play"></i></div>
                        </div>
                        <div class="post-content">
                            <div class="post-date">
                                <span class="month">{{arabic_Value_month($val_videos->created_at->format('m'))}}</span>
                                <span class="date">{{$val_videos->created_at->format('d')}}</span>
                            </div>
                            <h5 class="post-title">
                                <a href="{{ route('videos.single',$val_videos->link) }}">{{$val_videos->name}}</a>
                            </h5>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
</section> -->




<section class="section-padding wow fadeInUp gray-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="block-title">
                            <span>{{trans('app.last_videos')}}</span>
                            <a class="read-more" href="{{ route('videos.index') }}">{{trans('app.more_more')}}</a>
                            </h3>
                            

                            @foreach($videos as $keyvideos=>$val_videos)
                            <div class="col-md-6">
                                <div class="post-slide">
                                    <div class="post-img">
                                        <a href="{{ route('videos.single',$val_videos->link) }}">
                                        @if(!empty($val_videos->image))
                                        <img  src="{{ $val_videos->image }}" alt="">
                                        @else
                                        <img  src="{{ asset('images/news-img/1.jpg') }}" alt="">
                                        @endif
                                            <div class="video-icon"><i class="fa fa-play"></i></div>
                                        </a>
                                    </div>
                                    <div class="post-content">
                                    <div class="post-date">
                                    <span class="month">{{arabic_Value_month($val_videos->created_at->format('m'))}}</span>
                                    <span class="date">{{$val_videos->created_at->format('d')}}</span>
                                        </div>
                                        <h5 class="post-title">
                                            <a href="{{ route('videos.single',$val_videos->link) }}">{{$val_videos->name}}</a>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div>
                        <div class="col-md-4">
                            <div class="card fix-card">
                                <div class="nav" role="tablist">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item active">
                                            <a class="nav-link" data-toggle="tab" href="#gameweek-fix" role="tab">
                                            {{trans('app.roundMatches')}}
                                            </a>
                                        </li>
                                        @if(Session::has('teamFav'))
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#favourite-fix" role="tab">
                                            {{trans('app.favorite_team')}}
                                            </a>
                                        </li>
                                        @endIf
                                        @if(Session::has('followTeams'))
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#following-fix" role="tab">
                                            {{trans('app.teams_follow')}}
                                            </a>
                                        </li>
                                        @endIf
                                    </ul>
                                </div>


                                <div class="tab-content" id="myTabContent">
                                    <!-- Single Tab Content Start -->
                                    <div class="tab-pane fade active in" id="gameweek-fix" role="tabpanel">
                                        <div class="mytab-content">
                                            <div class="fixtures home-fix">
                                            @php 
                                            $dateNow = date("Y-m-d");
                                            $utcTime = gmdate("H:i:s");
                                            @endphp


                                            <h4 class="FixtureDay"> {{$match_public[0]['start_date_day']}} {{$match_public[0]['start_date']}}</h4>

                                            @foreach($match_public[0]['match_group'] as $value)

                                                <!--fixture-box -->
                                                <div class="fixture-box">
                                                    <div class="club-fixture">
                                                        <div class="club-img">
                                                            <img class="sm-img" src="{{$value['image_first']}}" alt="club logo">
                                                            <span>{{$value['short_first']}}</span>
                                                        </div>
                                                        <div class="club-schedule text-center">
                                                        @if ($dateNow < $value['date']) 
                                                        <h5>{{$value['userDate'][0]}}
                                                        @if($value['userDate'][1] == 'pm')
                                                        {{trans('app.pm')}}
                                                        @else
                                                        {{trans('app.am')}}
                                                        @endif
                                                        </h5>
                                                        @else

                                                        @php 
                                                        $endTime = strtotime("+110 minutes", strtotime($value['time']));
                                                        @endphp

                                                        <div class="FixtureScore 
                                                        @if ($dateNow == $value['date'] && $utcTime > date("H:i:s",$endTime)) 
                                                        live 
                                                        @endif
                                                        "
                                                        >
                                                            <span>{{$value['first_goon']}}</span>
                                                            <span>{{$value['second_goon']}}</span>
                                                        </div>
                                                        @endif
                                                        </div>
                                                        <div class="club-img">
                                                            <span>{{$value['short_second']}}</span>
                                                            <img class="sm-img" src="{{$value['image_second']}}" alt="club logo">
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Single Tab Content Start -->
                                    <div class="tab-pane fade" id="favourite-fix" role="tabpanel">
                                        <div class="mytab-content">
                                            <div class="fixtures home-fix">

                                            @foreach($favTeamMatches as $val)

                                            <h4 class="FixtureDay">{{$val->date}}</h4>
                                                <!--fixture-box -->
                                                <div class="fixture-box">
                                                    <div class="club-fixture">
                                                        <div class="club-img">
                                                            <img class="sm-img" src="{!!finalvaluebylang($val->teams_first->image,'','en') !!}" alt="club logo">
                                                            <span>{{ $val->teams_first->code }}</span>
                                                        </div>
                                                        <div class="club-schedule text-center">
                                                            <div class="FixtureScore">
                                                                <span>{{ $val->first_goon }}</span>
                                                                <span>{{ $val->second_goon }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="club-img">
                                                            <span>{{ $val->teams_second->code }}</span>
                                                            <img class="sm-img" src="{!!finalvaluebylang($val->teams_second->image,'','en') !!}" alt="club logo">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Single Tab Content Start -->
                                    <div class="tab-pane fade" id="following-fix" role="tabpanel">
                                        <div class="mytab-content">
                                            <div class="fixtures home-fix">

                                        @foreach ($attrs as $key => $value)
                                        <h4 class="FixtureDay">{{$key}}</h4>
                                            @foreach( $value as $val)
                                                   <!--fixture-box-->                                                              
                                              <div class="fixture-box">
                                                    <div class="club-fixture">
                                                        <div class="club-img">
                                                            <img class="sm-img" src="{!!finalvaluebylang($val->teams_first->image,'','en') !!}"  alt="club logo">
                                                            <span>{{ $val->teams_first->code }}</span>
                                                        </div>
                                                        <div class="club-schedule text-center">
                                                            <div class="FixtureScore">
                                                                <span>{{ $val->first_goon }}</span>
                                                                <span>{{ $val->second_goon }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="club-img">
                                                            <span>{{ $val->teams_second->code }}</span>
                                                            <img class="sm-img" src="{!!finalvaluebylang($val->teams_second->image,'','en') !!}" alt="club logo">
                                                        </div>
                                                    </div>
                                                </div>

                                            @endforeach
                                        @endforeach 
                                              
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>