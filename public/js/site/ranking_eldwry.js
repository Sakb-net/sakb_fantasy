$(document).ready(function () {

    $('body').on('click', '.filter_ranking_eldwry', function () {
        var obj = $(this);
        var user_key = obj.attr('data-user');
        var data_link = obj.attr('data-link');
        if ((body_user_key != user_key) || user_key == '' || user_key == "") {
            $('.bi-noti-fav').html('<div class ="bi-noti-wrap show-noti alert alert-danger" role="alert" ><button class="close" aria-label="Close" data-dismiss="alert" type="button"><span aria-hidden="true">×</span></button><p style="margin: 0px;">' + log_add_fav + '<a style="color:blue;" href="' + login_url + '">' + reg_login + '</a></p> </div>');
            setTimeout(function () {
                $('.show-noti').remove();
            }, 5000);
            return false;
        }
        if (typeof data_link == 'undefined' || data_link == '' || data_link == "") {
            return false;
        }
        $.ajax({
            type: "post",
            url: url + '/filter_ranking_eldwry',
            data: {
                _token: $('meta[name="_token"]').attr('content'),
                link: data_link,
                // type: 'news'
            },
            cache: false,
            dataType: 'json',
            success: function (data) {
                if (data != "") {
                    // var state_fav = data.state_action;
                   
                }
            },
            complete: function (data) {
                return false;
            }});
        return false;
    });
    //****************************** End ranking_eldwry *******************************************************************
});

function Load_ranking_eldwry(){
    get_ranking_eldwry();
    return false;
}
function get_ranking_eldwry(){
    $.ajax({
        type: "post",
        url: url + '/get_ranking_eldwry',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data != "") {
                console.log(data);
               draw_ranking_eldwry(data.data);
            }
        },
        complete: function (data) {
            return false;
        }});
    return false;
}
function draw_ranking_eldwry(all_data){
    var div_section = '';
    if (all_data != '') {
        $.each(all_data, function (index, value) {
            index_num = Number(index) + Number(1);
            div_section += '<tr>';
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
        div_section += info_dataDiv(not_found);
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
                div_section += '<div class="form-stauts '+class_state+'">'+value.state_lang+'</div>';
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
                    div_section += '<img src="'+current_match.first_team_image+'" alt="club-logo">';
                    div_section += '<span class="teamName">'+current_match.first_team_name+'</span>';
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
