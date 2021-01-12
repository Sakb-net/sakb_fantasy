$(document).ready(function () {
    $('body').on('click', '.show_eldwryoptions', function () {
        var obj = $(this);
        var link_group = obj.attr('data-link');
        var name_group = obj.attr('data-name');
        var owner_group = obj.attr('data-owner');
        var type_group = obj.attr('data-type');
        $('body').find('.name_groupEldwry').text(name_group);
        $('body').find('.show_group_groupeldwry').attr('data-link',link_group);        
        $('body').find('.show_group_groupeldwry').attr('data-type',type_group);        
        if(owner_group == 'owner'){
            $('body').find('.show_admin_groupeldwry').attr('data-link',link_group);
            $('body').find('.show_admin_groupeldwry').attr('data-type',type_group);
            $('body').find('.show_admin_groupeldwry').removeClass('hidden');
            $('body').find('.page_send_invite').attr('data-link',link_group);
            $('body').find('.page_send_invite').attr('data-type',type_group);
            $('body').find('.page_send_invite').removeClass('hidden');
            $('body').find('.leave_groupeldwry').attr('data-link','');
            $('body').find('.leave_groupeldwry').addClass('hidden');
        }else{
            $('body').find('.show_admin_groupeldwry').attr('data-link','');
            $('body').find('.show_admin_groupeldwry').attr('data-type','');
            $('body').find('.show_admin_groupeldwry').addClass('hidden');
            $('body').find('.page_send_invite').attr('data-link','');
            $('body').find('.page_send_invite').addClass('hidden');
            $('body').find('.leave_groupeldwry').attr('data-link',link_group);
            $('body').find('.leave_groupeldwry').attr('data-type',type_group);
            $('body').find('.leave_groupeldwry').removeClass('hidden');
        }
        $('body').find('.click_leagueoptions').click();
    });
    $('body').on('click', '.group_eldwry_create', function () {
        var obj = $(this);
        var type_page ='create';   
        tab_menu_groupEldwry(type_page,'group_eldwry_create');
    });

    $('body').on('click', '.group_eldwry_create_classic', function () {
        var obj = $(this);
        var type_page ='create_classic';   
        tab_menu_groupEldwry(type_page,'group_eldwry_create_classic','','classic');
        get_current_sub_eldwry('subeldwry_groupEldwry','classic');
    });

    $('body').on('click', '.group_eldwry_create_head', function () {
        var obj = $(this);
        var type_page ='create_head';   
        tab_menu_groupEldwry(type_page,'group_eldwry_create_head','','head');
        get_current_sub_eldwry('subeldwry_groupEldwry','head');
    });
    
    $('body').on('click', '.store_groupEldwry', function () {
        var obj = $(this);
        store_groupEldwry(0 ,'','classic');
    });
    $('body').on('click', '.update_groupEldwry', function () {
        var obj = $(this);
        var link_group = obj.attr('data-link');
        var type_group = obj.attr('data-type');
        if(type_group=='head'){
            store_head_groupEldwry(1,link_group,'classic');
        }else{
            store_groupEldwry(1,link_group,'classic');
        }
    });

    $('body').on('click', '.store_head_groupEldwry', function () {
        var obj = $(this);
        store_head_groupEldwry(0,'','head');
    });

    $('body').on('click', '.send_invite_emailphone', function () {
        var obj = $(this);
        var link_group = obj.attr('data-link');
        var type_group = obj.attr('data-type');
        send_invite_emailphone(link_group,type_group);
    });

    $('body').on('click', '.show_invite_groupeldwry', function () {
        var obj = $(this);
        var type_page ='invite'; //accept_invite   
        tab_menu_groupEldwry(type_page,'show_invite_groupeldwry');
    });

    $('body').on('click', '.page_send_invite', function () {
        var obj = $(this);
        var link_group = obj.attr('data-link');
        var type_group = obj.attr('data-type');
        var type_page ='invite';   
        tab_menu_groupEldwry(type_page,'page_send_invite',link_group,type_group);
        setting_invite_group_eldwry(link_group,type_group);
    });

    $('body').on('click', '.group_eldwry_join', function () {
        var obj = $(this);
        var type_page ='join';   
        tab_menu_groupEldwry(type_page,'group_eldwry_join');
    });

    $('body').on('click', '.group_eldwry_join_classic', function () {
        var obj = $(this);
        var type_page ='join_classic';   
        var type_group = 'classic';
        tab_menu_groupEldwry(type_page,'group_eldwry_join','',type_group);
    });

    $('body').on('click', '.group_eldwry_join_head', function () {
        var obj = $(this);
        var type_page ='join_head';  
        var type_group = 'head';
        tab_menu_groupEldwry(type_page,'group_eldwry_join','',type_group);
    });

    $('body').on('click', '.add_joinLink_group_eldwry', function () {
        var obj = $(this);
        var link_group = obj.attr('data-link');
        var type_group = obj.attr('data-type');
        add_join_group_eldwry('link',link_group,type_group);
        return false;
    });

    $('body').on('click', '.add_join_group_eldwry', function () {
        var obj = $(this);
        var type_group = obj.attr('data-type');
        add_join_group_eldwry('code','',type_group);
        return false;
    });

    $('body').on('click', '.show_group_groupeldwry', function () {
        var obj = $(this);
        var link_group = obj.attr('data-link');
        var type_group = obj.attr('data-type');
        var type_page ='group';   
        tab_menu_groupEldwry(type_page,'show_group_groupeldwry',link_group,type_group);
        get_current_sub_eldwry('subeldwry_groupEldwry',type_group,link_group,'<option value="">'+overAll+'</option>');
        get_data_group_eldwry(link_group,'',type_group);
    });

    $('body').on('click', '.show_admin_groupeldwry', function () {
        var obj = $(this);
        var link_group = obj.attr('data-link');
        var type_group = obj.attr('data-type');
        var type_page ='admin';   
        tab_menu_groupEldwry(type_page,'show_admin_groupeldwry',link_group);
        get_current_sub_eldwry('subeldwry_groupEldwry',type_group);
        setting_admin_group_eldwry(link_group,type_group);
    });

    $('body').on('click', '.leave_groupeldwry', function () {
        var obj = $(this);
        var link_group = obj.attr('data-link');
        var type_group = obj.attr('data-type');
        var type_page ='leave';   
        operation_group_eldwry(link_group,'leave',type_group);
        return false;
    });

	$('body').on('click', '.stop_group_eldwry', function () {
        var obj = $(this);
        var link_group = obj.attr('data-link');
        var type_group = obj.attr('data-type');
        operation_group_eldwry(link_group,'stop',type_group);
        return false;
    });

    $('body').on('click', '.deleteplayer_group_eldwry', function () {
        var obj = $(this);
        var link_group = obj.attr('data-link');
        var type_group = obj.attr('data-type');
        var user_name = $('body').find('.player_user_group').val();
        operation_group_eldwry(link_group,'delete_player',type_group,user_name);
        return false;
    });

    $('body').on('click', '.addadmin_group_eldwry', function () {
        var obj = $(this);
        var link_group = obj.attr('data-link');
        var type_group = obj.attr('data-type');
        var user_name = $('body').find('.admin_user_group').val();       
        operation_group_eldwry(link_group,'add_admin',type_group,user_name);
        return false;
    });

    $('body').on('change', '.filter_groupEldwry', function () {
        var obj = $(this);
        var sub_eldwry_link=obj.val();
        var link_group = obj.attr('data-link');
        var type_group = obj.attr('data-type');
        get_data_group_eldwry(link_group,sub_eldwry_link,type_group);
        return false;
    });
    
});

