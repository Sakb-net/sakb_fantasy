@extends('site.layouts.app')
@section('content')
@include('site.layouts.page_title')
<section class="contact section-padding wow fadeInUp">
    <div class="container">
        <div class="row wow fadeInUp" data-wow-delay=".2s">
            <div class="col-md-offset-2 col-md-8 ">
                <div class="contact-message-form" >
                    <form action="{{$shopperResultUrl}}" class="paymentWidgets" data-brands="VISA MASTER MADA"></form>
                    </form>
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
<script src="{{$url_pay_hyper}}{{$checkoutId}}"></script>
<script>
$(document).ready(function () {
    //$('body').css("direction", "ltr");
    $(".container").css("direction", "ltr");
    $('body').find(".contact-message-form input").css("direction", "ltr");
    //select, .contact-message-form input
});
</script>
@stop  
