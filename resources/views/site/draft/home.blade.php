@extends('site.layouts.app')
@section('content')
@include('site.draft.menu')
<section class="section-padding wow fadeInUp mainSection">
    <div class="container">
        <p>لقد قمت بتسجيل الدخول باسم  {{auth::user()->name}}.</p>
        <div class="row">
            <div class="col-md-4">
                <div class="draft-home">
                    <div class="draft-step" style="background-image: url ('/images/draft/home-step-1.jpg');"></div>
                    <div class="draft-step-content">
                        <h2>إنشاء أو الالتحاق بالدوري</h2>
                        <p>نافس أصدقائك أو عائلتك عن طريق إنشاء او الانضمام لدوري</p>
                        <a id="showStartPage">العب الآن</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="draft-home">
                    <div class="draft-step" style="background-image: url('/images/draft/home-step-2.jpg');"></div>
                    
                    <div class="draft-step-content">
                        <h2>قم بعمل الكشافة الخاصة بك</h2>
                        <p>قم ببناء قائمة مراقبة بأهداف اللاعبين استعدادًا ليوم المسودة.</p>
                        <a id="showStartPage1">العب الآن</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="draft-home">
                    <div class="draft-step" style="background-image: url('/images/draft/home-step-3.jpg');"></div>
                    <div class="draft-step-content">
                        <h2>انضم للدرافت</h2>
                        <p>خذ دورك للاختيار من المسبح وقم ببناء فريق من 15 لاعبًا فريدًا لك.</p>
                        <a id="showStartPage2">العب الآن</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-padding startSection">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <div class="draft-start">
                    <h2>التحق أو انضم لدوري</h2>
                    <p>أنشئ دوريًا خاصًا جديدًا وادعُ أصدقائك أو انضم إلى آخرين في دوري خاص موجود.</p>
                    <a href="{{route('draft.joinLeaugeDraft')}}" class="butn butn-bg">{{trans('app.join_a_league')}}</a>
                    <a href="{{route('draft.createLeaugeDraft')}}" class="butn butn-bg">{{trans('app.create_a_league')}}</a>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="draft-start">
                    <h2>جديد في درافت الدوري السعودي؟</h2>
                    <p>لماذا لا تشارك في مسودة وهمية وتتعلم كيف تعمل المسودة؟</p>
                    <a href="{{route('draft.createDraft')}}" class="butn butn-bg"> انضم للدرافت</a>
                </div>
            </div>
        </div>
    </div>
</section>        
@endsection
@section('after_head')

@stop  

@section('after_foot')
<script src="{{ asset('js/site/draft/home.js?v='.config('version.version_script')) }}') }}"></script>
@include('site.draft.pusher')
@stop