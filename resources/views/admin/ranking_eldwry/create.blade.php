@extends('admin.layouts.app')
@section('title') {{trans('app.ranking_eldwry')}}
@stop
@section('head_content')
	@include('admin.ranking_eldwry.head')
@stop
@section('content')

@include('admin.errors.errors')
@include('admin.errors.alerts')
	@if($create=="create")
		@include('admin.ranking_eldwry.form')
	@else
		@include('admin.ranking_eldwry.form_match')
	@endif
@stop

@section('after_foot')
@include('admin.layouts.add')
@stop