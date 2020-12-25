<?php

namespace App\Http\Controllers\ClassSiteApi;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Blog;
use App\Models\Video;
use App\Models\CommentVideo;
use App\Models\CommentBlog;
use App\Models\Comment;
use App\Models\Action;
use App\Models\Options;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\ClassSiteApi\Class_NotifController;

class Class_ActionController extends SiteController {

    public function __construct() {
        parent::__construct();

    }

    public function Check_fav_data($post_id, $type = 'video', $type_action = 'like', $current_id = 0, $api = 0) {
        $fav = Action::actionCheckUserId($current_id, $post_id, $type, $type_action);
        return $fav;
    }

    public function get_fav_data($post_id, $type = 'video', $type_action = 'like', $count = 0, $api = 0) {
        $data_fav = Action::get_DataAction($post_id, $type, $type_action, $count);

        return $data_fav;
    }

    public function add_delete_fav_data($post, $type = 'video', $type_action = 'like', $current_id = 0, $api = 0) {
        $state_fav = $this->Check_fav_data($post->id, $type, $type_action, $current_id, $api);

        if ($state_fav == 0) {
            $add_fav = Action::insertAction($current_id, $post->id, $type, $type_action);
            $like = $state_action = 1;
        } else {
            $delete_fav = Action::deleteUserAction($current_id, $post->id, $type, $type_action);
            $like = 0;
            $state_action = 2;
        }
        if ($current_id <= 0) {
            $state_action = 0;
        }
        if ($api == 0) {
            return array('like' => $like, 'state_action' => $state_action);
        } else {
            return array('like' => $like);
        }
    }

    public function action_fav_Data($link, $type, $type_action, $current_id = 0, $api = 0) {
        $user_key = $this->user_key;
        if ($api == 0) {
            $lang = $this->lang;
            $current_id = $this->current_id;
        } else {
            if (empty($lang)) {
                $lang = 'ar';
            }
        }
        $get_data = new Class_CommentController();
        $post = $get_data->get_data($link, $type, $current_id, 1);
        $array_data['state_action'] = -1;
        $array_data['like'] = 0;
        $array_data['num_like'] = 0;
        if (isset($post->id)) {
            $array_data = $this->add_delete_fav_data($post, $type, $type_action, $current_id, $api);
            $array_data['num_like'] = Action::get_DataAction($post->id, $type, $type_action, 1);
        }
        if ($api == 1) {
            $array_data['like'] = (string) $array_data['like'];
            $array_data['num_like'] = (string) $array_data['num_like'];
        } else {
//            $array_data['user_key'] = $user_key;
//            $array_data['post'] = $post;
        }
        return $array_data;
    }

}
