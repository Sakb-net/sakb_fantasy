@extends('site.layouts.app')
@section('content')
@include('site.layouts.page_title')
<section class="section-padding wow fadeInUp">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="filter">
                    <div class="all-fix">
                        <div class="col-md-4 col-sm-6">
                            <label class="control-label">عرض حسب الجولة:</label>
                            <select class="form-control ">
                                <option>الجولة 1</option>
                                <option>الجولة 2</option>
                                <option>الجولة 3</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label class="control-label">عرض حسب علي أرضه أو لا:</label>
                            <select class="form-control ">
                                <option>علي أرضه</option>
                                <option>علي أرض الخصم</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label class="control-label">عرض حسب الفوز أو الخسارة:</label>
                            <select class="form-control ">
                                <option>الفوز</option>
                                <option>الخسارة</option>
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div id="table-wrapper">
                    <div id="table-scroll">
                        <table class="table text-center clubs-order">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><abbr title="مباريات ملعوبة">لعب</abbr></th>
                                    <th><abbr title="فوز">ف</abbr></th>
                                    <th><abbr title="تعادل">ت</abbr></th>
                                    <th><abbr title="خسارة">خ</abbr></th>
                                    <th><abbr title="أهداف له">له</abbr></th>
                                    <th><abbr title="أهداف عليه">عليه</abbr></th>
                                    <th><abbr title="فرق أهداف">فرق</abbr></th>
                                    <th><abbr title="نقاط">نقاط</abbr></th>
                                    <th>Form</th>
                                    <th><span>المباراة القادمة</span></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--
                                ==============
                                 repeat it 16 for all the clubs 
                                 ======================== -->
                                <tr>
                                    <th>
                                        <div class="order">1</div>
                                    </th>
                                    <td class="text-center">
                                        <div class="club-image">
                                            <img src="images/clubs-logos/8.png" alt="club-logo">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="club-name">الهلال</div>
                                    </td>
                                    <td>27</td>
                                    <td>19</td>
                                    <td>6</td>
                                    <td>2</td>
                                    <td>65</td>
                                    <td>23</td>
                                    <td>42</td>
                                    <td>63</td>
                                    <td>
                                        <div class="form">
                                            <div class="form-stauts win">ف</div>
                                            <a href="match-detailes.html" class="tooltipContainer" role="tooltip">
                                                <span class="tooltip-content">
                                                    <div class="matchAbridged">
                                                        <span class="matchInfo">الاثنين 14/12/2020 </span>
                                                        <span class="teamName">الهلال</span>
                                                        <span class="badge badge-image-container" data-widget="club-badge-image" data-size="20">
                                                        <img class="badge-image" src="images/clubs-logos/8.png" alt="club-logo">
                                                        </span>
                                                        <span class="score">2 <span>-</span>0</span>
                                                        <span class="badge badge-image-container" data-widget="club-badge-image" data-size="20">
                                                        <img class="badge-image" src="images/clubs-logos/3.png" alt="club-logo">
                                                        </span>
                                                        <span class="teamName">الوحدة</span>
                                                    </div>
                                                </span>
                                            </a>
                                        </div>
                                        <div class="form">
                                            <div class="form-stauts win">ف</div>
                                            <a href="match-detailes.html" class="tooltipContainer" role="tooltip">
                                                <span class="tooltip-content">
                                                    <div class="matchAbridged">
                                                        <span class="matchInfo">الاثنين 14/12/2020 </span>
                                                        <span class="teamName">الهلال</span>
                                                        <span class="badge badge-image-container" data-widget="club-badge-image" data-size="20">
                                                        <img class="badge-image" src="images/clubs-logos/8.png" alt="club-logo">
                                                        </span>
                                                        <span class="score">2 <span>-</span>0</span>
                                                        <span class="badge badge-image-container" data-widget="club-badge-image" data-size="20">
                                                        <img class="badge-image" src="images/clubs-logos/3.png" alt="club-logo">
                                                        </span>
                                                        <span class="teamName">الوحدة</span>
                                                    </div>
                                                </span>
                                            </a>
                                        </div>
                                        <div class="form">
                                            <div class="form-stauts win">ف</div>
                                            <a href="match-detailes.html" class="tooltipContainer" role="tooltip">
                                                <span class="tooltip-content">
                                                    <div class="matchAbridged">
                                                        <span class="matchInfo">الاثنين 14/12/2020 </span>
                                                        <span class="teamName">الهلال</span>
                                                        <span class="badge badge-image-container" data-widget="club-badge-image" data-size="20">
                                                        <img class="badge-image" src="images/clubs-logos/8.png" alt="club-logo">
                                                        </span>
                                                        <span class="score">2 <span>-</span>0</span>
                                                        <span class="badge badge-image-container" data-widget="club-badge-image" data-size="20">
                                                        <img class="badge-image" src="images/clubs-logos/3.png" alt="club-logo">
                                                        </span>
                                                        <span class="teamName">الوحدة</span>
                                                    </div>
                                                </span>
                                            </a>
                                        </div>
                                        <div class="form">
                                            <div class="form-stauts win">ف</div>
                                            <a href="match-detailes.html" class="tooltipContainer" role="tooltip">
                                                <span class="tooltip-content">
                                                    <div class="matchAbridged">
                                                        <span class="matchInfo">الاثنين 14/12/2020 </span>
                                                        <span class="teamName">الهلال</span>
                                                        <span class="badge badge-image-container" data-widget="club-badge-image" data-size="20">
                                                        <img class="badge-image" src="images/clubs-logos/8.png" alt="club-logo">
                                                        </span>
                                                        <span class="score">2 <span>-</span>0</span>
                                                        <span class="badge badge-image-container" data-widget="club-badge-image" data-size="20">
                                                        <img class="badge-image" src="images/clubs-logos/3.png" alt="club-logo">
                                                        </span>
                                                        <span class="teamName">الوحدة</span>
                                                    </div>
                                                </span>
                                            </a>
                                        </div>
                                        <div class="form">
                                            <div class="form-stauts drawn">ت</div>
                                            <a href="match-detailes.html" class="tooltipContainer" role="tooltip" data-toggle="tooltip">
                                                <span class="tooltip-content">
                                                    <div class="matchAbridged">
                                                        <span class="matchInfo">الاثنين 14/12/2020 </span>
                                                        <span class="teamName">الهلال</span>
                                                        <span class="badge badge-image-container" data-widget="club-badge-image" data-size="20">
                                                        <img class="badge-image" src="images/clubs-logos/8.png" alt="club-logo">
                                                        </span>
                                                        <span class="score">2 <span>-</span>0</span>
                                                        <span class="badge badge-image-container" data-widget="club-badge-image" data-size="20">
                                                        <img class="badge-image" src="images/clubs-logos/3.png" alt="club-logo">
                                                        </span>
                                                        <span class="teamName">الوحدة</span>
                                                    </div>
                                                </span>
                                            </a>
                                        </div>
                                        <div class="form">
                                            <div class="form-stauts lose">خ</div>
                                            <a href="match-detailes.html" class="tooltipContainer" role="tooltip">
                                                <span class="tooltip-content">
                                                    <div class="matchAbridged">
                                                        <span class="matchInfo">الاثنين 14/12/2020 </span>
                                                        <span class="teamName">الهلال</span>
                                                        <span class="badge badge-image-container" data-widget="club-badge-image" data-size="20">
                                                        <img class="badge-image" src="images/clubs-logos/8.png" alt="club-logo">
                                                        </span>
                                                        <span class="score">2 <span>-</span>0</span>
                                                        <span class="badge badge-image-container" data-widget="club-badge-image" data-size="20">
                                                        <img class="badge-image" src="images/clubs-logos/3.png" alt="club-logo">
                                                        </span>
                                                        <span class="teamName">الوحدة</span>
                                                    </div>
                                                </span>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="club-image">
                                            <img src="images/clubs-logos/5.png" alt="club-logo">
                                            <a href="match-detailes.html" class="tooltipContainer" role="tooltip">
                                                <span class="tooltip-content">
                                                    <div class="matchAbridged">
                                                        <span class="matchInfo">الاثنين 14/12/2020 </span>
                                                        <span class="teamName">الهلال</span>
                                                        <span class="badge badge-image-container" data-widget="club-badge-image" data-size="20">
                                                        <img class="badge-image" src="images/clubs-logos/8.png" alt="club-logo">
                                                        </span>
                                                        <time> 4:00 pm </time>
                                                        <span class="badge badge-image-container" data-widget="club-badge-image" data-size="20">
                                                        <img class="badge-image" src="images/clubs-logos/5.png" alt="club-logo">
                                                        </span>
                                                        <span class="teamName">الشباب</span>
                                                    </div>
                                                </span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="#Q1" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                            <div class="arrow"><i class="fa fa-chevron-down"></i></div>
                                        </a>
                                    </td>
                                </tr>
                                <tr id="Q1" class="expandable panel-collapse collapse">
                                    <td colspan="14">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <a href="#" class="expandableTeam">
                                                    <img src="images/clubs-logos/8.png" alt="club-logo">
                                                    <span class="teamName">الهلال</span>
                                                </a>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="col-md-4">
                                                    <div class="matchAbridged">
                                                        <span class="matchInfo"><strong>أخر نتيجة </strong>- الاثنين 14/12/2020 </span>
                                                        <span class="teamName">الهلال</span>
                                                        <span class="badge badge-image-container" data-widget="club-badge-image" data-size="20">
                                                        <img class="badge-image" src="images/clubs-logos/8.png" alt="club-logo">
                                                        </span>
                                                        <span class="score">2 <span>-</span>0</span>
                                                        <span class="badge badge-image-container" data-widget="club-badge-image" data-size="20">
                                                        <img class="badge-image" src="images/clubs-logos/3.png" alt="club-logo">
                                                        </span>
                                                        <span class="teamName">الوحدة</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="matchAbridged">
                                                        <span class="matchInfo"><strong>المباراة القادمة </strong>- الاثنين 14/12/2020 </span>
                                                        <span class="teamName">الهلال</span>
                                                        <span class="badge badge-image-container" data-widget="club-badge-image" data-size="20">
                                                        <img class="badge-image" src="images/clubs-logos/8.png" alt="club-logo">
                                                        </span>
                                                        <time> 07:00 pm </time>
                                                        <span class="badge badge-image-container" data-widget="club-badge-image" data-size="20">
                                                        <img class="badge-image" src="images/clubs-logos/3.png" alt="club-logo">
                                                        </span>
                                                        <span class="teamName">الوحدة</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <a href="#" class="butn float-left">الذهاب لموقع النادي</a>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <hr>
                                                <h3>مخطط الأداء:</h3>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
<script type="text/javascript" src="{{ asset('js/site/ranking_eldwry.js?v='.config('version.version_script')) }}"></script>

<script>
$(document).ready(function () {    
    
});
</script>
@stop  