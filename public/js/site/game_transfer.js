$(document).ready(function () {
    $('body').on('click', '.close_player', function () { 
        $('body').find('#allBtnChangeModal').removeClass('hidden');
        return false;
    });

});
//transfer
function get_dataPageTRansfer(limit_match=12,type='basic',cal_hidden='') {
    GetDataMatch_Public(limit_match,1);
    GetDataPlayer_MasterTransfer(type,cal_hidden);
    check_btns_status();
}

function GetDataPlayer_MasterTransfer(type='',cal_hidden='') {
    $.ajax({
        type: "post",
        url: url + '/GetDataPlayer_MasterTransfer',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            type:type,
            val_view: '0'
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                DrawPlayerMaster_eldawryTransfer(data.player_master,data.order_lineup,data.current_lineup,cal_hidden);
                DrawPlayerList_eldawryTransfer(data.player_master,data.order_lineup,data.current_lineup,cal_hidden);
                return false;
            }
        },
        complete: function (data) {
            return false;
        }});
    return false;
}

function DrawPlayerList_eldawryTransfer(all_data,order_lineup,current_lineup,cal_hidden='',subeldwry_link='') {
   var div_section = '';
    if (all_data != '') {
        $.each(all_data, function (index, value) {
            index=index-1;
            if (index == order_lineup[0] || index == order_lineup[1] || index == order_lineup[2] || index == order_lineup[3] || index == order_lineup[4]) {
                if (index == order_lineup[0]){
                    div_section += '<tr class="goalkeeper">'; 
                    div_section +='<th></th>';
                    div_section +='<th>'+goalkeeper+'</th>';
                }else if(index == order_lineup[1]){
                    div_section += '<tr class="Defender">';
                    div_section +='<th></th>';
                    div_section +='<th>'+Defender+'</th>';
                }else if(index == order_lineup[2]){
                    div_section += '<tr class="Midfielder">';
                    div_section +='<th></th>';
                    div_section +='<th>'+Midfielder+'</th>';
                }else if(index == order_lineup[3]){
                    div_section += '<tr class="Forward">';
                    div_section +='<th></th>';
                    div_section +='<th>'+Forward+'</th>';
                }else{
                    div_section += '<tr class="Midfielder">';
                    div_section +='<th></th>';
                    div_section +='<th>'+Spare+'</th>';
                }    
                    div_section +='<th>'+current_price+'</th>';
                    div_section +='<th>'+sale_price+'</th>';
                   div_section +=' <th>'+purchase_price+'</th>';
                   div_section +='<th>'+form+'</th>';
                    div_section +='<th>'+total_points+'</th>';
                    div_section +='<th>'+fix+'</th>';
                div_section +='</tr>';
            }
            div_section +='<tr>';
                div_section +='<th>';
                    div_section += '<a data-name="' + value.name_player + '" data-link="' + value.link_player + '" class="control info showData_infoPlayer" >';
                        div_section +='<i class="fa fa-info"></i>';
                    div_section +='</a>';
                div_section +='</th>';
                div_section +=get_FoundPlayer_ListTransfer(value,cal_hidden,subeldwry_link);
            div_section +='</tr>';
        });
    } else {
        div_section += info_dataDiv(not_found);
    }
    $(".draw_playerList_eldawry").text('');
    $('.draw_playerList_eldawry').html(div_section);
}

