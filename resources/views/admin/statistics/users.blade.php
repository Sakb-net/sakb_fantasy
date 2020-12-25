@extends('admin.layouts.app')
@section('title') {{trans('app.home_page')}} @stop
@section('head_content')
@stop
@section('content')
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{ $user_count }}</h3>
                    <p>{{trans('app.all_member')}}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-stalker"></i>
                </div>
                <a href="{{ route('admin.users.index') }}" class="small-box-footer">{{trans('app.all_view')}} <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{ $user_count_month }}</h3>
                    <p>{{trans('app.member_last_month')}}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person"></i>
                </div>
                <a href="{{ route('admin.users.index') }}" class="small-box-footer">{{trans('app.all_view')}} <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{ $user_count_week }}</h3>
                    <p>{{trans('app.member_last_week')}}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-man"></i>
                </div>
                <a href="{{ route('admin.users.index') }}" class="small-box-footer">{{trans('app.all_view')}} <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ $user_count_day }}</h3>
                    <p>{{trans('app.member_last_day')}}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="{{ route('admin.users.index') }}" class="small-box-footer">{{trans('app.all_view')}} <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
</section>
@stop
