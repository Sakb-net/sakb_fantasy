<!-- <thead>
    <tr>

        <th>ID</th>
        <th>{{trans('app.eldwry')}}</th>
        <th>{{trans('app.name')}}</th>
        
        @if($subeldwry_edit == 1)
        <th>{{trans('app.state')}}</th>
        @endif
        @if($subeldwry_edit == 1  || $subeldwry_show == 1 || $subeldwry_delete == 1 )
        <th>{{trans('app.settings')}}</th>
        @endif
    </tr>
</thead> -->




<!-- @foreach ($data as $key => $subeldwry)

<tr>

    <td>{{ $subeldwry->id }}</td>
    <td>{{ $subeldwry->eldwry->name }}</td>

    <td>{{ $subeldwry->name }}</td>
    
    @if($subeldwry_edit == 1)
    <td>
    @if($subeldwry->is_active == 0)
        <a class="subeldwrystatus fa fa-remove btn  btn-danger"  data-id='{{ $subeldwry->id }}' data-status='1' ></a>
    @else
        <a class="subeldwrystatus fa fa-check btn  btn-success"  data-id='{{ $subeldwry->id }}' data-status='0' ></a>
    @endif
        
    </td>
    @endif
    @if($subeldwry_edit == 1  || $subeldwry_show == 1 || $subeldwry_delete == 1)
    <td>
        @if($subeldwry_edit == 1)
        <a class="btn btn-primary fa fa-edit" data-toggle="tooltip" data-placement="top" data-title="تعديل "  href="{{ route('admin.subeldwry.edit',$subeldwry->id) }}"></a>
        @endif
        @if($subeldwry_delete == 1)

        <a id="delete" data-id='{{ $subeldwry->id }}' data-name='{{ $subeldwry->name }}'  data-toggle="tooltip" data-placement="top" data-title="حذف  الجولة" class="btn btn-danger fa fa-trash"></a>


        <a class="btn btn-primary fa fa-eye" data-toggle="tooltip" data-placement="top" data-title="تفاصيل "  href="{{ route('admin.subeldwry.details',$subeldwry->id) }}"></a>

        {!! Form::open(['method' => 'DELETE','route' => ['admin.subeldwry.destroy', $subeldwry->id],'style'=>'display:inline']) !!}

        {!! Form::submit('Delete', ['class' => 'hide btn btn-danger delete-btn-submit','data-delete-id' => $subeldwry->id]) !!}

        {!! Form::close() !!}

        @endif
        
    </td>
    @endif
</tr>

@endforeach -->

