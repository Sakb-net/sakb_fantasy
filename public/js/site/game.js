$(document).ready(function () {

    //*************************************
    $('body').on('click', '.auto_selection_player', function () { //change
        var obj = $(this);
        submit_auto_selection_player();
        return false;
    });
    $('body').on('click', '.reset_all_player', function () { //change
        var obj = $(this);
        submit_reset_all_player();
        return false;
    });
    $('body').on('click', '.submit_search_player', function () { //change
        var obj = $(this);
        // submit_fun_filter_player(1);
        submit_fun_filter_player_transformation();
        return false;
    });
    $('body').on('change', '.filter_playersTeamsTransformation', function () { //change
        var obj = $(this);
        submit_fun_filter_player_transformation();
        return false;
    });
    $('body').on('change', '.order_playersTeamsTransformation', function () { //change
        var obj = $(this);
        submit_fun_filter_player_transformation();
        return false;
    });

    $('body').on('change', '.filter_playersTeams', function () { //change
        var obj = $(this);
        submit_fun_filter_player();
        return false;
    });
    $('body').on('change', '.order_playersTeams', function () { //change
        var obj = $(this);
        submit_fun_filter_player();
        return false;
    });

    $('body').on('click', '.info_plz_addplayer', function () { //change
        var obj = $(this);
        var loc_player = obj.attr('loc_player');
        var offset=1;
        Details_info_plz_addplayer(loc_player,offset);
        ShowScreenTeam();
        return false;
    });

    $('body').on('click', '.order_by_player_location', function () { //change
        var obj = $(this);
        var loc_player = obj.attr('loc_player');
        var offset=1;
        Details_order_by_player_location(loc_player,offset);
        return false;
    });

    $('body').on('click', '.change_info_plz_addplayer', function () { //change
        var obj = $(this);
        var eldwry_link = obj.attr('data-eldwry');
        var player_link = obj.attr('data-link');
        var player_name = obj.attr('data-name');
        //var player_delete = obj.attr('data-delete');
        var loc_player = obj.attr('loc_player');
        //to old player
        change_info_plz_addplayer(player_link,eldwry_link,player_name,loc_player);
        ShowScreenTeam();
        return false;
    });

    $('body').on('click', '.popModal_addPlayer', function () { //change
        var obj = $(this);
        var player_name = obj.attr('data-name');
        var player_link = obj.attr('data-link');
        emptyALLdataMsg();
        var url_string =window.location.href;
        var array_url=url_string.split("/transfer");
        $('body').find('.Name_PLayer_mod').html(player_name);
        if(array_url.length>=2){
            //substitute player
            $('body').find('.Modal_addPlayer').addClass('hidden');
            $('body').find('.Modal_substitutePlayer').removeClass('hidden');
            $('body').find('.Modal_substitutePlayer').attr('data-name', player_name);
            $('body').find('.Modal_substitutePlayer').attr('data-link', player_link);
        }else{
            //add player
            $('body').find('.Modal_substitutePlayer').addClass('hidden');
            $('body').find('.Modal_addPlayer').removeClass('hidden');
            $('body').find('.Modal_addPlayer').attr('data-name', player_name);
            $('body').find('.Modal_addPlayer').attr('data-link', player_link);
        }
        //show player
        $('body').find('.popModal_infoPlayer').attr('data-name', player_name);
        $('body').find('.popModal_infoPlayer').attr('data-link', player_link);
        //click on model
        $('body').find('.btnMod_add_player').click();
        return false;
    });
    $('body').on('click', '.popModal_infoPlayer,.showData_infoPlayer', function () { //change
        var obj = $(this);
        var player_name = obj.attr('data-name');
        var player_link = obj.attr('data-link');
        emptyALLdataMsg();
        $('body').find('.Name_PLayer_mod').html(player_name);
        //get date palyer
        get_dataOnePlayer(player_link);
        //click on model
        $('body').find('.close_point').click();
        $('body').find('#allBtnChangeModal').addClass('hidden');
        $('body').find('.btnMod_info_player').click();
        return false;
    });
    $('body').on('click', '.popModal_deletePlayer', function () { //change
        var obj = $(this);
        var eldwry_link = obj.attr('data-eldwry');
        var player_link = obj.attr('data-link');
        var player_name = obj.attr('data-name');
        var player_delete = obj.attr('data-delete');
        var loc_player = obj.attr('loc_player');
        $('body').find('.Name_PLayer_mod').html(player_name);
        DrawpopModal_outPlayer(eldwry_link,player_link,player_name,player_delete,loc_player);
        //click on model
        $('body').find('.btnMod_delete_player').click();
        return false;
    });
    $('body').on('click', '.popModal_add_team', function () { //change
        var obj = $(this);
        CheckNumPlayerTeam();
        //click on model
        $('body').find('.btnMod_team_modal').click();
        return false;
    });
    $('body').on('click', '.delete_player_game,.removData_deletePlayer', function () { //change
        var obj = $(this);
        var eldwry_link = obj.attr('data-eldwry');
        var player_link = obj.attr('data-link');
        var player_name = obj.attr('data-name');
        emptyALLdataMsg();
        $('body').find('.Name_PLayer_mod').html(player_name);
       
        $('body').find('.player .'+player_link).attr("data-delete",1);
        $('body').find('.player .'+player_link).parent().addClass('deleted'); //
        $('body').find('.player .'+player_link).parent().addClass('empty');
        fun_filter_player_transformation('','','','','', '',1,5);

        //delete date palyer
        var pay_total_cost=$('body').find('.pay_total_cost').text();
        var remide_sum_cost=$('body').find('.remide_sum_cost').text();
        var total_team_play=$('body').find('.total_team_play').text();
        var substitutes_points=$('body').find('.substitutes_points').text();
        var count_free_weekgamesubstitute=$('body').find('.count_free_weekgamesubstitute').text();

        $.ajax({
            type: "post",
            url: url + '/game_deletePlayer',
            data: {
                _token: $('meta[name="_token"]').attr('content'),
                eldwry_link: eldwry_link,
                player_link: player_link,
                pay_total_cost: pay_total_cost,
                remide_sum_cost : remide_sum_cost,
                total_team_play : total_team_play,
                substitutes_points : substitutes_points,
                count_free_weekgamesubstitute : count_free_weekgamesubstitute,
                val_view: '0',
            },
            cache: false,
            dataType: 'json',
            success: function (data) {
                if (data !== "") {
                    var delete_player = data.delete_player;
                    var msg_delete = data.msg_delete;
                    if (delete_player == 1 || delete_player == "1") {
                        if(data.found_session == 1 || data.found_session=='1'){
                            $('body').find('.Name_PLayer_mod').html(data.val_player.name_player);
                            var new_linkplayer=data.val_player.link_player;
                            $('body').find('.player .'+data.link_substituteplayer).parent().addClass(new_linkplayer);
                            $('body').find('.player .'+new_linkplayer).parent().removeClass(data.link_substituteplayer);
                            $('body').find('.player .'+new_linkplayer).attr("data-delete",0);
                            $('body').find('.player .'+new_linkplayer).parent().removeClass('deleted');
                            $('body').find('.player .'+new_linkplayer).parent().removeClass('empty');
                            html_substitutePlayer(new_linkplayer,data.val_player);
                            var success_msg = success_dataDiv();
                            $('body').find('.notif-msg').html(success_msg);
                            setTimeout(function () {
                                emptyALLdataMsg();
                                // close_Currentpop();
                                BackScreenTeam();
                            }, 1000);
                        }else{
                            $('body').find('.close_mod').click();
                        }
                        GetData_costPoint(data.remide_sum_cost, data.total_team_play,data.pay_total_cost,data.substitutes_points,data.count_free_weekgamesubstitute);
                        return false;
                    } else {
                        var fail_msg = fail_dataDiv(msg_delete);
                        $('body').find('.all-notif-msg').html(fail_msg);
                        $('body').find('.notif-msg').html(fail_msg);
                        setTimeout(function () {
                            emptyALLdataMsg();
                        }, 9000);
                    }
                }
            },
            complete: function (data) {
                return false;
            }});
        //$('body').find('.btnMod_info_player').click();
        return false;
    });
    $('body').on('click', '.return_player_game', function () { //change
        var obj = $(this);
        var eldwry_link = obj.attr('data-eldwry');
        var player_link = obj.attr('data-link');
        var player_name = obj.attr('data-name');
        emptyALLdataMsg();
        $('body').find('.Name_PLayer_mod').html(player_name);
        //delete date palyer //popModal_deletePlayer
        $('body').find('.player .'+player_link).attr("data-delete",0);
        $('body').find('.player .'+player_link).parent().removeClass('deleted'); //
        $('body').find('.player .'+player_link).parent().removeClass('empty');

        //return date player
        var pay_total_cost=$('body').find('.pay_total_cost').text();
        var remide_sum_cost=$('body').find('.remide_sum_cost').text();
        var total_team_play=$('body').find('.total_team_play').text();
        var substitutes_points=$('body').find('.substitutes_points').text();
        var count_free_weekgamesubstitute=$('body').find('.count_free_weekgamesubstitute').text();
        $.ajax({
            type: "post",
            url: url + '/return_player_game',
            data: {
                _token: $('meta[name="_token"]').attr('content'),
                eldwry_link: eldwry_link,
                player_link: player_link,
                pay_total_cost: pay_total_cost,
                remide_sum_cost : remide_sum_cost,
                total_team_play : total_team_play,
                substitutes_points : substitutes_points,
                count_free_weekgamesubstitute : count_free_weekgamesubstitute,
                val_view: '0'
            },
            cache: false,
            dataType: 'json',
            success: function (data) {
                if (data !== "") {
                    var delete_player = data.delete_player;
                    var msg_delete = data.msg_delete;
                    if (delete_player == 1 || delete_player == "1") {
                        GetData_costPoint(data.remide_sum_cost, data.total_team_play,data.pay_total_cost,data.substitutes_points,data.count_free_weekgamesubstitute);
                        $('body').find('.close_mod').click();
                        return false;
                        // GetDataPlayer_Master();  //for draw all players game
                        var success_msg = success_dataDiv(msg_delete);
                        $('body').find('.all-notif-msg').html(success_msg);
                        $('body').find('.notif-msg').html(success_msg);
                        setTimeout(function () {
                            emptyALLdataMsg();//$('.show-noti').remove();
                        }, 5000);
                    } else {
                        var fail_msg = fail_dataDiv(msg_delete);
                        $('body').find('.all-notif-msg').html(fail_msg);
                        $('body').find('.notif-msg').html(fail_msg);
                        setTimeout(function () {
                            emptyALLdataMsg();//$('.show-noti').remove();
                        }, 9000);
                    }
                }
            },
            complete: function (data) {
                return false;
            }});
        //$('body').find('.btnMod_info_player').click();
        return false;
    });

    $('body').on('click', '.Modal_addPlayer', function () { //change
        var obj = $(this);
        var player_name = obj.attr('data-name');
        var player_link = obj.attr('data-link');
        var add_play_msg = info_dataDiv(add_player_msg);
        $('body').find('.notif-msg').html(add_play_msg);
        setTimeout(function () {
            emptyALLdataMsg();//$('.show-noti').remove();
        }, 4000);
        $.ajax({
            type: "post",
            url: url + '/game_addPlayer',
            data: {
                _token: $('meta[name="_token"]').attr('content'),
                player_name: player_name,
                player_link: player_link,
                val_view: '0'
            },
            cache: false,
            dataType: 'json',
            success: function (data) {
                if (data !== "") {
                    var add_player = data.add_player;
                    var msg_add = data.msg_add;
                    if (add_player == 1 || add_player == "1") {
                        GetData_costPoint(data.remide_sum_cost, data.total_team_play, data.pay_total_cost,data.substitutes_points,data.count_free_weekgamesubstitute);
//                        var div_section = get_FoundPlayer_master(data.val_player);
//                        $('body').find('.empty_player_choose_1').html(div_section);
//                        changeClass_dataDiv('empty_player_choose_');
                        GetDataPlayer_Master(0,1);  //for draw all players game
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

    $('body').on('click', '.submit_game_team', function () { //change
        var obj = $(this);
        var team_name = obj.parent().parent().parent().parent().find('.game_team_name').val();
        var team_name_error = $('.team_name_error');
        team_name_error.addClass('hide');
        if (typeof team_name == 'undefined' || team_name == null || team_name == 'null' || team_name == "null" || team_name == '' || team_name == "" || team_name == '0' || team_name == "0") {
            team_name_error.removeClass('hide');
            team_name_error.html(enter_team_name);
            obj.parent().parent().parent().parent().find('.game_team_name').val("");
            obj.parent().parent().parent().parent().find('.game_team_name').focus();
            return false;
        }
        $.ajax({
            type: "post",
            url: url + '/submit_game_team',
            data: {
                _token: $('meta[name="_token"]').attr('content'),
                team_name: team_name,
                val_view: '0'
            },
            cache: false,
            dataType: 'json',
            success: function (data) {
                if (data !== "") {
                    var add_team = data.add_team;
                    var msg_add = data.msg_add;
                    if (add_team == 1 || add_team == "1") {
                        var success_msg = success_dataDiv(msg_add);
                        $('body').find('.all-notif-msg').html(success_msg);
                        $('body').find('.notif-msg').html(success_msg);
                        setTimeout(function () {
                            emptyALLdataMsg();//$('.show-noti').remove();
                            //location.reload(true);  
                            window.location.href="/game/my_team"; //transfer
                            //history.pushState('data to be passed', 'Title of the page', data.state_url);
                        }, 1000);
                    } else {
                        var fail_msg = fail_dataDiv(msg_add);
                        $('body').find('.all-notif-msg').html(fail_msg);
                        $('body').find('.notif-msg').html(fail_msg);
                        setTimeout(function () {
                            emptyALLdataMsg();//$('.show-noti').remove();
                        }, 5000);
                    }
                }
            },
            complete: function (data) {
                return false;
            }});
        return false;
    });
//************************** pagination ********************
    $(document).on('click', '.pagination_playerListStatistics a',function(event) {
        var obj = $(this);
        event.preventDefault();
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');

        var url_pag=obj.attr('href');
        var url = new URL(url_pag);
        var offset = url.searchParams.get("page");
        var all_count_li=$('body').find('.count_pag_playerList').attr('data-count');
        // ************************
        next_page=offset;
        prev_page=offset;
        prev_page--;
        next_page++;
        url_bn_prev=url.origin+url.pathname+'?page='+prev_page;
        url_bn_next=url.origin+url.pathname+'?page='+next_page;
        $('body').find('.prev_pag_playerList a').attr('href',url_bn_prev);
        $('body').find('.next_pag_playerList a').attr('href',url_bn_next);
        ////////////
        if(prev_page > 0){
            $('body').find('.prev_pag_playerList').removeClass('hidden');
        }else{
            $('body').find('.prev_pag_playerList').addClass('hidden');
        }
        if(all_count_li == next_page){
            $('body').find('.next_pag_playerList').addClass('hidden');
        }else{
            $('body').find('.next_pag_playerList').removeClass('hidden');
        }
        // *********************
        var type_key=$('body').find('.draw_pagination_side_bar').attr('data-type');
        var filter_play = $('body').find("#filter_playersTeams option:selected").val();
        var order_play = $('body').find("#order_playersTeams option:selected").val();
        var loc_player = $('body').find("#val_loc_player").val();
        GetDataPlayer_Public_Statistics(filter_play,1,offset,order_play);
    });
//eman
    $(document).on('click', '.pagination_playerListLocation a',function(event) {
        var obj = $(this);
        event.preventDefault();
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');

        var url_pag=obj.attr('href');
        var url = new URL(url_pag);
        var offset = url.searchParams.get("page");
        var all_count_li=$('body').find('.count_pag_playerList').attr('data-count');
        // ************************
        next_page=offset;
        prev_page=offset;
        prev_page--;
        next_page++;
        url_bn_prev=url.origin+url.pathname+'?page='+prev_page;
        url_bn_next=url.origin+url.pathname+'?page='+next_page;
        $('body').find('.prev_pag_playerList a').attr('href',url_bn_prev);
        $('body').find('.next_pag_playerList a').attr('href',url_bn_next);
        ////////////
        if(prev_page > 0){
            $('body').find('.prev_pag_playerList').removeClass('hidden');
        }else{
            $('body').find('.prev_pag_playerList').addClass('hidden');
        }
        if(all_count_li == next_page){
            $('body').find('.next_pag_playerList').addClass('hidden');
        }else{
            $('body').find('.next_pag_playerList').removeClass('hidden');
        }
        // *********************
        var type_key=$('body').find('.draw_location_pagination_side_bar').attr('data-type');
        var filter_play = $('body').find("#filter_playersTeamsTransformation option:selected").val();
        var order_play = $('body').find("#order_playersTeamsTransformation option:selected").val();
        var word_search = $('body').find("#word_search_player").val();
        var from_price = $('body').find("#from_price_player").val();
        var to_price = $('body').find("#to_price_player").val();
        var loc_player = $('body').find("#val_loc_player").val();
        if (filter_play != 'all')
            submit_fun_filter_player_transformation(offset);
        else if(type_key == '')
            GetDataPlayer_Public_Transformation(type_key,1,order_play,offset,0);
        else
            order_by_player_location_filter(type_key, '', order_play, '', '', '',offset);
    });

    $(document).on('click', '.pagination_fixtures a',function(event) {
        var obj = $(this);
        var team = $('#selectTeamChange').val();
        event.preventDefault();
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');
        var url_pag=obj.attr('href');
        var url = new URL(url_pag);
        var offset = url.searchParams.get("page");
        var all_count_li=$('body').find('.count_pagination_fixtures').attr('data-count');
        // ************************
        next_page=offset;
        prev_page=offset;
        prev_page--;
        next_page++;
        url_bn_prev=url.origin+url.pathname+'?page='+prev_page;
        url_bn_next=url.origin+url.pathname+'?page='+next_page;
        $('body').find('.prev_pagination_fixtures a').attr('href',url_bn_prev);
        $('body').find('.next_pagination_fixtures a').attr('href',url_bn_next);
        ////////////
        if(prev_page > 0){
            $('body').find('.prev_pagination_fixtures').removeClass('hidden');
        }else{
            $('body').find('.prev_pagination_fixtures').addClass('hidden');
        }
        if(all_count_li == next_page){
            $('body').find('.next_pagination_fixtures').addClass('hidden');
        }else{
            $('body').find('.next_pagination_fixtures').removeClass('hidden');
        }
        // *********************
        //var type_key=$('body').find('.draw_pagination_fixtures').attr('data-type');
        var limit=pub_limit_match;       
        GetDataMatch_Public(limit,offset,'fixtures',offset,team);
    });
//********************* End pagination **************************


});
//*********End

function get_dataPage(limit_match=2) {
    //GetDataPlayer_Public();
    var type='';
    GetDataPlayer_Master();
    submit_fun_filter_player_transformation();// GetDataPlayer_Public_Transformation('',1,0,1,1);
    GetDataMatch_Public(limit_match,1);
}

function GetDataPlayer_Public_Transformation(type_key='',start=0,order_play='',offset=1,first_pag=0) {
    $.ajax({
        type: "post",
        url: url + '/get_data_player_public_transformation',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            type_key: type_key,
            flage: 0,
            offset:offset,
            val_view: '0',
            order_play:order_play
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                player_side_bar_transformation(data.player_public,start);
                player_location_side_bar_pagination(data.count_pag,data.offset,type_key);
                return false;
            }
        },
        complete: function (data) {
            return false;
        }});
    return false;
}
function GetDataPlayer_Public_Statistics(filter_play='',start=0,offset=1,order_play='') {
    $.ajax({
        type: "post",
        url: url + '/get_data_player_public_statistics',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            filter_play: filter_play,
            flage: 0,
            offset:offset,
            val_view: '0',
            order_play: order_play
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                Static_player_side_bar(data.player_public,start);
                player_side_bar_pagination(data.count_pag,data.offset,data.type_key);
                return false;
            }
        },
        complete: function (data) {
            return false;
        }});
    return false;
}

function submit_fun_filter_player(transfer_page=0) {
    var filter_play = $('body').find("#filter_playersTeams option:selected").val();
    var order_play = $('body').find("#order_playersTeams option:selected").val();
    var word_search = $('body').find("#word_search_player").val();
    var from_price = $('body').find("#from_price_player").val();
    var to_price = $('body').find("#to_price_player").val();
    var loc_player = $('body').find("#val_loc_player").val();
    var offset=1;
    if(transfer_page==1){
        fun_filter_player_transformation(loc_player, filter_play, order_play, word_search, from_price, to_price,offset);
    }else{
        fun_filter_player(loc_player, filter_play, order_play, word_search, from_price, to_price,offset);
    }
    return false;
}

function submit_fun_filter_player_transformation(offset=1) {
    var filter_play = $('body').find("#filter_playersTeamsTransformation option:selected").val();
    var order_play = $('body').find("#order_playersTeamsTransformation option:selected").val();
    var word_search = $('body').find("#word_search_player").val();
    var from_price = $('body').find("#from_price_player").val();
    var to_price = $('body').find("#to_price_player").val();
    var loc_player = $('body').find("#val_loc_player").val();
    var type_key=$('body').find('.draw_location_pagination_side_bar').attr('data-type');
    if(type_key == ''){
        fun_filter_player_transformation(loc_player, filter_play, order_play, word_search, from_price, to_price,offset);
    }else{
        order_by_player_location_filter(type_key, '', order_play, '', '', '',offset);
    }
    return false;
}

function Details_info_plz_addplayer(loc_player,offset){
    if (typeof loc_player != 'undefined' && loc_player != '' && loc_player != "" && loc_player != '0' && loc_player != "0" && loc_player != 0) {
        //draw bar of players to choose from it
        $('body').find('.div_filter_side').removeClass('hidden');
        $('body').find('.val_loc_player').val(loc_player);
        fun_filter_player_transformation(loc_player, '', '', '', '', '',offset,limit_list_player); //fun_filter_player//eman
    }
    var div_section ='';// info_dataDiv(plz_addplayer);
    //$(".all-notif-msg").text('');
    $(".drawMsg_plz_addplayer").text('');
    $('.drawMsg_plz_addplayer').html(div_section);
    // $('body').find('.btnMod_empty_player').click();
    return false;
}

function Details_order_by_player_location(loc_player,offset){
    if (typeof loc_player != 'undefined' && loc_player != '' && loc_player != "" && loc_player != '0' && loc_player != "0" && loc_player != 0) {
        $('body').find('.div_filter_side').removeClass('hidden');
        $('body').find('.val_loc_player').val(loc_player);
        order_by_player_location_filter(loc_player, '', '', '', '', '',offset);
    }
}

function order_by_player_location_filter(loc_player, filter_play, order_play, word_search, from_price, to_price,offset=1) {
    $.ajax({
        type: "post",
        url: url + '/get_dataOrderByPlayerLocation',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            filter_play: filter_play,
            order_play: order_play,
            word_search: word_search,
            from_price: from_price,
            to_price: to_price,
            loc_player: loc_player,
            offset: offset,
            val_view: '0'
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                player_side_bar_transformation(data.player_public, order_play);
                player_location_side_bar_pagination(data.count_pag,data.offset,data.type_key);
                return false;
            }
        },
        complete: function (data) {
            return false;
        }});
    return false;
}

function fun_filter_player_transformation(loc_player, filter_play, order_play, word_search, from_price, to_price,offset=1,limit_list=5) {
    $.ajax({
        type: "post",
        url: url + '/get_dataFilterPlayerTransformation',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            filter_play: filter_play,
            order_play: order_play,
            word_search: word_search,
            from_price: from_price,
            to_price: to_price,
            loc_player: loc_player,
            limit:limit_list,
            offset: offset,
            val_view: '0'
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                player_side_bar_transformation(data.player_public, order_play);
                player_location_side_bar_pagination(data.count_pag,data.offset,data.type_key,loc_player, filter_play, order_play, word_search, from_price, to_price);
                return false;
            }
        },
        complete: function (data) {
            return false;
        }});
    return false;
}

