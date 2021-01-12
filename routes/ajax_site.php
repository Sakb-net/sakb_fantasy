<?php

Route::post('add_register_buy', ['as' => 'add_register_buy', 'uses' => 'App\Http\Controllers\Auth\RegisterController@ajax_add_register_buy']);

Route::group([
    'prefix' => '',
    'namespace' => $namespace,
        ], function () {
//page---->validation register
    Route::post('changeLanguage', ['as' => 'changeLanguage', 'uses' => 'AjaxController@changeLanguage']);
    Route::post('check_found_email', ['as' => 'check_found_email', 'uses' => 'AjaxController@check_found_email']);
    Route::post('check_found_phone', ['as' => 'check_found_phone', 'uses' => 'AjaxController@check_found_phone']);
    Route::post('add_image_user', ['as' => 'add_image_user', 'uses' => 'AjaxController@add_image_user']);
//comment
    Route::post('add_delete_fav', ['as' => 'add_delete_fav', 'uses' => 'AjaxCommentController@add_delete_fav']);
    Route::post('add_post_comment', ['as' => 'add_post_comment', 'uses' => 'AjaxCommentController@add_post_comment']);
    Route::post('remove_comments', ['as' => 'remove_comments', 'uses' => 'AjaxCommentController@remove_comments']);
    //contact us
    Route::post('add_contact_Us', ['as' => 'add_contact_Us', 'uses' => 'AjaxController@add_contact_Us']);
    //tab game
    Route::post('tab_menu_gameTeam', ['as' => 'tab_menu_gameTeam', 'uses' => 'AjaxController@tab_menu_gameTeam']);
    //game
    Route::post('get_data_player_public', ['as' => 'get_data_player_public', 'uses' => 'AjaxGameController@get_data_player_public']);
    Route::post('get_data_player_public_statistics', ['as' => 'get_data_player_public_statistics', 'uses' => 'AjaxGameController@get_data_player_public_statistics']);
    Route::post('get_data_player_public_transformation', ['as' => 'get_data_player_public_transformation', 'uses' => 'AjaxGameController@get_data_player_public_transformation']);
    Route::post('get_dataFilterPlayer', ['as' => 'get_dataFilterPlayer', 'uses' => 'AjaxGameController@get_dataFilterPlayer']);
    Route::post('get_dataFilterPlayerTransformation', ['as' => 'get_dataFilterPlayerTransformation', 'uses' => 'AjaxGameController@get_dataFilterPlayerTransformation']);
    Route::post('get_dataOrderByPlayerLocation', ['as' => 'get_dataOrderByPlayerLocation', 'uses' => 'AjaxGameController@get_dataOrderByPlayerLocation']);
    Route::post('get_data_match_public', ['as' => 'get_data_match_public', 'uses' => 'AjaxGameController@get_data_match_public']);
    Route::post('get_data_team', ['as' => 'get_data_team', 'uses' => 'AjaxGameController@get_data_team']);
    Route::post('GetDataPlayer_Master', ['as' => 'GetDataPlayer_Master', 'uses' => 'AjaxGameController@GetDataPlayer_Master']);
    Route::post('get_dataOnePlayer', ['as' => 'get_dataOnePlayer', 'uses' => 'AjaxGameController@get_dataOnePlayer']);
    Route::post('game_addPlayer', ['as' => 'game_addPlayer', 'uses' => 'AjaxGameController@game_addPlayer']);    
    Route::post('game_deletePlayer', ['as' => 'game_deletePlayer', 'uses' => 'AjaxGameController@game_deletePlayer']);

    Route::post('return_player_game', ['as' => 'return_player_game', 'uses' => 'AjaxGameController@return_player_game']);    
    Route::post('change_player_game', ['as' => 'change_player_game', 'uses' => 'AjaxGameController@change_player_game']);

    Route::post('submit_game_team', ['as' => 'submit_game_team', 'uses' => 'AjaxGameController@submit_game_team']);
    
    Route::post('checknum_playerteam', ['as' => 'checknum_playerteam', 'uses' => 'AjaxGameController@checknum_playerteam']);

    Route::post('auto_selection_player', ['as' => 'auto_selection_player', 'uses' => 'AjaxGameController@auto_selection_player']);
    Route::post('reset_all_player', ['as' => 'reset_all_player', 'uses' => 'AjaxGameController@reset_all_player']);
    //transfer
    Route::post('game_substitutePlayer', ['as' => 'game_substitutePlayer', 'uses' => 'AjaxTransferController@game_substitutePlayer']);
    Route::post('get_substitutePlayer', ['as' => 'get_substitutePlayer', 'uses' => 'AjaxTransferController@get_substitutePlayer']);
    Route::post('confirm_substitutePlayer', ['as' => 'confirm_substitutePlayer', 'uses' => 'AjaxTransferController@confirm_substitutePlayer']);
    Route::post('GetDataPlayer_MasterTransfer', ['as' => 'GetDataPlayer_MasterTransfer', 'uses' => 'AjaxTransferController@GetDataPlayer_MasterTransfer']);

    Route::post('confirm_cardgray', ['as' => 'confirm_cardgray', 'uses' => 'AjaxTransferController@confirm_cardgray']);

    Route::post('confirm_cardgold', ['as' => 'confirm_cardgold', 'uses' => 'AjaxTransferController@confirm_cardgold']);
    //myteam
    Route::post('get_datalineup', ['as' => 'get_datalineup', 'uses' => 'AjaxMyTeamController@get_datalineup']);
    Route::post('get_add_linupMyteam', ['as' => 'get_add_linupMyteam', 'uses' => 'AjaxMyTeamController@get_add_linupMyteam']);
    Route::post('get_add_Captain', ['as' => 'get_add_Captain', 'uses' => 'AjaxMyTeamController@get_add_Captain']);
    Route::post('inside_changePlayer', ['as' => 'inside_changePlayer', 'uses' => 'AjaxMyTeamController@inside_changePlayer']);
    Route::post('delete_allowchange', ['as' => 'delete_allowchange', 'uses' => 'AjaxMyTeamController@delete_allowchange']);
    Route::post('okInsid_ChangPlayer', ['as' => 'okInsid_ChangPlayer', 'uses' => 'AjaxMyTeamController@okInsid_ChangPlayer']);

    Route::post('check_btns_status', ['as' => 'check_btns_status', 'uses' => 'AjaxMyTeamController@check_btns_status']);
    Route::post('get_dataTripleCaptainPoints', ['as' => 'get_dataTripleCaptainPoints', 'uses' => 'AjaxMyTeamController@get_dataTripleCaptainPoints']);
    Route::post('get_dataBenchPlayersPoints', ['as' => 'get_dataBenchPlayersPoints', 'uses' => 'AjaxMyTeamController@get_dataBenchPlayersPoints']);
    Route::post('cancelBenchTripleCard', ['as' => 'cancelBenchTripleCard', 'uses' => 'AjaxMyTeamController@cancelBenchTripleCard']);
    
    //points
    Route::post('get_points_subeldwry', ['as' => 'get_points_subeldwry', 'uses' => 'AjaxPointController@get_points_subeldwry']);
    Route::post('get_pointsplayer_foruser', ['as' => 'get_pointsplayer_foruser', 'uses' => 'AjaxPointController@get_pointsplayer_foruser']);
//league/ranking
    Route::post('get_subeldwry_ranking_eldwry', ['as' => 'get_subeldwry_ranking_eldwry', 'uses' => 'AjaxRankingEldwryController@get_subeldwry_ranking_eldwry']);
    Route::post('get_all_ranking_eldwry', ['as' => 'get_all_ranking_eldwry', 'uses' => 'AjaxRankingEldwryController@get_all_ranking_eldwry']);
    Route::post('get_home_ranking_eldwry', ['as' => 'get_home_ranking_eldwry', 'uses' => 'AjaxRankingEldwryController@get_home_ranking_eldwry']);
    
    //groupEldwry
    Route::post('get_normal_eldwry', ['as' => 'get_normal_eldwry', 'uses' => 'AjaxGroupEldwryController@get_normal_eldwry']);
    Route::post('get_head_eldwry', ['as' => 'get_head_eldwry', 'uses' => 'AjaxGroupEldwryController@get_head_eldwry']);
    Route::post('tab_menu_groupEldwry', ['as' => 'tab_menu_groupEldwry', 'uses' => 'AjaxGroupEldwryController@tab_menu_groupEldwry']);
    Route::post('get_current_subeldwry_group', ['as' => 'get_current_subeldwry_group', 'uses' => 'AjaxGroupEldwryController@get_current_subeldwry_group']);
    Route::post('store_groupEldwry', ['as' => 'store_groupEldwry', 'uses' => 'AjaxGroupEldwryController@store_groupEldwry']);
    Route::post('store_head_groupEldwry', ['as' => 'store_head_groupEldwry', 'uses' => 'AjaxGroupEldwryController@store_head_groupEldwry']);
    Route::post('send_invite_emailphone', ['as' => 'send_invite_emailphone', 'uses' => 'AjaxGroupEldwryController@send_invite_emailphone']);
    Route::post('setting_invite_group_eldwry', ['as' => 'setting_invite_group_eldwry', 'uses' => 'AjaxGroupEldwryController@setting_invite_group_eldwry']);
    Route::post('get_last_group_eldwry', ['as' => 'get_last_group_eldwry', 'uses' => 'AjaxGroupEldwryController@get_last_group_eldwry']);
    Route::post('setting_admin_group_eldwry', ['as' => 'setting_admin_group_eldwry', 'uses' => 'AjaxGroupEldwryController@setting_admin_group_eldwry']);
    Route::post('operation_group_eldwry', ['as' => 'operation_group_eldwry', 'uses' => 'AjaxGroupEldwryController@operation_group_eldwry']);
    Route::post('add_join_group_eldwry', ['as' => 'add_join_group_eldwry', 'uses' => 'AjaxGroupEldwryController@add_join_group_eldwry']);
    Route::post('get_data_group_eldwry', ['as' => 'get_data_group_eldwry', 'uses' => 'AjaxGroupEldwryController@get_data_group_eldwry']);

    //****************Ex*******
    
    Route::post('ajax_pagination', ['as' => 'ajax_pagination', 'uses' => 'AjaxController@ajax_pagination']);

});
