@extends('admin.layouts.app')
@section('title') {{trans('app.update')}}
@stop
@section('head_content')
@include('admin.ranking_eldwry.head')
@stop
@section('content')
@include('admin.errors.errors')
@include('admin.errors.alerts')

{!! Form::model($ranking_eldwry, ['method' => 'PATCH','route' => ['admin.ranking_eldwry.update', $ranking_eldwry->id],'data-parsley-validate'=>""]) !!}
@include('admin.ranking_eldwry.form')
{!! Form::close() !!}
@stop
@section('after_foot')
@include('admin.ranking_eldwry.repeater')
@stop