function fun_filter_player(loc_player, filter_play, order_play, word_search, from_price, to_price,offset=1) {
    $.ajax({
        type: "post",
        url: url + '/get_dataFilterPlayer',  //get_dataFilterPlayerTransformation
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            filter_play: filter_play,
            order_play: order_play,
            word_search: word_search,
            from_price: from_price,
            to_price: to_price,
            loc_player: loc_player,
            limit: limit_list_player,
            offset: offset,
            val_view: '0'
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                // player_side_bar_transformation(data.player_public, order_play);
                Static_player_side_bar(data.player_public, order_play);
                player_side_bar_pagination(data.count_pag,data.offset,data.type_key,loc_player, filter_play, order_play, word_search, from_price, to_price);
                return false;
            }
        },
        complete: function (data) {
            return false;
        }});
    return false;
}

function submit_auto_selection_player() {
    $.ajax({
        type: "post",
        url: url + '/auto_selection_player',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            val_view: '0'
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                var add_player = data.add_player;
                var msg_add = data.msg_add;
                if (add_player == 1 || add_player == "1") {
                    GetData_costPoint(data.remide_sum_cost, data.total_team_play, data.pay_total_cost);
                    GetDataPlayer_Master(0,1);  //for draw all players game
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
            }
            return false;
        },
        complete: function (data) {
            return false;
        }});
    return false;
}
function submit_reset_all_player() {
    $.ajax({
        type: "post",
        url: url + '/reset_all_player',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            val_view: '0'
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                var delete_player = data.delete_player;
                var msg_delete = data.msg_delete;
                if (delete_player == 1 || delete_player == "1") {
                    GetData_costPoint(data.remide_sum_cost, data.total_team_play, data.pay_total_cost);
                    GetDataPlayer_Master(0,1);  //for draw all players game
                    var success_msg = success_dataDiv(msg_delete);
                    $('body').find('.notif-msg').html(success_msg);
                    setTimeout(function () {
                        emptyALLdataMsg();//$('.show-noti').remove();
                        close_Currentpop();
                        BackScreenTeam();
                    }, 1000);
                } else {
                    var fail_msg = fail_dataDiv(msg_delete);
                    $('body').find('.notif-msg').html(fail_msg);
                    setTimeout(function () {
                        emptyALLdataMsg();//$('.show-noti').remove();
                    }, 9000);
                }
            }
            return false;
        },
        complete: function (data) {
            return false;
        }});
    return false;
}
function change_info_plz_addplayer(player_link,eldwry_link,player_name,loc_player) {
    $('body').find('.close_mod').click();

        //change date player
        var pay_total_cost=$('body').find('.pay_total_cost').text();
        var remide_sum_cost=$('body').find('.remide_sum_cost').text();
        var total_team_play=$('body').find('.total_team_play').text();
        var substitutes_points=$('body').find('.substitutes_points').text();
        var count_free_weekgamesubstitute=$('body').find('.count_free_weekgamesubstitute').text();
    $.ajax({
        type: "post",
        url: url + '/change_player_game',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            player_link: player_link,
            eldwry_link: eldwry_link,
            player_name: player_name,
            loc_player: loc_player,
            pay_total_cost: pay_total_cost,
            remide_sum_cost : remide_sum_cost,
            total_team_play : total_team_play,
            substitutes_points : substitutes_points,
            count_free_weekgamesubstitute : count_free_weekgamesubstitute,
            val_view: '0'
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                var delete_player=data.delete_player;
                if (delete_player == 1 || delete_player == "1") {
                    GetData_costPoint(data.remide_sum_cost, data.total_team_play,data.pay_total_cost,data.substitutes_points,data.count_free_weekgamesubstitute);
                    currentEmptyPlayer_Master(player_link,loc_player);//or//GetDataPlayer_Master(0,1);
                    //show list by player according to loc_player 
                    Details_info_plz_addplayer(loc_player);
                }
                return false;
            }
        },
        complete: function (data) {
            return false;
        }});
    return false;
}

