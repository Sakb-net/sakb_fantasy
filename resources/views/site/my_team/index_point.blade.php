@extends('site.layouts.app')
@section('content')
	@include('site.my_team.menu')
	@include('site.my_team.point.index')
@endsection
@section('after_head')
@stop  
@section('after_foot')
@include('site.layouts.script.public_js')
<script>
$(document).ready(function () {
    var url_string =window.location.href;
    var data_url = new URL(url_string);
    var num_week =data_url.searchParams.get("week");
    get_dataPagePoint(start_limit_match,'','','hidden',num_week);
});
</script>
@stop  