//***********************************
function load_main_group_eldwry(){ 
    get_normal_eldwry();
    get_head_eldwry();
    return false;
}
function public_loadFunction(){ 
    $('body').find('.tab_game_group_eldwry').addClass('active');
    return false;
}
function get_normal_eldwry(){ 
    $.ajax({
        type: "post",
        url: url + '/get_normal_eldwry',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                draw_table_group_eldwry(data.data.group_eldwry,data.user_id,'draw_normal_eldwry','classic');
            }
        },
        complete: function (data) {
            return false;
        }
    });
    return false;
}
function get_head_eldwry(){ 
    $.ajax({
        type: "post",
        url: url + '/get_head_eldwry',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                draw_table_group_eldwry(data.data.group_eldwry,data.user_id,'draw_head_eldwry','head');
            }
        },
        complete: function (data) {
            return false;
        }
    });
    return false;
}
function draw_table_group_eldwry(all_data,user_id=0,class_name='',type_group='') {
   var div_section = '<thead>';
        div_section += '<tr>';
            div_section += '<th></th>';
            div_section += '<th>'+league+'</th>';
            div_section += '<th>'+current_rank+'</th>';
            div_section += '<th>'+last_rank+'</th>';
            div_section += '<th class="td-width"></th>';
        div_section += '</tr>';
    div_section += '</thead>';
    div_section += '<tbody>';
    if (all_data != '') {
        div_section += Loop_draw_table_group_eldwry(all_data,user_id,type_group);
    }else {
        div_section += info_dataDiv(not_found);
    }
    div_section += '</tbody>';
    $("."+class_name).text('');
    $('.'+class_name).html(div_section);
}
function Loop_draw_table_group_eldwry(all_data,user_id=0,type_group=''){
    var div_section='';
    $.each(all_data, function (index, value) {
            div_section +='<tr>';
            // value.current_sort
            if(index==0){
                div_section +='<td class="green">';
                   div_section +='<i class="fa fa-arrow-up"></i>'; 
            }else if(index==1){
                div_section +='<td class="red">';
                   div_section +='<i class="fa fa-arrow-down"></i>'; 
            }else{
                div_section +='<td class="gray">';
                   div_section +='<i class="fa fa-circle"></i>'; 
            }  
                div_section +='</td>';
                div_section +='<td><a data-name="' + value.name + '" data-link="' + value.link + '" data-type="'+type_group+'" class="show_group_groupeldwry">' + value.name + '</a></td>';
                div_section +='<td>' + value.current_sort + '</td>';
                div_section +='<td>' + value.prev_sort + '</td>';
                div_section +='<td>';
                    owner='';
                    if(user_id == value.user_id){owner='owner';} 
                    div_section += '<a data-owner="' +owner + '" data-name="' + value.name + '" data-link="' + value.link + '" data-type="'+type_group+'" class="butn show_eldwryoptions" >';
                        div_section +='<i class="fa fa-cog"></i><span class="hide-option">'+options+'</span>';
                    div_section +='</a>';
                div_section +='</td>';
            div_section +='</tr>';
        });
    return div_section;
}
function tab_menu_groupEldwry(type_page,active_class,link_group='',type_group=''){ 
    $('body').find('.'+active_class).addClass('active');
    $.ajax({
        type: "post",
        url: url + '/tab_menu_groupEldwry',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            type_page: type_page,
            link:link_group,
            type_group:type_group
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                if(data.redirect_route!=''){
                    window.location.href =url+'/' + data.redirect_route;
                }else{
                    history.pushState("data to be passed", "Title of the page",data.current_url_page);
                    $('body').find('.Draw_tab_game_transfer').text('');
                    $('body').find('.Draw_tab_game_transfer').html(data.response);
                }
                closePopModel();
                //location.reload(true);
            }
        },
        complete: function (data) {
            return false;
        }
    });
    return false;
}