function GetDataPlayer_Master(limit=0,chang=0) {
    $.ajax({
        type: "post",
        url: url + '/GetDataPlayer_Master',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            limit: limit,
            chang: chang,
            val_view: '0'
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                DrawPlayerMaster_eldawry(data.player_master);
                DrawPlayerList_eldawry(data.player_master);
                return false;
            }
        },
        complete: function (data) {
            return false;
        }});
    return false;
}
function get_dataOnePlayer(player_link) {
    $.ajax({
        type: "post",
        url: url + '/get_dataOnePlayer',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            player_link: player_link,
            val_view: '0'
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                drawMob_OnePlayer(data.all_details);
                return false;
            }
        },
        complete: function (data) {
            return false;
        }});
    return false;

}

function CheckNumPlayerTeam(player_link) {
    $.ajax({
        type: "post",
        url: url + '/checknum_playerteam',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            player_link: player_link,
            val_view: '0'
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                var total_num_player=data.total_num_player;
                var count_player_team=data.count_player_team;
                var msg_add=data.msg_add;
                if(count_player_team==total_num_player){
                    //remove disabled
                    $('body').find('.game_team_name').removeAttr("disabled");
                    $('body').find('.submit_game_team').removeAttr("disabled");
                }else{
                    var fail_msg = fail_dataDiv(msg_add);
                    $('body').find('.all-notif-msg').html(fail_msg);
                    $('body').find('.notif-msg').html(fail_msg);
                    setTimeout(function () {
                        emptyALLdataMsg();//$('.show-noti').remove();
                    },50000);
                }
                return false;
            }
        },
        complete: function (data) {
            return false;
        }});
    return false;

}
function show_player_side_bar_ordered(value,order_play){
    var div_section = '';
    if(order_play == 'low_price' || order_play == 'heigh_price'){
        div_section += '<tr class="Defender" style="background:' + value.color + '">';
            div_section += '<th></th>'
            
                div_section += '<th>';
                div_section += '<a class="order_by_player_location" loc_player="' + value.type_key + '">';
                div_section += '<span>' +value.name+ '</span>';
                div_section += '</a>'; 
                div_section += '</th>';
            
            div_section += '<th>' + value.currency + '</th>';
        div_section += '</tr>';
        }else if(order_play == 'heighest_point' || order_play == 'lowest_point'){
        div_section += '<tr class="Defender" style="background:' + value.color + '">';
            div_section += '<th></th>';
            
                div_section += '<th>';
                div_section += '<a class="order_by_player_location" loc_player="' + value.type_key + '">';
                div_section += '<span>' +value.name+ '</span>';
                div_section += '</a>'; 
                div_section += '</th>';
            
            div_section += '<th>' + value.points + '</th>';
        div_section += '</tr>';
        }else {
            div_section += '<tr class="Defender" style="background:' + value.color + '">';
                div_section += '<th></th>';

                div_section += '<th>';
                    div_section += '<a class="order_by_player_location" loc_player="' + value.type_key + '">';
                        div_section += '<span>' +value.name+ '</span>';
                    div_section += '</a>';    
                div_section += '</th>';

                div_section += '<th></th>';
                div_section += '<th></th>';
            div_section += '</tr>';
        }
        return div_section;
}

