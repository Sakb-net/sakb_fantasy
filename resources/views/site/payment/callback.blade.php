@extends('site.layouts.app')
@section('content')
@include('site.layouts.page_title')
<section class="contact section-padding wow fadeInUp">
    <div class="container">
        <div class="row wow fadeInUp" data-wow-delay=".2s">
            <div class="col-md-offset-2 col-md-8 ">
                <div class="contact-message-form" >
                    <div class="col-md-12 col-xs-12">
                        <div class="alert alert-info alert-dismissible" role="alert" style="color:#000; background:{{$back_color}} ">
                            <!--<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                            <span class="icon icon-info"></span>{{$mesage_pay}} 
                        </div>
                    </div>
                    <!-- butn butn-bg low_show -->
                    <div class="modal-footer footer_back">
                        <a href="{{ route('game.game_transfer') }}" class="butn butn-bg pull-right"><span>{{trans('app.goto_game')}}</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include('site.home.sponsers')
@endsection
@section('after_head')
@stop  
@section('after_foot')
@stop  
