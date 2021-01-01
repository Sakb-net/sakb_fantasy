@extends('admin.layouts.app')
@section('title') {{trans('app.add')}} opta
@stop
@section('head_content')
 <!-- include 'admin.opta.head' -->
@stop
@section('content')

@include('admin.errors.errors')
@include('admin.errors.alerts')

{!! Form::open(array('route' => 'admin.opta.store','method'=>'POST','data-parsley-validate'=>"")) !!}

@include('admin.opta.form')

{!! Form::close() !!}

@stop

@section('after_foot')
@include('admin.layouts.add')
@stop