function DrawPlayerMaster_eldawryTransfer(all_data,order_lineup,current_lineup,cal_hidden,subeldwry_link='') {
    var div_section = '';
    if (all_data != '') {
        $.each(all_data, function (index, value) {
            index=index-1;
            if (index == order_lineup[0]){
                div_section += '<div class="pitch-view">';
            }
            if (index == order_lineup[0] || index == order_lineup[1] || index == order_lineup[2] || index == order_lineup[3]) {
                div_section += '<div class="flex-row">';
            }else if(index == order_lineup[4]){
                div_section += '<div class="flex-row change-row">';
            }   
            if(index == order_lineup[4]){
                div_section += '<div class="player border-right">';
            }else{
                div_section += '<div class="player">';
            }
            //not-active  //active  
                div_section += '<div class="statChang_player ' + value.public_cla + '">';
                    div_section += '<div class="' + value.empty_class + '">';
                    //start player
                    if (value.found_player == 1 || value.found_player == '1') {
                            div_section += get_FoundPlayer_masterTransfer(value,cal_hidden,subeldwry_link);
                    } else {
                        //not found player
                        // div_section += '<a class="info_plz_addplayer" loc_player="' + value.type_loc_player + '">';
                        div_section += '<a class="" loc_player="' + value.type_loc_player + '">';
                        div_section += ' <img class="T-shirt" src="' + url + value.image_player + '">';
                        div_section += '<div class="player-name">' + value.name_player + '</div>';
                        div_section += '<div class="player-value"><span class="dir_left">'  + value.match_name  + '</span> </div>';
                        // if(value.cost_player>0){
                        //     div_section += '<div class="player-value"><span class="dir_left">'  + val_whda+ value.cost_player  + '</span> </div>'; //' + value.currency + '
                        // }else{
                        //     div_section += '<div class="player-value">'  + value.cost_player  + ' </div>';  //' + value.currency + '
                        // }             
                        div_section += '</a>';
                    }
                    //end player
                    div_section += '</div>';
                div_section += '</div>';
            //numbering and sort of sub player
            if(index == order_lineup[4]){
                div_section += '<div><h3><abbr title="'+goalkeeper+'">GK</abbr></h3></div>';
            }
            if(index == 12 || index == 13 || index == 14){
                num_ord=index-11;
                div_section += '<div><h3>'+num_ord+'</h3></div>';
            }
            //end numbering of subplayer
            div_section += '</div>';
            if (index == order_lineup[0] || index == order_lineup[2]-1|| index == order_lineup[3]-1 || index == order_lineup[4]-1 || index == 14) {
                div_section += '</div>';
            }
            if (index == order_lineup[4]-1) {
                var lineup=current_lineup[0]+'-'+current_lineup[1]+'-'+current_lineup[2];
                div_section +='<a data-eldwry="' + value.eldwry_link + '" data-lineup="' + lineup + '" data-link="' + value.link_player + '" class="plan showData_lineup '+cal_hidden+'" >'+lineup+'</a>';
                div_section += '</div>';
            }
        });
    } else {
        div_section += info_dataDiv(not_found);
    }
    $(".draw_playerMaster_eldawry").text('');
    $('.draw_playerMaster_eldawry').html(div_section);
}

