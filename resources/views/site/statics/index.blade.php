@extends('site.layouts.app')
@section('content')
@include('site.layouts.page_title')
<section class="section-padding wow fadeInUp">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="div_filter_side">
                    <div class="filter">
                        <h1>{{trans('app.choose_players')}}</h1>
                        @include('site.games.side_bar')
                    </div> 
                </div>
            </div>
        </div>
    </div>
</section>
@include('site.home.sponsers')
@include('site.games.all_modal')
@endsection

@section('after_head')
@stop  
@section('after_foot')
@include('site.layouts.script.game_js')
@include('site.layouts.script.match_js')
<script>
$(document).ready(function () {
    GetDataPlayer_Public_Statistics('',1,1);
});
</script>
@stop  



<!-- <thead><tr>
        <th></th>
        <th>اللاعب</th>
        <th>سعره الحالي</th>
        <th>سعر بيعه</th>
        <th>سعر شراءه</th>
        <th>form</th>
        <th>إجمالي النقاط</th>
        <th>fix</th>
    </tr>
</thead>
<tbody>                                                
    <tr>
        <th>
            <a class="control info" data-toggle="modal" data-target="#infoModal">
                <i class="fa fa-exclamation-triangle text-danger"></i>
            </a>
        </th>
        <td>
             <a data-toggle="modal" data-target="#myModal">
                <div class="list-player">
                    <div class="image">
                        <img src="images/full-shirt.png" alt="">
                    </div>
                    <div class="body">
                        <div class="name">محمد صلاح</div>
                        <div class="text">
                            <span>ليفربول</span>
                            <span>وسط</span>
                        </div>
                    </div>
                </div>
            </a>
        </td>
        <td>5.6</td>
        <td>5.6</td>
        <td>5.6</td>
        <td>4.7</td>
        <td>14</td>
        <td>WOL (H)</td>
    </tr> -->