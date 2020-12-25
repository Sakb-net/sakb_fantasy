<thead>
    <tr>

        <th>ID</th>

        <th>{{trans('app.name')}}</th>
        
        <th>{{trans('app.price')}}</th>
        @if($opta_edit == 1)
        <th>{{trans('app.state')}}</th>
        @endif
        @if($opta_edit == 1  || $opta_show == 1 || $opta_delete == 1 )
        <th>{{trans('app.settings')}}</th>
        @endif
    </tr>
</thead>

@foreach ($data as $key => $opta)

<tr>

    <td>{{ $opta->id }}</td>

    <td>{{ $opta->name }}</td>
    
    <td>{{$opta->cost}}</td>
    @if($opta_edit == 1)
    <td>
    @if($opta->is_active == 0)
        <a class="optastatus fa fa-remove btn  btn-danger"  data-id='{{ $opta->id }}' data-status='1' ></a>
    @else
        <a class="optastatus fa fa-check btn  btn-success"  data-id='{{ $opta->id }}' data-status='0' ></a>
    @endif
        
    </td>
    @endif
    @if($opta_edit == 1  || $opta_show == 1 || $opta_delete == 1)
    <td>
        <!--if  $opta_show == 1-->
        @if($opta_edit == 1)
        <a class="btn btn-primary fa fa-edit"  data-toggle="tooltip" data-placement="top" data-title=" تعديل" href="{{ route('admin.opta.edit',$opta->id) }}"></a>
        @endif

        <a id="delete" data-id='{{ $opta->id }}' data-name='{{ $opta->name }}' data-toggle="tooltip" data-placement="top" data-title="حذف الدورى " class="btn btn-danger fa fa-trash"></a>

        {!! Form::open(['method' => 'DELETE','route' => ['admin.opta.destroy', $opta->id],'style'=>'display:inline']) !!}

        {!! Form::submit('Delete', ['class' => 'hide btn btn-danger delete-btn-submit','data-delete-id' => $opta->id]) !!}

        {!! Form::close() !!}

        
    </td>
    @endif
</tr>

@endforeach

