@extends('admin.layouts.app')
@section('title') Time @stop
@section('content')

@include('admin.errors.errors')
@include('admin.errors.alerts')

{!! Form::open(array('route' => 'admin.option_time.store','method'=>'POST','data-parsley-validate'=>"")) !!}
<div class="row">
    <div class="col-sm-8">
        <div class="box">
            <div class="box-body">
	            <div class="form-group">
                    <label>{{trans('app.period_time_stop_subeldwry')}}
                    	<!-- <span style="color:red; margin-right:30px;"> Add value by Minutes</span> -->
                     </label>
                     
                    <div class="row">
                     <div class="col-sm-8">
                        <div class="bootstrap-timepicker">
                            <label>{{trans('app.time')}}:</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                                {!! Form::text('time', $time, array('class' => 'form-control timepicker pull-right','id'=>'')) !!}
                            </div>                     
                        </div>
	            </div>

                     <div class="col-sm-8">
                        <label>{{trans('app.date')}}:</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <!--'id'=>'reservation' 'id'=>'reservationtime'-->
                            {!! Form::text('date', $date, array('class' => 'form-control pull-right','id'=>'datepicker')) !!}
                        </div>
                    </div>

                </div>
                 <div class="box-footer text-center">
                    <button type="submit" class="btn btn-info">{{trans('app.save')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>

{!! Form::close() !!}

@stop
