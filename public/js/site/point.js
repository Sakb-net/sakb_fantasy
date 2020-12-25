$(document).ready(function () {
	$('body').on('click', '.next_subeldwry_points', function () { //change
        var obj = $(this);
        var subeldwry_link = obj.attr('attr-link');
        Details_points_subeldwry(subeldwry_link,'next','hidden');
 		return false;
    }); 
    $('body').on('click', '.prev_subeldwry_points', function () { //change
        var obj = $(this);
        var subeldwry_link = obj.attr('attr-link');
        Details_points_subeldwry(subeldwry_link,'prev','hidden');
 		return false;
    });   

    $('body').on('click', '.popMod_PointModal', function () { 
        var obj = $(this);
        var subeldwry_link = obj.attr('data-subeldwry');
        var player_name = obj.attr('data-name');
        var player_link = obj.attr('data-link');
        Details_pop_points(subeldwry_link,player_name,player_link);
        //click on model
        $('body').find('.btnMod_PointModal').click();
        return false;
    }); 

});

//transfer
function get_dataPagePoint(limit_match=12,sub_eldwry_link='',type_link='',cal_hidden='hidden',num_week=0) {
    Details_points_subeldwry(sub_eldwry_link,type_link,cal_hidden,num_week);
    GetDataMatch_Public(limit_match,1);
}

function Details_points_subeldwry(sub_eldwry_link,type_link,cal_hidden='hidden',num_week=0) {
    $.ajax({
        type: "post",
        url: url + '/get_points_subeldwry',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            sub_eldwry_link: sub_eldwry_link,
            type_link: type_link,
            num_week:num_week,
            val_view: '0'
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                var subeldwry_link='';
                var subeldwry_points=data.subeldwry_points;
                GetDataPoint_subeldwry(subeldwry_points,type_link);
                if(subeldwry_points!=''&& subeldwry_points!=null&& subeldwry_points!='null'){
                    window.history.pushState({}, document.title, "/game/my_point"+"?week="+subeldwry_points.num_week);
                    subeldwry_link=subeldwry_points.link;
                }   
                DrawPlayerMaster_eldawryTransfer(data.player_master,data.order_lineup,data.current_lineup,cal_hidden,subeldwry_link);
                DrawPlayerList_eldawryTransfer(data.player_master,data.order_lineup,data.current_lineup,cal_hidden,subeldwry_link);
                return false;
            }
        },
        complete: function (data) {
            return false;
        }});
    return false;
}
function GetDataPoint_subeldwry(subeldwry_points,type_link){
    var div_section='';
    if(subeldwry_points!=''&& subeldwry_points!=null&& subeldwry_points!='null'){
        // $('body').find('.next_subeldwry_points').removeClass('hidden');
        $('body').find('.prev_subeldwry_points').removeClass('hidden');
        var prev_hidden='';
        if(subeldwry_points.num_week==1 || subeldwry_points.num_week=='1'){
            prev_hidden='hidden';
        }
        div_section +='<a class="next-game prev_subeldwry_points '+prev_hidden+'" attr-link="'+subeldwry_points.link+'">';
            div_section +='<span class="hide-option">  '+prev+' </span>  <span class="fa fa-chevron-left"></span>';
        div_section +='</a>';
        div_section +='<a class="prev-game next_subeldwry_points" attr-link="'+subeldwry_points.link+'">';
            div_section +='<span class="fa fa-chevron-right"></span>  <span class="hide-option">  '+next+' </span>';
        div_section +='</a>';
        div_section +='<div id="game-week">';
        div_section +='<div class="item">';
            div_section +='<div class="section-head">';
                div_section +='<h4>'+subeldwry_points.lang_num_week+'</h4>';
            div_section +='</div>';
            div_section +='<div class="mypoints-container">';
                div_section +='<div class="final-points">';
                    div_section +='<p>'+final_points+'</p>';
                    div_section +='<p class="num">'+subeldwry_points.final_point+'</p>';
                    if(subeldwry_points.bench_card> 0){
                        div_section +='<p class="card-played">'+lang_benchmark_paper+'</p>';
                    }
                    if(subeldwry_points.triple_card> 0){
                        div_section +='<p class="card-played">'+lang_captain_triple_sheet+'</p>';
                    }
                    // if(subeldwry_points.gold_card> 0){
                    //     div_section +='<p class="card-played">'+lang_gold_card+'</p>';
                    // }
                    // if(subeldwry_points.gray_card> 0){
                    //     div_section +='<p class="card-played">'+lang_silver_card+'</p>';
                    // }
                div_section +='</div>';
                div_section +='<div class="points-detailes">';
                    div_section +='<div class="col-md-6">';
                        div_section +='<table class="table">';
                            div_section +='<tr>';
                                div_section +='<td>'+average_points+': </td>';
                                div_section +='<td>'+subeldwry_points.avg_point+'</td>';
                            div_section +='</tr>';
                            div_section +='<tr>';
                                div_section +='<td>'+heighest_point+':</td>';
                                div_section +='<td>'+subeldwry_points.heigh_point+'</td>';
                            div_section +='</tr>';
                        div_section +='</table>';
                    div_section +='</div>';
                    div_section +='<div class="col-md-6">';
                        div_section +='<table class="table">';
                            div_section +='<tr>';
                                div_section +='<td>'+sort_game_week+': </td>';
                                div_section +='<td>'+subeldwry_points.sort_gwla+'</td>';
                            div_section +='</tr>';
                            div_section +='<tr>';
                                div_section +='<td>'+transfers+':</td>';
                                div_section +='<td>'+subeldwry_points.transfer+'( '+subeldwry_points.transfer_points+' '+point_whda+' )</td>';
                            div_section +='</tr>';
                        div_section +='</table>';
                    div_section +='</div>';
                div_section +='</div>';
            div_section +='</div>';
        div_section +='</div>';
        div_section +='</div>';
        $(".draw_dataPoint_subeldwry").text('');
        $('.draw_dataPoint_subeldwry').html(div_section);
    }else{
        if(type_link=='next'){
            $('body').find('.tab_game_my_team').click();
            //$('body').find('.next_subeldwry_points').addClass('hidden');
        }
        if(type_link=='prev'){
            $('body').find('.prev_subeldwry_points').addClass('hidden');
        }
    }
    return false;
}

