@extends('site.layouts.app')
@section('content')
@include('site.my_team.menu')
<div class="Draw_tab_game_transfer">
	<!-- create create_done-->
	@include('site.group_eldwry.'.$type_page)
</div>
@endsection
@section('after_head')
@stop  
@section('after_foot')
	@include('site.layouts.script.public_js')
	@include('site.group_eldwry.script_page')
@stop  