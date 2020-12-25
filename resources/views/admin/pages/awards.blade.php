@extends('admin.layouts.app')
@section('title') الجوائز@stop
@section('content')
@include('admin.errors.errors')
@include('admin.errors.alerts')
{!! Form::open(array('route' => 'admin.pages.awards.store','method'=>'POST','data-parsley-validate'=>"")) !!}
<div class="row">
    <div class="col-sm-12 col-md-6 col-lg-6">
    <!-- This box contains the page name -->
        <div class="box">
            <div class="box-body">
                {!! Form::hidden('lang_id', $lang_id) !!}
                {!! Form::hidden('lang', $lang) !!}
                {!! Form::hidden('type', $type) !!}
                <div class="form-group">
                    <label>{{trans('app.name_page')}} </label>
                    {!! Form::text('award_page', $award_page, array('class' => 'form-control','required'=>'')) !!}
                </div>
            </div>
        </div>
        <!-- This box contains the page title -->
        <div class="box ">
            <div class="box-body">
                <div class="form-group">
                    <label>{{trans('app.title_page')}} </label>
                    {!! Form::text('award_title', $award_title, array('class' => 'form-control','required'=>'')) !!}
                </div>
                <div class="form-group hidden">
                    <label>{{trans('app.content_page')}}</label>
                    {!! Form::textarea('award_content', $award_content, array('class' => 'form-control')) !!}
                </div>
            </div>
        </div>
        <!-- This box contains the awards details -->
        <div class="box">
            <div class="box-body">
                <div class="form-group hidden">
                    {!! Form::text('award_title_two', $award_title_two, array('class' => 'form-control')) !!}
                </div>
                <div class="form-group hidden">
                    {!! Form::textarea('award_content_two', $award_content_two, array('class' => 'form-control')) !!}
                </div>
                <div class="form-group hidden">
                    <label>{{trans('app.image')}} </label>
                    <input id="icon_image" name="award_image" type="hidden" value="{{ $award_image }}">
                    <img  src="{{ $award_image }}"  width="60%" height="auto" @if($award_image == Null)  style="display:none;" @endif />
                          @if(Auth::user()->can('access-all', 'user-all'))
                          <a href="{{URL::asset('filemanager/dialog.php?type=1&akey=admin_panel&field_id=icon_image')}}" class="btn iframe-btn btn-success fa fa-download" type="button"></a>
                    @else
                    <a href="{{URL::asset('filemanager/dialog.php?type=0&akey=user&field_id=icon_image')}}" class="btn iframe-btn btn-success fa fa-download" type="button"></a>
                    @endif
                    <a href="#" class="btn btn-danger fa fa-remove  remove_image_link" type="button"></a>
                </div>
                        @include('admin.pages.awards_repeater')

                <div class="box-footer text-center">
                    <button type="submit" class="btn btn-info padding-40" >{{trans('app.save')}}</button>
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
