@extends('admin.layouts.app')
@section('title') {{trans('app.add')}} {{trans('app.ranking_eldwry')}}
@stop
@section('head_content')
 <!-- include 'admin.ranking_eldwry.head' -->
@stop
@section('content')

@include('admin.errors.errors')
@include('admin.errors.alerts')

@include('admin.ranking_eldwry.form')

@stop

@section('after_foot')
@include('admin.layouts.add')
@stop