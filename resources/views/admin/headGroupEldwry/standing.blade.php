@extends('admin.layouts.app')
@section('title') {{trans('app.groupEldwry')}}
@stop
@section('head_content')
<!-- <div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-right">

        </div>
    </div>
</div> -->
@stop
@section('content')
<div class="standing_head">

    <div class="nav" role="tablist">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item active">
                <a class="nav-link" data-toggle="tab" href="#help" role="tab">
                    {{trans('app.standings')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#rules" role="tab">
                    المباريات
                </a>
            </li>
        </ul>
    </div>

    <!-- Tab Content Start -->






    <div class="tab-content" id="myTabContent">
        <!-- Single Tab Content Start -->
        <div class="tab-pane fade active in" id="help" role="tabpanel">
            <div class="mytab-content">
                <div class="row">

                    <div class="col-md-6 col-sm-6">
                        <div class="form-group ">
                            <label> {{trans('app.show_according')}}:</label>
                            <select data-link="{{$eldwryLink}}" data-type="head" class="form-control filter_standing_groupEldwry">
                            <option value="0">overAll</option> 
                                @foreach($return_data as $value)
                                 <option value="{{$value->link}}">{{trans('app.gameweek')}} {{$value->num_week}}</option> 
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <table class="table table-bordered table-striped draw_data__standing_head_group_eldwry">


                    </table>

                <!-- <table class="table league-table draw_data_group_eldwry">
                </table> -->
                <!-- end panel group -->
            </div>
        </div>
        <!-- Single Tab Content End -->

        <!-- Single Tab Content Start -->
        <div class="tab-pane fade" id="rules" role="tabpanel">
            <div class="mytab-content">
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group ">
                            <label> {{trans('app.show_according')}}:</label>
                            <select data-link="{{$eldwryLink}}" data-type="head" class="form-control filter_groupEldwry">
                            <option value="0">overAll</option> 
                                @foreach($return_data as $value)
                                 <option value="{{$value->link}}">{{trans('app.gameweek')}} {{$value->num_week}}</option> 
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div id="table-wrapper">
                    <div id="table-scroll">
                    <table class="table table-bordered table-striped draw_data__matches_head_group_eldwry">


                    </table>
                    </div>
                </div>
                <!-- end panel group -->
            </div>
        </div>
        <!-- Single Tab Content End -->
    </div>

    <!-- Tab Content End -->
</div> 
@stop
@section('after_foot')
@stop




@section('scripts')


<script >
   $(document).ready( function () {
    var subLink = $(".filter_standing_groupEldwry").attr('data-link');
    get_data_standing_group_eldwry(subLink,0,'head');
    get_data_group_eldwry(subLink,0,'head');

    function draw_data_group_eldwry(all_data,user_id=0) {
   var div_section = '<thead>';
        div_section += '<tr>';
            div_section += '<th></th>';
            div_section += '<th><center>'+'rank'+'</center></th>';
            div_section += '<th><center>'+'teams'+'</center></th>';
            div_section += '<th><center>'+'GW'+'</center></th>';
            div_section += '<th><center>'+'TOT'+'</center></th>';
        div_section += '</tr>';
    div_section += '</thead>';
    div_section += '<tbody>';
    if (all_data != '') {
        $.each(all_data, function (index, value) {
            div_section +='<tr>';
                if(index==0){
                    div_section +='<td class="green"><center>';
                       div_section +='<i class="fa fa-arrow-up"></i>'; 
                }else if(index==1){
                    div_section +='<td class="red"><center>';
                       div_section +='<i class="fa fa-arrow-down"></i>'; 
                }else{
                    div_section +='<td class="gray"><center>';
                       div_section +='<i class="fa fa-circle"></i>'; 
                }  
                div_section +='</center></td>';
                div_section +='<td><center>' + value.sort + '</center></td>';
                div_section +='<td>';
                    // tab_menu_game
                    div_section +='<span><center>' + value.name_team + '</center></span>';
                div_section +='</td>';
                div_section +='<td><center>' + value.gw_points + '</center></td>';
                div_section +='<td><center>' + value.total_points + '</center></td>';
            div_section +='</tr>';
        });
    } else {
        div_section += 'notFound';
    }
    div_section += '</tbody>';
    $(".draw_data__standing_head_group_eldwry").text('');
    $('.draw_data__standing_head_group_eldwry').html(div_section);
}

    $('body').on('change', '.filter_standing_groupEldwry', function (e) {
        var sub_eldwry_link = $(this).val();
        var link_group = $(this).attr('data-link');
        var type_group = $(this).attr('data-type');

        get_data_standing_group_eldwry(link_group,sub_eldwry_link,type_group);
        return false;
    });


    function get_data_standing_group_eldwry(link_group='',sub_eldwry_link='',type_group=''){

        $.ajax({
            type: "post",
            // url: "{{url('get_data_group_eldwry')}}",
            url: '/get_data_group_eldwry',
            data: {
                _token: $('meta[name="_token"]').attr('content'),
                link:link_group,
                sub_eldwry_link:sub_eldwry_link,
                type_group:type_group
            },
            cache: false,
            dataType: 'json',
            success: function (data) {
                if (data !== "") {

                    draw_data_group_eldwry(data.data.users_group,data.user_id);
                }
            },
            complete: function (data) {
                return false;
            }
        });
    
    return false;
    }





    function draw_data__matches_head_group_eldwry(all_data,user_id=0) {
   var div_section = '<thead>';
        div_section += '<tr>';
            div_section += '<th class="td-width"><center>'+'GW'+'</center></th>';
            div_section += '<th><center>'+'teams'+'</center></th>';
            div_section += '<th></th>';
            div_section += '<th><center>'+'teams'+'</center></th>';
        div_section += '</tr>';
    div_section += '</thead>';
    div_section += '<tbody>';
    if (all_data != '') {
        $(".subeldwry_groupEldwry option[value="+all_data[0].subeldwry_link+"]").attr("selected","selected");
        $.each(all_data, function (index, value) {
            div_section +='<tr>';
                div_section +='<td><center>' + value.num_week  + '</center></td>';
                div_section +='<td><center>';
                    div_section +='<span class="user-name">' + value.first_user_name  + ' - '+value.first_team_name+ '</span>';
                div_section +='</center></td>';
                div_section +='<td><center>';
                    div_section +='<div class="Score">';
                        div_section +='<span>' + value.first_team_points + '</span>';
                        div_section +=' - ';
                        div_section +='<span>' + value.second_team_points  + '</span>';
                    div_section +='</div>';
                div_section +='</center></td>';
                div_section +='<td><center>';
                    div_section +='<span class="user-name">' + value.second_user_name  + ' - '+value.second_team_name+'</span>';
                div_section +='</center></td>';
            div_section +='</tr>';
        });
    } else {
        div_section += 'notFound';
    }
    div_section += '</tbody>';
    $(".draw_data__matches_head_group_eldwry").text('');
    $('.draw_data__matches_head_group_eldwry').html(div_section);
}

    $('body').on('change', '.filter_groupEldwry', function (e) {
        var sub_eldwry_link = $(this).val();
        var link_group = $(this).attr('data-link');
        var type_group = $(this).attr('data-type');

        get_data_group_eldwry(link_group,sub_eldwry_link,type_group);
        return false;
    });


    function get_data_group_eldwry(link_group='',sub_eldwry_link='',type_group=''){

        $.ajax({
            type: "post",
            // url: "{{url('get_data_group_eldwry')}}",
            url: '/get_data_group_eldwry',
            data: {
                _token: $('meta[name="_token"]').attr('content'),
                link:link_group,
                sub_eldwry_link:sub_eldwry_link,
                type_group:type_group
            },
            cache: false,
            dataType: 'json',
            success: function (data) {
                if (data !== "") {

                    draw_data__matches_head_group_eldwry(data.data.matches_group,data.user_id);
                }
            },
            complete: function (data) {
                return false;
            }
        });
    
    return false;
    }
     });

  </script>
@stop
