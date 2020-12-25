@extends('admin.layouts.app')
@section('title') {{trans('app.update')}} 
@stop
@section('head_content')
@include('admin.subeldwry.head')
@stop
@section('content')
@include('admin.errors.errors')
{!! Form::model($subeldwry, ['method' => 'PATCH','route' => ['admin.subeldwry.update', $subeldwry->id],'data-parsley-validate'=>""]) !!}
@include('admin.subeldwry.form')
{!! Form::close() !!}
@stop
@section('after_foot')
@include('admin.subeldwry.repeater')
@stop