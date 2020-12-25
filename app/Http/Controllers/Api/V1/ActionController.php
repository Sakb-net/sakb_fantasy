<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\API_Controller;
use App\Http\Controllers\ClassSiteApi\Class_ActionController;
use App\Http\Controllers\ClassSiteApi\Class_CommentController;
use App\Modelss\User;
use App\Modelss\Watche;

class ActionController extends API_Controller {
    /**
     * add video to table watches  
     * get method
     * url : http://localhost:8000/api/v1/add_watch
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     **   path="/add_watch",
     *   tags={"watch"},
     *   operationId="v1/add_watch",
     *   summary="add video watch",
     * @OA\Parameter(
     *    name="access-token",
     *    in= "header",
     *    required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *  ),
     *  @OA\Parameter(
     *     name="type-dev",
     *     in="header",
     *     required=true,
     *     description="ios or android",
     *      @OA\Schema(
     *           type="string",
     *           default="ios"
     *      )
     *   ),
     * @OA\Parameter(
     *     name="val-dev",
     *     in="header",
     *     required=true,
     *      @OA\Schema(
     *           type="string",
                 default="sakb"
     *      )
     *   ),
     * @OA\Parameter(
     *    name="lang",
     *    in="header",
     *           description= "default lang is ar ( ar , en)",
     *      @OA\Schema(
     *           type="string",
     *           default="ar"
     *      )
     *   ),
     * @OA\Parameter(
     *    name="video_link",
     *    in= "query",
     *    required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *  ),
     *   @OA\Response(
     *    response=200,
     *    description="success",
     *   ),
     *   @OA\Response(
     *    response=400,
     *    description="error",
     *  )
     * )
     */
    public function add_watch(Request $request) {
         $data_header = API_Controller::get_DataHeader(getallheaders());
        $access_token = $data_header['access_token'];
        $val_dev = $data_header['val_dev'];
        $type_dev = $data_header['type_dev'];
        $lang = $data_header['lang'];
        $data_ok = API_Controller::AuthAPI($type_dev, $val_dev);
        if (empty($data_ok) || $data_ok == 0) {
            $response = API_Controller::MessageData('AUTH_NOTALLOW', $lang, 49);
            return response()->json($response, 400);
        }
        $input = $request->all();
        $video_link = isset($input['video_link']) ? $input['video_link'] : '';
        $response = [];
        $fields = [];
        if ($video_link == "") {
            $fields['video_link'] = 'video_link';
        }
        if ($access_token == "") {
           $fields['access_token'] = 'access-token';
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
            $response['data'] = $fields;
            return response()->json($response, 401);
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang,2);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        if (!empty($access_token)) {
            $data_user = User::user_access_token($access_token, 1);
            if (isset($data_user->id)) {
                $user_id = $data_user->id;
                $add_watch = Watche::insertWatcheVideoLink($user_id, $video_link, 1);
                if ($add_watch > 0) {
                    $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
                    return response()->json($response, 200);
                } else {
                    $response = API_Controller::MessageData('NOT_SAVED', $lang,21);
                    return response()->json($response, 400);
                }
            } else {
                $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
                return response()->json($response, 401);
            }
        } else {
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
            return response()->json($response, 401);
        }
    }

    /**
     * add comment to like list by link and  access_token
     * post method
     * url : http://localhost:8000/api/v1/add_del_like
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/add_del_like",
     *   tags={"like,dislike"},
     *   operationId="v1/add_del_like",
     *   summary="add or delete like for video or news or comment or comment_news or comment_video ",
     *   @OA\Parameter(
     *    name="access-token",
     *    in= "header",
     *    required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *  ),
     *  @OA\Parameter(
     *     name="type-dev",
     *     in="header",
     *     required=true,
     *     description="ios or android",
     *      @OA\Schema(
     *           type="string",
     *          default="ios" 
     *      )
     *   ),
     * @OA\Parameter(
     *     name="val-dev",
     *     in="header",
     *     required=true,
     *      @OA\Schema(
     *           type="string",
     *           default="sakb" 
     *      ) 
     *   ),
     * @OA\Parameter(
     *    name="lang",
     *    in="header",
     *    description= "default lang is ar ( ar , en)",
     *     @OA\Schema(
     *           type="string",
     *           default="ar"
     *     )
     *   ),
     * @OA\Parameter(
     *    name="link",
     *    in= "query",
     *    required=true,
     *      @OA\Schema(
     *           type="string"
     *      ),
     *    description="link thing that add to like or delete",
     *  ),
     * @OA\Parameter(
     *    name="type",
     *    in= "query",
     *    required=true,
     *      @OA\Schema(
     *           type="string"
     *      ),
     *    description="video or news  or comment or comment_news or comment_video ",
     *  ),
     *   @OA\Response(
     *    response=200,
     *    description="success",
     *   ),
     *   @OA\Response(
     *    response=400,
     *    description="error",
     *  )
     * )
     */
    public function add_del_like(Request $request) {
        $data_header = API_Controller::get_DataHeader(getallheaders());
        $access_token = $data_header['access_token'];
        $val_dev = $data_header['val_dev'];
        $type_dev = $data_header['type_dev'];
        $lang = $data_header['lang'];
        $data_ok = API_Controller::AuthAPI($type_dev, $val_dev);
        if (empty($data_ok) || $data_ok == 0) {
            $response = API_Controller::MessageData('AUTH_NOTALLOW', $lang, 49);
            return response()->json($response, 400);
        }
        $input = $request->all();
        $link = isset($input['link']) ? $input['link'] : '';
        $type = isset($input['type']) ? $input['type'] : '';
        $response = [];
        $fields = [];
        if ($access_token == "") {
           $fields['access_token'] = 'access-token';
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
            $response['data'] = $fields;
            return response()->json($response, 401);
        }
        if ($link == "") {
            $fields['link'] = 'link';
        }
        if ($type == "" || !in_array($type, ['match', 'video', 'news', 'comment', 'comment_news', 'comment_video'])) {
            $fields['type'] = 'type';
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang,2);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        if ($type == 'news') {
            $type = 'blog';
        }
        if ($type == 'comment_news') {
            $type = 'comment_blog';
        }
        if (!empty($access_token)) {
            $data_user = User::user_access_token($access_token, 1);
            if (isset($data_user->id)) {
                $user_id = $data_user->id;
                $result = -1;
                if (in_array($type, ['blog', 'video'])) {
                    $get_action = new Class_ActionController();
                    $result = $get_action->action_fav_Data($link, $type, 'like', $user_id, 1);
                } elseif (in_array($type, ['comment', 'comment_blog', 'comment_video'])) {
                    $type_comment = 'comment';
                    if ($type == 'comment_blog') {
                        $type_comment = 'blog';
                    } elseif ($type == 'comment_video') {
                        $type_comment = 'video';
                    } 
                    $get_comment = new Class_CommentController();
                    $result = $get_comment->action_fav_comment($link, $type_comment, $type, 'like', 1, $user_id, 1);
                }
                if ($result['like'] == 1 || $result['like'] == 0) {
                    $response = API_Controller::MessageData('MISSING_FIELD', $lang,2);
                    $response['Message'] = API_Controller::SUCCESS_MESSAGE;
                    $response['data'] = $result['like'];
                    return response()->json($response, 200);
                } else {
                    $response = API_Controller::MessageData('NOT_SAVED', $lang,21);
                    return response()->json($response, 400);
                }
            } else {
                $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
                return response()->json($response, 401);
            }
        } else {
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
            return response()->json($response, 401);
        }
    }

}
