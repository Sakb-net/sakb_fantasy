$(document).ready(function () {
    $('body').on('click', '.pop_confirm_savetransfer', function () { 
        var obj = $(this);
        $('body').find('.confirm_save_changetransfer').removeClass('hidden');
        $.ajax({
            type: "post",
            url: url + '/get_substitutePlayer',
            data: {
                _token: $('meta[name="_token"]').attr('content'),
                val_view: '0'
            },
            cache: false,
            dataType: 'json',
            success: function (data) {
                if (data !== "") {
                    var response = data.response;
                    if (response == 1 || response == "1") {
                        PopConfirm_substitutePlayer(data.data_players);
                        //click on model
                        $('body').find('.btnConfirm_savetransferModal').click();
                        //$('body').find('.close').click();
                    }else{
                        //disable  button  #78847e !important
                        // $('body').find('.pop_confirm_savetransfer').css("pointer-events", "none");
                    }
                }
            },
            complete: function (data) {
                return false;
            }});
        return false;
    });

    $('body').on('click', '.confirm_save_changetransfer', function () { 
        var obj = $(this);
        // var subeldwry_link = obj.attr('data-subeldwry');
        $.ajax({
            type: "post",
            url: url + '/confirm_substitutePlayer',
            data: {
                _token: $('meta[name="_token"]').attr('content'),
                val_view: '0'
            },
            cache: false,
            dataType: 'json',
            success: function (data) {
                if (data !== "") {
                    var response = data.response;
                    var msg_add = data.msg_add;
                    if (response == 1 || response == "1") {
                        if (typeof data.substitutes_points != 'undefined' && data.substitutes_points != null && data.substitutes_points != 'null' && data.substitutes_points != "null" && data.substitutes_points != '' && data.substitutes_points != "") {
                            $('body').find('.substitutes_points').html(data.substitutes_points);
                        }
                        var success_msg = success_dataDiv();
                        $('body').find('.notif-msg').html(success_msg);
                    } else {
                        var fail_msg = fail_dataDiv(msg_add);
                        $('body').find('.notif-msg').html(fail_msg);
                    }
                    setTimeout(function () {
                        emptyALLdataMsg();//$('.show-noti').remove();
                        close_Currentpop();
                    }, 9000);
                }
            },
            complete: function (data) {
                return false;
            }});
        return false;
    }); 

    $('body').on('click', '.Modal_substitutePlayer', function () { //change
        var obj = $(this);
        var player_name = obj.attr('data-name');
        var player_link = obj.attr('data-link');
        var pay_total_cost=$('body').find('.pay_total_cost').text();
        var add_play_msg = info_dataDiv(add_player_msg);
        $('body').find('.notif-msg').html(add_play_msg);
        setTimeout(function () {
            emptyALLdataMsg();//$('.show-noti').remove();
        }, 4000);
        $.ajax({
            type: "post",
            url: url + '/game_substitutePlayer',
            data: {
                _token: $('meta[name="_token"]').attr('content'),
                player_name: player_name,
                player_link: player_link,
                pay_total_cost:pay_total_cost,
                val_view: '0'
            },
            cache: false,
            dataType: 'json',
            success: function (data) {
                if (data !== "") {
                    var add_player = data.add_player;
                    var msg_add = data.msg_add;
                    if (add_player == 1 || add_player == "1") {
                        GetData_costPoint(-1,-1, data.pay_total_cost,data.substitutes_points,data.count_free_weekgamesubstitute);
 						// GetDataPlayer_Master(0,1);  //for draw all players game
                        html_substitutePlayer(data.link_substituteplayer,data.val_player);
                        var success_msg = success_dataDiv(msg_add);
                        $('body').find('.notif-msg').html(success_msg);
                        setTimeout(function () {
                            emptyALLdataMsg();//$('.show-noti').remove();
                            close_Currentpop();
                            BackScreenTeam();
                        }, 1000);
                    } else {
                        var fail_msg = fail_dataDiv(msg_add);
                        $('body').find('.notif-msg').html(fail_msg);
                        setTimeout(function () {
                            emptyALLdataMsg();//$('.show-noti').remove();
                        }, 9000);
                    }
                    //$('body').find('.close').click();
                }
            },
            complete: function (data) {
                return false;
            }});
        return false;
    });
//********Card***************
    $('body').on('click', '.confirm_cardgray', function () { 
        var obj = $(this);
        $.ajax({
            type: "post",
            url: url + '/confirm_cardgray',
            data: {
                _token: $('meta[name="_token"]').attr('content'),
                val_view: '0'
            },
            cache: false,
            dataType: 'json',
            success: function (data) {
                if (data !== "") {
                    var active_card = data.active_card;
                    if (active_card == 1 || active_card == "1") {
                        $('body').find('.CardVal_player_points').html(0);
                        GetData_costPoint(data.remide_sum_cost, data.total_team_play,data.pay_total_cost,data.substitutes_points);
                        var data_msg = success_dataDiv();
                    }else{
                        //use before
                        var data_msg = fail_dataDiv(used_befor);
                    }
                    $('body').find('.notif-msg-card').html(data_msg);
                    setTimeout(function () {
                        emptyALLdataMsg();
                        $('body').find('.close').click();
                    }, 9000);
                }
            },
            complete: function (data) {
                return false;
            }});
        return false;
    });

    $('body').on('click', '.confirm_cardgold', function () { 
        // var info_msg = info_dataDiv(not_found_pay_method);
        // $('body').find('.notif-msg-card').html(info_msg);
        // setTimeout(function () {
        //     emptyALLdataMsg();//$('.show-noti').remove();
        //     $('body').find('.close').click();
        // }, 9000);
        // return false;
        /////////////////
        var obj = $(this);
        $.ajax({
            type: "post",
            url: url + '/confirm_cardgold',
            data: {
                _token: $('meta[name="_token"]').attr('content'),
                val_view: '0'
            },
            cache: false,
            dataType: 'json',
            success: function (data) {
                if (data !== "") {
                    var ok_chechout = data.ok_chechout;
                    if(ok_chechout==1 ||ok_chechout=='1'){
                        //success_payment
                        console.log(data);
                    }else{
                        var fail_msg = fail_dataDiv(failed_payment);
                        $('body').find('.notif-msg-card').html(fail_msg);
                        setTimeout(function () {
                            emptyALLdataMsg();//$('.show-noti').remove();
                            $('body').find('.close').click();
                        }, 9000);
                    }
                }
            },
            complete: function (data) {
                return false;
            }});
        return false;
    });

});

