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
                            <tbody class="draw_ranking_eldwry"> </tbody>
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
    Load_ranking_eldwry();
});
</script>
@stop  