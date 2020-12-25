@extends('site.layouts.app')
@section('content')
@include('site.my_team.menu')
<div class="Draw_tab_game_transfer">
	@include('site.group_eldwry.page')
</div>
@endsection
@section('after_head')
@stop  
@section('after_foot')
@include('site.layouts.script.public_js')
<script>
$(document).ready(function () {
	load_main_group_eldwry();
});
</script>
@stop  