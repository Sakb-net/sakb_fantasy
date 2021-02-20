@extends('admin.layouts.app')
@section('title') Time @stop
@section('content')

@include('admin.errors.errors')
@include('admin.errors.alerts')

{!! Form::open(array('route' => 'admin.draft_cooldown.store','method'=>'POST','data-parsley-validate'=>"")) !!}
<div class="row">
    <div class="col-sm-8">
        <div class="box">
            <div class="box-body">
	            <div class="form-group">
                    <label>{{trans('app.period_time_draft_cooldown_hours')}}
                    	<!-- <span style="color:red; margin-right:30px;"> Add value by Minutes</span> -->
                     </label>
	                    {!! Form::number('draft_cooldown', $draft_cooldown, array('class' => 'form-control')) !!}  
	            </div>
	            <br/><br/>
                 <div class="box-footer text-center">
                    <button type="submit" class="btn btn-info padding-40">{{trans('app.save')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>

{!! Form::close() !!}

@stop