function get_current_sub_eldwry(class_content='',type_group='',link_group='',overAll=''){ 
    $.ajax({
        type: "post",
        url: url + '/get_current_subeldwry_group',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            link_group:link_group,
            type_group:type_group
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                if(data.data!=''){
                    div_section = overAll;
        			$.each(data.data, function (index, value) {
                    	div_section += '<option value="'+ value.link+'">' + value.lang_num_week + '</option>';
        			});
				    $("."+class_content).text('');
				    $('.'+class_content).html(div_section);
                }
            }
        },
        complete: function (data) {
            return false;
        }
    });
    return false;
}

function store_groupEldwry(update=0,link_group='',type_group='classic'){ 
	var name_groupEldwry=$('body').find('.name_groupEldwry').val();
    var subeldwry_groupEldwry=$('body').find('.subeldwry_groupEldwry').val();
    var type_group='classic';   
    $.ajax({
        type: "post",
        url: url + '/store_groupEldwry',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            name: name_groupEldwry,
            link_subeldwry:subeldwry_groupEldwry,
            update:update,
            link_group:link_group
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                $('body').find('.redirect_admin_group_eldwry').attr('href','/game/groups/admin/'+type_group);
                if(data.status ==1 || data.status =='1'){ //add
					//invitelink_group
                    history.pushState('data to be passed', 'Title of the page', data.current_url_page);
                    $('body').find('.Draw_tab_game_transfer').text('');
                    $('body').find('.Draw_tab_game_transfer').html(data.response);
                	public_share_data(data.group_eldwry); 
            	}else if(data.status ==2 || data.status =='2'){ //update
            		var update_msg = success_dataDiv();
			        $('body').find('.notif-msg').html(update_msg);
                }else{
                    var fail_msg = fail_dataDiv();
			        $('body').find('.notif-msg').html(fail_msg);
                }
                setTimeout(function () {
		            emptyALLdataMsg();
		        }, 5000);
            }
        },
        complete: function (data) {
            return false;
        }
    });
    return false;
}

