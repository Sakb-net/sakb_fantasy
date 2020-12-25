<thead>
    <tr>

        <th>ID</th>

        <th>{{trans('app.lineups')}}</th>
        
        @if($setting_edit == 1)
        <th>{{trans('app.state')}}</th>
        @endif
        @if($setting_edit == 1  || $setting_show == 1 || $setting_delete == 1 )
        <th>{{trans('app.settings')}}</th>
        @endif
    </tr>
</thead>

@foreach ($data as $key => $setting)

<tr>

    <td>{{ $setting->id }}</td>

    <td>{!! $setting->setting_value !!}</td>
    
    @if($setting_edit == 1)
    <td>
    @if($setting->is_active == 0)
        <a class="settingstatus fa fa-remove btn  btn-danger"  data-id='{{ $setting->id }}' data-status='1' ></a>
    @else
        <a class="settingstatus fa fa-check btn  btn-success"  data-id='{{ $setting->id }}' data-status='0' ></a>
    @endif
        
    </td>
    @endif
    @if($setting_edit == 1  || $setting_show == 1 || $setting_delete == 1)
    <td>
        @if($setting_edit == 1)
        <a class="btn btn-primary fa fa-edit"  data-toggle="tooltip" data-placement="top" data-title=" تعديل" href="{{ route('admin.settings.edit',$setting->id) }}"></a>
        @endif
        @if($setting_delete == 1 && $setting->id!=1)

        <a id="delete" data-id='{{ $setting->id }}' data-name='{{ $setting->setting_value }}' data-toggle="tooltip" data-placement="top" data-title="حذف" class="btn btn-danger fa fa-trash"></a>

        {!! Form::open(['method' => 'DELETE','route' => ['admin.settings.destroy', $setting->id],'style'=>'display:inline']) !!}

        {!! Form::submit('Delete', ['class' => 'hide btn btn-danger delete-btn-submit','data-delete-id' => $setting->id]) !!}

        {!! Form::close() !!}

        @endif
        
    </td>
    @endif
</tr>

@endforeach

