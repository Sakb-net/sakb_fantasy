@extends('admin.layouts.app')
@section('title') {{trans('app.add')}} {{trans('app.new')}}
@stop
@section('head_content')
@include('admin.eldwry.head')
@stop
@section('content')

@include('admin.errors.errors')

{!! Form::open(array('route' => 'admin.eldwry.store','method'=>'POST','data-parsley-validate'=>"")) !!}

@include('admin.eldwry.form')

{!! Form::close() !!}

@stop

@section('after_foot')
@include('admin.eldwry.repeater')
@stop