function store_head_groupEldwry(update=0,link_group='',type_group='head'){ 
    var name_groupEldwry=$('body').find('.name_groupEldwry').val();
    var subeldwry_groupEldwry=$('body').find('.subeldwry_groupEldwry').val();
    $.ajax({
        type: "post",
        url: url + '/store_head_groupEldwry',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            name: name_groupEldwry,
            link_subeldwry:subeldwry_groupEldwry,
            update:update,
            link_group:link_group,
            type_group:type_group
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                $('body').find('.redirect_admin_group_eldwry').attr('href','/game/groups/admin/'+type_group);
                if(data.status ==1 || data.status =='1'){ //add
                    history.pushState('data to be passed', 'Title of the page', data.current_url_page);
                    $('body').find('.Draw_tab_game_transfer').text('');
                    $('body').find('.Draw_tab_game_transfer').html(data.response);
                    public_share_data(data.group_eldwry); 
                }else if(data.status ==2 || data.status =='2'){ //update
                    var update_msg = success_dataDiv();
                    $('body').find('.notif-msg').html(update_msg);
                }else{
                    var fail_msg = fail_dataDiv();
                    $('body').find('.notif-msg').html(fail_msg);
                }
                setTimeout(function () {
                    emptyALLdataMsg();
                }, 5000);
            }
        },
        complete: function (data) {
            return false;
        }
    });
    return false;
}

function send_invite_emailphone(link_group='',type_group=''){ 
    var emailphone=$('body').find('.val_invite_emailphone').val();       
    var code=$('body').find('.valcode_group').text();       
    $.ajax({
        type: "post",
        url: url + '/send_invite_emailphone',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            emailphone: emailphone,
            type_group:type_group,
            link_group:link_group,
            code:code
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                if(data.status ==1 || data.status =='1'){ //send
                    var send_msg = success_dataDiv();
                    $('body').find('.notif-msg').html(send_msg);
                }else if(data.status ==-1 || data.status =='-1'){ //valnocorrect
                    var send_msg = fail_dataDiv(data.val_msg);
                    $('body').find('.notif-msg').html(send_msg);
                }else{
                    var fail_msg = fail_dataDiv();
                    $('body').find('.notif-msg').html(fail_msg);
                }
                setTimeout(function () {
                    emptyALLdataMsg();
                }, 5000);
            }
        },
        complete: function (data) {
            return false;
        }
    });
    return false;
}

