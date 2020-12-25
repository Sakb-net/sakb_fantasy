@extends('admin.layouts.app')
@section('title') الشروط والاحكام@stop
@section('content')
@include('admin.errors.errors')
@include('admin.errors.alerts')
{!! Form::open(array('route' => 'admin.pages.terms.store','method'=>'POST','data-parsley-validate'=>"")) !!}
<div class="row">
    <div class="col-sm-8 col-xs-8 col-lg-8">
        <div class="box">
            <div class="box-body">
                {!! Form::hidden('lang_id', $lang_id) !!}
                {!! Form::hidden('lang', $lang) !!}
                {!! Form::hidden('type', $type) !!}
                <div class="form-group">
                    <label>{{trans('app.name_page')}} </label>
                    {!! Form::text('terms_page', $terms_page, array('class' => 'form-control','required'=>'')) !!}
                </div>
                <div class="form-group">
                    <label>{{trans('app.title_page')}} </label>
                    {!! Form::text('terms_title', $terms_title, array('class' => 'form-control','required'=>'')) !!}
                </div>
                <div class="form-group">
                    <label>{{trans('app.content_page')}} </label>
                    {!! Form::textarea('terms_content', $terms_content, array('class' => 'form-control','id'=>'my-textarea')) !!}
                </div>
                <div class="box-footer text-center">
                    <button type="submit" class="btn btn-info padding-40" >{{trans('app.save')}}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="box">
            <div class="box-body">
                <div class="form-group">
                    <label>الصورة </label>
                    <input id="icon_image" name="terms_image" type="hidden" value="{{ $terms_image }}">
                    <img  src="{{ $terms_image }}"  width="60%" height="auto" @if($terms_image == Null)  style="display:none;" @endif />
                    @if(Auth::user()->can('access-all', 'user-all'))
                        <a href="{{URL::asset('filemanager/dialog.php?type=1&akey=admin_panel&field_id=icon_image')}}" class="btn iframe-btn btn-success fa fa-download" type="button"></a>
                    @else
                        <a href="{{URL::asset('filemanager/dialog.php?type=0&akey=user&field_id=icon_image')}}" class="btn iframe-btn btn-success fa fa-download" type="button"></a>
                    @endif
                    <a href="#" class="btn btn-danger fa fa-remove  remove_image_link" type="button"></a>
                </div>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
@stop
@section('after_foot')
@include('admin.layouts.tinymce')
@include('admin.pages.repeater')
@stop
