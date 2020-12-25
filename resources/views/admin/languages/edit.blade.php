@extends('admin.layouts.app')
@section('title') {{trans('app.update')}} {{trans('app.lang')}} 
@stop
@section('head_content')
@include('admin.languages.head')
@stop
@section('content')
@include('admin.errors.errors')
{!! Form::model($language, ['method' => 'PATCH','route' => ['admin.languages.update', $language->id],'data-parsley-validate'=>""]) !!}
@include('admin.languages.form')
{!! Form::close() !!}
@stop