function html_substitutePlayer(link_substituteplayer,value){
	var div_section = '<div class="' + value.empty_class + '">';  //eman value.is_delete==1
    	div_section += get_FoundPlayer_master(value);
    div_section += '<div';
    $('body').find('#'+link_substituteplayer).html(div_section);
    return false;
}
function PopConfirm_substitutePlayer(all_data){
    var div_section='';
    div_section += '<thead>';
        div_section += '<tr>';
            div_section += '<th><i class="fa fa-arrow-down text-success"></i>'+enter+'</th>';
            div_section += '<th><i class="fa fa-arrow-up text-danger"></i>'+exict+'</th>';
            div_section += '<th>'+the_cost+'</th>';
        div_section += '</tr>';
    div_section += '<tbody>';
    if (all_data != '') {
        $.each(all_data, function (index, val_player) {
            div_section += '<tr>';
                div_section += '<td>';
                    div_section += '<div class="list-player" data-link="'+val_player.player_link+'">';
                        div_section += '<div class="image">';
                            div_section += '<img src="'+url+val_player.player_image+'" alt="">';
                        div_section += '</div>';
                        div_section += '<div class="body">';
                            div_section += '<div class="name">'+val_player.player_name+'</div>';
                        div_section += '</div>';
                    div_section += '</div>';
               div_section += '</td>';

               div_section += '<td>';
                    div_section += '<div class="list-player" data-link="'+val_player.player_substitute_link+'">';
                        div_section += '<div class="image">';
                            div_section += '<img src="'+url+val_player.player_substitute_image+'" alt="">';
                        div_section += '</div>';
                        div_section += '<div class="body">';
                            div_section += '<div class="name">'+val_player.player_substitute_name+'</div>';
                        div_section += '</div>';
                    div_section += '</div>';
               div_section += '</td>';
               div_section += '<td> <span class="CardVal_player_points">'+val_player.points+'</span> '+point_whda+'</td>';
           div_section += '</tr>';
        });
        div_section += ' </tbody>';
    } else {
        $('body').find('.confirm_save_changetransfer').addClass('hidden');
        var info_msg = info_dataDiv(not_found);
        $('body').find('.notif-msg').html(info_msg);
        setTimeout(function () {
            emptyALLdataMsg();//$('.show-noti').remove();
            $('body').find('.close').click();
        }, 9000);
    }
    $(".draw_allTransfer_save").text('');
    $('.draw_allTransfer_save').html(div_section);
    return false;
}