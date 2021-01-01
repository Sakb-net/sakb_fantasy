<thead>
    <tr>

        <th>ID</th>

        @if($ranking_eldwry_edit == 1)
        <th>{{trans('app.state')}}</th>
        @endif
        @if($ranking_eldwry_edit == 1  || $ranking_eldwry_show == 1 || $ranking_eldwry_delete == 1 )
        <th>{{trans('app.settings')}}</th>
        @endif
    </tr>
</thead>

@foreach ($data as $key => $ranking_eldwry)

<tr>

    <td>{{ $ranking_eldwry->id }}</td>

    @if($ranking_eldwry_edit == 1)
    <td>
    @if($ranking_eldwry->is_active == 0)
        <a class="ranking_eldwrystatus fa fa-remove btn  btn-danger"  data-id='{{ $ranking_eldwry->id }}' data-status='1' ></a>
    @else
        <a class="ranking_eldwrystatus fa fa-check btn  btn-success"  data-id='{{ $ranking_eldwry->id }}' data-status='0' ></a>
    @endif
        
    </td>
    @endif
    @if($ranking_eldwry_edit == 1  || $ranking_eldwry_show == 1 || $ranking_eldwry_delete == 1)
    <td>
        <!--if  $ranking_eldwry_show == 1-->
        @if($ranking_eldwry_edit == 1)
        <a class="btn btn-primary fa fa-edit"  data-toggle="tooltip" data-placement="top" data-title=" تعديل" href="{{ route('admin.ranking_eldwry.edit',$ranking_eldwry->id) }}"></a>
        @endif

        <a id="delete" data-id='{{ $ranking_eldwry->id }}' data-name='{{ $ranking_eldwry->name }}' data-toggle="tooltip" data-placement="top" data-title="حذف الدورى " class="btn btn-danger fa fa-trash"></a>

        {!! Form::open(['method' => 'DELETE','route' => ['admin.ranking_eldwry.destroy', $ranking_eldwry->id],'style'=>'display:inline']) !!}

        {!! Form::submit('Delete', ['class' => 'hide btn btn-danger delete-btn-submit','data-delete-id' => $ranking_eldwry->id]) !!}

        {!! Form::close() !!}

        
    </td>
    @endif
</tr>

@endforeach