function get_last_group_eldwry(type_group){
    $.ajax({
        type: "post",
        url: url + '/get_last_group_eldwry',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            type_group:type_group,
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                var group_eldwry=data.group_eldwry;
                if(group_eldwry!=''){
                    public_share_data(group_eldwry);
                }
            }
        },
        complete: function (data) {
            return false;
        }
    });
    return false;
}
function setting_invite_group_eldwry(link_group='',type_group=''){
    //$('body').find('.page_send_invite').attr('data-link',link_group);
    $.ajax({
        type: "post",
        url: url + '/setting_invite_group_eldwry',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            link:link_group,
            type_group:type_group
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                var group_eldwry=data.data.group_eldwry;
                if(group_eldwry!=''){
                    var invitelink=url + '/game/groups/invite/accept/'+type_group+'/'+group_eldwry.link;
                    if(body_lang=='ar' || body_lang=="ar"){
                        var text_email='فانتازي الدوري السعودي&body=مرحبا,%0D%0A يشرفني انضمامك إلي الدوري الخاص بي في فانتازي الدوري السعودي %0D%0A '+invitelink+' %0D%0A كود الدوري: '+group_eldwry.code+' %0D%0A في انتظارك';
                    }else{
                        var text_email='Fantasy Saudi League & body = Hello,%0D%0A I am honored to join my league in Fantasy Saudi League% %0D%0A '+ invitelink +' %0D%0A League code: '+ group_eldwry.code +'%0D%0A waiting for you';
                    }
                    $('body').find('.valcode_group').text(group_eldwry.code);
                    $('body').find('.name_group_eldwry').text('" '+group_eldwry.name+' "');
                    $('body').find('.invitelink_group').text(invitelink);
                    $('body').find('.inviteEmailtesxt_group').attr('onclick',"javascript:window.location='mailto:?subject="+text_email+"'");
                }
            }
        },
        complete: function (data) {
            return false;
        }
    });
    return false;
}

function setting_admin_group_eldwry(link_group='',type_group=''){
	$.ajax({
        type: "post",
        url: url + '/setting_admin_group_eldwry',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            link:link_group,
            type_group:type_group
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
            	var group_eldwry=data.data.group_eldwry;
                if(group_eldwry!=''){
                    public_share_data(group_eldwry,type_group);
                    $(".subeldwry_groupEldwry option[value="+group_eldwry.subeldwry_link+"]").attr("selected","selected");
                    get_user_group_eldwry(data.data.users_group);
                }
            }
        },
        complete: function (data) {
            return false;
        }
    });
    return false;
}

function public_share_data(group_eldwry,type_group='classic'){
    if (group_eldwry !== "") {
        var invitelink = url + '/game/groups/invite/accept/'+type_group+'/'+group_eldwry.link;
        $('body').find('.valcode_group').text(group_eldwry.code);
        $('body').find('.name_groupEldwry').val(group_eldwry.name);
        $('body').find('.name_groupEldwry').text(group_eldwry.name);
        $('body').find('.invitelink_group').text(invitelink);
        $('body').find('.send_invite_emailphone').attr('data-link', group_eldwry.link);
        $('body').find('.send_invite_emailphone').attr('data-type',type_group);
        $('body').find('.stop_group_eldwry').attr('data-link', group_eldwry.link);
        $('body').find('.stop_group_eldwry').attr('data-type', type_group);
        $('body').find('.deleteplayer_group_eldwry').attr('data-link', group_eldwry.link);
        $('body').find('.deleteplayer_group_eldwry').attr('data-type',type_group);
        $('body').find('.addadmin_group_eldwry').attr('data-link', group_eldwry.link);
        $('body').find('.addadmin_group_eldwry').attr('data-type', type_group);
        $('body').find('.update_groupEldwry').attr('data-link', group_eldwry.link);
        $('body').find('.update_groupEldwry').attr('data-type', type_group);
   }
    return false;
}

