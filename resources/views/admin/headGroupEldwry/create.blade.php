@extends('admin.layouts.app')
@section('title') {{trans('app.add')}} {{trans('app.new')}}
@stop
@section('head_content')
@include('admin.headGroupEldwry.head')
@stop
@section('content')

@include('admin.errors.errors')

{!! Form::open(array('route' => 'admin.headGroupEldwry.store','method'=>'POST','data-parsley-validate'=>"")) !!}

@include('admin.headGroupEldwry.form')

{!! Form::close() !!}

@stop

@section('after_foot')
@include('admin.headGroupEldwry.repeater')
@stop