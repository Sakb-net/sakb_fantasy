@extends('admin.layouts.app')
@section('title') Time @stop
@section('content')

@include('admin.errors.errors')
@include('admin.errors.alerts')

{!! Form::open(array('route' => 'admin.option_time.store','method'=>'POST','data-parsley-validate'=>"")) !!}
<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="box-body">
	            <div class="form-group">
                    <label>{{trans('app.period_time_stop_subeldwry')}}
                    	<!-- <span style="color:red; margin-right:30px;"> Add value by Minutes</span> -->
                     </label>
	                    {!! Form::text('time_stop_subeldwry', $time_stop_subeldwry, array('class' => 'form-control','id' => 'datePicker')) !!}  
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

@section('scripts')


<link rel="stylesheet" href="{{ asset('DateTimePicker/build/css/bootstrap-datetimepicker.min.css') }}">
<script src="{{ asset('DateTimePicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>

<script>
   $(document).ready( function () {

    $('#datePicker').datetimepicker({  
       format: 'DD-MM-YYYY HH:mm:ss',
     });  
     
    });
  </script>
@stop