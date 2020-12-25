@extends('admin.layouts.app')
@section('title') {{trans('app.search')}}  {{trans('app.all')}}  {{trans('app.members')}} 
@stop
@section('head_content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-right">
            @if($user_create == 1)
            <a class="btn btn-success fa fa-plus" data-toggle="tooltip" data-placement="top" data-title="{{trans('app.add')}}  {{trans('app.member')}} " href="{{ route('admin.users.create') }}"></a>
            @endif
            <a class="btn btn-primary fa fa-user" data-toggle="tooltip" data-placement="top" data-title="{{trans('app.members')}} " href="{{ route('admin.users.index') }}"></a>
        </div>
    </div>
</div>
@stop
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a aria-expanded="true" href="#members" data-toggle="tab">{{trans('app.search')}}  {{trans('app.members')}} <i class="fa"></i></a></li>
                    <!--<li><a aria-expanded="true" href="#nots" data-toggle="tab">{{trans('app.members')}}  {{trans('app.not')}} {{trans('app.active')}}  <i class="fa"></i></a></li>-->
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="members">
                        <form id="member_request"  onsubmit="return false;" method="post" role="form" autocomplete="off" data-validate="parsley">   
                            <div class="row">
                                <div class="form-group col-sm-5">
                                    <input type="text" id="searchInput"  name="data_member" class="form-control searchInput" placeholder=" {{trans('app.data')}} {{trans('app.search')}} " />
                                </div> 
                                <div class="form-group col-sm-2">
                                    <label for="search">{{trans('app.search')}}  {{trans('app.members')}} </label>
                                    <button type="submit" name="search" id="searchUserBtn" class="btn btn-primary searchUserBtn"><i class="fa fa-search"></i> {{trans('app.search')}} </button>
                                </div>
                            </div>
                        </form>
                        <div class="member_requests">@include('admin.layouts.search_ajax')</div>
                    </div>
                    <!--there another-->
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@stop
@section('after_foot')
@include('admin.layouts.delete')
@include('admin.layouts.status')
@stop

