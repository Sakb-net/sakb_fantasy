@extends('admin.layouts.app')
@section('title') {{trans('app.add')}} {{trans('app.new')}}
@stop
@section('head_content')
@include('admin.groupEldwry.head')
@stop
@section('content')

@include('admin.errors.errors')

{!! Form::open(array('route' => 'admin.groupEldwry.store','method'=>'POST','data-parsley-validate'=>"")) !!}

@include('admin.groupEldwry.form')

{!! Form::close() !!}

@stop

@section('after_foot')
@include('admin.groupEldwry.repeater')
@stop