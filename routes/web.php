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
$namespace= 'App\Http\Controllers\Site';

//https://www.alahlifc.sa

// Close
Route::get('close', ['as' => 'close', 'uses' => 'SiteController@close']);
Route::group([
    'prefix' => $public_url,
    'namespace' => $namespace,
        ], function () {
// Pages
    // Route::get('home', ['as' => 'home', 'uses' => 'HomeController@home']);
    // Route::get('/home', ['as' => 'home', 'uses' => 'HomeController@home']);
    // Route::get('/logout', ['as' => 'home', 'uses' => 'HomeController@home']);
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
    'namespace' => $namespace,
    'as' => 'game.',
        ], function () {
    Route::get('', ['as' => 'index', 'uses' => 'GameController@index']);
    Route::get('/my_team', ['as' => 'my_team', 'uses' => 'GameController@my_team']); 
    Route::get('/my_point', ['as' => 'my_point', 'uses' => 'GameController@my_point']); 
    Route::get('/transfer', ['as' => 'game_transfer', 'uses' => 'GameController@game_transfer']);
//    Route::get('/{link}', ['as' => 'single', 'uses' => 'GameController@single']);
});

// group_eldwry
Route::group([
    'prefix' => 'game' . $public_url,
    'namespace' => $namespace,
    'as' => 'game.',
        ], function () {  
    Route::get('/groups', ['as' => 'group_eldwry', 'uses' => 'GroupEldwryController@group_eldwry']);

    Route::get('/groups/create', ['as' => 'group_eldwry.create', 'uses' => 'GroupEldwryController@create']);
    Route::get('/groups/create/classic', ['as' => 'group_eldwry.create_classic', 'uses' => 'GroupEldwryController@create_classic']);
    Route::get('/groups/create/head', ['as' => 'group_eldwry.create_head', 'uses' => 'GroupEldwryController@create_head']);
    Route::get('/groups/create/{type_group}/done', ['as' => 'group_eldwry.create_done', 'uses' => 'GroupEldwryController@create_done']);

    Route::get('/groups/invite/{type_group}/{link}', ['as' => 'group_eldwry.invite', 'uses' => 'GroupEldwryController@invite']);
    // Route::get('/groups/invite/{type_group}/{link}', ['as' => 'group_eldwry.invitelink', 'uses' => 'GroupEldwryController@invite']);
    Route::get('/groups/invite/accept/{type_group}/{link}', ['as' => 'group_eldwry.accept_invite', 'uses' => 'GroupEldwryController@accept_invite']);

    Route::get('/groups/join', ['as' => 'group_eldwry.join', 'uses' => 'GroupEldwryController@join_group']);
    Route::get('/groups/join/classic', ['as' => 'group_eldwry.join_classic', 'uses' => 'GroupEldwryController@join_group_classic']);
    Route::get('/groups/join/head', ['as' => 'group_eldwry.join_head', 'uses' => 'GroupEldwryController@join_group_head']);

    Route::get('/groups/group/{type_group}/{link}', ['as' => 'group_eldwry.group', 'uses' => 'GroupEldwryController@single_group']);
    
    Route::get('/groups/admin/{type_group}', ['as' => 'group_eldwry.admin', 'uses' => 'GroupEldwryController@admin_group']);
    Route::get('/groups/admin/{type_group}/{link}', ['as' => 'group_eldwry.adminlink', 'uses' => 'GroupEldwryController@admin_group']);
});

// videos
Route::group([
    'prefix' => 'videos' . $public_url,
    'namespace' => $namespace,
    'as' => 'videos.',
        ], function () {
    Route::get('', ['as' => 'index', 'uses' => 'VideosController@index']);
    Route::get('/{link}', ['as' => 'single', 'uses' => 'VideosController@single']);
});
// news
Route::group([
    'prefix' => 'news' . $public_url,
    'namespace' => $namespace,
    'as' => 'news.',
        ], function () {
    Route::get('', ['as' => 'index', 'uses' => 'NewsController@index']);
    Route::get('/{link}', ['as' => 'single', 'uses' => 'NewsController@single']);
    Route::get('/league/table', ['as' => 'league', 'uses' => 'NewsController@league']);
});
// awards
Route::group([
    'prefix' => 'awards' . $public_url,
    'namespace' => $namespace,
    'as' => 'awards.',
        ], function () {
    Route::get('', ['as' => 'index', 'uses' => 'AwardController@index']);
    Route::get('/{link}', ['as' => 'single', 'uses' => 'AwardController@single']);
});
//instractions
Route::group([
    'prefix' => 'instractions' . $public_url,
    'namespace' => $namespace,
    'as' => 'instractions.',
        ], function () {
    Route::get('', ['as' => 'index', 'uses' => 'InstractionsController@index']);
    Route::get('/{link}', ['as' => 'single', 'uses' => 'InstractionsController@single']);
});
//statics
Route::group([
    'prefix' => 'statics' . $public_url,
    'namespace' => $namespace,
    'as' => 'statics.',
        ], function () {
    Route::get('', ['as' => 'index', 'uses' => 'StaticController@index']);
    Route::get('/{link}', ['as' => 'single', 'uses' => 'StaticController@single']);
});
//fixtures
Route::group([
    'prefix' => 'fixtures' . $public_url,
    'namespace' => $namespace,
    'as' => 'fixtures.',
        ], function () {
    Route::get('', ['as' => 'index', 'uses' => 'FixtureController@index']);
    Route::get('/{link}', ['as' => 'single', 'uses' => 'FixtureController@single']);
});
// Profile
Route::group([
    'prefix' => 'profile' . $public_url,
    'namespace' => $namespace,
    'as' => 'profile.',
    'middleware' => ['auth']
        ], function () {
    Route::get('', ['as' => 'index', 'uses' => 'ProfileController@index']);
    Route::post('', ['as' => 'store', 'uses' => 'ProfileController@store']);
});

// payment
Route::group([
   'prefix' => 'payment/card' . $public_url,
   'namespace' => $namespace,
   'as' => 'payment_card.',
   'middleware' => ['auth']
       ], function () {
   Route::get('', ['as' => 'index', 'uses' => 'PaymentCardController@index']);
   Route::get('callback', ['as' => 'callback', 'uses' => 'PaymentCardController@callback']);
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