<h1>{{ Auth::user()->display_name }}</h1>
@if(isset($curent_subeldwry['link']))
	<h2> {{trans('app.game_week_points')}} {!!$curent_subeldwry['num_week']!!}</h2>
	<!-- points -->
	<div class="home-points">
	    <p>{{trans('app.rate')}}</p>
	    <p class="num">{!!$home_points['total_avg']!!}</p>
	</div>
	<div class="home-points">
	    <p>{{trans('app.points')}}</p>
	    <p class="num">{!!$home_points['user_total_mypoint']!!}</p>
	</div>
	<div class="home-points">
	    <p>{{trans('app.heighest')}}</p>
	    <p class="num">{!!$home_points['heigh_point']!!}</p>
	</div>  
@endif