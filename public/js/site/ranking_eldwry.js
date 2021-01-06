$(document).ready(function () {

    $('body').on('change', '.filter_subeldwry_ranking', function () {
        var obj = $(this);
        var subeldwry_link =$('body').find('.filter_subeldwry_ranking').val();
        if (typeof subeldwry_link == 'undefined' || subeldwry_link == '' || subeldwry_link == "") {
            return false;
        }
        get_ranking_eldwry(subeldwry_link);
        return false;
    });

    $('body').on('change', '.filter_location_match_ranking', function () {
        var obj = $(this);
        filter_result_location_match();
        // return false;
    });

    $('body').on('change', '.filter_result_match_ranking', function () {
        var obj = $(this);
        filter_result_location_match();
        // return false;
    });
    //****************************** End ranking_eldwry *******************************************************************
});
function filter_result_location_match(){
    var result_match =$('body').find('.filter_result_match_ranking').val();
    var location_match =$('body').find('.filter_location_match_ranking').val();
        
    // win  lose  drawn //won draw  loss
    $('body').find('.win').removeClass('hidden');
    $('body').find('.drawn').removeClass('hidden');
    $('body').find('.lose').removeClass('hidden');
    //away //home
    $('body').find('.away').removeClass('hidden');
    $('body').find('.home').removeClass('hidden');

    if(location_match=="home" || location_match=='home' ){
        $('body').find('.away').addClass('hidden');
        $('body').find('.home').removeClass('hidden');
    }else if(location_match=="away" || location_match=='away'){
        $('body').find('.home').addClass('hidden');
        $('body').find('.away').removeClass('hidden');
    }

    if(result_match=="win" || result_match=='win' ||result_match=="won" || result_match=='won'){
        $('body').find('.lose').addClass('hidden');
        $('body').find('.drawn').addClass('hidden');
        $('body').find('.win').removeClass('hidden');
    }else if(result_match=="drawn" || result_match=='drawn' ||result_match=="draw" || result_match=='draw'){
        $('body').find('.win').addClass('hidden');
        $('body').find('.lose').addClass('hidden');
        $('body').find('.drawn').removeClass('hidden');
    }else if(result_match=="lose" || result_match=='lose' ||result_match=="loss" || result_match=='loss'){
        $('body').find('.win').addClass('hidden');
        $('body').find('.drawn').addClass('hidden');
        $('body').find('.lose').removeClass('hidden');
    }
 
    return false;
}
function Load_ranking_eldwry(){
    get_subeldwry_ranking_eldwry('filter_subeldwry_ranking');
    get_ranking_eldwry();
    return false;
}

