<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


Route::get('/clear', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});
$public_url = ''; //public/
$admin_panel = $public_url . 'admin';
//https://www.alahlifc.sa
//https://github.com/barryvdh/laravel-translation-manager

// Close
Route::get('close', ['as' => 'close', 'uses' => 'SiteController@close']);
Route::group([
    'prefix' => $public_url,
    'namespace' => 'Site',
        ], function () {
// Pages
    Route::get('home', ['as' => 'home', 'uses' => 'HomeController@home']);
    Route::get('/home', ['as' => 'home', 'uses' => 'HomeController@home']);
    Route::get('/logout', ['as' => 'home', 'uses' => 'HomeController@home']);
    Route::get('/', ['as' => 'home', 'uses' => 'HomeController@home']);
    Route::get('', ['as' => 'home', 'uses' => 'HomeController@home']);
    Route::get('wellcome', ['as' => 'wellcome', 'uses' => 'HomeController@wellcome']);
    Route::get('about', ['as' => 'about', 'uses' => 'HomeController@about']);
    Route::get('terms', ['as' => 'terms', 'uses' => 'HomeController@terms']);
    Route::get('contact', ['as' => 'contact', 'uses' => 'HomeController@contact']);
    Route::post('contact', ['as' => 'contact.store', 'uses' => 'HomeController@contactStore']);

    Route::get('script', ['as' => 'script', 'uses' => 'ScriptController@script']);
});

// game
Route::group([
    'prefix' => 'game' . $public_url,
    'as' => 'game.',
        ], function () {
    Route::get('', ['as' => 'index', 'uses' => 'Site\GameController@index']);
    Route::get('/my_team', ['as' => 'my_team', 'uses' => 'Site\GameController@my_team']); 
    Route::get('/my_point', ['as' => 'my_point', 'uses' => 'Site\GameController@my_point']); 
    Route::get('/transfer', ['as' => 'game_transfer', 'uses' => 'Site\GameController@game_transfer']);
//    Route::get('/{link}', ['as' => 'single', 'uses' => 'Site\GameController@single']);
});

// group_eldwry
Route::group([
    'prefix' => 'game' . $public_url,
    'as' => 'game.',
        ], function () {  
    Route::get('/groups', ['as' => 'group_eldwry', 'uses' => 'Site\GroupEldwryController@group_eldwry']);

    Route::get('/groups/create', ['as' => 'group_eldwry.create', 'uses' => 'Site\GroupEldwryController@create']);
    Route::get('/groups/create/classic', ['as' => 'group_eldwry.create_classic', 'uses' => 'Site\GroupEldwryController@create_classic']);
    Route::get('/groups/create/head', ['as' => 'group_eldwry.create_head', 'uses' => 'Site\GroupEldwryController@create_head']);
    Route::get('/groups/create/{type_group}/done', ['as' => 'group_eldwry.create_done', 'uses' => 'Site\GroupEldwryController@create_done']);

    Route::get('/groups/invite/{type_group}/{link}', ['as' => 'group_eldwry.invite', 'uses' => 'Site\GroupEldwryController@invite']);
    // Route::get('/groups/invite/{type_group}/{link}', ['as' => 'group_eldwry.invitelink', 'uses' => 'Site\GroupEldwryController@invite']);
    Route::get('/groups/invite/accept/{type_group}/{link}', ['as' => 'group_eldwry.accept_invite', 'uses' => 'Site\GroupEldwryController@accept_invite']);

    Route::get('/groups/join', ['as' => 'group_eldwry.join', 'uses' => 'Site\GroupEldwryController@join_group']);
    Route::get('/groups/join/classic', ['as' => 'group_eldwry.join_classic', 'uses' => 'Site\GroupEldwryController@join_group_classic']);
    Route::get('/groups/join/head', ['as' => 'group_eldwry.join_head', 'uses' => 'Site\GroupEldwryController@join_group_head']);

    Route::get('/groups/group/{type_group}/{link}', ['as' => 'group_eldwry.group', 'uses' => 'Site\GroupEldwryController@single_group']);
    
    Route::get('/groups/admin/{type_group}', ['as' => 'group_eldwry.admin', 'uses' => 'Site\GroupEldwryController@admin_group']);
    Route::get('/groups/admin/{type_group}/{link}', ['as' => 'group_eldwry.adminlink', 'uses' => 'Site\GroupEldwryController@admin_group']);
});

