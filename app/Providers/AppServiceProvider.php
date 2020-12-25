<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Validator;
use App\Models\Options;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Contact;
use App\Models\Category;
use App\Models\Language;
use App\Models\Page;
use App\Models\Fees;
use App\Models\UserNotif;
use Session;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
        public function boot() {
        //table session
        Schema::defaultStringLength(191);
        //for convert route from http to https 
        $this->app['request']->server->set('HTTPS', $this->app->environment() != 'local');
        if(config('app.env') === 'production') {
             \URL::forceScheme('https');
       }
        // $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);
        Relation::morphMap([
            'posts' => 'App\Models\Post',
            'comments' => 'App\Models\Comment',
            'searches' => 'App\Models\Search',
            'categories' => 'App\Models\Category',
        ]);

        Validator::extend('ValueSelectID', function ($attribute, $value, $parameters, $validator) {

            if ($parameters[0] == 0) {
                $count = 0;
            } else {
                $count = 1;
            }
            return $count === 1;
        });
        Validator::extend('uniqueCategoryLinkType', function ($attribute, $value, $parameters, $validator) {
            $count = Category::where('link', $value)
                    ->where('type', $parameters[0])
                    ->count();

            return $count === 0;
        });

        Validator::extend('uniqueCategoryUpdateLinkType', function ($attribute, $value, $parameters, $validator) {
            $count = Category::where('link', $value)
                    ->where('type', $parameters[0])
                    ->where('id', '!=', $parameters[1])
                    ->count();

            return $count === 0;
        });

        Validator::extend('uniquePostLinkType', function ($attribute, $value, $parameters, $validator) {
            $count = Post::where('link', $value)
                    ->where('type', $parameters[0])
                    ->count();

            return $count === 0;
        });

        Validator::extend('uniquePostUpdateLinkType', function ($attribute, $value, $parameters, $validator) {
            $count = Post::where('link', $value)
                    ->where('type', $parameters[0])
                    ->where('id', '!=', $parameters[1])
                    ->count();

            return $count === 0;
        });

        view()->composer('admin.layouts.app', function($view) {
            $user_key = '';
            $user_account = Auth::user();
            $post_count = Post::countPostTypeUnRead('posts');
            $message_count = 0;
            $comment_count = Comment::countUnRead();
            $contact_count = Contact::countUnRead();

            $access_all = $user_all = $post_all = $tag_all = $search_all = $message_all = $category_all = $comment_all = $contact_all = 0;
            if ($user_account->can('access-all')) {
                $access_all = $user_all = $post_all = $tag_all = $search_all = $message_all = $category_all = $comment_all = $contact_all = 1;
            }

            if ($user_account->can(['post-type-all', 'post-all'])) {
                $post_all = $tag_all = $search_all = $category_all = $comment_all = $contact_all = 1;
            }

            if ($user_account->can(['user*'])) {
                $user_all = 1;
            }

            if ($user_account->can(['post-list', 'post-edit', 'post-delete', 'post-show'])) {
                $post_all = 1;
            }

            if ($user_account->can(['message*'])) {
                $message_all = 1;
            }

            if ($user_account->can(['category*'])) {
                $category_all = 1;
            }

            if ($user_account->can(['comment-all', 'comment-create', 'comment-list', 'comment-edit', 'comment-delete'])) {
                $comment_all = 1;
            }
            $cuRRlocal = Language::currentLang(0);
            $about_title = $award_title = $instraction_title = $contact_title = $chair_title = $fees_title = $terms_title = $album_title = null;
            $dataAllpage = Page::get_Pages($cuRRlocal);
            foreach ($dataAllpage as $key_page => $val_page) {
                $type_title = $val_page->type . '_title';
                $$type_title = $val_page->name;
            }

            $user_account = Auth::user();
            if (!empty($user_account)) {
                $user_key = $user_account->name;
            }
            $view->with(array(
                'cuRRlocal' => $cuRRlocal, 'user_account' => $user_account, 'access_all' => $access_all, 'user_all' => $user_all,
                'category_all' => $category_all, 'tag_all' => $tag_all, 'search_all' => $search_all,
                'post_all' => $post_all, 'post_count' => $post_count, 'user_key' => $user_key, 'fees_title' => $fees_title,
                'chair_title' => $chair_title, 'album_title' => $album_title, 'contact_all' => $contact_all, 'terms_title' => $terms_title, 'about_title' => $about_title,'instraction_title'=>$instraction_title,'award_title'=>$award_title, 'contact_title' => $contact_title,
                'comment_all' => $comment_all, 'comment_count' => $comment_count, 'contact_count' => $contact_count,
                'message_all' => $message_all, 'message_count' => $message_count
            ));
        });

        view()->composer(['site.layouts.app','site.layouts.app_close'], function($view) {
            $email = $phone = $address = $chat_pixel = $chat_pixel = $twitter = $default_image = $keywords = $youtube = $share_image = $description = $whatsapp = $facebook_pixel = $googleplus = $logo_image = $linkedin = $google_analytic = '';
            $user_id = $message_count = $admin_panel = $count_notif = 0;
            $user_name = $user_image = $user_key = $data_notif = "";
            $option = Options::where('autoload', 1)->pluck('option_value', 'option_key')->toArray();
            foreach ($option as $key => $value) {
                $$key = $value;
            }

            $categories_product = [];
            $categories_team = Category::cateorySelect(0, 'team', '', '', 1, 0);
            $languages = Language::get_Languag('is_active',1,'',0);
            $cuRRlocal = Language::currentLang(0);

            $user_account = Auth::user();
            if (!empty($user_account)) {
                $user_id = $user_account->id;
                $user_name = $user_account->display_name;
                $user_image = $user_account->image;
                $user_key = $user_account->name;
                $message_count = 0;
                if ($user_account->can(['access-all', 'category*', 'user*', 'message*', 'post-type-all', 'post-all', 'comment-all', 'admin-panel'])) {
                    $admin_panel = 1;
                }
                $get_notif = UserNotif::get_UserNotif($user_id, 1, 0);
                $data_notif = UserNotif::SelectDataNotif($get_notif, 0);
                $count_notif = count($data_notif);
            }
            $fast = 0;
            $share_link = route('home');
//$share_title=''; //'share_title'=>$share_title,
            $view->with(array(
                'categories_team' => $categories_team, 'count_notif' => $count_notif, 'data_notif' => $data_notif, 'cuRRlocal' => $cuRRlocal, 'languages'=>$languages,'fast' => $fast,
                'user_account' => $user_account, 'user_key' => $user_key, 'user_image' => $user_image, 'user_name' => $user_name, 'message_count' => $message_count,
                'email' => $email, 'phone' => $phone, 'address' => $address, 'admin_panel' => $admin_panel, 'share_link' => $share_link,
                'description' => $description, 'share_description' => $description, 'share_key' => $keywords, 'keywords' => $keywords, 'chat_pixel' => $chat_pixel, 'facebook_pixel' => $facebook_pixel, 'google_analytic' => $google_analytic,
                'facebook' => $chat_pixel, 'twitter' => $twitter, 'youtube' => $youtube, 'googleplus' => $googleplus, 'whatsapp' => $whatsapp, 'linkedin' => $linkedin,
                'share_image' => $share_image, 'default_image' => $default_image, 'logo_image' => $logo_image,
            ));
        });
    }

}
