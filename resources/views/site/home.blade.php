@extends('site.layouts.app')
@section('content')
   @include('site.home.silder')
   <!-- here include ('site.home.fixtures') -->
   <section class="section-padding wow fadeInUp home-matches">
	    <div class="container">
	        <div class="row">
	   			@include('site.home.news')
	   			@include('site.home.ranking_eldwry')
			</div>
	    </div>
	</section>
   @include('site.home.videos')
   @include('site.home.sponsers')
@endsection
@section('after_foot')
<script type="text/javascript" src="{{ asset('js/site/ranking_eldwry.js?v='.config('version.version_script')) }}"></script>
<script>
$(document).ready(function () {    
    Load_home_ranking_eldwry();
});
</script>
@stop  