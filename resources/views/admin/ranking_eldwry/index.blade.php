@extends('admin.layouts.app')
@section('title') {{trans('app.ranking_eldwry')}}
@stop
@section('head_content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-right">
            @if($ranking_eldwry_create > 0)
            <a class="btn btn-success fa fa-plus"  data-toggle="tooltip" data-placement="top" data-title="{{trans('app.add')}} {{trans('app.ranking_eldwry')}}" href="{{ route('admin.ranking_eldwry.create') }}"></a>
            @endif
            <a class="btn btn-primary fa fa-search"  data-toggle="tooltip" data-placement="top" data-title="{{trans('app.search')}} {{trans('app.ranking_eldwry')}}" href="{{ route('admin.ranking_eldwry.search') }}"></a>
        </div>
    </div>
</div>
@stop
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="box-body">	

                @include('admin.errors.alerts')

                <table  id="datatable"  class='table table-bordered table-striped'>

                    @include('admin.ranking_eldwry.table')   

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


