@extends('admin.layouts.app')
@section('title') {{trans('app.update')}}
@stop
@section('head_content')
@include('admin.headGroupEldwry.head')
@stop
@section('content')
@include('admin.errors.errors')
@include('admin.errors.alerts')
{!! Form::model($headGroupEldwry, ['method' => 'PATCH','route' => ['admin.headGroupEldwry.update', $headGroupEldwry->id],'data-parsley-validate'=>""]) !!}
@include('admin.headGroupEldwry.form')
{!! Form::close() !!}
@stop
@section('after_foot')
@include('admin.headGroupEldwry.repeater')
@stop