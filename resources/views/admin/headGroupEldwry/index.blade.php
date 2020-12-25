@extends('admin.layouts.app')
@section('title') {{trans('app.groupEldwry')}}
@stop
@section('head_content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-right">
            @if($headGroupEldwry_create > 0)
            <a class="btn btn-success fa fa-plus"  data-toggle="tooltip" data-placement="top" data-title="{{trans('app.add')}} {{trans('app.groupEldwry')}}" href="{{ route('admin.headGroupEldwry.create') }}"></a>
            @endif
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

                <table id="groupEldwryTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th><center>ID</center></th>
                        <th><center>{{trans('app.name')}}</center></th>
                        <th><center>{{trans('app.user')}}</center></th>
                        <th><center>{{trans('app.week')}}</center></th>
                        <th><center>{{trans('app.team')}}</center></th>
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
@include('admin.layouts.status')
@stop




@section('scripts')


<script>
   $(document).ready( function () {
    var deleteId;
    $('#groupEldwryTable').DataTable({
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
                    columns: [0,1, 2, 3, 4]
                }
            }
        ],
        ajax: "{{ route('admin.headGroupEldwry.list') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name'  },
            { data: 'user', name: "user"  },
            { data: 'subeldwry', name: 'subeldwry'  },
            { data: 'game', name: 'game'  },
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
            url: "headGroupEldwry/"+deleteId,
            data: {
                id:deleteId,
                _token: $('meta[name="_token"]').attr('content'),
            },
            dataType: 'json',
            success: function (data) {
            var oTable = $('#groupEldwryTable').dataTable(); 
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
