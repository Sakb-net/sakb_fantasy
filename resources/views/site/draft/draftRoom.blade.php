@extends('site.layouts.app')
@section('content')
<div class="myinner-banner">
                <div class="opacity container p-b-0">
                    <h2>The Draft</h2>
                    <div class="game-menu">
                        <a class="butn active" id="mainPage">الرئيسية</a>
                        <a class="butn" href="draft-room.html">غرفة الدرافت</a>
                        <a class="butn" href="draft-start.html">دوري جديد</a>
                        <a class="butn" href="{{route('game.index')}}">الفانتازي</a>
                    </div>
                </div>
                <!-- /.opacity -->
            </div>


            <section class="section-padding wow fadeInUp">
                <div class="container">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="section-head">
                                <h4>الفريق الحالي: <span class="black">فريق الأهلاوية</span></h4>
                            </div>
                            <h3></h3>
                            <div class="col-md-6">
                                <div class="status">
                                    <h3>بناء قائمة المشاهدة الخاصة بك</h3>
                                    <a href="draft-room.html" class="butn">أدخل غرفة الدرافت</a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="draft-countdown">
                                    <h3>العد التنازلي للدرافت</h3>
                                    <!--Countdown Timer-->
                                    <div class="time-counter">
                                        <div class="time-countdown clearfix" data-countdown="2021/01/06 22:00:00"></div>
                                    </div>
                                    <p class="draft-start-date">تاريخ الدرافت : 20/12/2020 12:30 pm</p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="draft-container">
                                    <h2>درافت 214541</h2>
                                    <div class="col-md-6">
                                        <h3>المنضمين للدوري:<span>4</span></h3>
                                        <table class="table draft-l-info">
                                            <tr>
                                                <th>الفريق</th>
                                                <th>المدير</th>
                                            </tr>
                                            <tr>
                                                <td>فريق الأهلاوية </td>
                                                <td>فايز القناوي</td>
                                            </tr>
                                            <tr>
                                                <td>فريق إيمان </td>
                                                <td>إيمان العباسي</td>
                                            </tr>
                                            <tr>
                                                <td>فريق الأهلاوية </td>
                                                <td>فايز القناوي</td>
                                            </tr>
                                            <tr>
                                                <td>فريق إيمان </td>
                                                <td>إيمان العباسي</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h3>معلومات الدوري</h3>
                                        <table class="table draft-l-info">
                                            <tr>
                                                <td>مدير الدوري: </td>
                                                <td>لا يوجد</td>
                                            </tr>
                                            <tr>
                                                <td>تاريخ الدرافت: </td>
                                                <td>20/12/2020</td>
                                            </tr>
                                            <tr>
                                                <td>وقت الدرافت: </td>
                                                <td>12:30 pm</td>
                                            </tr>
                                            <tr>
                                                <td>جولة البداية: </td>
                                                <td>الجولة 15</td>
                                            </tr>
                                            <tr>
                                                <td>الحد الأدني للدوري: </td>
                                                <td>4</td>
                                            </tr>
                                            <tr>
                                                <td>الحد الأقصي للدوري: </td>
                                                <td>8</td>
                                            </tr>
                                            <tr>
                                                <td>إحراز النقاط: </td>
                                                <td>head to head</td>
                                            </tr>
                                            <tr>
                                                <td>الوقت المحدد للاختيار: </td>
                                                <td>90 ثانية</td>
                                            </tr>
                                            <tr>
                                                <td>صفقات اللاعب: </td>
                                                <td>كلها</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- sidebar -->
                        <div class="col-md-3">
                            <div class="sidebar">
                                <h2>فاطمة شداد</h2>
                                <!-- Points/Rankings-->
                                <div class="panel panel-default">
                                    <div class="panel-heading">فريق الأهلاوية</div>
                                    <div class="panel-body">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th colspan="2">النقاط و المرتبة</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>نقاط الجولة:</td>
                                                    <td>28</td>
                                                </tr>
                                                <tr>
                                                    <td>إجمالي النقاط:</td>
                                                    <td>66</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <a class="butn-inline">للذهاب للسجل</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- Leagues -->
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th colspan="2">الدوريات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="2">
                                                        <a class="butn-inline">إنشاء او الانضمام لدوري</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- Transactions-->
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th colspan="2">التغييرات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>تغييرات الجولة:</td>
                                                    <td>1</td>
                                                </tr>
                                                <tr>
                                                    <td>إجمالي التغييرات:</td>
                                                    <td>2</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <a class="butn-inline">الذهاب للتغييرات</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


@endsection
@section('after_head')

@stop  