function closePopModel(){
    $('body').find('.modal-backdrop.in').css('opacity','0');
    $('body').find('.modal-backdrop').css('display','none');
    $('body').find('.close').click();
    return false;
}
function get_user_group_eldwry(users_group){ 
    var div_section='';
    if(users_group!=''){
        $.each(users_group, function (index, value) {
            div_section += '<option value="'+ value.user_name+'">' + value.display_name + '</option>';
        });
    }
    $(".draw_users_group").text('');
    $('.draw_users_group').html(div_section);
    return false;
}
function operation_group_eldwry(link_group='',type_page='',type_group='classic',user_name=''){
    if(link_group!=''){
    	$.ajax({
            type: "post",
            url: url + '/operation_group_eldwry',
            data: {
                _token: $('meta[name="_token"]').attr('content'),
                link:link_group,
                user_name:user_name,
                type_page:type_page,
                type_group:type_group
            },
            cache: false,
            dataType: 'json',
            success: function (data) {
                if (data !== "") {
                	if(type_page =='stop' && (data.status==1 || data.status=='1')){
    			        var delete_msg = delete_dataDiv();
    			        $('body').find('.notif-msg').html(delete_msg);
    			        setTimeout(function () {
    			            emptyALLdataMsg();
                        	window.location.href=url+'/game/groups';
    			        }, 3000);
                	}else if(data.status ==1 || data.status =='1'){
                        if(type_page == 'leave'){
                            var delete_msg = delete_dataDiv();
                            $('body').find('.notif-msg').html(delete_msg);
                            get_normal_eldwry();
                        }else if(type_page == 'add_admin'){
                            var add_msg = success_dataDiv();
                            $('body').find('.notif-msg').html(add_msg);
                            setTimeout(function () {
                                emptyALLdataMsg();
                                $('body').find('.tab_game_group_eldwry').click();
                            }, 2000);
                        }else if(type_page == 'delete_player'){
                            var delete_msg = delete_dataDiv();
                            $('body').find('.notif-msg').html(delete_msg);
                            get_user_group_eldwry(data.users_group);
                            $('body').find('.player_user_group').val('');
                        }
                    }else{
                        var fail_msg = fail_dataDiv();
                        $('body').find('.notif-msg').html(fail_msg);
                    }
                    setTimeout(function () {
                        emptyALLdataMsg();
                        $('body').find('.close').click();
                    }, 3000);
                }    
            },
            complete: function (data) {
                return false;
            }
        });
    }    
    return false;
}

function add_join_group_eldwry(val_type,link_group='',type_group=''){ 
    var val_code=$('body').find('.input_join_group_eldwry').val();
    $.ajax({
        type: "post",
        url: url + '/add_join_group_eldwry',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            val_type:val_type,
            val_code: val_code,
            type_group:type_group,
            link_group:link_group
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                if(data.status ==1 || data.status =='1'){ //add
                    var add_msg = success_dataDiv();
                    $('body').find('.notif-msg').html(add_msg);
                }else if(data.status ==2 || data.status =='2'){ //found before
                    var used_msg = success_dataDiv(already_add);
                    $('body').find('.notif-msg').html(used_msg);
                }else if(data.status ==-1 || data.status =='-1'){ //owner
                     var own_msg = info_dataDiv(owner_msg);
                    $('body').find('.notif-msg').html(own_msg);
                }else{
                    var fail_msg = fail_dataDiv();
                    $('body').find('.notif-msg').html(fail_msg);
                }
                setTimeout(function () {
                    emptyALLdataMsg();
                    $('body').find('.tab_game_group_eldwry').click();
                }, 2000);
            }
        },
        complete: function (data) {
            return false;
        }
    });
    return false;
}
function get_data_group_eldwry(link_group='',sub_eldwry_link='',type_group=''){
    $('body').find('.page_send_invite').attr('data-link','');
    $('body').find('.page_send_invite').addClass('hidden');
    console.log(link_group)
    if(link_group!=''){
        $.ajax({
            type: "post",
            url: url + '/get_data_group_eldwry',
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
                    if(data.data.owner ==1 ||data.data.owner =='1' ){
                        $('body').find('.page_send_invite').attr('data-link',link_group);
                        $('body').find('.page_send_invite').removeClass('hidden');
                    } 
                    $('body').find('.name_group_eldwry').text(data.data.group_eldwry.name);
                    if(type_group=='head' || type_group== "head"){
                        $('body').find('.standing_head').removeClass('hidden');
                        draw_data_head_group_eldwry(data.data.matches_group,data.user_id);
                    }else{
                        $('body').find('.standing_classic').removeClass('hidden');
                    }
                    draw_data_group_eldwry(data.data.users_group,data.user_id);
                }
            },
            complete: function (data) {
                $('body').find('.subeldwry_groupEldwry').attr('data-link',link_group); 
                return false;
            }
        });
    }
    return false;
}

