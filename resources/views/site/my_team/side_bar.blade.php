<div class="sidebar">
    <h2>{{ Auth::user()->display_name }}</h2>
    <!-- Points/Rankings-->
    <div class="panel panel-default">
        <div class="panel-heading">{{trans('app.team')}} {{ $team_name }}</div>
        <div class="panel-body">
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="2">{{trans('app.points_rank')}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{trans('app.total_points')}}:</td>
                        <td>{{$sum_total_points}}</td>
                    </tr>
                    <tr>
                        <td>{{trans('app.overall_rank')}}:</td>
                        <td>{{$sort_final_users}}</td>
                    </tr>
                    <tr>
                        <td>{{trans('app.total_players')}}:</td>
                        <td>{{$count_total_users}}</td>
                    </tr>
                    @if($sum_total_subeldwry)
                    <tr>
                        <td>{{trans('app.game_week_points')}}:</td>
                        <td>{{$sum_total_subeldwry}}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <!-- favourite team-->
    <div class="panel panel-default">
        <div class="panel-heading">{{trans('app.favorite_team')}}</div>
        <div class="panel-body">
            <img class="fav-club" src="{{$image_best_team}}">
        </div>
    </div>
    <!-- Transfers and Finance-->
    <div class="panel panel-default">
        <div class="panel-heading">{{trans('app.changes')}}</div>
        <div class="panel-body">
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="2">{{trans('app.points_rank')}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{trans('app.game_week_changes')}}:</td>
                        <td>{{$game_week_changes}}</td>
                    </tr>
                    <tr>
                        <td>{{trans('app.total_changes')}}:</td>
                        <td>{{$total_changes}}</td>
                    </tr>
                    <tr>
                        <td>{{trans('app.defenses_amount')}}:</td>
                        <td>{{$remide_sum_cost}} {{trans('app.SAR')}}</td>
                    </tr>
                    <tr>
                        <td>{{trans('app.remaining_amount')}}:</td>
                        <td>{{$pay_total_cost}} {{trans('app.SAR')}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>  