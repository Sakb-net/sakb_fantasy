@extends('admin.layouts.app')
@section('title') {{trans('app.update')}}
@stop
@section('head_content')
@include('admin.eldwry.head')
@stop
@section('content')
@include('admin.errors.errors')
{!! Form::model($eldwry, ['method' => 'PATCH','route' => ['admin.eldwry.update', $eldwry->id],'data-parsley-validate'=>""]) !!}
@include('admin.eldwry.form')
{!! Form::close() !!}
@stop
@section('after_foot')
@include('admin.eldwry.repeater')
@stop