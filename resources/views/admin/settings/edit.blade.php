@extends('admin.layouts.app')
@section('title') {{trans('app.update')}} {{trans('app.lineup')}}
@stop
@section('head_content')
@include('admin.settings.head')
@stop
@section('content')
@include('admin.errors.errors')
{!! Form::model($setting, ['method' => 'PATCH','route' => ['admin.settings.update', $setting->id],'data-parsley-validate'=>""]) !!}
@include('admin.settings.form')
{!! Form::close() !!}
@stop
@section('after_foot')
@include('admin.settings.repeater')
@stop