function get_FoundPlayer_ListTransfer(value,cal_hidden='',subeldwry_link='') {
   var  div_section ='<td>';
        if(cal_hidden=='hidden' || cal_hidden=="hidden" || value.myteam_order_id >= 12){
            div_section += '<a class="popMod_PointModal" data-subeldwry="'+subeldwry_link+'" data-name="' + value.name_player + '" data-link="' + value.link_player + '">';
            //div_section += '<a data-name="' + value.name_player + '" data-link="' + value.link_player + '" class="showData_infoPlayer" >';
        }else{
            div_section += '<a  data-coatch="' + value.type_key_coatch + '" data-eldwry="' + value.eldwry_link + '" data-name="' + value.name_player + '" data-link="' + value.link_player + '" loc_player="' + value.type_loc_player + '" class="allBtn_changeModal" >';
        }
            div_section +='<div class="list-player">';
                div_section +='<div class="image">';
                    div_section +='<img src="' + url + value.image_player + '" alt="">';
                div_section +='</div>';
                div_section +='<div class="body">';
                    div_section +='<div class="name">' + value.name_player + '</div>';
                    div_section +='<div class="text">';
                        div_section +='<span>' + value.team + '</span>';
                        // div_section +='<span>' + value.location_player + '</span>';
                    div_section +='</div>';
                div_section +='</div>';
            div_section +='</div>';
        div_section +='</a>';
    div_section +='</td>';
    div_section +='<td>'+ value.cost_player + '</td>';
    div_section +='<td>'+ value.sell_cost + '</td>';
    div_section +='<td>'+ value.buy_cost + '</td>';
    div_section +='<td>'+ value.form + '</td>';
    div_section +='<td>'+ value.total_points + '</td>';
    div_section +='<td>'+ value.fix + '</td>';
return div_section;
}
function get_FoundPlayer_masterTransfer(value,cal_hidden='',subeldwry_link='') {
    var div_section = '';
    if(cal_hidden=='hidden' || cal_hidden=="hidden"){ //for point page
        div_section += '<a class="popMod_PointModal" data-subeldwry="'+subeldwry_link+'" data-name="' + value.name_player + '" data-link="' + value.link_player + '">';
    }else if(value.myteam_order_id >= 12){ //for sub player in myteam page
        div_section += '<a data-name="' + value.name_player + '" data-link="' + value.link_player + '" class="showData_infoPlayer" >';
    }else{ //for main player in myteam page
        div_section += '<a data-coatch="' + value.type_key_coatch + '" data-eldwry="' + value.eldwry_link + '" data-name="' + value.name_player + '" data-link="' + value.link_player + '" loc_player="' + value.type_loc_player + '" class="allBtn_changeModal" >';
    }
    div_section += ' <img class="T-shirt" src="' + url + value.image_player + '">';
    div_section += '<div class="player-name">' + value.name_player + '</div>';
    if(cal_hidden=='hidden' || cal_hidden=="hidden"){
        //add point 
        div_section += '<div class="player-value val_point"><span class="">'  + value.point_player + point_whda +  '</span> </div>';
    }else{ 
        div_section += '<div class="player-value"><span class="dir_left">'  +value.match_name + '</span> </div>';
        // if(value.cost_player>0){
        //     div_section += '<div class="player-value"><span class="dir_left">'  + val_whda+ value.cost_player + '</span> </div>'; //' + value.currency + '
        // }else{
        //     div_section += '<div class="player-value">'+ value.cost_player + ' </div>'; //' + value.currency + '
        // }
    }   
    div_section += '</a>';
    div_section += '<a class="control change Data_changePlayer '+cal_hidden+'" loc_player="' + value.type_loc_player + '"  data-coatch="' + value.type_key_coatch + '" data-eldwry="' + value.eldwry_link + '" data-name="' + value.name_player + '" data-link="' + value.link_player + '" >';
        div_section += '<i class="fa fa-exchange fa-rotate-90"></i>';
    div_section += '</a>';  
    div_section += '<a data-name="' + value.name_player + '" data-link="' + value.link_player + '" class="control info showData_infoPlayer" >';
    // div_section += '<a class="control info" data-toggle="modal" data-target="#infoModal">';
        div_section += '<i class="fa fa-info"></i>';
    div_section += '</a>';
     // -----> 8:coatch or 9:sub_coatch
    if(value.type_coatch!=''){
        var letter_coatch=ch_sub_coatch;
        if(value.type_key_coatch=='coatch' || value.type_key_coatch=='captain'){
            letter_coatch=ch_coatch;
        }
        div_section += '<span class="captain" title="' + value.type_coatch + '">'+letter_coatch+'</span>';  
    }
    return div_section;
}

function get_datalineup() {
    $.ajax({
        type: "post",
        url: url + '/get_datalineup',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            val_view: '0'
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                Drawget_datalineup(data.lineup,data.current_lineup);
                return false;
            }
        },
        complete: function (data) {
            return false;
        }});
    return false;
}
function Drawget_datalineup(all_data,current_lineup) {
    var div_section = '';
    if (all_data != '') {
        var index_start=0;
        var index_end=0;
        $.each(all_data, function (index, value) {
            if(index==index_start){
                index_end=index_start+1;
                div_section += '<div class="flex">';
            }
                div_section += '<div class="plan-div">';
                    if(current_lineup==value.link){
                    div_section += '<a data-link="'+value.link+'" class="butn butn-bg w100 add_linupMyteam" data-dismiss="modal">';
                    }else{
                    div_section += '<a data-link="'+value.link+'" class="butn w100 add_linupMyteam" data-dismiss="modal">';
                    }
                    div_section += value.linup_one+'-'+value.linup_second+'-'+value.linup_three+'</a>';
                div_section += '</div>';
            if(index==index_end){
                index_start=index_end+1;
                div_section += '</div>';
            }
        });
    } else {
        div_section += info_dataDiv(not_found);
    }
    $(".draw_gamedatalineup").text('');
    $('.draw_gamedatalineup').html(div_section);
}