function sort_player_group_side_bar(value, order_play){
    if(order_play == 'lowest_point'){
        value.players_group.sort(function(a, b) // تصاعدي
        {
            if ( a.point < b.point ){
              return -1;
            }
            if ( a.point > b.point ){
              return 1;
            }
            return 0;
          }
        );
    }else{
        value.players_group.sort(function(a, b) // تنازلي
        {
            if ( a.point > b.point ){
            return -1;
            }
            if ( a.point < b.point ){
            return 1;
            }
            return 0;
        }
        );
    }
    return value.players_group;
}

function show_player_group_side_bar_ordered(value,order_play){
    var div_section = '';
    $.each(value.players_group, function (ind_player, val_player) {
        div_section += '<tr><th>';
        div_section += '<a class="control info popModal_addPlayer"  data-name="' + val_player.name + '" data-link="' + val_player.link + '">';
        div_section += '<i class="fa fa-info"></i></a></th>';
        div_section += '<td>';
        div_section += '<a class="popModal_addPlayer"  data-name="' + val_player.name + '" data-link="' + val_player.link + '">';
        div_section += '<div class="list-player">';
        div_section += '<div class="image">';
        div_section += ' <img src="' + url + val_player.image + '" alt="">';
        div_section += '</div>';
        div_section += '<div class="body">';
        div_section += ' <div class="name">' + val_player.name + '</div>';
        div_section += ' <div class="text">';
        div_section += '<span class="player_team_name">' + val_player.teamCode + '</span>';
            // div_section += '<span>' + val_player.type_player + '</span>';
            div_section += '<span>' + val_player.location_player + '</span>';
        div_section += '</div>';
        div_section += '</div>';
        div_section += '</div>';
        div_section += '</a>';
        div_section += '</td>';
        if(order_play == 'low_price' || order_play == 'heigh_price')
        div_section += '<td class="dir_left">' + val_player.cost +val_whda+ '</td>';
        else if(order_play == 'heighest_point' || order_play == 'lowest_point')
        div_section += '<td class="dir_left">' + val_player.point + '</td>';
        else {
            div_section += '<td class="dir_left">' + val_player.cost +val_whda+ '</td>';
            div_section += '<td class="dir_left">' + val_player.point + '</td>';
        }
        //                div_section += '<td>18</td>';
        div_section += '</tr>';
    });
    return div_section;
}
function player_side_bar_transformation(all_data,order_play,start=0) {
    var all_players_group=[];
    var div_section = '';
    if (all_data != '') {
        div_section +='tbody';
        var goalKeeper = all_data.find(x => x.type_key === 'goalkeeper');
        var defender = all_data.find(x => x.type_key === 'defender_center');
        var centerLine = all_data.find(x => x.type_key === 'center_line');
        var attacker = all_data.find(x => x.type_key === 'attacker_center');

        // if(goalKeeper != null && Object.keys(goalKeeper).length != 0)
        // goalKeeper.players_group = sort_player_group_side_bar(goalKeeper,order_play);
        // if(defender != null && Object.keys(defender).length != 0)
        // defender.players_group = sort_player_group_side_bar(defender,order_play);
        // if(centerLine != null && Object.keys(centerLine).length != 0)
        // centerLine.players_group = sort_player_group_side_bar(centerLine,order_play);
        // if(attacker != null && Object.keys(attacker).length != 0)
        // attacker.players_group = sort_player_group_side_bar(attacker,order_play);

        if(goalKeeper != null && Object.keys(goalKeeper).length != 0){
            div_section += show_player_side_bar_ordered(goalKeeper,order_play);
            div_section += show_player_group_side_bar_ordered(goalKeeper,order_play);
        }
        
        if(defender != null && Object.keys(defender).length != 0){
            div_section += show_player_side_bar_ordered(defender,order_play);
            div_section += show_player_group_side_bar_ordered(defender,order_play);
        }
        
        if(centerLine != null && Object.keys(centerLine).length != 0){
            div_section += show_player_side_bar_ordered(centerLine,order_play);
            div_section += show_player_group_side_bar_ordered(centerLine,order_play);
        }
        
        if(attacker != null && Object.keys(attacker).length != 0){
            div_section += show_player_side_bar_ordered(attacker,order_play);
            div_section += show_player_group_side_bar_ordered(attacker,order_play);
        }
        div_section +='/tbody';
    } else {
        div_section += info_dataDiv(not_found);
    }
    $(".draw_player_side_bar_transformation").text('');
    $('.draw_player_side_bar_transformation').html(div_section);
    if(start!=0 && start!=1){
        //to show in pop
        ShowScreenTeam();
    }
}
function player_side_bar(all_data,order_play,start=0) {
    var all_players_group=[];
    var div_section = '';
    if (all_data != '') {
        div_section +='tbody';
        $.each(all_data, function (index, value) {
            if(order_play == 'low_price' || order_play == 'heigh_price')
            div_section += '<tr class="Defender" style="background:' + value.color + '"><th></th><th>' + value.name + '</th><th class="text-right">' + value.currency + '</th></tr>';
            else if(order_play == 'heighest_point' || order_play == 'lowest_point')
            div_section += '<tr class="Defender" style="background:' + value.color + '"><th></th><th>' + value.name + '</th><th class="text-right">' + value.points + '</th></tr>';
            else {
                div_section += '<tr class="Defender" style="background:' + value.color + '"><th></th><th>'+value.name+'</th><th></th><th></th></tr>';
            }
            if(order_play == 'heighest_point'){
            value.players_group.sort(function(a, b) // تنازلي
            {
                if ( a.point > b.point ){
                  return -1;
                }
                if ( a.point < b.point ){
                  return 1;
                }
                return 0;
              }
            );
            }else if(order_play == 'lowest_point'){
                value.players_group.sort(function(a, b) // تصاعدي
                {
                    if ( a.point < b.point ){
                      return -1;
                    }
                    if ( a.point > b.point ){
                      return 1;
                    }
                    return 0;
                  }
                );
            }
            all_players_group=value.players_group;
            $.each(value.players_group, function (ind_player, val_player) {
                div_section += '<tr><th>';
                div_section += '<a class="control info popModal_infoPlayer"  data-name="' + val_player.name + '" data-link="' + val_player.link + '">';
                div_section += '<i class="fa fa-info"></i></a></th>';
                div_section += '<td>';
                div_section += '<a class="popModal_infoPlayer"  data-name="' + val_player.name + '" data-link="' + val_player.link + '">';
                div_section += '<div class="list-player">';
                div_section += '<div class="image">';
                div_section += ' <img src="' + url + val_player.image + '" alt="">';
                div_section += '</div>';
                div_section += '<div class="body">';
                div_section += ' <div class="name">' + val_player.name + '</div>';
                div_section += ' <div class="text">';
                div_section += '<span class="player_team_name">' + val_player.teamCode + '</span>';
                    // div_section += '<span>' + val_player.type_player + '</span>';
                    div_section += '<span>' + val_player.location_player + '</span>';
                div_section += '</div>';
                div_section += '</div>';
                div_section += '</div>';
                div_section += '</a>';
                div_section += '</td>';
                if(order_play == 'low_price' || order_play == 'heigh_price')
                div_section += '<td class="dir_left">' + val_player.cost +val_whda+ '</td>';
                else if(order_play == 'heighest_point' || order_play == 'lowest_point')
                div_section += '<td class="dir_left">' + val_player.point + '</td>';
                else {
                    div_section += '<td class="dir_left">' + val_player.cost +val_whda+ '</td>';
                    div_section += '<td class="dir_left">' + val_player.point + '</td>';
                }
                //                div_section += '<td>18</td>';
                div_section += '</tr>';
            });
        });
        div_section +='/tbody';
    } else {
        div_section += info_dataDiv(not_found);
    }
    $(".draw_player_side_bar").text('');
    $('.draw_player_side_bar').html(div_section);
    if(start!=1){
        //to show in pop
        ShowScreenTeam();
    }
}
function Static_player_side_bar(all_data,start=0) {
    var div_section = '';
    div_section += '<thead><th></th>';
    div_section += '<th>'+player+'</th>';
    div_section += '<th>'+current_price+'</th>';
    div_section += '<th>'+sale_price+'</th>';
    div_section += '<th>'+purchase_price+'</th>';
    div_section += '<th>'+form+'</th>';
    div_section += '<th>'+total_points+'</th>';
    div_section += '<th>'+fix+'</th></thead><tbody>';
    if (all_data != '') {
        $.each(all_data, function (index, val_player) {
            //div_section += '<tr class="Defender" style="background:' + value.color + '"><th></th><th>' + value.name + '</th><th>' + value.currency + '</th></tr>'; //<th>**</th>
            //$.each(value.players_group, function (ind_player, val_player) {
                div_section += '<tr><th>';
                div_section += '<a class="control info popModal_infoPlayer"  data-name="' + val_player.name + '" data-link="' + val_player.link + '">';
                    div_section += '<i class="fa fa-exclamation-triangle text-danger"></i>';
                div_section += '</a>';
                div_section += '</th>';
                div_section += '<td>';
                    div_section += '<a class="popModal_infoPlayer"  data-name="' + val_player.name + '" data-link="' + val_player.link + '">';
                        div_section += '<div class="list-player">';
                            div_section += '<div class="image">';
                                div_section += '<img src="' + url + val_player.image + '" alt="">';
                            div_section += '</div>';
                            div_section += '<div class="body">';
                                div_section += '<div class="name">' + val_player.name + '</div>';
                                div_section += '<div class="text">';
                                    div_section += '<span>' + val_player.team + '</span>';
                                    div_section += '<span>' + val_player.location_player + '</span>';
                                    // div_section += '<span>' + val_player.type_player + '</span>';
                                div_section += '</div>';
                            div_section += '</div>';
                        div_section += '</div>';
                    div_section += '</a>';
                div_section += '</td>';
                div_section += '<td class="text-center">' + val_player.cost +val_whda+ '</td>';
                div_section += '<td class="text-center">' + val_player.cost +val_whda+ '</td>';
                div_section += '<td class="text-center">' + val_player.cost +val_whda+ '</td>';
                div_section += '<td class="text-center">4.7</td>';
                div_section += '<td class="text-center">' + val_player.point+ '</td>';
                div_section += '<td class="text-center">' + val_player.fix+ '</td>';
                div_section += '</tr>';
            //});
        });
    } else {
        div_section += info_dataDiv(not_found);
    }
    div_section += '</tbody>';
    $(".draw_player_side_bar").text('');
    $('.draw_player_side_bar').html(div_section);
    // if(start!=1){
    //     //to show in pop
    //     ShowScreenTeam();
    // }
}

