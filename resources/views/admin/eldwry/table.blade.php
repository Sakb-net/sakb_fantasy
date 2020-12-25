<thead>
    <tr>

        <th>ID</th>

        <th>{{trans('app.name')}}</th>
        
        <th>{{trans('app.price')}}</th>
        @if($eldwry_edit == 1)
        <th>{{trans('app.state')}}</th>
        @endif
        @if($eldwry_edit == 1  || $eldwry_show == 1 || $eldwry_delete == 1 )
        <th>{{trans('app.settings')}}</th>
        @endif
    </tr>
</thead>

@foreach ($data as $key => $eldwry)

<tr>

    <td>{{ $eldwry->id }}</td>

    <td>{{ $eldwry->name }}</td>
    
    <td>{{$eldwry->cost}}</td>
    @if($eldwry_edit == 1)
    <td>
    @if($eldwry->is_active == 0)
        <a class="eldwrystatus fa fa-remove btn  btn-danger"  data-id='{{ $eldwry->id }}' data-status='1' ></a>
    @else
        <a class="eldwrystatus fa fa-check btn  btn-success"  data-id='{{ $eldwry->id }}' data-status='0' ></a>
    @endif
        
    </td>
    @endif
    @if($eldwry_edit == 1  || $eldwry_show == 1 || $eldwry_delete == 1)
    <td>
        <!--if  $eldwry_show == 1-->
        @if($eldwry_edit == 1)
        <a class="btn btn-primary fa fa-edit"  data-toggle="tooltip" data-placement="top" data-title=" تعديل" href="{{ route('admin.eldwry.edit',$eldwry->id) }}"></a>
        @endif

        <a id="delete" data-id='{{ $eldwry->id }}' data-name='{{ $eldwry->name }}' data-toggle="tooltip" data-placement="top" data-title="حذف الدورى " class="btn btn-danger fa fa-trash"></a>

        {!! Form::open(['method' => 'DELETE','route' => ['admin.eldwry.destroy', $eldwry->id],'style'=>'display:inline']) !!}

        {!! Form::submit('Delete', ['class' => 'hide btn btn-danger delete-btn-submit','data-delete-id' => $eldwry->id]) !!}

        {!! Form::close() !!}

        
    </td>
    @endif
</tr>

@endforeach

