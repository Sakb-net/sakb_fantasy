<thead>
    <tr>
        <th>ID</th>
        <th>{{trans('app.lang')}} </th>
        <th>{{trans('app.key')}}  {{trans('app.lang')}} </th>
        <th>{{trans('app.state')}} </th>
        <th>Default</th>
        @if($post_edit == 1  || $post_show == 1  || $post_delete == 1)
        <th style="width: 350px;">{{trans('app.settings')}} </th>
        @endif
    </tr>
</thead>
@foreach ($data as $key => $lang)
<tr>
    <td>{{ $lang->id }}</td>
    <!--<td>{{ ++$key }}</td>-->
    <td>{{ $lang->name }}</td>
    <td>{{ $lang->lang }}</td>
    <td>
    @if($lang->is_active == 0)
        <a class="poststatus fa fa-remove btn  btn-danger btn-state"  data-id='{{ $lang->id }}' data-status='1' ></a>
    @else
        <a class="poststatus fa fa-check btn  btn-success btn-state"  data-id='{{ $lang->id }}' data-status='0' ></a>
    @endif
    </td>
    <td>
    @if($lang->is_default == 1)
       <a class="btn btn-success btn-state" data-id='{{ $lang->id }}' data-status='0' >Default</a>
    @endif
    </td>
    @if($post_edit == 1  || $post_show == 1  || $post_delete == 1 || $comment_list == 1 || $comment_create == 1)
    <td>
<!--            @if($post_show == 1)
                <a class="btn btn-info fa fa-eye-slash" href="{{ route('admin.languages.show',$lang->id) }}"></a>
            @endif-->
            @if($post_edit == 1)
                <a class="btn btn-primary fa fa-edit btn-post" data-toggle="tooltip" data-placement="top" data-title="{{trans('app.update')}}  {{trans('app.lang')}} " href="{{ route('admin.languages.edit',$lang->id) }}"></a>
            @endif
            @if($post_delete == 1 && $lang->id != 1)
            <a id="delete" data-id='{{ $lang->id }}' data-name='{{ $lang->name }}' data-toggle="tooltip" data-placement="top" data-title="{{trans('app.delete')}}  {{trans('app.lang')}} " class="btn btn-danger fa fa-trash btn-post"></a>
            {!! Form::open(['method' => 'DELETE','route' => ['admin.languages.destroy', $lang->id],'style'=>'display:inline']) !!}
            {!! Form::submit('Delete', ['class' => 'hide btn btn-danger delete-btn-submit','data-delete-id' => $lang->id]) !!}
            {!! Form::close() !!}
            @endif
    </td>
    @endif
</tr>
@endforeach