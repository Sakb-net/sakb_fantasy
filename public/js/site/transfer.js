$(document).ready(function () {
    $('body').on('click', '.close_player', function () { 
        $('body').find('#allBtnChangeModal').removeClass('hidden');
        return false;
    });
    $('body').on('click', '.allBtn_changeModal', function () { //change
        var obj = $(this);
        var eldwry_link = obj.attr('data-eldwry');
        var player_name = obj.attr('data-name');
        var player_link = obj.attr('data-link');
        var loc_player = obj.attr('loc_player');
        var type_coatch = obj.attr('data-coatch');
        var change=0;
        emptyALLdataMsg();
        $('body').find('.Name_PLayer_mod').html(player_name);
        //get date all btn
        get_dataallBtun(player_name,player_link,eldwry_link,loc_player,type_coatch,change);
        //click on model
        $('body').find('.btnMod_allchangeModal').click();
        return false;
    }); 
    $('body').on('click', '.showData_lineup', function () { //change
        var obj = $(this);
        var eldwry_link = obj.attr('data-eldwry');
        var lineup = obj.attr('data-lineup');
        var player_link = obj.attr('data-link');
        emptyALLdataMsg();
        $('body').find('.Name_lineup_mod').html(lineup);
        //get date lineup
        get_datalineup();
        //click on model
        $('body').find('.btnMod_lineup').click();
        return false;
    }); 

    $('body').on('click', '.add_linupMyteam', function () { //change
        var obj = $(this);
        var linup_link = obj.attr('data-link');
        emptyALLdataMsg();
        //get date lineup
        get_add_linupMyteam(linup_link);
        return false;
    }); 

    $('body').on('click', '.add_captain_player', function () { //change
        var obj = $(this);
        var eldwry_link = obj.attr('data-eldwry');
        var name_player = obj.attr('data-name');
        var player_link = obj.attr('data-link');
        var type='captain';
        //add and get
        get_add_Captain(eldwry_link,player_link,name_player,type);
        return false;
    }); 

$('body').on('click', '.add_captain_assist_player', function () { //change
        var obj = $(this);
        var eldwry_link = obj.attr('data-eldwry');
        var name_player = obj.attr('data-name');
        var player_link = obj.attr('data-link');
        var type='assist';
        //add and get
        get_add_Captain(eldwry_link,player_link,name_player,type);
        return false;
    }); 

    $('body').on('click', '.Data_changePlayer', function () { //change
        var obj = $(this);
        var eldwry_link = obj.attr('data-eldwry');
        var name_player = obj.attr('data-name');
        var player_link = obj.attr('data-link');
        var type_key_coatch = obj.attr('data-coatch');  //coatch sub_coatch
        var loc_player=obj.attr('loc_player');
        obj.parent().parent().parent().find('.statChang_player').addClass('active');
        $('body').find('.statChang_player').removeClass('current_ch_active');
        obj.parent().parent().parent().find('.statChang_player').addClass('current_ch_active');
        //add and get
        Inside_changePlayer(obj,eldwry_link,player_link,name_player,loc_player,type_key_coatch);
        return false;
    });

    $('body').on('click', '.btndelete_allowchange', function () { //change
        var obj = $(this);
        Delete_allowchange();
        Remove_statChang_player();
        Remove_current_ch_active();
        remove_delete_allowchange();
        return false;
    }); 

    $('body').on('click', '#allBtnChangeModal', function () { //of pop change
        var obj = $(this);
        Remove_current_ch_active();
        return false;
    }); 

    $('body').on('click', '.okData_ChangPlayer', function () { //change
        var obj = $(this);
        var name_player = obj.attr('data-name');
        var player_link = obj.attr('data-link');
        //add and get
        okInsid_ChangPlayer(player_link,name_player);
        remove_delete_allowchange();
        return false;
    }); 

});
