@extends('admin.layouts.app')
@section('title') {{trans('app.search')}} {{trans('app.subeldwrys')}} {{trans('app.subeldwrys')}}
@stop
@section('head_content')
<div class="row">

    <div class="col-lg-12 margin-tb">
        <div class="pull-right">
            @if($subeldwry_create > 0)
            <a class="btn btn-success fa fa-plus"  data-toggle="tooltip" data-placement="top" data-title="{{trans('app.add')}} {{trans('app.subeldwry')}}" href="{{ route('admin.subeldwry.create') }}"></a>
            @endif
            <a class="btn btn-primary fa fa-task"  data-toggle="tooltip" data-placement="top" data-title=" {{trans('app.subeldwrys')}}" href="{{ route('admin.subeldwry.index') }}"></a>
        </div>

    </div>

</div>
@stop
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="box-body">	


                <table  id="datatable_search" data-page-length='50' class='table table-bordered table-striped'>

                 @include('admin.subeldwry.table')   

                </table>

               
            </div>
        </div>
    </div>
</div>
@stop

@section('after_foot')
@include('admin.layouts.delete')
@include('admin.layouts.status')
@stop

