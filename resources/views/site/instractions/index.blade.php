@extends('site.layouts.app')
@section('content')
@include('site.layouts.page_title')
<section class="section-padding wow fadeInUp">
<div class="container">
    <div class="row">
        <div class="instractions">
            <div class="nav" role="tablist">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item active">
                        <a class="nav-link" data-toggle="tab" href="#help" role="tab">
                            <i class="fa fa-question-circle"></i> {{trans('app.help')}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#rules" role="tab">
                            <i class="fa fa-info-circle"></i>{{trans('app.role_game')}}
                        </a>
                    </li>
                </ul>
            </div>
            <!-- Tab Content Start -->
            <div class="tab-content" id="myTabContent">
                <!-- Single Tab Content Start -->
                <div class="tab-pane fade active in" id="help" role="tabpanel">
                    <div class="mytab-content">
                        <!-- =========== Dear Eman, Take care with href & Id here :) ============ -->
                        <div class="panel-group" id="accordion">
                            @foreach ($content_help as $key => $val_data)
                                @include('site.instractions.helps')
                            @endforeach   
                        </div>
                        <!-- end panel group -->
                    </div>
                </div>
                <!-- Single Tab Content End -->

                <!-- Single Tab Content Start -->
                <div class="tab-pane fade" id="rules" role="tabpanel">
                    <div class="mytab-content">
                        <div class="panel-group" id="accordion2">
                           @foreach ($content_role as $key_role => $val_role)
                                @include('site.instractions.roles')
                            @endforeach 
                        </div>
                        <!-- end panel group -->
                    </div>
                </div>
                <!-- Single Tab Content End -->
            </div>
            <!-- Tab Content End -->
        </div>  
    </div>
</div>
</section>
@include('site.home.sponsers')
@endsection
