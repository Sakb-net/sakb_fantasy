@extends('admin.layouts.app')
@section('title') {{trans('app.add')}} {{trans('app.player')}} 
@stop
@section('head_content')
@include('admin.players.head')
@stop
@section('content')

@include('admin.errors.errors')

{!! Form::open(array('route' => 'admin.players.store','method'=>'POST','data-parsley-validate'=>"")) !!}

@include('admin.players.form')

{!! Form::close() !!}

@stop

@section('after_foot')
@include('admin.players.repeater')
@stop