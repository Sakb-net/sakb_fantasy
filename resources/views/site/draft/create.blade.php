@extends('site.layouts.app')
@section('content')
<div class="myinner-banner">
                <div class="opacity container p-b-0">
                    <h2>The Draft</h2>
                    <div class="game-menu">
                        <a class="butn" id="mainPage">الرئيسية</a>
                        <a class="butn active" id="startPage">ابدأ</a>
                        <a class="butn" href="{{route('game.index')}}">الفانتازي</a>
                    </div>
                </div>
                <!-- /.opacity -->
            </div>
            <!-- 
                =============================================
                    draft
                ============================================== 
                -->
            <section class="section-padding mainSection">
                <div class="container">
                    <p>لقد قمت بتسجيل الدخول باسم {{auth::user()->name}}.</p>
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
                                <a href="{{route('draft.joinLeaugeDraft')}}" class="butn butn-bg">انضم إلي دوري </a>
                                <a href="{{route('draft.createLeaugeDraft')}}" class="butn butn-bg">إنشاء دوري جديد</a>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="draft-start">
                                <h2>جديد في درافت الدوري السعودي؟</h2>
                                <p>لماذا لا تشارك في مسودة وهمية وتتعلم كيف تعمل المسودة؟</p>
                                <a id="joinDraftButton" class="butn butn-bg"> انضم للدرافت</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>        



            <section class="section-padding wow fadeInUp joinDraftSection">
                <div class="container">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3>التحق ب الدرافت</h3>
                                </div>

                                <div id='formErorr' class='alert alert-danger text-center mb-10' style="display:none;">
                                <p id="errorText"></p>
                                </div>

                                <div class="panel-body">
                                    <form id="joinDraft" method="post">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>اسم الفريق <span> (20 حرف علي الأكثر)</span></label>
                                                <input name="teamName" type="text" maxlength="20" class="form-control" value="" required="" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>إشعارات اللعبة:</label>
                                                <p class="font-normal"> تلقي رسائل بريد إلكتروني حول المسودة والدوري والصفقات. يمكنك إلغاء الاشتراك في أي وقت.</p>
                                                <div class="team-checkbox">
                                                    <input type="checkbox" name="followed" id="followed">
                                                    <label for="followed" class="checkbox-text">نعم ، أرغب في تلقي إشعارات اللعبة عبر البريد الإلكتروني</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>حجم الدوري:</label>
                                                <select class="form-control" name="dawrySize">
                                                    <option value="4">4</option>
                                                    <option value="6">6</option>
                                                    <option value="8">8</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="team-checkbox">
                                                    <input type="checkbox" name="condition" id="condition">
                                                    <label for="condition" class="checkbox-text">قرأت <a href="">الشروط و الأحكام</a></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <input type="submit" class="butn butn-bg m0" value="التحق بالدرافت" placeholder="">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>     



@endsection
@section('after_head')

@stop  


@section('after_foot')

<script src="{{ asset('js/site/draft/createDraft.js') }}"></script>

@stop