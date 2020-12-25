<!-- <a class="next-game"><span class="fa fa-chevron-left"></span></a>
<a class="prev-game"><sp -->an class="fa fa-chevron-right"></span></a>
<div id="game-week">
@foreach($subeldwry_points as $key_point=>$val_point)
<div class="item">
    <div class="section-head">
        <h4>{{$val_point['name']}}</h4>
    </div>
    <div class="mypoints-container">
        <div class="final-points">
            <p>{{trans('app.final_points')}}</p>
            <p class="num">{{$val_point['final_point']}}</p>
        </div>
        <div class="points-detailes">
            <div class="col-md-6">
                <table class="table">
                    <tr>
                        <td>{{trans('app.average_points')}}: </td>
                        <td>{{$val_point['avg_point']}}</td>
                    </tr>
                    <tr>
                        <td>{{trans('app.heighest_point')}}:</td>
                        <td>{{$val_point['heigh_point']}}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table">
                    <tr>
                        <td>{{trans('app.sort_game_week')}}: </td>
                        <td>{{$val_point['sort_gwla']}}</td>
                    </tr>
                    <tr>
                        <td>{{trans('app.transfers')}}:</td>
                        <td>{{$val_point['transfer']}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endforeach
</div>