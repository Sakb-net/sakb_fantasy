<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Api\V1')->prefix('v1')->group(function () {
// Page
    Route::name('version')->post('/version', 'PageController@version');
    Route::name('home')->post('/home', 'PageController@home');
    Route::name('instraction')->post('/instraction', 'PageController@instraction');
    Route::name('award')->post('/award', 'PageController@award');
    Route::name('about')->post('/about', 'PageController@about');
    Route::name('terms')->post('/terms', 'PageController@terms');
    Route::name('contact_us')->post('/contact_us', 'PageController@contact_us');
    Route::name('add_contact_us')->post('/add_contact_us', 'PageController@add_contact_us');
// login and register
    Route::name('get_country')->get('/get_country', 'AuthController@get_country');
    Route::name('get_city')->get('/get_city', 'AuthController@get_city');
    Route::name('get_teams')->get('/get_teams', 'AuthController@get_teams');
    Route::name('register')->post('/register', 'AuthController@register');
    Route::name('login.email')->post('/login/email', 'AuthController@loginEmail');
    Route::name('logout')->post('/logout', 'AuthController@logout');
   //Social login with google
    Route::name('social.login')->post('/social/login', 'SocialLogController@findorCreate');
    
    // user Rest Password
    Route::name('forgetpassword')->post('/forgetpassword', 'PasswordResetController@forgetpassword');
   // Route::name('passwor_reset_token')->get('/password/reset/{token}', 'PasswordResetController@passwor_reset_token');
    Route::name('confirm_passwor_token')->post('/password/confirm_reset', 'PasswordResetController@confirm_passwor_token');
    Route::name('restpassword')->post('/restpassword', 'PasswordResetController@restpassword');
    
    // user
    Route::name('profile')->get('/profile', 'UserController@profile');
    Route::name('change_password')->post('/change_password', 'UserController@change_password');
    Route::name('update_profile')->post('/update_profile', 'UserController@update_profile');
    Route::name('update_fcmtoken')->post('/update_fcmtoken', 'UserController@update_fcmtoken');
    Route::name('mypoint')->post('/mypoint', 'UserController@mypoint');

    //subeldwry
    Route::name('subeldwry')->get('/subeldwry', 'SubeldwryController@subeldwry');   
    Route::name('active_subeldwry')->get('/active_subeldwry', 'SubeldwryController@active_subeldwry');   
    //fixtures
    Route::name('subeldwry_fixtures')->get('/subeldwry/{link}/fixtures', 'FixtureController@subeldwry_fixtures');
    Route::name('fixtures')->get('/fixtures', 'FixtureController@fixtures');    
    Route::name('single_fixtures')->get('/fixtures/{link}', 'FixtureController@single_fixtures');
    //game  
    Route::name('eldwry_locations')->post('/eldwry_locations', 'GameController@eldwry_locations');
    Route::name('players_by_type')->post('/players_by_type', 'GameController@players_by_type'); 
    Route::name('filterPlayer')->post('/filterPlayer', 'GameController@filterPlayer');
    Route::name('player_master')->post('/player_master', 'GameController@player_master');
    
    Route::name('auto_selection_player')->post('/auto_selection_player', 'GameController@auto_selection_player');
    Route::name('reset_all_player')->post('/reset_all_player', 'GameController@reset_all_player');
    //player
    Route::name('player')->post('/player', 'PlayerController@player');
    Route::name('add_player')->post('/add_player', 'PlayerController@add_player');

    Route::name('delete_player')->post('/delete_player', 'PlayerController@delete_player');   

    Route::name('change_player')->post('/change_player', 'PlayerController@change_player');

    Route::name('checknum_myteam')->post('/checknum_myteam', 'PlayerController@checknum_myteam');
    Route::name('add_myteam')->post('/add_myteam', 'PlayerController@add_myteam');
    //substitute or transfer
    Route::name('confirm_substitutePlayer')->post('/confirm_substitutePlayer', 'TransferController@confirm_substitutePlayer');
    Route::name('status_card')->get('/status_card/{type}', 'TransferController@status_card');
    Route::name('count_team_players')->get('/count_team_players', 'TransferController@count_team_players');
//payment cardgold by hyper
    Route::name('payment_card')->post('/payment/card', 'PaymentCardController@payment_card');
    Route::name('confirmPayment_card')->post('/confirmPayment/card', 'PaymentCardController@confirmPayment_card');
