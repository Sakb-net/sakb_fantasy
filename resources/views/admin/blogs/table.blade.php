<thead>
    <tr>
        <th>ID</th>
        <th>{{trans('app.name')}} </th>
        <th>{{trans('app.detail_desc')}} </th>
        <th style="width: 100px;" >{{trans('app.create_at')}} </th>
        <th>{{trans('app.num_view')}} </th>
        <th>{{trans('app.state')}} </th>
        @if($blog_edit == 1  || $blog_show == 1  || $blog_delete == 1 || $comment_list == 1 || $comment_create == 1)
        <th style="width:150px;">{{trans('app.settings')}} </th>
        @endif
    </tr>
</thead>
@foreach ($data as $key => $blog)
<tr>
    <td>{{ $blog->id }}</td>
    <td  class='main-td'>{{ $blog->name }} </td>
    <td>{!! \Illuminate\Support\Str::limit($blog->content, $limit = 50, $end = '...')!!}</td>
    <td>{{ $blog->created_at }}</td>
    <td>{{ $blog->view_count }}</td>
    <td>
    @if($blog->is_active == 0)
        <a class="blogstatus fa fa-remove btn  btn-danger btn-state"  data-id='{{ $blog->id }}' data-status='1' ></a>
    @else
        <a class="blogstatus fa fa-check btn  btn-success btn-state"  data-id='{{ $blog->id }}' data-status='0' ></a>
    @endif
    </td>
    @if($blog_edit == 1  || $blog_show == 1  || $blog_delete == 1 || $comment_list == 1 || $comment_create == 1)
    <td>
<!--            @if($blog_show == 1)
                <a class="btn btn-info fa fa-eye-slash" href="{{ route('admin.blogs.show',$blog->id) }}"></a>
            @endif-->
            @if($blog_edit == 1)
                <a class="btn btn-primary fa fa-edit btn-blog" data-toggle="tooltip" data-placement="top" data-title="{{trans('app.update')}}  {{trans('app.new_one')}} " href="{{ route('admin.blogs.edit',$blog->id) }}"></a>
            @endif
           
            @if($comment_list == 1 && $blog->lang =='ar')
                <a style="background-color:#436209; " class="btn btn-success fa fa-commenting btn-blog" data-toggle="tooltip" data-placement="top" data-title="{{trans('app.comments')}} {{trans('app.new_one')}} " href="{{ route('admin.blogs.comments.index',$blog->id) }}"></a>
              
    <!--            <a id="Makearrange" data-id='{{ $blog->id }}' data-name='{{ $blog->name }}' class="btn btn-primary fa fa-exchange btn-blog" data-toggle="tooltip" data-placement="top" data-title=" اعادة{{trans('app.arrange')}}  {{trans('app.new_one')}} " style="background-color:#840e7e; "  ></a>
                {!! Form::open(['method' => 'blog','route' => ['admin.blogs.arrange.index', $blog->id],'style'=>'display:inline']) !!}
                {!! Form::submit('Delete', ['class' => 'hide btn btn-danger delete-btn-submit','data-arrange-id' => $blog->id]) !!}
                {!! Form::close() !!}-->   
            @endif
         <!--@if($comment_create == 1 )
            <a class="btn btn-info fa fa-plus" data-toggle="tooltip" data-placement="top" data-title="{{trans('app.new_ones')}} " href="{{ route('admin.blogs.comments.create',$blog->id) }}"></a>
            @endif-->
           @if($blog_delete == 1)
            <a id="delete" data-id='{{ $blog->id }}' data-name='{{ $blog->name }}' data-toggle="tooltip" data-placement="top" data-title="{{trans('app.delete')}}  {{trans('app.new_one')}} " class="btn btn-danger fa fa-trash btn-blog"></a>
            {!! Form::open(['method' => 'DELETE','route' => ['admin.blogs.destroy', $blog->id],'style'=>'display:inline']) !!}
            {!! Form::submit('Delete', ['class' => 'hide btn btn-danger delete-btn-submit','data-delete-id' => $blog->id]) !!}
            {!! Form::close() !!}
            @endif
    </td>
    @endif
</tr>
@endforeach