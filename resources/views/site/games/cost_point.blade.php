<p><strong>{{$msg_condition}}</strong></p>
<div class="DeadlineBar">
    <h3>{{trans('app.gameweek')}} {{$start_dwry->num_week}}</h3><br>
    <h3>{{trans('app.transfer_dealline')}}</h3>
    <time>{{$end_change_date}}</time>
    <!--<time>الجمعة 23 أغسطس 20:00</time>-->
    <br><h3>{{trans('app.msg_choose_player')}}</h3>
</div>
<div class="after-DeadlineBar"></div>
@if(isset($substitutes_points))
    <div class="row p20 text-center">
        <div class="col-md-4 col-sm-4 col-xs-4">
            <h3>{{trans('app.free_weekgamesubstitute')}}</h3>
            <div class="num"> <span class="free_weekgamesubstitute"> {{$count_free_weekgamesubstitute}} </span></div>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-4">
            <h3>{{trans('app.cost')}}</h3>
            <div class="num switch_points"><span class="substitutes_points"> {{$substitutes_points}} </span> pt</div>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-4">
            <h3>{{trans('app.remaining_amount')}}</h3>
            <div class="num money">
                <span class="pay_total_cost"> {{$pay_total_cost}} </span> 
                <!-- / <span class="total_cost_play"> {{$total_cost_play}} </span -->

                <!-- <span class="remide_sum_cost"> {{$remide_sum_cost}} </span> -->
            </div>
        </div>
    </div>
@else
    <div class="row p20 text-center">
        <div class="col-md-6 col-sm-6 col-xs-6 p0">
            <a  class="butn butn-bg auto_selection_player"><i class="fa fa-random"></i>{{trans('app.auto_selection')}}</a>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 p0">
            <a class="butn reset_all_player"><i class="fa fa-refresh"></i>{{trans('app.reset_all')}}</a>
        </div>
    </div>
    <div class="row p20 text-center">
        <div class="col-md-6 col-sm-6 col-xs-6">
            <h3>{{trans('app.number_players')}}</h3>
            <div class="num"> <span class="total_team_play"> {{$total_team_play}} </span> / {{$total_team}}</div>
        </div>  
        <div class="col-md-6 col-sm-6 col-xs-6">
            <h3>{{trans('app.remaining_amount')}}</h3>
            <div class="num money">
                <span class="pay_total_cost"> {{$pay_total_cost}} </span>
                <!-- / <span class="total_cost_play"> {{$total_cost_play}} </span> -->
                <!--<span class="remide_sum_cost"> {{$remide_sum_cost}} </span> -->
             </div>
        </div>
    </div>
@endif