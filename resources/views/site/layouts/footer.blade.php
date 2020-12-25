<footer class="myfooter">
    <div class="container">
        <div class="top-footer row">
            <div class="col-md-3 hidden-sm col-xs-12">
                <img class="center-block" src="{{ asset('images/logo/logo.png')}}" alt="{{$title}}"/>
            </div>
            <div class="col-md-3 col-sm-4 col-xs-12">
                <h6><span>{{trans('app.import_links')}}</span></h6>
                <ul>
                    <li>
                        <a href="{{ route('news.index') }}">{{trans('app.news')}}</a>
                    </li>
                    <li>
                        <a href="{{ route('videos.index') }}">{{trans('app.videos')}}</a>
                    </li>
                    <li>
                        <a href="{{ route('awards.index') }}">{{trans('app.awards')}}</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-3 col-sm-4 col-xs-12">
                <h6><span>{{trans('app.for_help')}}</span></h6>
                <ul>
                    <li>
                        <a href="{{ route('instractions.index') }}">{{trans('app.instractions')}}</a>
                    </li>
                    <li>
                        <a href="{{ route('terms') }}">{{trans('app.terms_condition')}}</a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}">{{trans('app.contact_us')}}</a>
                    </li>
                </ul>
            </div>
            <div class="col-sm-3" style="overflow: hidden;">
                <h6><span>{{trans('app.download_app')}}</span></h6>
                <div class="store">
                    <p>{{trans('app.system_ios_android')}}</p>
                    <ul class="list-inline">
                        <li><a href="#"><i class="fa fa-android"></i></a></li>
                        <li><a href="#"><i class="fa fa-apple"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /.top-footer -->
    </div>
</footer>
@include('site.layouts.footer_bottom')        
<!-- Scroll Top Button -->
<button class="scroll-top tran3s">
    <i class="fa fa-angle-up" aria-hidden="true"></i>
</button>