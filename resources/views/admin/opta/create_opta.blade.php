@extends('admin.layouts.app')
@section('title') {{trans('app.add')}} opta
@stop
@section('head_content')
 <!-- include 'admin.opta.head' -->
@stop
@section('content')

@include('admin.errors.errors')
@include('admin.errors.alerts')
	@if(!empty($type_page))
		@include('admin.opta.form_'.$type_page)
	@else
		@include('admin.opta.form_opta')
	@endif
@stop

@section('after_foot')
@include('admin.layouts.add')
@stop