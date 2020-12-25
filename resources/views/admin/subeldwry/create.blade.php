@extends('admin.layouts.app')
@section('title') {{trans('app.add')}} {{trans('app.subeldwry')}} 
@stop
@section('head_content')
@include('admin.subeldwry.head')
@stop
@section('content')

@include('admin.errors.errors')

{!! Form::open(array('route' => 'admin.subeldwry.store','method'=>'POST','data-parsley-validate'=>"")) !!}

@include('admin.subeldwry.form')

{!! Form::close() !!}

@stop

@section('after_foot')
@include('admin.subeldwry.repeater')
@stop