function Details_pop_points(subeldwry_link,player_name,player_link) {
    $.ajax({
        type: "post",
        url: url + '/get_pointsplayer_foruser',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            subeldwry_link: subeldwry_link,
            player_name: player_name,
            player_link: player_link,
            val_view: '0'
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                DrawPop_points(data.data,player_name,player_link);
                return false;
            }
        },
        complete: function (data) {
            return false;
        }});
    return false;
}
function DrawPop_points(user_points,player_name,player_link){
    var div_section='';
    if(user_points!=''&& user_points!=null&& user_points!='null'){
        player_name=user_points.player_name;
        player_link=user_points.player_link;
    }
        div_section +='<div class="modal-header">';
            div_section +='<button type="button" class="close close_point" data-dismiss="modal">&times;</button>';
            div_section +='<h4 class="modal-title Name_PLayer_mod">'+player_name+'</h4>';
        div_section +='</div>';
        div_section +='<div class="modal-body">';
            div_section +='<div class="row">';
            if(user_points!=''&& user_points!=null&& user_points!='null'){
                div_section +=DrawTable_points(user_points);
            }else{
                div_section += info_dataDiv(not_found);  
            } 
        div_section +='</div>';
    div_section +='</div>';
    div_section +='<div class="modal-footer">';
        div_section +='<div class="row text-center">';
            div_section += '<a data-name="' + player_name + '" data-link="' + player_link + '" class="butn butn-bg showData_infoPlayer" >'+view_information+'</a>';
        div_section +='</div>';
    div_section +='</div>';
    
    $(".draw_Pop_dataPoint").text('');
    $('.draw_Pop_dataPoint').html(div_section);
    return false;
}

function DrawTable_points(all_data){
    var div_section='';
    div_section +='<div class="dream-result">';
        div_section +='<h3>'+all_data.teams_first+' <span> '+all_data.first_goon+' - '+all_data.second_goon+' </span> '+all_data.teams_second+'</h3>';
    div_section +='</div>';
    div_section +='<div class="col-md-12">';
       div_section +=' <table class="table text-center">';
            div_section +='<thead>';
               div_section +=' <tr>';
                    div_section +='<th>'+statistic+'</th>';
                    div_section +='<th>'+count_number+'</th>';
                    div_section +='<th>'+points+'</th>';
                div_section +='</tr>';
            div_section +='</thead>';
            div_section +='<tbody>';
            $.each(all_data.statistic, function (index, value) {
                div_section +='<tr>';
                    div_section +='<td>'+value.lang_point+'</td>';
                    div_section +='<td>'+value.number+'</td>';
                    div_section +='<td>'+value.points+'</td>';
                div_section +='</tr>';
            }); 
            div_section +='</tbody>';
        div_section +='</table>';
    div_section +='</div>';
    return div_section;  
}