//my_team
    Route::name('player_myteam')->post('/player_myteam', 'MyTeamController@player_myteam');
    Route::name('add_captain_assist')->post('/add_captain_assist', 'MyTeamController@add_captain_assist');
    Route::name('get_lineup')->post('/get_lineup', 'MyTeamController@get_lineup');
    Route::name('add_lineup')->post('/add_lineup', 'MyTeamController@add_lineup');
    Route::name('check_insideChange')->post('/check_insideChange', 'MyTeamController@check_insideChange');
    Route::name('add_insideChange')->post('/add_insideChange', 'MyTeamController@add_insideChange');
    Route::name('add_direct_insideChange')->post('/add_direct_insideChange', 'MyTeamController@add_direct_insideChange');
    //Triple Bench Card
    Route::name('check_btns_status')->get('/check_btns_status', 'TripleBenchCardController@check_btns_status');
    Route::name('triple_captain_card')->get('/triple_captain_card', 'TripleBenchCardController@triple_captain_card');
    Route::name('bench_players_card')->get('/bench_players_card', 'TripleBenchCardController@bench_players_card');
    Route::name('cancel_players_card')->post('/cancel_players_card', 'TripleBenchCardController@cancel_players_card');
    //points_subeldwry
    Route::name('home_points_eldwry')->get('/home_points_eldwry', 'PointsController@home_points_eldwry');
    Route::name('public_points_eldwry')->get('/public_points_eldwry', 'PointsController@public_points_eldwry');
    Route::name('points_eldwry')->get('/points_eldwry', 'PointsController@points_eldwry');
    Route::name('points_subeldwry')->post('/points_subeldwry', 'PointsController@points_subeldwry');
    Route::name('pointplayersubeldwry')->post('/pointplayersubeldwry', 'PointsController@pointplayersubeldwry');

    //statistics
    Route::name('statistics')->post('/statistics', 'StatisticController@statistics');
    //group_eldwry
    Route::prefix('group_eldwry')->name('group_eldwry')->group(function (){
        Route::get('/{type_group}', 'GroupEldwryController@group_eldwry')->name('group_eldwry');
        Route::get('subeldwrys/{type_group}/{link_group}', 'GroupEldwryController@subeldwrys')->name('subeldwrys');
        Route::post('standings/{type_group}/{link_group}', 'GroupEldwryController@standings')->name('standings');
        Route::put('/leave/{type_group}/{link_group}', 'GroupEldwryController@leave')->name('leave');

        Route::post('/create/{type_group}', 'GroupEldwrySettingController@create')->name('create');
        Route::put('/update/{type_group}/{link_group}', 'GroupEldwrySettingController@update')->name('update');
        Route::put('/stop/{type_group}/{link_group}', 'GroupEldwrySettingController@stop')->name('stop');

        Route::get('/setting_admin/{type_group}/{link_group}', 'GroupEldwryAdminController@setting_admin')->name('setting_admin');
        Route::post('/add_admin/{type_group}/{link_group}', 'GroupEldwryAdminController@add_admin')->name('add_admin'); 
        Route::delete('/delete_player/{type_group}/{link_group}', 'GroupEldwryAdminController@delete_player')->name('delete_player');
        Route::get('/setting_invite/{type_group}/{link_group}', 'GroupEldwryAdminController@setting_invite')->name('setting_invite');
        Route::post('/join/{type_group}', 'GroupEldwryAdminController@join')->name('join');
    });

//comment
//    Route::name('allcomments')->post('/allcomments', 'CommentController@allcomments');
    Route::name('comments')->post('/comments', 'CommentController@comments');
    Route::name('add_comment')->post('/add_comment', 'CommentController@add_comment');
    Route::name('update_comment')->post('/update_comment', 'CommentController@update_comment');
    Route::name('delete_comment')->post('/delete_comment', 'CommentController@delete_comment');
//action    
    Route::name('add_watch')->post('/add_watch', 'ActionController@add_watch');
    Route::name('add_del_like')->post('/add_del_like', 'ActionController@add_del_like');
//notifications    
    Route::name('search')->post('/search', 'Notif_SearchController@search');
    Route::name('notifications')->get('/notifications', 'Notif_SearchController@notifications');
    Route::name('update_notif')->post('/update_notif', 'Notif_SearchController@update_notif');
//Route::name('addSubscribe')->post('/addSubscribe', 'Notif_SearchController@addSubscribe');
//attachment
    Route::name('uploadImage')->post('/uploadImage', 'AttachmentController@uploadImage');
    Route::name('uploadImageFile')->post('/uploadImageFile', 'AttachmentController@uploadImageFile');
    Route::name('uploadVideo')->post('/uploadVideo', 'AttachmentController@uploadVideo');
    Route::name('uploadAudio')->post('/uploadAudio', 'AttachmentController@uploadAudio');
    Route::name('uploadAudioFile')->post('/uploadAudioFile', 'AttachmentController@uploadAudioFile');

// news
    Route::name('news')->post('/news', 'NewsController@news');
    Route::name('news.single')->post('/news/single', 'NewsController@newsSingle');
    Route::name('news.followingNews')->post('/news/followingNews', 'NewsController@followingNews');
// videos
    Route::name('videos')->post('/videos', 'VideosController@videos');
    Route::name('videos.single')->post('/videos/single', 'VideosController@videosSingle');
// TeamUser
    Route::name('followingMatches')->post('/followingMatches', 'TeamUserController@followingMatches');

    Route::name('addFollowingTeam')->post('/addFollowingTeam', 'TeamUserController@addFollowingTeam');
    
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