function player_location_side_bar_pagination(count_li,offset,type_key=''){
    $('body').find('.draw_pagination_side_bar').attr('data-type', type_key);
    $('body').find('.draw_location_pagination_side_bar').attr('data-type', type_key);
    var div_section ='';
    var num_li_2= offset;
    var num_li_3=offset;
    var prev_num =offset;
    var next_num =offset;
    prev_num--;
    next_num++;
    var array_sort=[];
    if(offset == 1){
        num_li_2++;
        num_li_3=num_li_2;
        num_li_3++;
        array_sort=[offset,num_li_2,num_li_3];
    }else if(offset >= count_li){
        num_li_3--;
        num_li_2=num_li_3;
        num_li_2--;
        array_sort=[num_li_2,num_li_3,offset];
    }else{
        num_li_2--
        num_li_3++;
        array_sort=[num_li_2,offset,num_li_3];
    }     
    ///////start///////
    div_section += '<li class="page-item prev_pag_playerList disabled">';
        div_section += '<a class="page-link" href="'+url+'/ajax_pagination?page='+prev_num+'" rel="prev">';
            div_section += '<i class="fa fa-angle-double-right"></i>';
        div_section += '</a>';
    div_section += '</li>';
    $.each(array_sort, function (index, i) {
        var cal_active='';
        if(offset == i){
            cal_active='active';
        }
        div_section += '<li class="page-item '+cal_active+'">';
            div_section += '<a class="page-link" href="'+url+'/ajax_pagination?page='+i+'">'+i+'</a>';
        div_section += '</li>';
    });
    div_section += '<li class="page-item next_pag_playerList">';
        div_section += '<a class="page-link" href="'+url+'/ajax_pagination?page='+next_num+'" rel="next">';
            div_section += '<i class="fa fa-angle-double-left"></i>';
        div_section += '</a>';
    div_section += '</li>';
    /////// end /////////  
    div_section += '<span class="hidden count_pag_playerList" data-count="'+count_li+'"></span>';
    $(".draw_location_pagination_side_bar").text('');
    $('.draw_location_pagination_side_bar').html(div_section);
    if(offset > 1){
        $('body').find('.prev_pag_playerList').removeClass('disabled');
    }else{
        $('body').find('.prev_pag_playerList').addClass('hidden');
    }
    if(offset == count_li){
        $('body').find('.next_pag_playerList').addClass('hidden');
    }
    return false;
}

