@extends('site.layouts.app')
@section('content')
<div class="myinner-banner" @if(isset($team_image_fav) && !empty($team_image_fav) ) style="background-image: url({{$team_image_fav}}) !important;" @endif>
    <div class="opacity">
        <h2>{{trans('app.gameweek_stopped')}}</h2>
    </div>
</div>
<section class="section-padding wow fadeInUp">
	<div class="container">
	    <div class="panel-sign">
	        <div class="col-md-6 col-sm-6">
	            <img class="center-block p10" src="{{ asset('images/icon/timer.png') }}">
	        </div>
	        <div class="col-md-6 col-sm-6">
	            <div class="form-group mytimer">
	                <h2>{{trans('app.gameweek_stopped_now')}}</h2>
	                <h3>{{trans('app.gameweek_placeafter')}} 
	                </h3>
	                <!--Countdown Timer-->
	                <div class="time-counter">
	                    <div class="time-countdown clearfix" data-countdown="{{$start_date_subeldwry}}"></div>
	                    <!-- 2020/07/01 04:50:00  -->
	                </div>
	            </div>
	        </div>
	        <div class="col-md-12">
	            <div class="form-group text-center">
	                <a href="index.html" class="butn butn-bg">{{trans('app.goto_home')}}</a>
	            </div>
	        </div>
	    </div>
	</div>
</section>
@include('site.home.sponsers')
@endsection
@section('after_head')
@stop  
@section('after_foot')
<script>
$(document).ready(function () {
	//Event Countdown Timer
          if($('.time-countdown').length){  
            $('.time-countdown').each(function() {
            var $this = $(this), finalDate = $(this).data('countdown');
            $this.countdown(finalDate, function(event) {
              var $this = $(this).html(event.strftime(''+ '<div class="counter-column"><div class="inner"><span class="count">%S</span>Seconds</div></div>  ' + '<div class="counter-column"><div class="inner"><span class="count">%M</span>Minutes</div></div>'  + '<div class="counter-column"><div class="inner"><span class="count">%H</span>Hours</div></div> '));
            });
       });
    }
});
</script>
@stop  