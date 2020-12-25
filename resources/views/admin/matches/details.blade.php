@extends('admin.layouts.app')
@section('title') {{trans('app.details')}} 
@stop
@section('head_content')
@stop
@section('content')





<div class="col-xs-12">
    {{trans('app.week')}} : {{$match->week}}<br>
    {{trans('app.date')}} : {{$match->date}}<br>
</div>



<table class="table table-striped">
    <thead>
    <th style="width: 4%" colspan="2"></th>
        <th style="width: 8%"></th>
        <th style="width: 44%"><center><h3>{{$match->teams_first->name}} ({{$match->teams_first->code}})</h3><br>
        ({{$match->first_type}})<br>
            <h3>
            {{$match->first_goon}}
            </h3><center/></th>
        <th style="width: 44%"><center><h3>{{$match->teams_second->name}} ({{$match->teams_second->code}})</h3><center/><br>
        ({{$match->second_type}})<br>
            <h3>
            {{$match->second_goon}}
            </h3></th>
    </thead>
    <tbody>

    </tbody>
</table>



<table class="table table-striped">
    <thead>
        <th style="width: 4%"><center>min</center></th>
        <th style="width: 8%"><center>type</center></th>
        <th style="width: 44%"><center>result</center></th>
        <th style="width: 44%"><center>result</center></th>
    </thead>
    <tbody>
        @foreach ($details as $row)
        <tr>
            @if ($row->team_id != null)

            <td><center>{{$row->lengthMin}}</center></td>
            <td><center>{{ $row->type }}
             @if($row->type == 'card')
                    @if ($row->yellow_cart ==1)
                    (yellow)
                    @elseif($row->red_cart ==1)
                    (red)
                    @endif
            @endif

            @if($row->type == 'goal')
            ({{$row->type_state}})
            @endif
            </center></td>
            @if ($row->team_id == $match->first_team_id)
            <td><center> @if($row->type == 'substitute')
            <p>[in]: {{$row->player->name}}  </p><p> [out]: {{$row->off_player->name}}</p>
                    @else
                    {{$row->player->name}}
                        @if($row->type != 'goal')
                        ({{$row->reason}})
                        @endif
                    @endif
                </center></td>
            <td><center>-</center></td>
            @elseif($row->team_id == $match->second_team_id)
            <td><center>-</center></td>
            <td><center> @if($row->type == 'substitute')
                    <p>[in]: {{$row->player->name}} </p><p> [out]: {{$row->off_player->name}}
                    </p>
                    @elseif($row->type == 'goal')
                    {{$row->player->name}}
                    @else
                    {{$row->player->name}}
                        @if($row->type != 'goal')
                        ({{$row->reason}})
                        @endif
                    @endif
                </center></td>
            @endif
            
            @endif
        </tr>
        @endforeach


    </tbody>
</table>





@stop
@section('after_foot')
@stop