function get_add_linupMyteam(linup_link) {
    $.ajax({
        type: "post",
        url: url + '/get_add_linupMyteam',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            linup_link:linup_link,
            val_view: '0'
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                var msg_add=data.msg_add;
                if(data.ok_update==1||data.ok_update=='1'){
                    DrawPlayerMaster_eldawryTransfer(data.player_master,data.order_lineup,data.current_lineup);
                    DrawPlayerList_eldawryTransfer(data.player_master,data.order_lineup,data.current_lineup);
                    var success_msg = success_dataDiv(msg_add);
                    $('body').find('.notif-msg').html(success_msg);
                    setTimeout(function () {
                        emptyALLdataMsg();//$('.show-noti').remove();
                        close_Currentpop();
                    }, 1000);
                }else{
                    var fail_msg = fail_dataDiv(msg_add);
                    $('body').find('.notif-msg').html(fail_msg);
                    setTimeout(function () {
                        emptyALLdataMsg();//$('.show-noti').remove();
                    }, 9000);
                }
                return false;
            }
        },
        complete: function (data) {
            return false;
        }});
    return false;
}

function get_dataallBtun(name_player,link_player,eldwry_link,loc_player,type_coatch,change=0){
    var div_section ='';
    remove_delete_allowchange();
    if(change==1 || change=='1'){
        div_section +='<div class="form-group">';
            div_section += '<a data-name="' + name_player + '" data-link="' + link_player + '" class="butn butn-bg w100 okData_ChangPlayer" >'+name_change+'</a>';
        div_section +='</div>';
        add_delete_allowchange();
    }else if(change==-1 || change=='-1'){
        var fail_msg = fail_dataDiv(not_allow_change);
        $('body').find('.notif-msg').html(fail_msg);
        setTimeout(function () {
            emptyALLdataMsg();//$('.show-noti').remove();
            $('body').find('.close_mod').click();
        }, 5000);
    }  
    if(change!=1 && change!='1' && change!=-1 && change!='-1'){  
        if(type_coatch=='coatch'||type_coatch=="coatch"||type_coatch=='captain'||type_coatch=="captain"){}else{
            div_section +='<div class="form-group">';
                div_section +='<a data-eldwry="' + eldwry_link + '" data-name="' + name_player + '" data-link="' + link_player + '"  class="butn butn-bg w100 add_captain_player" data-dismiss="modal">'+choose_captain+'</a>';
            div_section +='</div>';
        }
        if(type_coatch=='sub_coatch'||type_coatch=="sub_coatch" || type_coatch=='sub_captain'||type_coatch=="sub_captain"){}else{
            div_section +='<div class="form-group">';
                div_section +='<a data-eldwry="' + eldwry_link + '" data-name="' + name_player + '" data-link="' + link_player + '"  class="butn butn-bg w100 add_captain_assist_player" data-dismiss="modal">'+choose_sub_captain+'</a>';
            div_section +='</div>';
        }
    }
    div_section +='<div class="form-group">';
        div_section += '<a data-name="' + name_player + '" data-link="' + link_player + '" class="butn butn-bg w100 showData_infoPlayer" >'+player_data+'</a>';
        //div_section +='<a data-toggle="modal" data-target="#infoModal" data-dismiss="modal" class="butn butn-bg w100">'+player_data+'</a>';
    div_section +='</div>';

    $(".draw_allbtn_change").text('');
    $('.draw_allbtn_change').html(div_section);
}

