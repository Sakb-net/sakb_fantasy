@extends('site.layouts.app')
@section('content')
@include('site.layouts.page_title')
<section class="section-padding wow fadeInUp">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!--<p><strong>حدد 3 لاعبين كحد أقصى من فريق واحد.</strong></p>-->
                <div class="DeadlineBar">
                    <!--<h3></h3>-->
                    <time>{!!$msg_finish_dwry!!}</time>
                </div>
                <div class="after-DeadlineBar"></div>

            </div>

        </div>
    </div>
</section>
@include('site.home.sponsers')
@endsection
@section('after_head')
@stop  
@section('after_foot')
<!--<script type="text/javascript" src="{{ asset('js/site/game.js') }}"></script>-->
@stop  
