<?php

namespace App\Http\Controllers\Api\V1;

//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\API_Controller;
use App\Http\Controllers\ClassSiteApi\Class_CommentController;
use App\Models\User;
use App\Models\Comment;
use App\Models\CommentVideo;
use App\Models\CommentBlog;

class CommentController extends API_Controller {
    /**
     * get allcomments of cart  with type (comment,news,video)  
     * get method
     * url : http://localhost:8000/api/v1/comments
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/comments",
     *   tags={"comment"},
     *   operationId="comments",
     *   summary="get all comments",
     * @OA\Parameter(
     *    name="access-token",
     *    in= "header",
     *      @OA\Schema(
     *           type="string"
     *      ),
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
     *      @OA\Schema(
     *           type="string",
     *           default="ar"
     *      )
     *   ),
     * @OA\Parameter(
     *    name="link",
     *    in= "query",
     *    required=true,
     *      @OA\Schema(
     *           type="string"
     *      ),
     *    description="this link type only",
     *  ),
     * @OA\Parameter(
     *    name="type",
     *    in= "query",
     *    description="default type is video and can use (news,video)",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="limit",
     *    in= "query",
     *    description="default limit 12  and can you increase limit",
     *      @OA\Schema(
     *           type="string"
     *      ),
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
    public function comments(Request $request) {
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
        $limit = isset($input['limit']) ? $input['limit'] : 12;
        $type = isset($input['type']) ? $input['type'] : 'video';
        $type_comment = 'comment';
        $response = [];
        $fields = [];
        if ($link == "") {
            $fields['link'] = 'link';
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        if ($type == 'news') {
            $type = 'blog';
        }
        $get_data = new Class_CommentController();
        $post = $get_data->get_data($link, $type, 0, 1);
        if (isset($post->id)) {
            $user_id = 0;
            if (!empty($access_token)) {
                $data_user = User::user_access_token($access_token, 1);
                if (isset($data_user->id)) {
                    $user_id = $data_user->id;
                } else {
                    $response = API_Controller::MessageData('USER_NOT_Found', $lang);
                    return response()->json($response, 400);
                }
            }
            $get_comment = new Class_CommentController();
            $all_comment = $get_comment->get_commentdata($post, $type,$lang, '', 'id', 'DESC', '', '', $user_id, 1, $limit, $type_comment);
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang);
            $response['count_data'] = $all_comment['comt_quest_count'];
            $response['data'] = $all_comment['comments'];
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('NO_DATA_FOUND', $lang);
            return response()->json($response, 400);
        }
    }

    /**
     * add new comment of cart  with type (comment)  
     * get method
     * url : http://localhost:8000/api/v1/add_comment
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/add_comment",
     *   tags={"comment"},
     *   operationId="add_comment",
     *   summary="add comment",
     * @OA\Parameter(
     *    name="access-token",
     *    in= "header",
     *      @OA\Schema(
     *           type="string"
     *      ),
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
     *      @OA\Schema(
     *           type="string",
     *           default="ar"
     *      )
     *   ),
     * @OA\Parameter(
     *    name="link",
     *    in= "query",
     *    required=true,
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="content",
     *    in= "query",
     *    description="You must be present if image and video and audio are empty",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="link_parent",
     *    in= "query",
     *    description="You must be present link of parent comment with answer and reply",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="user_email",
     *    in= "query",
     *    description="should be found if access-token is empty",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="user_name",
     *    in= "query",
     *    description="should be found if access-token is empty",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="image",
     *    in= "query",
     *    description="path image after upload on server",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="video",
     *    in= "query",
     *    description="path video after upload on server",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="audio",
     *    in= "query",
     *    description="path audio after upload on server",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="type",
     *    in= "query",
     *    description="default type is video and can use (news,video)",
     *      @OA\Schema(
     *           type="string"
     *      ),
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
    public function add_comment(Request $request) {
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
        $content = isset($input['content']) ? $input['content'] : '';
        $user_name = isset($input['user_name']) ? $input['user_name'] : '';
        $user_email = isset($input['user_email']) ? $input['user_email'] : '';
        $link_parent = isset($input['link_parent']) ? $input['link_parent'] : NULL;
        $rate = isset($input['rate']) ? $input['rate'] : 0;
        $image = isset($input['image']) ? $input['image'] : NULL;
        $video = isset($input['video']) ? $input['video'] : NULL;
        $audio = isset($input['audio']) ? $input['audio'] : NULL;
        $type = isset($input['type']) ? $input['type'] : 'video';
        $type_comment = 'comment';
        $response = [];
        $fields = [];
        if ($link == "") {
            $fields['link'] = 'link';
        }
        if ($content == "" && $image == "" && $video == "" && $audio == "") {
            $fields['content'] = 'content';
        }
//        if ($access_token == "") {
//            $fields['access_token'] = 'access-token';
//        }
        if ($access_token == "") {
            if ($user_name == "") {
                $fields['user_name'] = 'user_name';
            }
            if ($user_email == "") {
                $fields['user_email'] = 'user_email';
            } else {
                if (filter_var($user_email, FILTER_VALIDATE_EMAIL) === false) {
                    $response = API_Controller::MessageData('INVALID_EMAIL', $lang);
                    return response()->json($response, 400);
                }
            }
        }
        if ($type == "" || !in_array($type, ['match', 'video', 'comment', 'news'])) {
            $fields['type'] = 'type';
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        if ($type == 'news') {
            $type = 'blog';
        }
        $get_data = new Class_CommentController();
        $post = $get_data->get_data($link, $type, 0, 1);
        if (isset($post->id)) {
            $user_id = 0;
            if (!empty($access_token)) {
                $data_user = User::user_access_token($access_token, 1);
                if (isset($data_user->id)) {
                    $user_id = $data_user->id;
                } else {
                    $response = API_Controller::MessageData('USER_NOT_Found', $lang);
                    return response()->json($response, 400);
                }
            }
            if (!empty($video) || $video != '') {
                $video = getPathViemo($video);
            }
            $input = [
                'link' => $link,
                'link_parent' => $link_parent,
                'type' => $type,
                'content' => $content,
                'image' => $image,
                'video' => $video,
                'audio' => $audio
            ];
            if ($type == 'blog') {
                $input['blog_id'] = $post->id;
            } elseif ($type == 'video') {
                $input['video_id'] = $post->id;
            } else {
                $input['post_id'] = $post->id;
            }
            if ($access_token == "") {
                $input['name'] = $user_name;
                $input['email'] = $user_email;
            }
            $get_comment = new Class_CommentController();
            $add_comment = $get_comment->add_commentDATA($input, $type, $type_comment, $post, $user_id, 1);
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang);
//            $response['subscribed'] = True;
            $response['data'] = $add_comment['comment'];
            $response['state_add'] = $add_comment['state_add'];
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('NO_DATA_FOUND', $lang);
            return response()->json($response, 400);
        }
    }

    /**
     * update  comment of cart  with type (comment)  
     * get method
     * url : http://localhost:8000/api/v1/update_comment
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/update_comment",
     *   tags={"comment"},
     *   operationId="update_comment",
     *   summary="update comment",
     * @OA\Parameter(
     *    name="access-token",
     *    in= "header",
     *    required=true,
     *      @OA\Schema(
     *           type="string"
     *      ),
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
     *      @OA\Schema(
     *           type="string",
     *           default="ar"
     *      )
     *   ),
     * @OA\Parameter(
     *    name="comment_link",
     *    in= "query",
     *    required=true,
     *    description="link comment to update data of it",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="content",
     *    in= "query",
     *    description="You must be present if image and video and audio are empty",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="image",
     *    in= "query",
     *    description="path image after upload on server",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="type",
     *    in= "query",
     *    description="default type is video and can use (news,video)",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="video",
     *    in= "query",
     *    description="path video after upload on server",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="audio",
     *    in= "query",
     *    description="path audio after upload on server",
     *      @OA\Schema(
     *           type="string"
     *      ),
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
    public function update_comment(Request $request) {
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
        $comment_link = isset($input['comment_link']) ? $input['comment_link'] : '';
        $content = isset($input['content']) ? $input['content'] : '';
        $rate = isset($input['rate']) ? $input['rate'] : 0;
        $image = isset($input['image']) ? $input['image'] : NULL;
        $video = isset($input['video']) ? $input['video'] : NULL;
        $audio = isset($input['audio']) ? $input['audio'] : NULL;
        $type = isset($input['type']) ? $input['type'] : 'video';
        $type_comment = 'comment';
        $response = [];
        $fields = [];
        if ($comment_link == "") {
            $fields['comment_link'] = 'comment_link';
        }
        if ($content == "" && $image == "" && $video == "" && $audio == "") {
            $fields['content'] = 'content';
        }
        if ($access_token == "") {
            $fields['access_token'] = 'access-token';
        }
        if ($type == "" || !in_array($type, ['match', 'video', 'comment', 'news'])) {
            $fields['type'] = 'type';
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }

        if ($type == 'news') {
            $type = 'blog';
        }
        if (!empty($access_token)) {
            $data_user = User::user_access_token($access_token, 1);
            if (isset($data_user->id)) {
                $user_id = $data_user->id;
                $get_data = new Class_CommentController();
                $comment = $get_data->get_dataComment($comment_link, $type, 1, 1);
                if (isset($comment->id)) {
                    if ($user_id == $comment->user_id) {
                        if (!empty($video) && $comment->video != $video) {
                            $video = getPathViemo($video);
                        }
                        $input = [
                            'comment_link' => $comment_link,
                            'content' => $content,
                            'image' => $image,
                            'video' => $video,
                            'audio' => $audio,
                        ];
                        $get_comment = new Class_CommentController();
                        $update_comment = $get_comment->update_commentDATA($input, $type, $user_id, 1);
                        if ($update_comment) {
                            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang);
                            return response()->json($response, 200);
                        } else {
                            $response = API_Controller::MessageData('ERROR_MESSAGE', $lang);
                            return response()->json($response, 400);
                        }
                    } else {
                        $response = API_Controller::MessageData('NoOWner', $lang);
                        return response()->json($response, 400);
                    }
                } else {
                    $response = API_Controller::MessageData('NO_DATA_FOUND', $lang);
                    return response()->json($response, 400);
                }
            } else {
                $response = API_Controller::MessageData('USER_NOT_Found', $lang);
                return response()->json($response, 400);
            }
        } else {
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang);
            return response()->json($response, 400);
        }
    }

    /**
     * delete comment by id  
     * get method
     * url : http://localhost:8000/api/v1/delete_comment
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/delete_comment",
     *   tags={"comment"},
     *   operationId="delete_comment",
     *   summary="delete comment",
     * @OA\Parameter(
     *    name="access-token",
     *    in= "header",
     *    required=true,
     *      @OA\Schema(
     *           type="string"
     *      ),
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
     *      @OA\Schema(
     *           type="string",
     *           default="ar"
     *      )
     *   ),
     * @OA\Parameter(
     *    name="comment_link",
     *    in= "query",
     *    required=true,
     *    description="link of comment or video or news  to delete",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="type",
     *    in= "query",
     *    description="default type is video and can use (news,video)",
     *      @OA\Schema(
     *           type="string"
     *      ),
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
    public function delete_comment(Request $request) {
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
        $comment_link = isset($input['comment_link']) ? $input['comment_link'] : '';
        $type = isset($input['type']) ? $input['type'] : 'video';
        $type_comment = 'comment';
        $response = [];
        $fields = [];
        if ($comment_link == "") {
            $fields['comment_link'] = 'comment_link';
        }
        if ($access_token == "") {
            $fields['access_token'] = 'access-token';
        }

        if ($type == "" || !in_array($type, ['match', 'video', 'comment', 'news'])) {
            $fields['type'] = 'type';
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }

        if ($type == 'news') {
            $type = 'blog';
        }
        if (!empty($access_token)) {
            $data_user = User::user_access_token($access_token, 1);
            if (isset($data_user->id)) {
                $user_id = $data_user->id;
                if ($type == 'blog') {
                    $comment = CommentBlog::commentLink('link', $comment_link, 1);
                } elseif ($type == 'video') {
                    $comment = CommentVideo::commentLink('link', $comment_link, 1);
                } else {
                    $comment = Comment::commentLink('link', $comment_link, 1);
                }
                if (isset($comment->id)) {
                    if ($user_id == $comment->user_id) {
                        $delete_comment = $comment->delete();
                        if ($delete_comment) {
                            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang);
                            return response()->json($response, 200);
                        } else {
                            $response = API_Controller::MessageData('ERROR_MESSAGE', $lang);
                            return response()->json($response, 400);
                        }
                    } else {
                        $response = API_Controller::MessageData('NoOWner', $lang);
                        return response()->json($response, 400);
                    }
                } else {
                    $response = API_Controller::MessageData('NO_DATA_FOUND', $lang);
                    return response()->json($response, 400);
                }
            } else {
                $response = API_Controller::MessageData('USER_NOT_Found', $lang);
                return response()->json($response, 400);
            }
        } else {
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang);
            return response()->json($response, 400);
        }
    }

}
