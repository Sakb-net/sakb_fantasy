<section class="home-matches section-padding wow fadeInUp">
    <div class="container">
        <div class="row">
            @if(count($fixtures)>0)
            @foreach($fixtures as $key_subeldwry=>$val_subeldwry)
            <div class="section-head">
                <h4>{{trans('app.week_table')}} </h4>
                <p>
                    {{$val_subeldwry['start_date_day']}}  
                    {{$val_subeldwry['start_date']}}
                </p>
            </div>
                @foreach($val_subeldwry['match_group'] as $keyfixtures=>$val_fixtures)
                <div class="col-md-4 col-sm-6">
                    <div class="match">
                        <ul class="match-vs">
                            <li><img src="{{$val_fixtures['image_first']}}" alt="{{$val_fixtures['name_first']}}"><span>{{$val_fixtures['name_first']}}</span></li>
                            <li class="vs">
                                <h4 class="yellow">{{$val_fixtures['time']}}</h4>
                            </li>
                            <li>
                                <img src="{{$val_fixtures['image_second']}}" alt="{{$val_fixtures['name_second']}}"><span>{{$val_fixtures['name_second']}}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                @endforeach
            @endforeach
            @else
                @include('site.fixtures.match_empty')
            @endif
        </div>
        <div class="row text-center">
            <a href="{{ route('fixtures.index') }}" class="butn">{{trans('app.show_all_matches')}}</a>
        </div>
    </div>
</section>