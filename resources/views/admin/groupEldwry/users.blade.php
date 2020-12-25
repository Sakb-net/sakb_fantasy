@extends('admin.layouts.app')
@section('title') {{trans('app.users')}}
@stop
@section('head_content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-right">
        {{$groupEldwryName}}
        </div>
    </div>
</div>
@stop
@section('content')
@include('admin.groupEldwry.models')

<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="box-body">	

                @include('admin.errors.alerts')

                <table id="usersTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th><center>ID</center></th>
                        <th><center>{{trans('app.user')}}</center></th>
                        <th><center>{{trans('app.team')}}</center></th>
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
    var modelId;
    var id = {{ $id }};
    // console.log(bool)
    $('#usersTable').DataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        searching:false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'pageLength',
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [0,1, 2]
                }
            }
        ],
        ajax:{
            url: "{{ route('admin.groupEldwry.listUsers')}}",
            data: {
                id:id,
                _token: $('meta[name="_token"]').attr('content'),
            }
        },
        columns: [
            { data: 'id', name: 'id', orderable: false },
            { data: 'user', name: "user" , orderable: false },
            { data: 'game', name: 'game',  orderable: false  },
            { data: 'action', name: 'action',  orderable: false },
        ]
        });

        $('body').on('click', '#setAdmin', function(){
            $('#admin-modal').modal('show');
             modelId = $(this).attr('data-id');
             adminId = $(this).attr('data-adminId');
             console.log(modelId);
             console.log(adminId);
        });
        
        $('body').on('click', '#active', function(){
            $('#active-modal').modal('show');
             modelId = $(this).attr('data-id');
             console.log(modelId);

        });

        $('body').on('click', '#deactivate', function(){
            $('#deactivate-modal').modal('show');
             modelId = $(this).attr('data-id');
             console.log(modelId);

        });

        $('body').on('click', '#block', function(){
            $('#block-modal').modal('show');
             modelId = $(this).attr('data-id');
             console.log(modelId);

        });

        $('body').on('click', '#removeBlock', function(){
            $('#removeBlock-modal').modal('show');
             modelId = $(this).attr('data-id');
             console.log(modelId);

        });
        

        $('body').on('click', '#confirm-admin', function(){ 
            console.log(modelId);
        $('#admin-modal').modal('hide');
        $.ajax({
            type: "post",
            url: "{{ route('admin.groupEldwry.setAdmin')}}",
            data: {
                id:modelId,
                adminId:adminId,
                _token: $('meta[name="_token"]').attr('content'),
            },
            dataType: 'json',
            success: function (data) {
            var oTable = $('#usersTable').dataTable(); 
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


        $('body').on('click', '#confirm-active', function(){ 
            console.log(modelId);
        $('#active-modal').modal('hide');
        $.ajax({
            type: "post",
            url: "{{ route('admin.groupEldwry.activeUser')}}",
            data: {
                id:modelId,
                _token: $('meta[name="_token"]').attr('content'),
            },
            dataType: 'json',
            success: function (data) {
            var oTable = $('#usersTable').dataTable(); 
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



    $('body').on('click', '#confirm-deactivate', function(){ 
            console.log(modelId);
        $('#deactivate-modal').modal('hide');
        $.ajax({
            type: "post",
            url: "{{ route('admin.groupEldwry.disActiveUser')}}",
            data: {
                id:modelId,
                _token: $('meta[name="_token"]').attr('content'),
            },
            dataType: 'json',
            success: function (data) {
            var oTable = $('#usersTable').dataTable(); 
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


    $('body').on('click', '#confirm-block', function(){ 
            console.log(modelId);
        $('#block-modal').modal('hide');
        $.ajax({
            type: "post",
            url: "{{ route('admin.groupEldwry.block')}}",
            data: {
                id:modelId,
                _token: $('meta[name="_token"]').attr('content'),
            },
            dataType: 'json',
            success: function (data) {
            var oTable = $('#usersTable').dataTable(); 
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



    $('body').on('click', '#confirm-removeBlock', function(){ 
            console.log(modelId);
        $('#removeBlock-modal').modal('hide');
        $.ajax({
            type: "post",
            url: "{{ route('admin.groupEldwry.removeBlock')}}",
            data: {
                id:modelId,
                _token: $('meta[name="_token"]').attr('content'),
            },
            dataType: 'json',
            success: function (data) {
            var oTable = $('#usersTable').dataTable(); 
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