// videos
Route::group([
    'prefix' => 'videos' . $public_url,
    'as' => 'videos.',
        ], function () {
    Route::get('', ['as' => 'index', 'uses' => 'Site\VideosController@index']);
    Route::get('/{link}', ['as' => 'single', 'uses' => 'Site\VideosController@single']);
});
// news
Route::group([
    'prefix' => 'news' . $public_url,
    'as' => 'news.',
        ], function () {
    Route::get('', ['as' => 'index', 'uses' => 'Site\NewsController@index']);
    Route::get('/{link}', ['as' => 'single', 'uses' => 'Site\NewsController@single']);
    Route::get('/league/table', ['as' => 'league', 'uses' => 'Site\NewsController@league']);
});
// awards
Route::group([
    'prefix' => 'awards' . $public_url,
    'as' => 'awards.',
        ], function () {
    Route::get('', ['as' => 'index', 'uses' => 'Site\AwardController@index']);
    Route::get('/{link}', ['as' => 'single', 'uses' => 'Site\AwardController@single']);
});
//instractions
Route::group([
    'prefix' => 'instractions' . $public_url,
    'as' => 'instractions.',
        ], function () {
    Route::get('', ['as' => 'index', 'uses' => 'Site\InstractionsController@index']);
    Route::get('/{link}', ['as' => 'single', 'uses' => 'Site\InstractionsController@single']);
});
//statics
Route::group([
    'prefix' => 'statics' . $public_url,
    'as' => 'statics.',
        ], function () {
    Route::get('', ['as' => 'index', 'uses' => 'Site\StaticController@index']);
    Route::get('/{link}', ['as' => 'single', 'uses' => 'Site\StaticController@single']);
});
//fixtures
Route::group([
    'prefix' => 'fixtures' . $public_url,
    'as' => 'fixtures.',
        ], function () {
    Route::get('', ['as' => 'index', 'uses' => 'Site\FixtureController@index']);
    Route::get('/{link}', ['as' => 'single', 'uses' => 'Site\FixtureController@single']);
});
// Profile
Route::group([
    'prefix' => 'profile' . $public_url,
    'as' => 'profile.',
    'middleware' => ['auth']
        ], function () {
    Route::get('', ['as' => 'index', 'uses' => 'Site\ProfileController@index']);
    Route::post('', ['as' => 'store', 'uses' => 'Site\ProfileController@store']);
});

// payment
Route::group([
   'prefix' => 'payment/card' . $public_url,
   'as' => 'payment_card.',
   'middleware' => ['auth']
       ], function () {
   Route::get('', ['as' => 'index', 'uses' => 'Site\PaymentCardController@index']);
   Route::get('callback', ['as' => 'callback', 'uses' => 'Site\PaymentCardController@callback']);
});

Route::get('auth/{provider}', 'Auth\RegisterController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\RegisterController@handleProviderCallback');
//ajax
if (Request::ajax()) {
    require __DIR__ . '/ajax_site.php';
}
// Auth::routes();

// Auth Admin
Route::get($admin_panel . '/login', ['as' => 'admin.login', 'uses' => 'Auth\LoginAdminController@showLoginForm']);
Route::post($admin_panel . '/login', ['uses' => 'Auth\LoginAdminController@login']);
Route::post($admin_panel . 'logout', ['as' => 'admin.logout', 'uses' => 'Auth\LoginAdminController@logout']);

Route::post('logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);

//Admin
require __DIR__ . '/admin.php';
