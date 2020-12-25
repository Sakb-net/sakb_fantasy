@extends('site.layouts.app_close',['title' => 'Site Close '])
@section('content')
<div class="myinner-banner">
    <div class="opacity">
       <h2>{!!$message_close!!}</h2>
    </div>
</div>
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="myaccount-content">
                    <div style="margin-bottom: 35px"></div>
                    <div class="account-details-form">
                        <p class="text-center" style="font-size:18px;">{!!$msgmain_close!!}
                           
                        </p>
                    </div>
                    <div style="margin-bottom: 45px"></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('after_foot')
<script>
    $(document).ready(function () {
        $('body').find('.bottom-footer').addClass('footer_style');
        $('body').find('.bottom-footer').css({ "position": "static", "background": "#000" });;
    });
</script>
@stop  