function get_subeldwry_ranking_eldwry(class_content){ 
    $.ajax({
        type: "post",
        url: url + '/get_subeldwry_ranking_eldwry',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                if(data.data!=''){
                    div_section ='';// '<option value="">عرض حسب</option>';
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

function get_ranking_eldwry(subeldwry_link=''){
    $.ajax({
        type: "post",
        url: url + '/get_ranking_eldwry',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            subeldwry_link: subeldwry_link,
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data != "") {
               draw_ranking_eldwry(data.data.ranking_eldwry);
               afterdraw_ranking_eldwry(data.data.active_subeldwry);
            }
        },
        complete: function (data) {
            return false;
        }});
    return false;
}
function afterdraw_ranking_eldwry(active_subeldwry){
    if(active_subeldwry!="" && active_subeldwry!=null ){
        $(".filter_subeldwry_ranking option[value="+active_subeldwry.subeldwry_link+"]").attr("selected","selected");
    }
    filter_result_location_match();
    return false;
}

function draw_ranking_eldwry(all_data){
    var div_section = '';
    if (all_data != '') {
        $.each(all_data, function (index, value) {
            index_num = Number(index) + Number(1);
            div_section += '<tr class="'+value.class_state+'">';
                div_section +='<th>';
                    div_section +='<div class="order">'+index_num+'</div>';
                div_section +='</th>';
                div_section +='<td class="text-center">';
                    div_section +='<div class="club-image">';
                        div_section +='<img src="'+value.team_image+'" alt="club-logo">';
                    div_section +='</div>';
                div_section +='</td>';
                div_section +='<td>';
                    div_section +='<div class="club-name">'+value.team_name+'</div>';
                div_section +='</td>';
                div_section +='<td>'+value.count_played+'</td>';
                div_section +='<td>'+value.won+'</td>';
                div_section +='<td>'+value.draw+'</td>';
                div_section +='<td>'+value.loss+'</td>';
                div_section +='<td>'+value.goals_own+'</td>';
                div_section +='<td>'+value.goals_aganist+'</td>';
                div_section +='<td>'+value.goals_diff+'</td>';
                div_section +='<td>'+value.points+'</td>';
                div_section +='<td>';
                   div_section += draw_form_ranking_eldwry(value.form);
                div_section +='</td>';
                div_section +='<td class="text-center">';
                    if(value.next_match !="" && value.next_match !=null){
                        div_section +='<div class="club-image">';
                            div_section +='<img src="'+value.next_match.second_team_image+'" alt="club-logo">';
                            div_section +='<a href="match-detailes.html" class="tooltipContainer" role="tooltip">';
                                div_section +='<span class="tooltip-content">';
                                    div_section +='<div class="matchAbridged">';
                                        div_section +='<span class="matchInfo">'+value.next_match.date_day+' </span>';
                                        div_section +='<span class="teamName">'+value.team_name+'</span>';
                                        div_section +='<span class="badge badge-image-container" data-widget="club-badge-image" data-size="20">';
                                        div_section +='<img class="badge-image" src="'+value.team_image+'" alt="club-logo">';
                                        div_section +='</span>';
                                        div_section +='<time>'+value.next_match.time+'</time>';
                                        div_section +='<span class="badge badge-image-container" data-widget="club-badge-image" data-size="20">';
                                        div_section +='<img class="badge-image" src="'+value.next_match.second_team_image+'" alt="club-logo">';
                                        div_section +='</span>';
                                        div_section +='<span class="teamName">'+value.next_match.second_team_name+'</span>';
                                    div_section +='</div>';
                                div_section +='</span>';
                            div_section +='</a>';
                        div_section +='</div>';
                    }else{
                        div_section +='---';
                    }
                div_section +='</td>';
                div_section +='<td>';
                    div_section +='<a href="#Q'+index+'" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">';
                        div_section +='<div class="arrow"><i class="fa fa-chevron-down"></i></div>';
                    div_section +='</a>';
                div_section +='</td>';
            div_section +='</tr>';
            div_section +='<tr id="Q'+index+'" class="expandable panel-collapse collapse">';
               div_section += draw_expandableTeam(value.site_team,value.current_match,value.next_match,value.team_link,value.team_name,value.team_image);
            div_section +='</tr>';
        });
    } else {
        div_section +='<tr><td colspan="15">';
            div_section += info_dataDiv(not_found);
        div_section +='</td></tr>';
    }
    $(".draw_ranking_eldwry").text('');
    $('.draw_ranking_eldwry').html(div_section);
}

function draw_form_ranking_eldwry(all_data){
    var div_section = '';
    if (all_data != '') {
        $.each(all_data, function (index, value) {
            if(value.state=="w" || value.state=='w'){
                class_state="win";
            }else if(value.state=="d" || value.state=='d'){
                class_state="drawn";
            }else{ //l
                class_state="lose";
            }
            div_section += '<div class="form">';
                div_section += '<div class="form-stauts '+class_state+'  '+value.location_type+'">'+value.state_lang+'</div>';
                div_section += '<a href="" class="tooltipContainer" role="tooltip">';
                    div_section += '<span class="tooltip-content">';
                        div_section += '<div class="matchAbridged">';
                            div_section += '<span class="matchInfo">'+value.date_day+'</span>';
                            div_section += '<span class="teamName">'+value.first_team_name+'</span>';
                            div_section += '<span class="badge badge-image-container" data-widget="club-badge-image" data-size="20">';
                            div_section += '<img class="badge-image" src="'+value.first_team_image+'" alt="club-logo">';
                            div_section += '</span>';
                            div_section += '<span class="score">'+value.first_team_goon+' <span>-</span> '+value.second_team_goon+'</span>';
                            div_section += '<span class="badge badge-image-container" data-widget="club-badge-image" data-size="20">';
                            div_section += '<img class="badge-image" src="'+value.second_team_image+'" alt="club-logo">';
                            div_section += '</span>';
                            div_section += '<span class="teamName">'+value.second_team_name+'</span>';
                        div_section += '</div>';
                    div_section += '</span>';
                div_section += '</a>';
            div_section += '</div>';
        });
    }        
  return div_section;
}

function draw_expandableTeam(site_team,current_match,next_match,first_team_link,first_team_name,first_team_image){
 var div_section = '<td colspan="14">';
        div_section += '<div class="row">';
            div_section += '<div class="col-md-3">';
                div_section += '<a href="#" class="expandableTeam">';
                    div_section += '<img src="'+first_team_image+'" alt="club-logo">';
                    div_section += '<span class="teamName">'+first_team_name+'</span>';
                div_section += '</a>';
            div_section += '</div>';
            div_section += '<div class="col-md-9">';
                div_section += '<div class="col-md-4">';
                    div_section += '<div class="matchAbridged">';
                        div_section += '<span class="matchInfo"><strong>أخر نتيجة </strong>- '+current_match.date_day+'</span>';
                        div_section += '<span class="teamName">'+current_match.first_team_name+'</span>';
                        div_section += '<span class="badge badge-image-container" data-widget="club-badge-image" data-size="20">';
                        div_section += '<img class="badge-image" src="'+current_match.first_team_image+'" alt="club-logo">';
                        div_section += '</span>';
                        div_section += '<span class="score">'+current_match.first_team_goon+' <span>-</span> '+current_match.second_team_goon+'</span>';
                        div_section += '<span class="badge badge-image-container" data-widget="club-badge-image" data-size="20">';
                        div_section += '<img class="badge-image" src="'+current_match.second_team_image+'" alt="club-logo">';
                        div_section += '</span>';
                        div_section += '<span class="teamName">'+current_match.second_team_name+'</span>';
                    div_section += '</div>';
                div_section += '</div>';
                div_section += '<div class="col-md-4">';
                    if(next_match !="" && next_match !=null){
                       div_section += '<div class="matchAbridged">';
                            div_section += '<span class="matchInfo"><strong>المباراة القادمة </strong>- '+next_match.date_day+' </span>';
                            div_section += '<span class="teamName">'+first_team_name+'</span>';
                            div_section += '<span class="badge badge-image-container" data-widget="club-badge-image" data-size="20">';
                            div_section += '<img class="badge-image" src="'+first_team_image+'" alt="club-logo">';
                            div_section += '</span>';
                            div_section += '<time> '+next_match.time+' </time>';
                            div_section += '<span class="badge badge-image-container" data-widget="club-badge-image" data-size="20">';
                            div_section += '<img class="badge-image" src="'+next_match.second_team_image+'" alt="club-logo">';
                            div_section += '</span>';
                            div_section += '<span class="teamName">'+next_match.second_team_name+'</span>';
                        div_section += '</div>';
                    }
                div_section += '</div>';
                div_section += '<div class="col-md-4">';
                    div_section += '<a href="'+site_team+'" class="butn float-left">الذهاب لموقع النادي</a>';
                div_section += '</div>';
            div_section += '</div>';
            div_section += '<div class="col-md-12">';
                div_section += '<hr><h3>مخطط الأداء:</h3>';
            div_section += '</div>';
        div_section += '</div>';
    div_section += '</td>';
    return div_section;
}
