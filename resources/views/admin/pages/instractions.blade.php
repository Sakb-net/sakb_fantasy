@extends('admin.layouts.app')
@section('title') التعليمات@stop
@section('content')
@include('admin.errors.errors')
@include('admin.errors.alerts')
{!! Form::open(array('route' => 'admin.pages.instractions.store','method'=>'POST','data-parsley-validate'=>"")) !!}
<div class="row">
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="box">
            <div class="box-body">
                {!! Form::hidden('lang_id', $lang_id) !!}
                {!! Form::hidden('lang', $lang) !!}
                {!! Form::hidden('type', $type) !!}
                <div class="form-group">
                    <label>{{trans('app.name_page')}} </label>
                    {!! Form::text('instraction_page', $instraction_page, array('class' => 'form-control','required'=>'')) !!}
                </div>
            </div>
        </div>
        <div class="box ">
            <div class="box-body">
                <div class="form-group">
                    <label>{{trans('app.title_page')}} </label>
                    {!! Form::text('instraction_title', $instraction_title, array('class' => 'form-control','required'=>'')) !!}
                </div>
                <div class="form-group hidden">
                    <label>{{trans('app.content_page')}}</label>
                    <!--,'id'=>'my-textarea' ,'required'=>''-->
                    {!! Form::textarea('instraction_content', $instraction_content, array('class' => 'form-control')) !!}
                </div>
            </div>
        </div>
        <div class="box">
            <div class="box-body">
                <div class="form-group hidden">
                    <label>العنوان الفكرة الثانية </label>
                    {!! Form::text('instraction_title_two', $instraction_title_two, array('class' => 'form-control')) !!}
                </div>
                <div class="form-group hidden">
                    <label>المحتوى الفكرة الثانية </label>
                    <!--,'id'=>'my-textarea'-->
                    {!! Form::textarea('instraction_content_two', $instraction_content_two, array('class' => 'form-control')) !!}
                </div>
                <div class="form-group hidden">
                    <label>{{trans('app.image')}} </label>
                    <input id="icon_image" name="instraction_image" type="hidden" value="{{ $instraction_image }}">
                    <img  src="{{ $instraction_image }}"  width="60%" height="auto" @if($instraction_image == Null)  style="display:none;" @endif />
                          @if(Auth::user()->can('access-all', 'user-all'))
                          <a href="{{URL::asset('filemanager/dialog.php?type=1&akey=admin_panel&field_id=icon_image')}}" class="btn iframe-btn btn-success fa fa-download" type="button"></a>
                    @else
                    <a href="{{URL::asset('filemanager/dialog.php?type=0&akey=user&field_id=icon_image')}}" class="btn iframe-btn btn-success fa fa-download" type="button"></a>
                    @endif
                    <a href="#" class="btn btn-danger fa fa-remove  remove_image_link" type="button"></a>
                </div>
                        @include('admin.pages.instraction_repeater')

                <div class="box-footer text-center">
                    <button type="submit" class="btn btn-info padding-40" >{{trans('app.save')}}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-6">
        <!-- <div class="box">
            <div class="box-body">
            </div>
        </div> -->
        <!--repeater-->
        @include('admin.pages.role_repeater')
        <!--Endrepeater-->
    </div>
</div>
{!! Form::close() !!}
@stop
@section('after_foot')
@include('admin.layouts.tinymce')
@include('admin.pages.repeater')
@stop
