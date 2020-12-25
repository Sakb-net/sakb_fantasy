<?php

namespace App\Http\Controllers\ClassSiteApi;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Comment;
use App\Models\UserNotif;
use App\Models\Options;
use App\Http\Controllers\SiteController;

class Class_NotifController extends SiteController {

    public function __construct() {
        parent::__construct();
    }

    public function Update_readNotif($array_type_id = []) {
        $lang = $this->lang;
        $current_id = $this->current_id;
        $update = UserNotif::updateOrderColumTwo('to_id', $current_id, 'type_id', $array_type_id, 'is_read', 1);
        return $update;
    }

    public function insert_NotifOrder($current_user, $checkoutId, $api = 0) {
        $order = Order::get_DataByThreeCondition('user_id',$current_user->id,'checkoutId',$checkoutId,'is_active', 1);
        $add =0;
        if(isset($order->id)){
            //for user that request order
            $all_user = [$current_user];
            $from_user_id = 1; //id of admin
            $content = 'تمت عملية الدفع بنجاح سيتم استبدال الاعبين';
            $add = UserNotif::insert_SendNotification($all_user, $order, $from_user_id, $order->id, 'order','add_order',null, $content, 0, 1);
        }
        return $add;
    }

    public function insert_NotificationTableComment($current_data, $post, $comment, $current_id = 0, $api = 0) {
        if ($api == 0) {
            $lang = $this->lang;
            $current_id = $this->current_id;
        }
        $add_notif = new Class_NotifController();
        $all_user = $add_notif->get_UserSendNotifSubscribe($current_data, $comment);
        $add = UserNotif::insert_SendNotification($all_user, $current_data, $current_id, $post->id, $post->type, $comment['type'], $comment['id'], $comment['content'], 0, 1);
        return $add;
    }

    public function get_UserSendNotifSubscribe($current_data, $comment) {
        $all_user = [];
        $instructor_id = $current_data->user_id;
        //get user 
        if ($comment['user_id'] != $instructor_id) {
            //get Instructor
            $all_user[] = $current_data->user;
        }
        //get student
        if ($comment['parent_one_id'] != Null && $comment['parent_two_id'] != Null) { //parent (comment or question)
            //$comment['parent_one_id'] == $comment['parent_two_id'] first_child parent (answer)
            $parent_comment = Comment::commentLink('parent_one_id', $comment['parent_one_id'], 1, '', 1);
            foreach ($parent_comment as $key_pat => $val_pat) {
                if (!empty($val_pat->user) && $val_pat->user_id != $instructor_id && $comment['user_id'] != $val_pat->user_id) {
                    $all_user[] = $val_pat->user;
                }
            }
        }
        return $all_user;
    }

    public function get_UserSendNotifSubscribe_old($current_data, $comment) {
        $all_user = [];
        $instructor_id = $current_data->user_id;
        //get user 
        if ($comment['user_id'] != $instructor_id) {
            //get Instructor
            $all_user[] = $current_data->user;
        }
        //get student
        if ($comment['parent_one_id'] != Null && $comment['parent_two_id'] != Null) { //parent (comment or question)
            //$comment['parent_one_id'] == $comment['parent_two_id'] first_child parent (answer)
            $parent_comment = Comment::commentLink('id', $comment['parent_one_id'], 1, '');
            if (!empty($parent_comment->user) && $parent_comment->user_id != $instructor_id && $comment['user_id'] != $parent_comment->user_id) {
                $all_user[] = $parent_comment->user;
            }
            if ($comment['parent_one_id'] != $comment['parent_two_id']) { //child first_child  (repaly)
                $first_child = Comment::commentParent($comment['parent_one_id'], $comment['parent_one_id'], 1, '');
                if (!empty($first_child->user) && $first_child->user_id != $instructor_id && $comment['user_id'] != $first_child->user_id) {
                    $all_user[] = $first_child->user;
                }
            }
        }
        return $all_user;
    }

}