function player_side_bar_pagination(count_li,offset,type_key='',loc_player='', filter_play='', order_play='', word_search='', from_price='', to_price=''){
    $('body').find('.draw_pagination_side_bar').attr('data-type', type_key);
    $('body').find('.draw_location_pagination_side_bar').attr('data-type', type_key);
    var div_section ='';
    var num_li_2= offset;
    var num_li_3=offset;
    var prev_num =offset;
    var next_num =offset;
    prev_num--;
    next_num++;
    var array_sort=[];
    if(offset == 1){
        num_li_2++;
        num_li_3=num_li_2;
        num_li_3++;
        array_sort=[offset,num_li_2,num_li_3];
    }else if(offset >= count_li){
        num_li_3--;
        num_li_2=num_li_3;
        num_li_2--;
        array_sort=[num_li_2,num_li_3,offset];
    }else{
        num_li_2--
        num_li_3++;
        array_sort=[num_li_2,offset,num_li_3];
    }     
    ///////start///////
    div_section += '<li class="page-item prev_pag_playerList disabled">';
        div_section += '<a class="page-link" href="'+url+'/ajax_pagination?page='+prev_num+'" rel="prev">';
            div_section += '<i class="fa fa-angle-double-right"></i>';
        div_section += '</a>';
    div_section += '</li>';
    $.each(array_sort, function (index, i) {
        var cal_active='';
        if(offset == i){
            cal_active='active';
        }
        div_section += '<li class="page-item '+cal_active+'">';
            div_section += '<a class="page-link" href="'+url+'/ajax_pagination?page='+i+'">'+i+'</a>';
        div_section += '</li>';
    });
    div_section += '<li class="page-item next_pag_playerList">';
        div_section += '<a class="page-link" href="'+url+'/ajax_pagination?page='+next_num+'" rel="next">';
            div_section += '<i class="fa fa-angle-double-left"></i>';
        div_section += '</a>';
    div_section += '</li>';
    /////// end /////////
    div_section += '<span class="hidden count_pag_playerList" data-count="'+count_li+'"></span>';
    $(".draw_pagination_side_bar").text('');
    $('.draw_pagination_side_bar').html(div_section);
    if(offset > 1){
        $('body').find('.prev_pag_playerList').removeClass('disabled');
    }else{
        $('body').find('.prev_pag_playerList').addClass('hidden');
    }
    if(offset == count_li){
        $('body').find('.next_pag_playerList').addClass('hidden');
    }
    return false;
}
function pagination_fixtures(count_li,num_week){
    // $('body').find('.draw_pagination_fixtures').attr('data-type', type_key);
    var div_section ='';
    var i;
    for (i = 1; i <= count_li; i++) { 
        cal_active='';
        if(num_week == i){
            prev_num = i-1;
            next_num = i+1;
            cal_active='active';
            div_section += '<li class="page-item prev_pagination_fixtures disabled">';
                div_section += '<a class="page-link" href="'+url+'/ajax_pagination?page='+prev_num+'" rel="prev">';
                    div_section += '<i class="fa fa-angle-double-right"></i>';
                div_section += '</a>';
            div_section += '</li>';

            div_section += '<li class="page-item '+cal_active+'">';
                div_section += '<a class="page-link" href="'+url+'/ajax_pagination?page='+i+'">'+i+'</a>';
            div_section += '</li>'; 

            div_section += '<li class="page-item next_pagination_fixtures">';
                div_section += '<a class="page-link" href="'+url+'/ajax_pagination?page='+next_num+'" rel="next">';
                    div_section += '<i class="fa fa-angle-double-left"></i>';
                div_section += '</a>';
            div_section += '</li>';
        }
    }
    div_section += '<span class="hidden count_pagination_fixtures" data-count="'+count_li+'"></span>';
    $(".draw_pagination_fixtures").text('');
    $('.draw_pagination_fixtures').html(div_section);
    if(num_week > 1){
        $('body').find('.prev_pagination_fixtures').removeClass('disabled');
    }else{
        $('body').find('.prev_pagination_fixtures').addClass('hidden');
    }
    if(num_week == count_li){
        $('body').find('.next_pagination_fixtures').addClass('hidden');
    }
    return false;
}

function DrawPlayerMaster_eldawry(all_data) {
    var div_section = '';
    if (all_data != '') {
        $.each(all_data, function (index, value) {
            if (index == 0 || index == 2 || index == 7 || index == 12) {
                div_section += '<div class="flex-row">';
            }
            div_section += '<div class="player" id="' + value.link_player + '">';
            div_section += '<div class="' + value.empty_class + '">';  //eman value.is_delete==1
            if (value.found_player == 1 || value.found_player == '1') {
                div_section += get_FoundPlayer_master(value);
            } else {
                //not found player
                div_section += '<a class="info_plz_addplayer" loc_player="' + value.type_loc_player + '">';
                div_section += ' <img class="T-shirt" src="' + url + value.image_player + '">';
                div_section += '<div class="player-name">' + value.name_player + '</div>';
                if(value.cost_player>0){
                    div_section += '<div class="player-value"><span class="dir_left">'  + val_whda+ value.cost_player  + '</span> </div>'; //' + value.currency + '
                }else{
                    div_section += '<div class="player-value">'  + value.cost_player  + ' </div>';  //' + value.currency + '
                }             
                div_section += '</a>';
            }
            div_section += '</div>';
            div_section += '</div>';
            if (index == 1 || index == 6 || index == 11 || index == 14) {
                div_section += '</div>';
            }
        });
    } else {
        div_section += info_dataDiv(not_found);
    }
    $(".draw_playerMaster_eldawry").text('');
    $('.draw_playerMaster_eldawry').html(div_section);
    return false;
}
function currentEmptyPlayer_Master(player_link,type_loc_player) {
    //not found player
    div_section = '<div class="empty">';  //eman deleted//value.is_delete==1
        div_section += '<a class="info_plz_addplayer" loc_player="' + type_loc_player + '">';
        div_section += ' <img class="T-shirt" src="' + url + image_empty_player + '">';
        div_section += '<div class="player-name">' + name_player + '</div>';
        div_section += '<div class="player-value"> 0 </div>';  
        div_section += '</a>';
    div_section += '</div>';
    $('body').find("#"+player_link).text('');
    $('body').find("#"+player_link).html(div_section);
    return false;
}
function DrawPlayerList_eldawry(all_data) {
    var div_section = '';
    div_section +='<tr class="goalkeeper">';
        div_section +='<th></th>';
        div_section +='<th>'+players+'</th>';
        div_section +='<th>'+current_price+'</th>';
        div_section +='<th>'+sale_price+'</th>';
        div_section +='<th>'+purchase_price+'</th>';
        div_section +='<th>'+form+'</th>';
        div_section +='<th>'+total_points+'</th>';
        div_section +='<th>'+fix+'</th>';
    div_section +='</tr>';
    if (all_data != '') {
        $.each(all_data, function (index, value) {
            if (value.found_player == 1 || value.found_player == '1') {
                div_section += get_FoundPlayer_list(value);
            } else {
                //not found player
                div_section += get_NotFoundPlayer_list(value);
            }
        });
    } else {
        div_section += info_dataDiv(not_found);
    }
    $(".draw_AllDataList").text('');
    $('.draw_AllDataList').html(div_section);
}

function get_FoundPlayer_master(value) {
    var div_section = '<a data-delete="' + value.is_delete + '" data-eldwry="' + value.eldwry_link + '" data-name="' + value.name_player + '" data-link="' + value.link_player + '" loc_player="' + value.type_loc_player + '" class="popModal_deletePlayer ' + value.link_player + '" >';
    //div_section += '<a data-toggle="modal" data-target="#myModal">';
    div_section += '<img class="T-shirt" src="' + url + value.image_player + '">';
    div_section += '<div class="player-name">' + value.name_player + '</div>';
    if(value.cost_player>0){
        div_section += '<div class="player-value"><span class="dir_left">'  + val_whda+ value.cost_player + '</span></div>'; // ' + value.currency + '
    }else{
        div_section += '<div class="player-value">'+ value.cost_player + ' </div>'; //' + value.currency + '
    }
    div_section += '</a>';
    div_section += '<a data-eldwry="' + value.eldwry_link + '" data-name="' + value.name_player + '" data-link="' + value.link_player + '" class="control delete removData_deletePlayer">';
        div_section += '<i class="fa fa-close"></i>';
    div_section += '</a>';
    div_section += '<a data-name="' + value.name_player + '" data-link="' + value.link_player + '" class="control info showData_infoPlayer" >';
        div_section += ' <i class="fa fa-info"></i>';
    div_section += ' </a>';
    return div_section;
}
function get_FoundPlayer_list(value){
    var div_section = '<tr>';
        div_section += '<th>';
            div_section += '<a class="control info popModal_infoPlayer" data-name="' + value.name_player + '" data-link="' + value.link_player +'">';
                div_section += '<i class="fa fa-info"></i>';
            div_section += '</a>';
        div_section += '</th>';
        div_section += '<td>';
            div_section += '<a data-eldwry="' + value.eldwry_link + '" data-name="' + value.name_player + '" data-link="' + value.link_player + '" loc_player="' + value.type_loc_player + '" class="popModal_deletePlayer ' + value.link_player + '">';
                //div_section += '<a data-toggle="modal" data-target="#myModal">';
                div_section += '<div class="list-player">';
                    div_section += '<div class="image">';
                        div_section += '<img src="' + url + value.image_player + '" alt="">';
                    div_section += '</div>';
                    div_section += '<div class="body">';
                        div_section += '<div class="name">' + value.name_player + '</div>';
                        div_section += '<div class="text">';
                            div_section += '<span>' + value.team + '</span>';
                            div_section += '<span>' + value.location_player + '</span>';
                        div_section += '</div>';
                    div_section += '</div>';
                div_section += '</div>';
            div_section += '</a>';
        div_section += '</td>';
        if(value.cost_player>0){
            div_section += '<td><span class="dir_left">'  + val_whda+ value.cost_player + '</span> </td>'; //' + value.currency + '
        }else{
            div_section += '<td>'+ value.cost_player + ' </td>';//' + value.currency + '
        }
        div_section += '<td>'+ value.sell_cost + '</td>';
        div_section += '<td>'+ value.buy_cost + '</td>';
        div_section += '<td>'+ value.form + '</td>';
        div_section += '<td>'+ value.total_points + '</td>';
        div_section += '<td>'+ value.fix + '</td>';
    div_section += '</tr>';
    return div_section;
}

