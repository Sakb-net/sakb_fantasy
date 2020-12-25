<thead>
    <tr>
        <th>ID</th>
        <th>الجولة </th>
        <th>الفريق الاول</th>
        <th>الفريق الثانى</th>
        <th>تاريخ المباراة</th>
        <th>وقت المباراة</th>
        <th>الحالة</th>
        @if($match_edit == 1  || $match_show == 1  || $match_delete == 1 || $comment_list == 1 || $comment_create == 1)
        <th style="width: 100px;">الاعدادات</th>
        @endif
    </tr>
</thead>
@foreach ($data as $key => $match)
<tr>
    <td>{{ $match->id }}</td>
    <!--<td>{{ ++$key }}</td>-->
    <td>{!! $match->sub_eldwry['name'] !!}</td>
    <td>{{ $match->teams_first->name }}</td>
    <td>{{ $match->teams_second->name }}</td>
    <td>{{ $match->date }}</td>
    <td> {{ $match->time }}</td>
    <td>
        @if($match->is_active == 0)
        <a class="poststatus fa fa-remove btn  btn-danger btn-state"  data-id='{{ $match->id }}' data-status='1' ></a>
        @else
        <a class="poststatus fa fa-check btn  btn-success btn-state"  data-id='{{ $match->id }}' data-status='0' ></a>
        @endif
    </td>
    @if($match_edit == 1  || $match_show == 1  || $match_delete == 1 || $comment_list == 1 || $comment_create == 1)
    <td>

        @if($match_show == 1)
        
        <a class="btn btn-primaryfas fa-eye" data-toggle="tooltip" data-placement="top" data-title="تفاصيل  " href="{{ route('admin.matches.details',[$match->id]) }}"></a>
        @endif

        @if($match_edit == 1)
        <a class="btn btn-primary fa fa-edit btn-user" data-toggle="tooltip" data-placement="top" data-title="تعديل  " href="{{ route('admin.matches.edit',[$match->id]) }}"></a>
        @endif

        @if($match_delete == 1)
        <a id="delete" data-id='{{ $match->id }}' data-name='{{ $match->name }}' data-toggle="tooltip" data-placement="top" data-title="حذف  " class="btn btn-danger fa fa-trash btn-user"></a>
        {!! Form::open(['method' => 'DELETE','route' => ['admin.matches.destroy', $match->id],'style'=>'display:inline']) !!}
        {!! Form::submit('Delete', ['class' => 'hide btn btn-danger delete-btn-submit','data-delete-id' => $match->id]) !!}
        {!! Form::close() !!}
        @endif
    </td>
    @endif
</tr>
@endforeach