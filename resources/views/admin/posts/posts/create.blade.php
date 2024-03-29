@extends('admin.layouts.app')
@section('title') اضافة  جديدة 
@stop
@section('head_content')
@include('admin.posts.posts.head')
@stop
@section('content')
@include('admin.errors.errors')
{!! Form::open(array('route' => 'admin.posts.store','method'=>'post','data-parsley-validate'=>"")) !!}

@include('admin.posts.posts.form')
{!! Form::close() !!}
@stop
@section('after_foot')
@include('admin.layouts.tinymce')
@include('admin.posts.posts.repeater')
@stop