@extends('admin.layouts.app')
@section('title') {{trans('app.add')}}  {{trans('app.lang')}}  {{trans('app.new')}}  
@stop
@section('head_content')
@include('admin.languages.head')
@stop
@section('content')
@include('admin.errors.errors')
{!! Form::open(array('route' => 'admin.languages.store','method'=>'post','data-parsley-validate'=>"")) !!}
@include('admin.languages.form')
{!! Form::close() !!}
@stop