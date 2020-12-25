@extends('admin.layouts.app')
@section('title') {{trans('app.update')}}   
@stop
@section('head_content')
@include('admin.videocomments.head')
@stop
@section('content')
@include('admin.errors.errors')
{!! Form::model($comment, ['method' => 'PATCH','route' => ['admin.videocomments.update', $comment->id],'data-parsley-validate'=>""]) !!}
@include('admin.videocomments.form')
{!! Form::close() !!}
@stop