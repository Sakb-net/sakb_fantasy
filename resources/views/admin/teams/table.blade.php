<thead>
    <tr>
        <th>ID</th>

        <!-- <th>{{trans('app.eldwry')}}</th> -->
        <th>{{trans('app.name')}}</th>
        
        <th>{{trans('app.content')}}</th>
        @if($team_edit == 1)
        <th>{{trans('app.state')}}</th>
        @endif
        @if($team_edit == 1  || $team_show == 1 || $team_delete == 1 )
        <th>{{trans('app.settings')}}</th>
        @endif
    </tr>
</thead>

@foreach ($data as $key => $team)

<tr>

    <td>{{ $team->id }}</td>
    <!-- <td>{!!$team->eldwry->name!!}</td> -->

    <td>{!! $team->name !!}</td>
    
    <td>{{\Illuminate\Support\Str::limit($team->content, $limit = 80, $end = '...')}}</td>
    @if($team_edit == 1)
    <td>
    @if($team->is_active == 0)
        <a class="teamstatus fa fa-remove btn  btn-danger"  data-id='{{ $team->id }}' data-status='1' ></a>
    @else
        <a class="teamstatus fa fa-check btn  btn-success"  data-id='{{ $team->id }}' data-status='0' ></a>
    @endif
        
    </td>
    @endif
    @if($team_edit == 1  || $team_show == 1 || $team_delete == 1)
    <td>
       
        @if($team_edit == 1)
        <a class="btn btn-primary fa fa-edit"  data-toggle="tooltip" data-placement="top" data-title=" تعديل" href="{{ route('admin.clubteams.edit',$team->id) }}"></a>
        @endif
        @if($team_delete == 1)

        <a id="delete" data-id='{{ $team->id }}' data-name='{{ $team->name }}' data-toggle="tooltip" data-placement="top" data-title="حذف الفريق رئيسى" class="btn btn-danger fa fa-trash"></a>

        {!! Form::open(['method' => 'DELETE','route' => ['admin.clubteams.destroy', $team->id],'style'=>'display:inline']) !!}

        {!! Form::submit('Delete', ['class' => 'hide btn btn-danger delete-btn-submit','data-delete-id' => $team->id]) !!}

        {!! Form::close() !!}

        @endif
        
    </td>
    @endif
</tr>

@endforeach

