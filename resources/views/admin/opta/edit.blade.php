@extends('admin.layouts.app')
@section('title') {{trans('app.update')}}
@stop
@section('head_content')
@include('admin.opta.head')
@stop
@section('content')
@include('admin.errors.errors')
{!! Form::model($opta, ['method' => 'PATCH','route' => ['admin.opta.update', $opta->id],'data-parsley-validate'=>""]) !!}
@include('admin.opta.form')
{!! Form::close() !!}
@stop
@section('after_foot')
@include('admin.opta.repeater')
@stop