function get_add_Captain(eldwry_link,player_link,name_player,type){
    emptyALLdataMsg();
       $.ajax({
        type: "post",
        url: url + '/get_add_Captain',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            eldwry_link:eldwry_link,
            player_link:player_link,
            type:type,
            val_view: '0'
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                var msg_add=data.msg_add;
                if(data.ok_update==1||data.ok_update=='1'){
                    DrawPlayerMaster_eldawryTransfer(data.player_master,data.order_lineup,data.current_lineup);
                    DrawPlayerList_eldawryTransfer(data.player_master,data.order_lineup,data.current_lineup);
                    var success_msg = success_dataDiv(msg_add);
                    $('body').find('.notif-msg').html(success_msg);
                    setTimeout(function () {
                        emptyALLdataMsg();//$('.show-noti').remove();
                        close_Currentpop();
                    }, 1000);
                }else{
                    var fail_msg = fail_dataDiv(msg_add);
                    $('body').find('.notif-msg').html(fail_msg);
                    setTimeout(function () {
                        emptyALLdataMsg();//$('.show-noti').remove();
                    }, 10000);
                }
                return false;
            }
        },
        complete: function (data) {
            return false;
        }});
    return false;
}
function Remove_current_ch_active() {
    $('body').find('.current_ch_active').removeClass('active');
    $('body').find('.current_ch_active').removeClass('not-active');
    $('body').find('.current_ch_active').removeClass('allow-active');
    return false;
}
function Remove_statChang_player(){
    $('body').find('.statChang_player').removeClass('active');
    $('body').find('.statChang_player').removeClass('not-active');
    $('body').find('.statChang_player').removeClass('allow-active');
    return false;
}
function Show_POP_change(name_player,player_link,eldwry_link,loc_player,type,change){
    //click on pop
    $('body').find('.Name_PLayer_mod').html(name_player);
    //get date all btn
    get_dataallBtun(name_player,player_link,eldwry_link,loc_player,type,change);
    //click on model
    $('body').find('.btnMod_allchangeModal').click();
    return false;
}
function Inside_changePlayer(obj,eldwry_link,player_link,name_player,loc_player,type){
   //type -->coatch or  sub_coatch 
   // console.log('type:'+type);
    emptyALLdataMsg();
       $.ajax({
        type: "post",
        url: url + '/inside_changePlayer',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            eldwry_link:eldwry_link,
            player_link:player_link,
            type:type,
            val_view: '0'
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                var msg_add=data.msg_add;
                var change=data.change;
                var change_sub=data.change_sub
                var all_hide=data.all_hide;
                console.log('type_loc_player:'+data.type_loc_player);
                console.log('type_loc_player_one:'+data.type_loc_player_one);
                console.log('type_loc_player_two:'+data.type_loc_player_two);
                console.log('all_hide:'+data.all_hide);
                console.log('first_choose:'+data.first_choose);
                console.log('second_choose:'+data.second_choose);
                console.log('change:'+change);
                console.log('change_sub:'+change_sub);
                console.log('remove_class:'+data.remove_class);
                console.log('ok_update:'+data.ok_update);
                console.log('type_loc_hidden:'+data.type_loc_hidden);

                if(change==1||change=='1'||change_sub==1||change_sub=='1'){
                    if(change_sub==1||change_sub=='1'){
                        //for change last three sub player
                        Show_POP_change(name_player,player_link,eldwry_link,loc_player,type,change_sub);
                    }else if((all_hide==1||all_hide=='1')&&(data.first_choose==1||data.first_choose=='1')&&(data.type_loc_player_one!=data.type_loc_player_two)){
                        Remove_current_ch_active();
                        change=-1;
                        Show_POP_change(name_player,player_link,eldwry_link,loc_player,type,change);
                    }else{ 
                        Show_POP_change(name_player,player_link,eldwry_link,loc_player,type,change);
                    }    
                }else if(data.remove_class==3 || data.remove_class=='3'){
                    Remove_statChang_player();
                }else if(data.remove_class==2||data.remove_class=='2' ||data.remove_class==1||data.remove_class=='1'){
                    Remove_current_ch_active();
                }else{
                    if(data.first_choose==1||data.first_choose=='1'){
                        Remove_statChang_player();
                    }else if(data.second_choose==1||data.second_choose=='1'){
                        Remove_statChang_player();
                    }
                    if((data.ok_update==1||data.ok_update=='1' ) && data.type_loc_player!=''){
                        //not-active  //active    //allow-active
                        if(all_hide==1||all_hide=='1'){
                            //all class
                            $('body').find('.statChang_player').addClass('not-active');
                            $('body').find('.'+data.type_loc_player).removeClass('not-active');
                            $('body').find('.'+data.type_loc_player).addClass('allow-active');
                        }else{
                            if(data.type_loc_player !='goalkeeper'){
                                $('body').find('.goalkeeper').addClass('not-active');
                            }
                            if(data.type_loc_hidden!=''){
                                $('body').find('.'+data.type_loc_hidden).addClass('not-active');
                                $('body').find('.change-row .'+data.type_loc_hidden).removeClass('not-active');
                            }
                        }
                        if(data.type_loc_player =='goalkeeper'){
                            $('body').find('.goalkeeper').addClass('active');
                            $('body').find('.goalkeeper').removeClass('allow-active');
                        }else{
                            $('body').find('.current_ch_active').addClass('active');
                            $('body').find('.current_ch_active').removeClass('allow-active');
                        }
                    }else{
                        var fail_msg = fail_dataDiv(msg_add);
                        $('body').find('.notif-msg').html(fail_msg);
                        setTimeout(function () {
                            emptyALLdataMsg();//$('.show-noti').remove();
                        }, 10000);
                    }
                }
                return false;
            }
        },
        complete: function (data) {
            return false;
        }});
    return false;
}

