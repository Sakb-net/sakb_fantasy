<?php

namespace App\Http\Controllers\Api\V1;

//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\API_Controller;
use App\Models\User;
use App\Models\Blog;
use App\Models\TeamUser;

class NewsController extends API_Controller {
    /**
     * get data news , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/news
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/api/v1/news",
     *   tags={"news"},
     *   operationId="v1_news",
     *   summary="get news",
     *   description="",
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
     *  @OA\Parameter(
     *    name="num_page",
     *    in= "query",
     *      @OA\Schema(
     *           type="number",
     *      ),
     *    description=" number of page start from zero ( 0 )",
     *  ),
     *  @OA\Parameter(
     *    name="limit",
     *    in= "query",
     *      @OA\Schema(
     *           type="number",
     *      ),
     *    description=" limit is number news will send in each time default ( 12 )",
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
    public function news(Request $request) {

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
        $num_page = isset($input['num_page']) ? $input['num_page'] : 0;
        $limit = isset($input['limit']) ? $input['limit'] : 12;
        $offset = $num_page * $limit;
        $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
        $all_news = Blog::get_BlogActive(1, '', '', $lang, 0, $limit, $offset);
        $news = Blog::dataNews($all_news, 1);
        $response['data'] = $news;
        return response()->json($response, 200);
    }



      /**
     * Show single page of one news   
     * get method
     * url : http://localhost:8000/api/v1/news/followingNews
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/api/v1/news/followingNews",
     *   tags={"news"},
     *   operationId="followingNews",
     *   summary="get following news",
     *  @OA\Parameter(
     *    name="access-token",
     *    in= "header",
     *    required=true,
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
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
     *  @OA\Parameter(
     *    name="num_page",
     *    in= "query",
     *      @OA\Schema(
     *           type="number",
     *      ),
     *    description=" number of page start from zero ( 0 )",
     *  ),
     *  @OA\Parameter(
     *    name="limit",
     *    in= "query",
     *      @OA\Schema(
     *           type="number",
     *      ),
     *    description=" limit is number following news will send in each time default ( 12 )",
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
    
    public function followingNews(Request $request) {

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
        $num_page = isset($input['num_page']) ? $input['num_page'] : 0;
        $limit = isset($input['limit']) ? $input['limit'] : 12;
        $offset = $num_page * $limit;

        $user = User::user_access_token($access_token, 1);
        if (isset($user->id)) {
            $followingTeams = TeamUser :: selectFollowingTeams($user->id);
        if(count($followingTeams) > 0){
            $allNews = Blog::getFollowingBlogs($followingTeams, $lang, $limit, $offset);
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            $response['data'] = $allNews;
            return response()->json($response, 200);
        }else{
            $response = API_Controller::MessageData('NO_FOLLOWING_TEAMS', $lang,11);
            return response()->json($response, 401);
        }
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }

    /**
     * Show single page of one news   
     * get method
     * url : http://localhost:8000/api/v1/news/single
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/api/v1/news/single",
     *   tags={"news"},
     *   operationId="newsSingle",
     *   summary="get single news by link",
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
     *    name="news_link",
     *    in= "query",
     *    required=true,
     *      @OA\Schema(
     *           type="string"
     *      ),
     *    description="link of news",
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
    public function newsSingle(Request $request) {
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
        $news_link = isset($input['news_link']) ? $input['news_link'] : '';
        $response = [];
        $fields = [];
        if ($news_link == "") {
            $fields['news_link'] = 'news_link';
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang,2);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        $news = Blog::get_blog('link', $news_link, $lang, 1);
        if (isset($news->id)) {
            Blog::updateBlogViewCount($news->id);
            $data_news = Blog::dataNews_single($news, 1);
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            $response['data'] = $data_news;
            return response()->json($response, 200);
        } else {
                $response = API_Controller::MessageData('NO_DATA_FOUND', $lang,11);
            return response()->json($response, 400);
        }
    }

}
