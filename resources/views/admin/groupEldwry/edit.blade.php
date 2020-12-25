@extends('admin.layouts.app')
@section('title') {{trans('app.update')}}
@stop
@section('head_content')
@include('admin.groupEldwry.head')
@stop
@section('content')
@include('admin.errors.errors')
@include('admin.errors.alerts')
{!! Form::model($groupEldwry, ['method' => 'PATCH','route' => ['admin.groupEldwry.update', $groupEldwry->id],'data-parsley-validate'=>""]) !!}
@include('admin.groupEldwry.form')
{!! Form::close() !!}
@stop
@section('after_foot')
@include('admin.groupEldwry.repeater')
@stop