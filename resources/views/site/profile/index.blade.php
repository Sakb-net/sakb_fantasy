@extends('site.layouts.app')
@section('content')
<div class="myinner-banner best-teamimg">
    <div class="opacity opcit-teamimg" @if(isset($team_image_fav) && !empty($team_image_fav) ) style="background-image: url({{$team_image_fav}}) !important;" @endif>
        <h2>{{trans('app.profile')}}</h2>
    </div> <!-- /.opacity -->
</div>
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-center">
                    <!--@include('errors.alerts')
                        @include('errors.errors')-->
                    @include('site.layouts.alert_save')
                </div>
                <div class="text-center mb-10">
                    @include('site.layouts.correct_wrong')
                </div>
                @include('site.profile.update')
            </div>
        </div>
    </div>
</section>
@endsection
@section('after_head')

@stop  
@section('after_foot')

@stop  