function Delete_allowchange(){
    $.ajax({
        type: "post",
        url: url + '/delete_allowchange',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            val_view: '0'
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                
                return false;
            }
        },
        complete: function (data) {
            return false;
        }});
    return false;
}

function okInsid_ChangPlayer(player_link,name_player){
    emptyALLdataMsg();
    $.ajax({
        type: "post",
        url: url + '/okInsid_ChangPlayer',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            player_link:player_link,
            val_view: '0'
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                var msg_add=data.msg_add;
                if(data.ok_update==1||data.ok_update=='1'){
                    DrawPlayerMaster_eldawryTransfer(data.player_master,data.order_lineup,data.current_lineup);
                    DrawPlayerList_eldawryTransfer(data.player_master,data.order_lineup,data.current_lineup);
                    var success_msg = success_dataDiv(msg_add);
                    $('body').find('.notif-msg').html(success_msg);
                    setTimeout(function () {
                        emptyALLdataMsg();//$('.show-noti').remove();
                        close_Currentpop();
                    }, 1000);
                    // $('body').find('.statChang_player').removeClass('active');
                    // $('body').find('.statChang_player').removeClass('not-active');
                    // $('body').find('.statChang_player').removeClass('allow-active');
                }else{
                    var fail_msg = fail_dataDiv(msg_add);
                    $('body').find('.notif-msg').html(fail_msg);
                    setTimeout(function () {
                        emptyALLdataMsg();//$('.show-noti').remove();
                    }, 10000);
                }
                return false;
            }
        },
        complete: function (data) {
            return false;
        }});
    return false;
}

function check_btns_status() {
    $.ajax({
        type: "post",
        url: url + '/check_btns_status',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            // val_view: '0'
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            console.log('benchCard == '+data.cards_status.benchCard);
            console.log('tripleCard == '+data.cards_status.tripleCard);
            if(data.cards_status.benchCard == 1 | data.cards_status.tripleCard == 1){
            $('body').find('.bench_players_card').addClass('disabled');
            $('.disabled').prop('disabled', true);
            $('body').find('.captain_triple_card').addClass('disabled');
            $('.disabled').prop('disabled', true);
            }
        },
        complete: function (data) {
            return false;
        }});
    return false;
}
