function GetDataMatch_Public(limit,offset=1,page_name='',num_week=0,team='') {
    $.ajax({
        type: "post",
        url: url + '/get_data_match_public',
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            limit: limit,
            offset: offset,
            num_week:num_week,
            team:team,
            val_view: '0'
        },
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data !== "") {
                LastNewMatch_eldawry(data.match_public);
                if(page_name=='fixtures'||page_name=="fixtures"){
                    window.history.pushState({}, document.title, "/" + page_name+"?week="+data.num_week);
                    pagination_fixtures(data.count_pag,data.num_week);
                }
                return false;
            }
        },
        complete: function (data) {
            return false;
        }});
    return false;
}

function LastNewMatch_eldawry(all_data) {
    var match_index = 1;
    var today = new Date();
    var matchDate;
    var div_section = '<h2 class="text-center">'+matches+'</h2>';
    if (all_data != '') {
        $.each(all_data, function (index, value) {
            div_section += '<h4 class="FixtureDay sub_fixture"><span class="num_week">' + value.lang_num_week + '</span><span class="start_date">' + value.start_date_day + ' ' + value.start_date + '</span>\n\
                            <span class="end_date">' + value.end_date_day + ' ' + value.end_date + '</span></h4>';
            var merg_FixtureDay='';    
            $.each(value.match_group, function (ind_match, val_match) {
                matchDate = new Date(val_match.date.split("-"));
                if(ind_match==0 || merg_FixtureDay=='' || merg_FixtureDay!=val_match.date){
                    merg_FixtureDay=val_match.date;
                    div_section += '<h4 class="FixtureDay">' + val_match.date_day + ' ' + val_match.date + '</h4>';
                }
                div_section += '<div class="fixture-box">';
                if (today > matchDate)
                div_section += '<a href="#collapse'+match_index+'" class="accordion-toggle block" data-toggle="collapse" data-parent="#accordion" aria-expanded="true">';
                    div_section += '<div class="club-fixture">';
                        div_section += '<div class="club-img">';
                            div_section += '<img class="sm-img" src="' + url + val_match.image_first + '" alt="club logo">';
                            div_section += '<span>' + val_match.name_first + '</span>';
                        div_section += '</div>';
                    div_section += '<div class="club-schedule text-center">';
                    if (today <= matchDate) {
                        div_section += '<h5>' + val_match.time + '</h5>';
                    } else {
                        div_section += '<div class="FixtureScore">';
                            div_section += '<span>' + val_match.first_goon + '</span>';
                            div_section += '<span>' + val_match.second_goon + '</span>';
                        div_section += '</div>';
                    }
                    div_section += '</div>';
                    div_section += '<div class="club-img">';
                    div_section += '<span>' + val_match.name_second + '</span>';
                    div_section += '<img class="sm-img" src="' + url + val_match.image_second + '" alt="club logo">';
                    div_section += '</div>';
                    div_section += '</div>';
                if (today > matchDate)    
                div_section += '</a>';

                if (today > matchDate){
                    div_section +=DrawResultMatchTeams(match_index,val_match.details);
                }
                div_section += '</div>';
                match_index++;
            });
        });
    } else {
        div_section += info_dataDiv(not_found);
    }
    $(".LastNewMatch_eldawry").text('');
    $('.LastNewMatch_eldawry').html(div_section);
}
function DrawResultMatchTeams(match_index,details) {
    var div_section='';
    div_section += '<div id="collapse' +match_index+ '" class="panel-collapse collapse" aria-expanded="true" style="">';
        div_section += '<div class="panel-body">';
            div_section += '<div class="fix-container">';
                //Goals scored
                if(details.goals.first_team !='' || details.goals.second_team !=''){
                    div_section += '<ul>';
                        div_section += '<li class="fixtures-detailes">';
                            div_section += '<h5>'+lang_goals+'</h5>';
                            div_section += '<div class="flex-row">';
                                div_section += '<ul class="fix-list">';
                                    $.each(details.goals.first_team, function (index, goals) {
                                        div_section += '<li><a class="control info popModal_infoPlayer" data-name="' + goals.player_name + '" data-link="' + goals.player_link + '">'+goals.player_name+'</a><span class="float-right">('+goals.value+')</span></li>';
                                    });
                                div_section += '</ul>';
                                div_section += '<ul class="fix-list">';
                                    $.each(details.goals.second_team, function (index, goals) {
                                        div_section += '<li><a class="control info popModal_infoPlayer"  data-name="' + goals.player_name + '" data-link="' + goals.player_link + '">'+goals.player_name+'</a><span class="float-left">('+goals.value+')</span></li>';
                                    });
                                div_section += '</ul>';
                            div_section += '</div>';
                        div_section += '</li>';
                    div_section += '</ul>';
                }
                // Assists
                if(details.goalAssist.first_team !='' || details.goalAssist.second_team !=''){
                    div_section += '<ul>';
                        div_section += '<li class="fixtures-detailes">';
                            div_section += '<h5>'+lang_goalAssist+'</h5>';
                            div_section += '<div class="flex-row">';
                                div_section += '<ul class="fix-list">';
                                    $.each(details.goalAssist.first_team, function (index, goalAssist) {
                                        div_section += '<li><a class="control info popModal_infoPlayer"  data-name="' + goalAssist.player_name + '" data-link="' + goalAssist.player_link + '">'+goalAssist.player_name+'</a><span class="float-right">('+goalAssist.value+')</span></li>';
                                    });
                                div_section += '</ul>';
                                div_section += '<ul class="fix-list">';
                                    $.each(details.goalAssist.second_team, function (index, goalAssist) {
                                        div_section += '<li><a class="control info popModal_infoPlayer"  data-name="' + goalAssist.player_name + '" data-link="' + goalAssist.player_link + '">'+goalAssist.player_name+'</a><span class="float-left">('+goalAssist.value+')</span></li>';
                                    });
                                div_section += '</ul>';
                            div_section += '</div>';
                        div_section += '</li>';
                    div_section += '</ul>';
                }    
                //  Yellow cards
                if(details.yellowCard.first_team !='' || details.yellowCard.second_team !=''){
                    div_section += '<ul>';
                        div_section += '<li class="fixtures-detailes">';
                            div_section += '<h5>'+lang_yellowCard+'</h5>';
                            div_section += '<div class="flex-row">';
                                div_section += '<ul class="fix-list">';
                                    $.each(details.yellowCard.first_team, function (index, yellowCard) {
                                        div_section += '<li><a class="control info popModal_infoPlayer"  data-name="' + yellowCard.player_name + '" data-link="' + yellowCard.player_link + '">'+yellowCard.player_name+'</a><span class="float-right">('+yellowCard.value+')</span></li>';
                                    });
                                div_section += '</ul>';
                                div_section += '<ul class="fix-list">';
                                    $.each(details.yellowCard.second_team, function (index, yellowCard) {
                                        div_section += '<li><a class="control info popModal_infoPlayer"  data-name="' + yellowCard.player_name + '" data-link="' + yellowCard.player_link + '">'+yellowCard.player_name+'</a><span class="float-left">('+yellowCard.value+')</span></li>';
                                    });
                                div_section += '</ul>';
                            div_section += '</div>';
                        div_section += '</li>';
                    div_section += '</ul>';
                }    
                //Red cards
                if(details.redCard.first_team !='' || details.redCard.second_team !=''){
                    div_section += '<ul>';
                        div_section += '<li class="fixtures-detailes">';
                            div_section += '<h5>'+lang_redCard+'</h5>';
                            div_section += '<div class="flex-row">';
                                div_section += '<ul class="fix-list">';
                                    $.each(details.redCard.first_team, function (index, redCard) {
                                        div_section += '<li><a class="control info popModal_infoPlayer"  data-name="' + redCard.player_name + '" data-link="' + redCard.player_link + '">'+redCard.player_name+'</a><span class="float-right">('+redCard.value+')</span></li>';
                                    });
                                div_section += '</ul>';
                                div_section += '<ul class="fix-list">';
                                    $.each(details.redCard.second_team, function (index, redCard) {
                                        div_section += '<li><a class="control info popModal_infoPlayer"  data-name="' + redCard.player_name + '" data-link="' + redCard.player_link + '">'+redCard.player_name+'</a><span class="float-left">('+redCard.value+')</span></li>';
                                    });
                                div_section += '</ul>';
                            div_section += '</div>';
                        div_section += '</li>';
                    div_section += '</ul>';
                }    
                // Saves;
                if(details.saves.first_team !='' || details.saves.second_team !=''){
                    div_section += '<ul>';
                        div_section += '<li class="fixtures-detailes">';
                            div_section += '<h5>'+lang_el_saves+'</h5>';
                            div_section += '<div class="flex-row">';
                                div_section += '<ul class="fix-list">';
                                    $.each(details.saves.first_team, function (index, saves) {
                                        div_section += '<li><a class="control info popModal_infoPlayer"  data-name="' + saves.player_name + '" data-link="' + saves.player_link + '">'+saves.player_name+'</a><span class="float-right">('+saves.value+')</span></li>';
                                    });
                                div_section += '</ul>';
                                div_section += '<ul class="fix-list">';
                                    $.each(details.saves.second_team, function (index, saves) {
                                        div_section += '<li><a class="control info popModal_infoPlayer"  data-name="' + saves.player_name + '" data-link="' + saves.player_link + '">'+saves.player_name+'</a><span class="float-left">('+saves.value+')</span></li>';
                                    });
                                div_section += '</ul>';
                            div_section += '</div>';
                        div_section += '</li>';
                    div_section += '</ul>';
                }    
                // Bonus;
                if(details.bouns.first_team !='' || details.bouns.second_team !=''){
                    div_section += '<ul>';
                        div_section += '<li class="fixtures-detailes">';
                            div_section += '<h5>'+lang_bouns+'</h5>';
                            div_section += '<div class="flex-row">';
                                div_section += '<ul class="fix-list">';
                                    $.each(details.bouns.first_team, function (index, bouns) {
                                        div_section += '<li><a class="control info popModal_infoPlayer"  data-name="' + bouns.player_name + '" data-link="' + bouns.player_link + '">'+bouns.player_name+'</a><span class="float-right">('+bouns.value+')</span></li>';
                                    });
                                div_section += '</ul>';
                                div_section += '<ul class="fix-list">';
                                    $.each(details.bouns.second_team, function (index, bouns) {
                                        div_section += '<li><a class="control info popModal_infoPlayer"  data-name="' + bouns.player_name + '" data-link="' + bouns.player_link + '">'+bouns.player_name+'</a><span class="float-left">('+bouns.value+')</span></li>';
                                    });
                                div_section += '</ul>';
                            div_section += '</div>';
                        div_section += '</li>';
                    div_section += '</ul>';
                }    
                //Bonus Points System;
                if(details.bouns_system.first_team !='' || details.bouns_system.second_team !=''){
                    div_section += '<ul>';
                        div_section += '<li class="fixtures-detailes">';
                            div_section += '<h5>'+lang_bouns_system+'</h5>';
                            div_section += '<div class="flex-row">';
                                div_section += '<ul class="fix-list">';
                                    $.each(details.bouns_system.first_team, function (index, bouns_system) {
                                        div_section += '<li><a class="control info popModal_infoPlayer"  data-name="' + bouns_system.player_name + '" data-link="' + bouns_system.player_link + '">'+bouns_system.player_name+'</a><span class="float-right">('+bouns_system.value+')</span></li>';
                                    });
                                div_section += '</ul>';
                                div_section += '<ul class="fix-list">';
                                    $.each(details.bouns_system.second_team, function (index, bouns_system) {
                                        div_section += '<li><a class="control info popModal_infoPlayer"  data-name="' + bouns_system.player_name + '" data-link="' + bouns_system.player_link + '">'+bouns_system.player_name+'</a><span class="float-left">('+bouns_system.value+')</span></li>';
                                    });
                                div_section += '</ul>';
                            div_section += '</div>';
                        div_section += '</li>';
                    div_section += '</ul>';
                }    
            div_section += '</div>';
        div_section += '</div>';
    div_section += '</div>';
  return div_section;  
}

