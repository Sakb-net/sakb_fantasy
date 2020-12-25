@extends('admin.layouts.app')
@section('title') {{trans('app.add')}} {{trans('app.lineup')}} 
@stop
@section('head_content')
@include('admin.settings.head')
@stop
@section('content')

@include('admin.errors.errors')

{!! Form::open(array('route' => 'admin.settings.store','method'=>'POST','data-parsley-validate'=>"")) !!}

@include('admin.settings.form')

{!! Form::close() !!}

@stop

@section('after_foot')
@include('admin.settings.repeater')
@stop