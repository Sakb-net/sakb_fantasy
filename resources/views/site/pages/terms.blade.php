@extends('site.layouts.app')
@section('content')
   @include('site.layouts.page_title')
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="wow fadeInUp">
                <div class="terms">
                    {!!$content!!}
                </div>
            </div>
        </div>
    </div>
</section>
@include('site.home.sponsers')
@endsection
