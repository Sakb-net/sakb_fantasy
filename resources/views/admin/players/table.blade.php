<thead>
    <tr>

        <th>ID</th>
        <th>{{trans('app.team')}}</th>
        <th>{{trans('app.name')}}</th>
        <th>{{trans('app.num_t_shirt')}}</th>
        <th>{{trans('app.location')}}</th>
        <th>{{trans('app.price')}}</th>
        @if($player_edit == 1)
        <th>{{trans('app.state')}}</th>
        @endif
        @if($player_edit == 1  || $player_show == 1 || $player_delete == 1 )
        <th>{{trans('app.settings')}}</th>
        @endif
    </tr>
</thead>

@foreach ($data as $key => $player)

<tr>

    <td>{{ $player->id }}</td>
    <td>{!! $player->teams->name !!}</td>
    <td>{!! $player->name !!}</td>
    <td>{{$player->num_t_shirt}}</td>
    <td>{!! $player->location_player->value_ar !!}</td>
    <td>{{$player->cost}}</td>
    @if($player_edit == 1)
    <td>
    @if($player->is_active == 0)
        <a class="playerstatus fa fa-remove btn  btn-danger"  data-id='{{ $player->id }}' data-status='1' ></a>
    @else
        <a class="playerstatus fa fa-check btn  btn-success"  data-id='{{ $player->id }}' data-status='0' ></a>
    @endif
        
    </td>
    @endif
    @if($player_edit == 1  || $player_show == 1 || $player_delete == 1)
    <td>
        <!--if  $player_show == 1-->
        @if($player->type=="team")
       <!--fa-eye-slash-->
        <a class="btn btn-info fa fa-cube"  data-toggle="tooltip" data-placement="top" data-title="{{trans('app.show')}} {{trans('app.players')}}" href="{{ route('admin.players.show',$player->id) }}"></a>
        @endif
        @if($player_edit == 1)
        <a class="btn btn-primary fa fa-edit"  data-toggle="tooltip" data-placement="top" data-title=" {{trans('app.update')}}" href="{{ route('admin.players.edit',$player->id) }}"></a>
        @endif
        @if($player_delete == 1)

        <a id="delete" data-id='{{ $player->id }}' data-name='{{ $player->name }}' data-toggle="tooltip" data-placement="top" data-title="{{trans('app.delete')}}" class="btn btn-danger fa fa-trash"></a>

        {!! Form::open(['method' => 'DELETE','route' => ['admin.players.destroy', $player->id],'style'=>'display:inline']) !!}

        {!! Form::submit('Delete', ['class' => 'hide btn btn-danger delete-btn-submit','data-delete-id' => $player->id]) !!}

        {!! Form::close() !!}

        @endif
        
    </td>
    @endif
</tr>

@endforeach

