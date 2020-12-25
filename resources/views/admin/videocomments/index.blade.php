@extends('admin.layouts.app')
@section('title') {{trans('app.all')}}  {{trans('app.comments')}} 
@stop
@section('head_content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-right">
             @if($comment_create > 0)
             @if(isset($video->id))
            <a class="btn btn-success fa fa-plus" data-toggle="tooltip" data-placement="top" data-title="{{trans('app.add')}}  " href="{{ route('admin.videos.comments.create',$video->id) }}"></a>
            @else
            <!--<a class="btn btn-success fa fa-plus" data-toggle="tooltip" data-placement="top" data-title="{{trans('app.add')}}  " href="{{ route('admin.videocomments.create') }}"></a>-->
            @endif 
            <!--<a class="btn btn-info fa fa-sort" data-toggle="tooltip" data-placement="top" data-title=" {{trans('app.arrange')}}   {{trans('app.comments')}} " href=""></a>-->
            @endif
            <!--<a class="btn btn-primary fa fa-search" href="{{ route('admin.videocomments.search') }}"></a>-->
            <a id="MakeallRead" data-id='0' data-name='{{trans('app.all_view')}}' class="btn btn-success fa fa-eye btn-order" data-toggle="tooltip" data-placement="top" data-title=" {{trans('app.all_view')}} "  style="background-color:#306302; "></a>
            {!! Form::open(['method' => 'post','route' => ['admin.videocomments.allread'],'style'=>'display:inline']) !!}
            {!! Form::submit('Delete', ['class' => 'hide btn btn-danger delete-btn-submit','data-allRead-id' => 0]) !!}
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="box-body">	
                @include('admin.errors.alerts')
                <table id="videocommentsTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th><center>ID</center></th>
                        <th><center>{{trans('app.email')}}</center></th>
                        <th><center>{{trans('app.comment')}}</center></th>
                        <th><center>{{trans('app.read')}}</center></th>
                        <th><center>{{trans('app.state')}}</center></th>
                        <th><center>{{trans('app.settings')}}</center></th>
                    </tr>
                </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
@section('after_foot')
@include('admin.layouts.delete')
@include('admin.layouts.allRead')
@include('admin.layouts.status')
@stop



@section('scripts')


<script>
   $(document).ready( function () {
    var deleteId;
    $('#videocommentsTable').DataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'pageLength',
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [0,1]
                }
            }
        ],
        ajax:{
            url: "{{ route('admin.videocomments.list') }}",
            data: function (d) {
                d.id ={{$id}}
            }
        },

        columns: [
            { data: 'id', name: 'id' },
            { data: 'email', name: "email"  },
            { data: 'content', name: "content"  },
            { data: 'is_read', name: "is_read"  },
            { data: 'active', name: 'active', searchable: false },
            { data: 'action', name: 'action', searchable: false, orderable: false },
        ]
        });

        $('body').on('click', '#delete', function(){
            $('#delete-modal').modal('show');
             deleteId = $(this).attr('data-id');
             console.log(deleteId);
            $('#confirm-delete').attr('data-id', $(this).attr('data-id'));
            var title_h=' الحذف {{$type_action}}'+' : '+$(this).attr('data-name');
            var text_div=' هل تريد تاكيد الحذف  {{$type_action}} ؟'+' : '+$(this).attr('data-name');
            $( "h4.modal-title" ).text(title_h);
            $( "div.modal-body" ).text(text_div);
        });
        
        $('body').on('click', '#confirm-delete', function(){ 
    
            console.log(deleteId);
        $('#delete-modal').modal('hide');
        $.ajax({
            type: "delete",
            url: "videocomments/"+deleteId,
            data: {
                id:deleteId,
                _token: $('meta[name="_token"]').attr('content'),
            },
            dataType: 'json',
            success: function (data) {
            var oTable = $('#videocommentsTable').dataTable(); 
            oTable.fnDraw(false);
                console.log(data)
            $('#res_message').html(data.msg);
            $('#msg_div').show();
            $('#res_message').show();

            setTimeout(function(){
          $('#res_message').hide();
          $('#msg_div').hide();
          },3000);
            },
            complete: function (data) {
                return false;
            }
        });
    });

     });


  </script>
@stop