function get_NotFoundPlayer_list(value){
    var div_section = '<tr>';
        div_section += '<th>';
            div_section += '<a class="control info info_plz_addplayer" loc_player="' + value.type_loc_player + '">';
                div_section += '<i class="fa fa-info"></i>';
            div_section += '</a>';
        div_section += '</th>';
        div_section += '<td>';
            div_section += '<a class="info_plz_addplayer" loc_player="' + value.type_loc_player + '">';
                div_section += '<div class="list-player">';
                    div_section += '<div class="image">';
                        div_section += '<img src="' + url + value.image_player + '" alt="">';
                    div_section += '</div>';
                    div_section += '<div class="body">';
                        div_section += '<div class="name">' + value.name_player + '</div>';
                        div_section += '<div class="text">';
                            div_section += '<span>' + value.team + '</span>';
                            div_section += '<span>' + value.location_player + '</span>';
                        div_section += '</div>';
                    div_section += '</div>';
                div_section += '</div>';
            div_section += '</a>';
        div_section += '</td>';
        div_section += '<td>'  + value.cost_player  + ' ' + value.currency + '</td>';
        div_section += '<td>'  + value.sell_cost  + ' ' + value.currency + '</td>';
        div_section += '<td>'  + value.buy_cost  + ' ' + value.currency + '</td>';
        div_section += '<td>'+ value.form + '</td>';
        div_section += '<td>'+ value.total_points + '</td>';
        div_section += '<td>'+ value.fix + '</td>';
    div_section += '</tr>';
    return div_section;
}       
function drawMob_OnePlayer(value) {
    var div_section = '';
    if (value != '') {
        div_section += '<div class="info-header"><div class="player-info">';
        div_section += '<div class="text">';
        div_section += '<h2>' + value['player_data'].name +'</h2>';
        // div_section += '<h2>' + value.name + ' ( ' + value.type_player + ' )</h2>';
        div_section += '<div class="position">' + value['player_data'].location_player + '</div>';
        div_section += '<span>' + value['player_data'].team + '</span>';
        div_section += '</div>';
        div_section += '<div class="pic">';
        div_section += '<img src="' + url + value['player_data'].image + '">';
        div_section += '</div>';
        div_section += '</div>';
        div_section += '</div>'; //</div>
        div_section += '<ul class="player-l">';
            div_section += '<li>';
                div_section += '<h3>'+form+'</h3>';
                div_section += '<p>'+value['player_data'].form+'</p>';
            div_section += '</li>';
            div_section += '<li>';
                div_section += '<h3>'+week+'</h3>';
                div_section += '<p>'+value['player_data'].week+'</p>';
            div_section += '</li>';
            div_section += '<li>';
                div_section += '<h3>'+total+'</h3>';
                div_section += '<p>' + val_whda+ value['player_data'].point +'</p>';
            div_section += '</li>';
            div_section += '<li>';
                div_section += '<h3>'+price+'</h3>';
                div_section += '<p>' + val_whda+ value['player_data'].cost +'</p>';
            div_section += '</li>';
            div_section += '<li>';
                div_section += '<h3>'+sel_percentage+'</h3>';
                div_section += '<p>'+value['player_data'].sel_percentage+'%</p>';
            div_section += '</li>';
        div_section += '</ul>';
        div_section += '<ul class="player-l player-li">';
            div_section += '<li>';
                div_section += '<h3>'+influence+'</h3>';
                div_section += '<p>'+value['player_data'].influence+'</p>';
            div_section += '</li>';
            div_section += '<li>';
                div_section += '<h3>'+creativity+'</h3>';
                div_section += '<p>'+value['player_data'].creativity+'</p>';
            div_section += '</li>';
            div_section += '<li>';
                div_section += '<h3>'+threats+'</h3>';
                div_section += '<p>'+value['player_data'].threats+'</p>';
            div_section += '</li>';
            div_section += '<li>';
                div_section += '<h3>'+ICT_index+'</h3>';
                div_section += '<p>'+value['player_data'].ICT_index+'</p>';
            div_section += '</li>';
        div_section += '</ul>';
           // <!-- table -->
            div_section += '<div>';
            div_section += '<h4 class="p20">'+this_season+'</h4>';
            div_section += '<div id="table-wrapper">';
                div_section += '<div id="table-scroll" class="table-scroll">';
                    div_section += '<table class="table text-center table-striped table-bordered">';
                        div_section += '<thead>';
                            div_section += '<tr>';
                                div_section += '<th><abbr title="'+game_week+'">'+lang_gw+'</abbr></th>';// week
                                div_section += '<th class="opp">'+lang_opp+'</th>';//againestTeam -> Team Model
                                div_section += '<th><abbr title="'+lang_points+'">'+lang_pts+'</abbr></th>';//points fetch from DB
                                div_section += '<th><abbr title="'+lang_minutes_played+'">'+lang_mp+'</abbr></th>';//minsPlayed -> DetailPlayerMatche
                                div_section += '<th><abbr title="'+lang_goals_scored+'">'+lang_gs+'</abbr></th>';//goals -> DetailPlayerMatche
                                div_section += '<th><abbr title="'+lang_assists+'">'+lang_a+'</abbr></th>';//keyPass -> DetailMatche التمريرة قبل الهدف 
                                div_section += '<th><abbr title="'+lang_clean_sheets+'">'+lang_cs+'</abbr></th>';//cleanSheet if both teams has 0 (as points) keep it for later
                                div_section += '<th><abbr title="'+lang_goals_conceded+'">'+lang_gc+'</abbr></th>';//goalAssist -> DetailPlayerMatche
                                div_section += '<th><abbr title="'+lang_own_goals+'">'+lang_og+'</abbr></th>';//reverseGoal as [ownGoals]
                                if (value['player_data'].location_id == 1){
                                    div_section += '<th><abbr title="'+lang_penalties_saves+'">'+lang_ps+'</abbr></th>';//penalitySave as penality = 1 as number in keeper case (as action)، [attPenTarget]
                                }else {
                                    div_section += '<th><abbr title="'+lang_penalties_missed+'">'+lang_pm+'</abbr></th>';//penalityLost in player case (as action), [attPenMiss]
                                }
                                div_section += '<th><abbr title="'+lang_yellow_card+'">'+lang_yc+'</abbr></th>';//yellow_cart -> DetailMatche
                                div_section += '<th><abbr title="'+lang_red_card+'">'+lang_rc+'</abbr></th>';//red_cart -> DetailMatche
                                div_section += '<th><abbr title="'+lang_saves+'">'+lang_s+'</abbr></th>';//saves -> DetailPlayerMatche
                                div_section += '<th><abbr title="'+lang_bouns+'">'+lang_b+'</abbr></th>';//extraPoints bunas -> totalPasses + الاستحواذ
                            div_section += '</tr>';
                        div_section += '</thead>';
                        div_section += '<tbody>';
                        for (jsonData in value['returned_json']){
                            div_section += '<tr>';
                                div_section += '<td>'+value['returned_json'][jsonData].week+'</td>';
                                div_section += '<td>'+value['returned_json'][jsonData].againestTeam+' ('+value['returned_json'][jsonData].againestTeamResult+' - '+value['returned_json'][jsonData].ownTeamResult+')</td>';
                                div_section += '<td>'+value['returned_json'][jsonData].points+'</td>';
                                div_section += '<td>'+value['returned_json'][jsonData].minsPlayed+'</td>';
                                div_section += '<td>'+value['returned_json'][jsonData].goals+'</td>';
                                div_section += '<td>'+value['returned_json'][jsonData].keyPass+'</td>';
                                div_section += '<td>'+value['returned_json'][jsonData].cleanSheet+'</td>';
                                div_section += '<td>'+value['returned_json'][jsonData].goalAssist+'</td>';
                                div_section += '<td>'+value['returned_json'][jsonData].reverseGoal+'</td>';
                                if(value['player_data'].location_id == 1){// Goal Keeper case
                                    div_section += '<td>'+value['returned_json'][jsonData].penalitySave+'</td>';
                                }else{
                                    div_section += '<td>'+value['returned_json'][jsonData].penalityLost+'</td>';
                                }
                                div_section += '<td>'+value['returned_json'][jsonData].yellowCard+'</td>';
                                div_section += '<td>'+value['returned_json'][jsonData].redCard+'</td>';
                                div_section += '<td>'+value['returned_json'][jsonData].saves+'</td>';
                                div_section += '<td>'+value['returned_json'][jsonData].extraPoints+'</td>';
                            div_section += '</tr>'; 
                        }
                        div_section += '</tbody>';
                    div_section += '</table>';
                div_section += '</div>';
            div_section += '</div>';
            div_section += '<h4 class="p20">'+previous_seasons+'</h4>';
            div_section += '<div id="table-wrapper">';
                div_section += '<div id="table-scroll" class="table-scroll">';
                    div_section += '<table class="table text-center table-striped table-bordered">';
                        div_section += '<thead>';
                             div_section += '<tr>';
                                div_section += '<th><abbr title="'+game_week+'">'+lang_gw+'</abbr></th>';// week
                                div_section += '<th class="opp">'+lang_opp+'</th>';//againestTeam -> Team Model
                                div_section += '<th><abbr title="'+lang_points+'">'+lang_pts+'</abbr></th>';//points fetch from DB
                                div_section += '<th><abbr title="'+lang_minutes_played+'">'+lang_mp+'</abbr></th>';//minsPlayed -> DetailPlayerMatche
                                div_section += '<th><abbr title="'+lang_goals_scored+'">'+lang_gs+'</abbr></th>';//goals -> DetailPlayerMatche
                                div_section += '<th><abbr title="'+lang_assists+'">'+lang_a+'</abbr></th>';//keyPass -> DetailMatche التمريرة قبل الهدف 
                                div_section += '<th><abbr title="'+lang_clean_sheets+'">'+lang_cs+'</abbr></th>';//cleanSheet if both teams has 0 (as points) keep it for later
                                div_section += '<th><abbr title="'+lang_goals_conceded+'">'+lang_gc+'</abbr></th>';//goalAssist -> DetailPlayerMatche
                                div_section += '<th><abbr title="'+lang_own_goals+'">'+lang_og+'</abbr></th>';//reverseGoal as [ownGoals]
                                if (value['player_data'].location_id == 1){
                                    div_section += '<th><abbr title="'+lang_penalties_saves+'">'+lang_ps+'</abbr></th>';//penalitySave as penality = 1 as number in keeper case (as action)، [attPenTarget]
                                }else {
                                    div_section += '<th><abbr title="'+lang_penalties_missed+'">'+lang_pm+'</abbr></th>';//penalityLost in player case (as action), [attPenMiss]
                                }
                                div_section += '<th><abbr title="'+lang_yellow_card+'">'+lang_yc+'</abbr></th>';//yellow_cart -> DetailMatche
                                div_section += '<th><abbr title="'+lang_red_card+'">'+lang_rc+'</abbr></th>';//red_cart -> DetailMatche
                                div_section += '<th><abbr title="'+lang_saves+'">'+lang_s+'</abbr></th>';//saves -> DetailPlayerMatche
                                div_section += '<th><abbr title="'+lang_bouns+'">'+lang_b+'</abbr></th>';//extraPoints bunas -> totalPasses + الاستحواذ
                            div_section += '</tr>';
                        div_section += '</thead>';
                        div_section += '<tbody>';
                        for (jsonData in value['returned_json']){
                            div_section += '<tr>';
                                div_section += '<td>'+value['returned_json'][jsonData].week+'</td>';
                                div_section += '<td>'+value['returned_json'][jsonData].againestTeam+'</td>';
                                div_section += '<td>'+value['returned_json'][jsonData].points+'</td>';
                                div_section += '<td>'+value['returned_json'][jsonData].minsPlayed+'</td>';
                                div_section += '<td>'+value['returned_json'][jsonData].goals+'</td>';
                                div_section += '<td>'+value['returned_json'][jsonData].keyPass+'</td>';
                                div_section += '<td>'+value['returned_json'][jsonData].cleanSheet+'</td>';
                                div_section += '<td>'+value['returned_json'][jsonData].goalAssist+'</td>';
                                div_section += '<td>'+value['returned_json'][jsonData].reverseGoal+'</td>';
                                if(value['player_data'].location_id == 1){// Goal Keeper case
                                    div_section += '<td>'+value['returned_json'][jsonData].penalitySave+'</td>';
                                }else{
                                    div_section += '<td>'+value['returned_json'][jsonData].penalityLost+'</td>';
                                }
                                div_section += '<td>'+value['returned_json'][jsonData].yellowCard+'</td>';
                                div_section += '<td>'+value['returned_json'][jsonData].redCard+'</td>';
                                div_section += '<td>'+value['returned_json'][jsonData].saves+'</td>';
                                div_section += '<td>'+value['returned_json'][jsonData].extraPoints+'</td>';
                            div_section += '</tr>'; 
                        }
                        div_section += '</tbody>';
                    div_section += '</table>';
                div_section += '</div>';
            div_section += '</div>';
        div_section += '</div>';
    } else {
        div_section += info_dataDiv(not_found);
    }
    $('body').find('.close_mod').click();
    $(".draw_player_InfoModal").text('');
    $('.draw_player_InfoModal').html(div_section);
}
function DrawpopModal_outPlayer(eldwry_link,player_link,name_player,player_delete,loc_player){
    //var chooseChange=1;
    var div_section='';
    if(player_delete==1 || player_delete=='1' || player_delete=="1"){
        div_section += '<div class="form-group">';
            div_section += '<a data-eldwry="' + eldwry_link + '" data-name="' + name_player + '" data-link="' + player_link + '" data-delete="' + player_delete + '" class="butn butn-bg w100 return_player_game" id="return_player_game">'+return_player+'</a>';
        div_section += '</div>';
        div_section += '<div class="form-group">';
            div_section += '<a data-eldwry="' + eldwry_link + '" data-name="' + name_player + '" data-link="' + player_link + '" data-delete="' + player_delete + '"  loc_player="' + loc_player + '" class="butn butn-bg w100 change_info_plz_addplayer" >'+choose_replacement+'</a>';
        div_section += '</div>';
    }else{
        div_section += '<div class="form-group">';
            div_section += '<a data-eldwry="' + eldwry_link + '" data-name="' + name_player + '" data-link="' + player_link + '" data-delete="' + player_delete + '" class="butn butn-bg w100 delete_player_game">'+delete_player+'</a>';
        div_section += '</div>';
    }
    div_section += '<div class="form-group">';
        div_section += '<a data-eldwry="' + eldwry_link + '" data-name="' + name_player + '" data-link="' + player_link + '" data-delete="' + player_delete + '" class="butn butn-bg w100 popModal_infoPlayer" id="popModal_infoPlayer">'+thedata_player+'</a>';
    div_section += '</div>';
    $(".draw_Modal_outPlayer").text('');
    $('.draw_Modal_outPlayer').html(div_section);
    return false;
}

