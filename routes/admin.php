<?php

//Admin
Route::group([
    'prefix' => $admin_panel,
    'as' => 'admin.',
    'namespace' => 'App\Http\Controllers\Admin',
    'middleware' => ['admin']
        ], function () {

    //Pages
    Route::group([
        'prefix' => 'pages',
        'as' => 'pages.',
            ], function () {

        Route::get('contact', ['as' => 'contact', 'uses' => 'pageController@contactLang']);
        Route::get('contact/{lang_id}/{lang}', ['as' => 'contact.lang', 'uses' => 'pageController@contact']); 
        Route::post('contact', ['as' => 'contact.store', 'uses' => 'pageController@contactStore']);
//        Route::get('contact', ['as' => 'contact', 'uses' => 'optionController@contact']);
//        Route::post('contact', ['as' => 'contact.store', 'uses' => 'optionController@contactStore']);

        Route::get('home', ['as' => 'home', 'uses' => 'pageController@homeOption']);
        Route::post('home', ['as' => 'home.store', 'uses' => 'pageController@homeStore']);

        Route::get('about', ['as' => 'about', 'uses' => 'PageController@aboutLang']);
        Route::get('about/{lang_id}/{lang}', ['as' => 'about.lang', 'uses' => 'PageController@about']);
        Route::post('about', ['as' => 'about.store', 'uses' => 'PageController@aboutStore']);

        Route::get('terms', ['as' => 'terms', 'uses' => 'PageController@termsLang']);
        Route::get('terms/{lang_id}/{lang}', ['as' => 'terms.lang', 'uses' => 'pageController@terms']);
        Route::post('terms', ['as' => 'terms.store', 'uses' => 'pageController@termsStore']);

        Route::get('instractions', ['as' => 'instractions', 'uses' => 'pageController@instractionsLang']);
        Route::get('instractions/{lang_id}/{lang}', ['as' => 'instractions.lang', 'uses' => 'pageController@instractions']);
        Route::post('instractions', ['as' => 'instractions.store', 'uses' => 'pageController@instractionsStore']);
//        Route::get('payment', ['as' => 'payment', 'uses' => 'pageController@payment']);
//        Route::post('payment', ['as' => 'payment.store', 'uses' => 'pageController@paymentStore']);
        Route::get('awards', ['as' => 'awards', 'uses' => 'pageController@awardsLang']);
        Route::get('awards/{lang_id}/{lang}', ['as' => 'awards.lang', 'uses' => 'pageController@awards']);
        Route::post('awards', ['as' => 'awards.store', 'uses' => 'pageController@awardsStore']);

        Route::get('goal', ['as' => 'goal', 'uses' => 'pageController@goalLang']);
        Route::get('goal/{lang_id}/{lang}', ['as' => 'goal.lang', 'uses' => 'pageController@goal']);
        Route::post('goal', ['as' => 'goal.store', 'uses' => 'pageController@goalStore']);

        Route::get('message', ['as' => 'message', 'uses' => 'pageController@messageLang']);
        Route::get('message/{lang_id}/{lang}', ['as' => 'message.lang', 'uses' => 'pageController@message']);   
        Route::post('message', ['as' => 'message.store', 'uses' => 'pageController@messageStore']);

//        Route::get('manager', ['as' => 'manager', 'uses' => 'pageController@manager']);
//        Route::post('manager', ['as' => 'manager.store', 'uses' => 'pageController@managerStore']);
    });

    //Statistics
    Route::get('', ['as' => 'index', 'uses' => 'StatisticsReportController@homeAdmin']);
    Route::get('statisticsorders', ['as' => 'statisticsorders', 'uses' => 'StatisticsReportController@statisticsOrders']);
    Route::get('statisticsusers', ['as' => 'statisticsusers', 'uses' => 'StatisticsReportController@statisticsUsers']);
    Route::get('statisticspublic', ['as' => 'statisticspublic', 'uses' => 'StatisticsReportController@statisticsPublic']);

    //Setting
    Route::get('options', ['as' => 'options', 'uses' => 'OptionController@options']);
    Route::post('options', ['as' => 'options.store', 'uses' => 'OptionController@optionsStore']);

    Route::get('option_time', ['as' => 'option_time', 'uses' => 'OptionController@option_time']);
    Route::post('option_time', ['as' => 'option_time.store', 'uses' => 'OptionController@option_timeStore']);

    Route::get('draft_cooldown',['as' => 'draft_cooldown', 'uses' => 'OptionController@draft_cooldown']);

    Route::post('draft_cooldown', ['as' => 'draft_cooldown.store', 'uses' => 'OptionController@draft_cooldownStore']);
    

    //sendmessage
    Route::get('sendmessage', ['as' => 'sendmessage', 'uses' => 'OptionController@sendMessage']);
    Route::post('sendmessage', ['as' => 'sendmessage.store', 'uses' => 'OptionController@sendMessageStore']);

    //User
    Route::get('users/list', ['as' => 'users.list', 'uses' => 'UserController@usersList']);

    Route::get('users/{id}/type/{name}', ['as' => 'users.posttype', 'uses' => 'UserController@postType']);
    Route::get('users/{id}/comments', ['as' => 'users.comments', 'uses' => 'UserController@comments']);
    Route::get('users/search', ['as' => 'users.search', 'uses' => 'UserController@search']);
    Route::post('users/searchUser', ['as' => 'users.searchUser', 'uses' => 'AjaxController@searchUser']);
    Route::post('userstatus', ['as' => 'userstatus', 'uses' => 'AjaxController@userStatus']);

    //Role && Message
    Route::get('roles/search', ['as' => 'roles.search', 'uses' => 'RoleController@search']);
    Route::get('messages/search', ['as' => 'messages.search', 'uses' => 'MessageController@search']);
//permission
    Route::get('permission/search', ['as' => 'permission.search', 'uses' => 'PermissionController@search']);

    //Comment
    Route::get('comments/search', ['as' => 'comments.search', 'uses' => 'CommentController@search']);
    Route::get('comments/type/{type}', ['as' => 'comments.type', 'uses' => 'CommentController@type']);
    Route::get('comments/{id}/reply', ['as' => 'comments.reply', 'uses' => 'CommentController@reply']);
    Route::post('comments/{id}/reply/store', ['as' => 'comments.reply.store', 'uses' => 'CommentController@replyStore']);
    Route::post('comments/allread', ['as' => 'comments.allread', 'uses' => 'CommentController@allread']);
    //Ajax Comment
    Route::post('commentstatus', ['as' => 'commentstatus', 'uses' => 'AjaxController@commentStatus']);
    Route::post('commentread', ['as' => 'commentread', 'uses' => 'AjaxController@commentRead']);

    //Tag
    Route::get('tags/search', ['as' => 'tags.search', 'uses' => 'TagController@search']);
    Route::get('tags/{id}/type/{type}', ['as' => 'tags.type', 'uses' => 'TagController@type']);

    //Search
    Route::get('searches/search', ['as' => 'searches.search', 'uses' => 'SearchController@search']);
    Route::post('searchstatus', ['as' => 'searchstatus', 'uses' => 'AjaxController@searchStatus']);
    Route::delete('usersearches/{id}/delete', ['as' => 'usersearches.destroy', 'uses' => 'SearchController@destroySearch']);

    //Contact
//    Route::get('contacts/search', ['as' => 'contacts.search', 'uses' => 'ContactController@search']);
    Route::get('contacts/search/{type}', ['as' => 'contacts.search', 'uses' => 'ContactController@search']);
    Route::get('contacts/type/{type}', ['as' => 'contacts.type', 'uses' => 'ContactController@type']);
    Route::post('contactread', ['as' => 'contactread', 'uses' => 'AjaxController@contactRead']);
    Route::post('contactreply', ['as' => 'contactreply', 'uses' => 'AjaxController@contactReply']);

    //Ajax Post
    Route::post('posts/{id}/comments/store', ['as' => 'posts.comments.store', 'uses' => 'PostController@commentStore']);
    Route::post('poststatus', ['as' => 'poststatus', 'uses' => 'AjaxController@postStatus']);
    Route::post('postread', ['as' => 'postread', 'uses' => 'AjaxController@postRead']);
    Route::post('posts/ajax_subcategory', ['as' => 'posts.ajax_subcategory', 'uses' => 'AjaxController@ajaxSubcategory']);
    //Category
    Route::get('categories/type/{type}', ['as' => 'categories.type', 'uses' => 'CategoryController@type']);
    Route::get('categories/search', ['as' => 'categories.search', 'uses' => 'CategoryController@search']);
    Route::post('categorystatus', ['as' => 'categorystatus', 'uses' => 'AjaxController@categoryStatus']);
    //Subcategory
    Route::get('subcategories/search', ['as' => 'subcategories.search', 'uses' => 'SubcategoryController@search']);
    //Category and Subcategory
    Route::get('allcategories/search', ['as' => 'allcategories.search', 'uses' => 'CategoryController@allSearch']);
    //post
    Route::get('posts/{id}/comments', ['as' => 'posts.comments.index', 'uses' => 'PostController@comments']);
    Route::get('posts/{id}/comments/create', ['as' => 'posts.comments.create', 'uses' => 'PostController@commentCreate']);
    Route::get('posts/type/{type}', ['as' => 'posts.type', 'uses' => 'PostController@type']);
    Route::get('posts/search/{type}', ['as' => 'posts.search', 'uses' => 'PostController@search']);
    Route::get('posts/create/{type}', ['as' => 'posts.creat', 'uses' => 'PostController@create']);
    Route::get('posts/edit/{id}/{type}', ['as' => 'posts.edittype', 'uses' => 'PostController@edit']);

    Route::get('posts/type/{type}/{cat_id}', ['as' => 'posts.type.category', 'uses' => 'PostController@typeCategory']);
    Route::delete('posts/deletetype/{type}/{cat_id}', ['as' => 'posts.deletetype.category', 'uses' => 'PostController@deletetypeCategory']);
    Route::get('posts/createallpost/{type}', ['as' => 'posts.createallpost', 'uses' => 'PostController@createallpost']);
    Route::post('posts/store_allpost', ['as' => 'posts.store_allpost', 'uses' => 'PostController@store_allpost']);

    //videos
    Route::get('videos/list', ['as' => 'videos.list', 'uses' => 'VideoController@videosList']);


    Route::get('videos/search', ['as' => 'videos.search', 'uses' => 'VideoController@search']);
    Route::get('videos/{id}/comments', ['as' => 'videos.comments.index', 'uses' => 'VideoController@comments']);
    Route::get('videos/{id}/comments/create', ['as' => 'videos.comments.create', 'uses' => 'VideoController@commentCreate']);
    Route::post('videos/{id}/comments/store', ['as' => 'videos.comments.store', 'uses' => 'VideoController@commentStore']);
    Route::post('videos/allread/comments', ['as' => 'videos.comments.allread', 'uses' => 'VideoController@commentallread']);
    //videocomments

    Route::get('videocomments/list', ['as' => 'videocomments.list', 'uses' => 'CommentVideoController@videoCommentsList']);

    Route::get('videocomments/search', ['as' => 'videocomments.search', 'uses' => 'CommentVideoController@search']);
    Route::get('videocomments/type/{type}', ['as' => 'videocomments.type', 'uses' => 'CommentVideoController@type']);
    Route::get('videocomments/{id}/reply', ['as' => 'videocomments.reply', 'uses' => 'CommentVideoController@reply']);
    Route::post('videocomments/{id}/reply/store', ['as' => 'videocomments.reply.store', 'uses' => 'CommentVideoController@replyStore']);
    Route::post('videocomments/allread', ['as' => 'videocomments.allread', 'uses' => 'CommentVideoController@allread']);
    //Ajax videocomments
    Route::post('videocommentsstatus', ['as' => 'videocommentsstatus', 'uses' => 'AjaxController@videocommentsStatus']);
    Route::post('videocommentsread', ['as' => 'videocommentsread', 'uses' => 'AjaxController@videocommentsRead']);

    //blogs
    Route::get('blog/list', ['as' => 'blogs.list', 'uses' => 'BlogController@blogsList']);

    Route::get('blog/{id}/createLang/{lang}', ['as' => 'blogs.createLang', 'uses' => 'BlogController@createLang']);
    Route::get('blog/{id}/editLang/{lang}', ['as' => 'blogs.editLang', 'uses' => 'BlogController@editLang']);
    Route::get('blogs/{id}/languages', ['as' => 'blogs.languages.index', 'uses' => 'BlogController@languages']);
    Route::get('blogs/search', ['as' => 'blogs.search', 'uses' => 'BlogController@search']);
    Route::get('blogs/arrange', ['as' => 'blogs.arrange.index', 'uses' => 'BlogController@BlogArrange']);
    Route::PATCH('blogs/Arrange/store', ['as' => 'blogs.arrange.store', 'uses' => 'BlogController@storeBlogArrange']);
    Route::get('blogs/{id}/comments', ['as' => 'blogs.comments.index', 'uses' => 'BlogController@comments']);
    Route::get('blogs/{id}/comments/create', ['as' => 'blogs.comments.create', 'uses' => 'BlogController@commentCreate']);
    Route::post('blogs/{id}/comments/store', ['as' => 'blogs.comments.store', 'uses' => 'BlogController@commentStore']);
    Route::post('blogs/allread/comments', ['as' => 'blogs.comments.allread', 'uses' => 'BlogController@commentallread']);
    //blogcomments

    Route::get('blogcomments/list', ['as' => 'blogcomments.list', 'uses' => 'CommentBlogController@blogCommentsList']);
    Route::get('blogcomments/search', ['as' => 'blogcomments.search', 'uses' => 'CommentBlogController@search']);
    Route::get('blogcomments/type/{type}', ['as' => 'blogcomments.type', 'uses' => 'CommentBlogController@type']);
    Route::get('blogcomments/{id}/reply', ['as' => 'blogcomments.reply', 'uses' => 'CommentBlogController@reply']);
    Route::post('blogcomments/{id}/reply/store', ['as' => 'blogcomments.reply.store', 'uses' => 'CommentBlogController@replyStore']);
    Route::post('blogcomments/allread', ['as' => 'blogcomments.allread', 'uses' => 'CommentBlogController@allread']);
    //Ajax blogcomments
    Route::post('blogcommentsstatus', ['as' => 'blogcommentsstatus', 'uses' => 'AjaxController@blogcommentsStatus']);
    Route::post('blogcommentsread', ['as' => 'blogcommentsread', 'uses' => 'AjaxController@blogcommentsRead']);
//apimessages
    Route::get('apimessages/search', ['as' => 'apimessages.search', 'uses' => 'ApimessagesController@search']);
//team , Subteam and Userteam 
    Route::get('clubteams/list', ['as' => 'clubteams.list', 'uses' => 'TeamController@clubteamsList']);
    Route::get('clubteams/editAll', ['as' => 'clubteams.editAll', 'uses' => 'TeamController@clubteamsEditAll']);
    Route::post('clubteams/updateAll', ['as' => 'clubteams.updateAll', 'uses' => 'TeamController@clubteamsUpdateAll']);
    Route::get('clubteams/editAllPlayers/{id}', ['as' => 'clubteams.editAllPlayers', 'uses' => 'TeamController@editAllPlayers']);
    Route::post('clubteams/updateAllPlayers', ['as' => 'clubteams.updateAllPlayers', 'uses' => 'TeamController@updateAllPlayers']);
    Route::get('clubteams/search', ['as' => 'clubteams.search', 'uses' => 'TeamController@search']);
    Route::get('subclubteams/creat/{id}', ['as' => 'subclubteams.creat', 'uses' => 'SubteamController@create']);
    Route::get('subclubteams/search', ['as' => 'subclubteams.search', 'uses' => 'SubteamController@search']);
    Route::get('userclubteams/creat/{id}', ['as' => 'userclubteams.creat', 'uses' => 'UserteamController@create']);
    Route::get('userclubteams/search', ['as' => 'userclubteams.search', 'uses' => 'UserteamController@search']);
    Route::get('allclubteams/search', ['as' => 'allclubteams.search', 'uses' => 'TeamController@allSearch']);
      Route::post('ajax_get_subteam', ['as' => 'ajax_get_subteam', 'uses' => 'AjaxController@ajax_get_subteam']);
          //matches
    Route::get('matches/{id}/comments', ['as' => 'matches.comments.index', 'uses' => 'MatchController@comments']);
    Route::get('matches/{id}/details', ['as' => 'matches.details', 'uses' => 'MatchController@details']);
    Route::get('matches/{id}/comments/create', ['as' => 'matches.comments.create', 'uses' => 'MatchController@commentCreate']);
    Route::get('matches/type/{type}', ['as' => 'matches.type', 'uses' => 'MatchController@type']);
    Route::get('matches/search', ['as' => 'matches.search', 'uses' => 'MatchController@search']);
    Route::get('matches/create/{type}', ['as' => 'matches.creat', 'uses' => 'MatchController@create']);
    Route::get('matches/edit/{id}/{type}', ['as' => 'matches.edittype', 'uses' => 'MatchController@edit']);

    Route::get('matches/list', ['as' => 'matches.list', 'uses' => 'MatchController@matchesList']);
    
    //players
          
    Route::get('players/list', ['as' => 'players.list', 'uses' => 'PlayerController@playersList']);
    
    Route::get('players/{id}/details', ['as' => 'players.showDetails', 'uses' => 'PlayerController@details']);

    Route::get('players/{id}/comments', ['as' => 'players.comments.index', 'uses' => 'PlayerController@comments']);
    Route::get('players/{id}/comments/create', ['as' => 'players.comments.create', 'uses' => 'PlayerController@commentCreate']);
    Route::get('players/type/{type}', ['as' => 'players.type', 'uses' => 'PlayerController@type']);
    Route::get('players/search', ['as' => 'players.search', 'uses' => 'PlayerController@search']);
    Route::get('players/create/{type}', ['as' => 'players.creat', 'uses' => 'PlayerController@create']);
    Route::get('players/edit/{id}/{type}', ['as' => 'players.edittype', 'uses' => 'PlayerController@edit']);

    Route::get('eldwry/search', ['as' => 'eldwry.search', 'uses' => 'EldwryController@search']);


    
    Route::get('eldwry/list', ['as' => 'eldwry.list', 'uses' => 'EldwryController@eldwryList']);

    Route::get('subeldwry/list', ['as' => 'subeldwry.list', 'uses' => 'SubeldwryController@subEldwryList']);


    Route::get('subeldwry/search', ['as' => 'subeldwry.search', 'uses' => 'SubeldwryController@search']);
    //settings
    Route::get('settings/search/{type}', ['as' => 'settings.search', 'uses' => 'SettingController@search']);

    Route::get('settings/planList', ['as' => 'settings.planList', 'uses' => 'SettingController@planList']);

    //language
    Route::get('languages/list', ['as' => 'languages.list', 'uses' => 'LanguageController@languagesList']);
    Route::get('languages/search', ['as' => 'languages.search', 'uses' => 'LanguageController@search']);
       //change language Localization
    Route::post('changeLanguage', ['as' => 'changeLanguage', 'uses' => 'AjaxController@changeLanguage']);
    //opta
    Route::get('opta/creat/{type}', ['as' => 'opta.creat', 'uses' => 'OptaController@create']);
    Route::post('opta/championship', ['as' => 'opta.championship', 'uses' => 'OptaController@championship']);
    Route::post('opta/eldwry', ['as' => 'opta.eldwry', 'uses' => 'OptaController@eldwry']);
    Route::post('opta/subeldwrys', ['as' => 'opta.subeldwrys', 'uses' => 'OptaController@subeldwrys']);
    Route::post('opta/teams', ['as' => 'opta.teams', 'uses' => 'OptaController@teams']);
    Route::post('opta/players', ['as' => 'opta.players', 'uses' => 'OptaController@players']);
    Route::post('opta/matches', ['as' => 'opta.matches', 'uses' => 'OptaController@matches']);

    Route::post('opta/result/matche', ['as' => 'opta.result_matche', 'uses' => 'OptaController@result_matche']);
    Route::post('opta/result/subeldwry', ['as' => 'opta.result_subeldwry', 'uses' => 'OptaController@result_subeldwry']);
    Route::post('opta/transfer/player', ['as' => 'opta.transfer_player', 'uses' => 'OptaController@transfer_player']);

    Route::get('opta/search', ['as' => 'opta.search', 'uses' => 'OptaController@search']);

    Route::get('subeldwry/details/{id}', ['as' => 'subeldwry.details', 'uses' => 'SubeldwryController@details']);

    Route::get('subeldwry/pointsDetails/{subeldwryId}/{userId}', ['as' => 'subeldwry.pointsDetails', 'uses' => 'SubeldwryController@pointsDetails']);

    //group eldwry
    Route::get('groupEldwry/list', ['as' => 'groupEldwry.list', 'uses' => 'GroupEldwryController@groupEldwryList']);
    Route::get('groupEldwry/create', ['as' => 'groupEldwry.creat', 'uses' => 'GroupEldwryController@create']);
    Route::get('groupEldwry/users/{id}', ['as' => 'groupEldwry.users', 'uses' => 'GroupEldwryController@groupEldwryUsers']);
    Route::get('groupEldwry/listUsers', ['as' => 'groupEldwry.listUsers', 'uses' => 'GroupEldwryController@groupEldwryUsersList']);
    Route::post('groupEldwry', ['as' => 'groupEldwry.store', 'uses' => 'GroupEldwryController@store']);
    Route::post('groupEldwry/activeUser', ['as' => 'groupEldwry.activeUser', 'uses' => 'GroupEldwryController@activeUser']);
    Route::post('groupEldwry/disActiveUser', ['as' => 'groupEldwry.disActiveUser', 'uses' => 'GroupEldwryController@disActiveUser']);
    Route::post('groupEldwry/block', ['as' => 'groupEldwry.block', 'uses' => 'GroupEldwryController@block']);
    Route::post('groupEldwry/removeBlock', ['as' => 'groupEldwry.removeBlock', 'uses' => 'GroupEldwryController@removeBlock']);
    Route::post('groupEldwry/setAdmin', ['as' => 'groupEldwry.setAdmin', 'uses' => 'GroupEldwryController@setAdmin']);
    Route::post('get_data_group_eldwry', ['as' => 'get_data_group_eldwry', 'uses' => 'AjaxGroupEldwryController@get_data_group_eldwry']);
    Route::get('groupEldwry/standing/{id}', ['as' => 'groupEldwry.standing', 'uses' => 'GroupEldwryController@groupEldwryStanding']);
    //head eldwry
    Route::get('headGroupEldwry/list', ['as' => 'headGroupEldwry.list', 'uses' => 'HeadGroupEldwryController@headGroupEldwryList']);
    Route::get('headGroupEldwry/users/{id}', ['as' => 'headGroupEldwry.users', 'uses' => 'HeadGroupEldwryController@headGroupEldwryUsers']);
    Route::get('headGroupEldwry/listUsers', ['as' => 'headGroupEldwry.listUsers', 'uses' => 'HeadGroupEldwryController@headGroupEldwryUsersList']);
    Route::get('headGroupEldwry/standing/{id}', ['as' => 'headGroupEldwry.standing', 'uses' => 'HeadGroupEldwryController@headGroupEldwryStanding']);
    Route::get('headGroupEldwry/create', ['as' => 'headGroupEldwry.creat', 'uses' => 'HeadGroupEldwryController@create']);
    Route::post('headGroupEldwry', ['as' => 'headGroupEldwry.store', 'uses' => 'HeadGroupEldwryController@store']);
    Route::post('headGroupEldwry/activeUser', ['as' => 'headGroupEldwry.activeUser', 'uses' => 'HeadGroupEldwryController@activeUser']);
    Route::post('headGroupEldwry/disActiveUser', ['as' => 'headGroupEldwry.disActiveUser', 'uses' => 'HeadGroupEldwryController@disActiveUser']);
    Route::post('headGroupEldwry/block', ['as' => 'headGroupEldwry.block', 'uses' => 'HeadGroupEldwryController@block']);
    Route::post('headGroupEldwry/removeBlock', ['as' => 'headGroupEldwry.removeBlock', 'uses' => 'HeadGroupEldwryController@removeBlock']);
    Route::post('headGroupEldwry/setAdmin', ['as' => 'headGroupEldwry.setAdmin', 'uses' => 'HeadGroupEldwryController@setAdmin']);
//ranking_eldwry
    Route::get('ranking_eldwry/match', ['as' => 'ranking_eldwry.create_match', 'uses' => 'RankingEldwryController@create_match']);
    Route::post('ranking_eldwry/match', ['as' => 'ranking_eldwry.store_match', 'uses' => 'RankingEldwryController@store_match']);
    Route::get('ranking_eldwry/search', ['as' => 'ranking_eldwry.search', 'uses' => 'RankingEldwryController@search']);
    
    //Resource
    Route::resource('opta', 'OptaController');
    Route::resource('ranking_eldwry', 'RankingEldwryController');
    Route::resource('headGroupEldwry', 'HeadGroupEldwryController');
    Route::resource('groupEldwry', 'GroupEldwryController');
    Route::resource('fantasy', 'FantasyController');
    Route::resource('languages', 'LanguageController');
    Route::resource('settings', 'SettingController');
    Route::resource('users', 'UserController');
    Route::resource('orders', 'OrderController');
    Route::resource('roles', 'RoleController');
    Route::resource('posts', 'PostController');
    Route::resource('players', 'PlayerController');
    Route::resource('matches', 'MatchController');
    Route::resource('eldwry', 'EldwryController');
    Route::resource('subeldwry', 'SubeldwryController');
    Route::resource('clubteams', 'TeamController');
    Route::resource('subclubteams', 'SubteamController');
    Route::resource('videos', 'VideoController');
//    Route::resource('videos', 'VideoController', ['except' => ['index','store','create','show','edit','destroy']]);
    Route::resource('videocomments', 'CommentVideoController');
    Route::resource('blogs', 'BlogController');
    Route::resource('blogcomments', 'CommentBlogController');
    Route::resource('contacts', 'ContactController', ['except' => ['create', 'store', 'index']]);
    Route::resource('comments', 'CommentController');
//    Route::resource('comments', 'CommentController', ['except' => ['create', 'store']]);
    Route::resource('userclubteams', 'UserteamController');
    Route::resource('categories', 'CategoryController');
    Route::resource('subcategories', 'SubcategoryController');
    Route::resource('apimessages', 'ApimessagesController');
    Route::resource('permission', 'PermissionController');
    Route::resource('tags', 'TagController');
    Route::resource('searches', 'SearchController');
    Route::resource('messages', 'MessageController', ['except' => ['edit', 'destroy']]);
});

