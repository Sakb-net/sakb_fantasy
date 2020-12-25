@extends('admin.layouts.app')
@section('title') {{trans('app.update')}} {{trans('app.team')}}
@stop
@section('head_content')
@include('admin.teams.head')
@stop
@section('content')
@include('admin.errors.errors')
{!! Form::model($team, ['method' => 'PATCH','route' => ['admin.clubteams.update', $team->id],'data-parsley-validate'=>""]) !!}
@include('admin.teams.form')
{!! Form::close() !!}
@stop
@section('after_foot')
@include('admin.teams.repeater')
@stop