function draw_data_group_eldwry(all_data,user_id=0) {
   var div_section = '<thead>';
        div_section += '<tr>';
            div_section += '<th></th>';
            div_section += '<th>'+rank+'</th>';
            div_section += '<th>'+teams+'</th>';
            div_section += '<th>'+GW+'</th>';
            div_section += '<th>'+TOT+'</th>';
        div_section += '</tr>';
    div_section += '</thead>';
    div_section += '<tbody>';
    if (all_data != '') {
        $(".subeldwry_groupEldwry option[value="+all_data[0].subeldwry_link+"]").attr("selected","selected");
        $.each(all_data, function (index, value) {
            div_section +='<tr>';
                if(index==0){
                    div_section +='<td class="green">';
                       div_section +='<i class="fa fa-arrow-up"></i>'; 
                }else if(index==1){
                    div_section +='<td class="red">';
                       div_section +='<i class="fa fa-arrow-down"></i>'; 
                }else{
                    div_section +='<td class="gray">';
                       div_section +='<i class="fa fa-circle"></i>'; 
                }  
                div_section +='</td>';
                div_section +='<td>' + value.sort + '</td>';
                div_section +='<td>';
                    // tab_menu_game
                    div_section +='<a class="tab_game_my_team">' + value.name_team + '</a>';
                    div_section +='<span class="user-name">' + value.display_name + '</span>';
                div_section +='</td>';
                div_section +='<td>' + value.gw_points + '</td>';
                div_section +='<td>' + value.total_points + '</td>';
            div_section +='</tr>';
        });
    } else {
        div_section += info_dataDiv(not_found);
    }
    div_section += '</tbody>';
    $(".draw_data_group_eldwry").text('');
    $('.draw_data_group_eldwry').html(div_section);
}

function draw_data_head_group_eldwry(all_data,user_id=0) {
   var div_section = '<thead>';
        div_section += '<tr>';
            div_section += '<th class="td-width">'+GW+'</th>';
            div_section += '<th>'+teams+'</th>';
            div_section += '<th></th>';
            div_section += '<th>'+teams+'</th>';
        div_section += '</tr>';
    div_section += '</thead>';
    div_section += '<tbody>';
    if (all_data != '') {
        $(".subeldwry_groupEldwry option[value="+all_data[0].subeldwry_link+"]").attr("selected","selected");
        $.each(all_data, function (index, value) {
            div_section +='<tr>';
                div_section +='<td>' + value.num_week  + '</td>';
                div_section +='<td>';
                    div_section +='<a>' + value.first_team_name  + '</a>';
                    div_section +='<span class="user-name">' + value.first_user_name  + '</span>';
                div_section +='</td>';
                div_section +='<td>';
                    div_section +='<div class="Score">';
                        div_section +='<span>' + value.first_team_points + '</span>';
                        div_section +='<span>' + value.second_team_points  + '</span>';
                    div_section +='</div>';
                div_section +='</td>';
                div_section +='<td>';
                    div_section +='<a>' + value.second_team_name + '</a>';
                    div_section +='<span class="user-name">' + value.second_user_name  + '</span>';
                div_section +='</td>';
            div_section +='</tr>';
        });
    } else {
        div_section += info_dataDiv(not_found);
    }
    div_section += '</tbody>';
    $(".draw_data_head_group_eldwry").text('');
    $('.draw_data_head_group_eldwry').html(div_section);
}