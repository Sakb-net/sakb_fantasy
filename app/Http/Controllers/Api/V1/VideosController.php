<?php

namespace App\Http\Controllers\Api\V1;

//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\API_Controller;
use App\Models\User;
use App\Models\Video;

class VideosController extends API_Controller {
    /**
     * get data videos , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/videos
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/api/v1/videos",
     *   tags={"video"},
     *   operationId="videos",
     *   summary="get all videos",
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
     *    description=" number of page strat from zero ( 0 )",
     *  ),
     *  @OA\Parameter(
     *    name="limit",
     *    in= "query",
     *      @OA\Schema(
     *           type="number",
     *      ),
     *    description=" limit is number videos will send in each time default ( 12 )",
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
    public function videos(Request $request) {
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
        $all_videos = Video::get_ALLVideoData(null, 'id', 'DESC', $limit, $offset);
        $videos = Video::datavideos($all_videos, 1);
        $response['data'] = $videos;
        return response()->json($response, 200);
    }

    /**
     * Show single page of one videos   
     * get method
     * url : http://localhost:8000/api/v1/videos/single
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/api/v1/videos/single",
     *   tags={"video"},
     *   operationId="videosSingle",
     *   summary=" get single video by link",
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
     *    name="videos_link",
     *    in= "query",
     *    required=true,
     *      @OA\Schema(
     *           type="string"
     *      ),
     *    description="link of videos",
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
    public function videosSingle(Request $request) {
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
        $videos_link = isset($input['videos_link']) ? $input['videos_link'] : '';
        $response = [];
        $fields = [];
        if ($videos_link == "") {
            $fields['videos_link'] = 'videos_link';
        }
        if (!empty($fields)) {
            $response['StatusCode'] = 2;
            $response['Message'] = API_Controller::MISSING_FIELD;
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        $videos = Video::get_videoColum('link', $videos_link, 1);
        if (isset($videos->id)) {
            Video::updateVideoViewCount($videos->id);
            $data_videos = Video::datavideos_single($videos, 1);
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            $response['data'] = $data_videos;
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('NO_DATA_FOUND', $lang,15);
            return response()->json($response, 400);
        }
    }

}
