@extends('site.layouts.app')
@section('content')
@include('site.layouts.page_title')
<section class="contact section-padding wow fadeInUp">
    <div class="container">
        <div class="row">
            <div class="contact-info">
                <div class="col-md-4 col-sm-4">
                    <div class="item">
                        <span class="icon fa fa-volume-control-phone" aria-hidden="true"></span> 
                        <div class="cont">
                            <h6>{{trans('app.mobile')}} : </h6>
                            <p>{{$phone}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="item">
                        <span class="icon fa fa-envelope-o" aria-hidden="true"></span>
                        <div class="cont">
                            <h6>{{trans('app.email')}} : </h6>
                            <p>{{$email}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="item">
                        <span class="icon fa fa-map-marker" aria-hidden="true"></span>
                        <div class="cont">
                            <h6>{{trans('app.address')}}: </h6>
                            <p>{{$address}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ============== contact form ============-->
<section class="section-padding wow fadeInUp">
    <div class="container">
        <div class="row">
            <div class="section-head">
                <h4>{{trans('app.send_message')}}</h4>
            </div>
            @include('site.pages.contact_form')
        </div>
    </div>
</section>
@include('site.home.sponsers')
@endsection