function changeClass_dataDiv(val_class) {
    var value_cal = val_class + '1';
    $("." + value_cal).removeClass(value_cal);
    var i;
    for (i = 1; i <= 15; i++) {
        var next = i + 1;
        $("." + val_class + next).addClass(val_class + i);
        $("." + val_class + next).removeClass(val_class + next);
    }
    return true;
}

function GetData_costPoint(remide_sum_cost, total_team_play,pay_total_cost=-1,substitutes_points='',count_free_weekgamesubstitute=0) {
    if (remide_sum_cost > -1) {
        $('body').find('.remide_sum_cost').html(remide_sum_cost);
    }
    if (pay_total_cost > -1) {
        $('body').find('.pay_total_cost').html(pay_total_cost);
    }
    if (total_team_play > -1) {
        $('body').find('.total_team_play').html(total_team_play);
    }
    if (typeof substitutes_points != 'undefined' && substitutes_points != null && substitutes_points != 'null' && substitutes_points != "null" && substitutes_points != '' && substitutes_points != "") {
        $('body').find('.substitutes_points').html(substitutes_points);
    }
    if (typeof count_free_weekgamesubstitute != 'undefined' && count_free_weekgamesubstitute > -1 ) {
        $('body').find('.free_weekgamesubstitute').html(count_free_weekgamesubstitute);
    }
    return true;
}
function close_Currentpop() {
    $('body').find('.close').click();
    return true;
}
function emptyALLdataMsg() {
    $('body').find('.notif-msg').html('');
    $('body').find('.all-notif-msg').html('');
    $('body').find('.notif-msg-card').html('');
    return true;
}
function add_delete_allowchange() {
    $('body').find('.close_mod').addClass('btndelete_allowchange');
    $('body').find('.btndelete_allowchange').removeClass('close_mod');
    $('body').find('.btndelete_allowchange').removeClass('close');
    return true;
};
function remove_delete_allowchange() {
    $('body').find('.btndelete_allowchange').addClass('close_mod');
    $('body').find('.close_mod').addClass('close');
    $('body').find('.close_mod').removeClass('btndelete_allowchange');
    return true;
};
function ShowScreenTeam() {
    $('body').find('#filter').removeClass('transform');    
};
function BackScreenTeam() {// $('.back').on('click', function() {
   //Click to back button in pick team
    $('body').find('#filter').addClass('transform');
}