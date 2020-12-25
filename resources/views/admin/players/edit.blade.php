@extends('admin.layouts.app')
@section('title') {{trans('app.update')}}  
@stop
@section('head_content')
@include('admin.players.head')
@stop
@section('content')
@include('admin.errors.errors')
{!! Form::model($player, ['method' => 'PATCH','route' => ['admin.players.update', $player->id],'data-parsley-validate'=>""]) !!}
@include('admin.players.form')
{!! Form::close() !!}
@stop
@section('after_foot')
@include('admin.players.repeater')
@stop