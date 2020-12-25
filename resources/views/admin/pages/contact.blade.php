@extends('admin.layouts.app')
@section('title') صفحة اتصل بنا@stop
@section('content')

@include('admin.errors.errors')
@include('admin.errors.alerts')

{!! Form::open(array('route' => 'admin.pages.contact.store','method'=>'POST','data-parsley-validate'=>"")) !!}

<div class="row">
    <div class="col-xs-9 col-md-6 col-lg-6">
        <div class="box">
            <div class="box-body">
                {!! Form::hidden('lang_id', $lang_id) !!}
                {!! Form::hidden('lang', $lang) !!}
                {!! Form::hidden('type', $type) !!}
                <div class="form-group">
                    <label>{{trans('app.name_page')}} </label>
                    {!! Form::text('contact_page', $contact_page, array('class' => 'form-control')) !!}
                </div>
                <div class="form-group">
                    <label>{{trans('app.title_page')}}</label>
                    {!! Form::text('contact_title', $contact_title, array('class' => 'form-control')) !!}

                </div>
                <div class="form-group">
                    <label>{{trans('app.email')}} </label>
                    {!! Form::text('contact_email', $contact_email, array('class' => 'form-control','required'=>'','data-parsley-type'=>"email")) !!}
                </div>
                <div class="form-group">
                    <label>{{trans('app.mobile')}} </label>
                    {!! Form::text('contact_phone', $contact_phone, array('class' => 'form-control')) !!}
                </div>
                <div class="form-group">

                    {!! Form::label('address', trans('app.address')) !!}
                    {!! Form::text('address', $address, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('longitude', trans('app.longitude')) !!}
                    {!! Form::text('longitude', $long, ['class' => 'form-control']) !!}
                    <i class="form-group__bar"></i>
                </div>
                <div class="form-group">
                    {!! Form::label('latitude', trans('app.latitude')) !!}
                    {!! Form::text('latitude', $lat, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group hidden">
                    <!--,'id'=>'my-textarea'-->
                    <label>{{trans('app.content_page')}}</label>
                    {!! Form::textarea('contact_content', $contact_content, array('class' => 'form-control')) !!}
                </div>

                <div class="box-footer text-center">
                    <button type="submit" class="btn btn-info padding-40" >{{trans('app.save')}}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-9 col-md-6 col-lg-6">
        <div class="box">
            <div class="box-body">
                <div id="mapCanvas" class="mapCanvas col-sm-12 m-t-20 m-b-20"></div>


            </div>
        </div>
    </div>
</div>


{!! Form::close() !!}

@stop
@section('after_foot')
@include('admin.layouts.tinymce')
@if(empty($long) || empty($lat))
@include('admin.layouts.mapCreate')
@else
@include('admin.layouts.mapEdit')
@endif
@stop

