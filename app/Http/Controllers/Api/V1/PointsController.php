<?php
namespace App\Http\Controllers\Api\V1;

//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\API_Controller;
use App\Models\User;
use App\Models\Game;
use App\Models\Player;
use App\Models\Subeldwry;
use App\Models\PointUser;

use App\Http\Controllers\ClassSiteApi\Class_PointController;

class PointsController extends API_Controller {

    /**
     * get data home_points_eldwry , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/home_points_eldwry
     *
     * @return response Json
     */

    /**
     * @OA\Get(
     *  path="/api/v1/home_points_eldwry",
     *   tags={"points"},
     *   operationId="home_points_eldwry",
     *   summary="get all points of user in current subeldwry",
     *   @OA\Parameter(
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
    public function home_points_eldwry(Request $request) { 
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
        $user = User::user_access_token($access_token, 1);
        if (isset($user->id)) {
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            $start_dwry = Subeldwry::get_startFirstSubDwry();
            $response['data']=[];
            $response['home_points']=[];
            if (isset($start_dwry->id)) {
                $response['data'] = Subeldwry::single_DataSubeldwryUser($start_dwry,1,$lang);
                $response['home_points']=PointUser::Home_Finaltotal($user->id,'sub_eldwry_id',$start_dwry->id);
            }
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }

/**
     * get data public_points_eldwry , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/public_points_eldwry
     *
     * @return response Json
     */

    /**
     * @OA\Get(
     *  path="/api/v1/public_points_eldwry",
     *   tags={"points"},
     *   operationId="public_points_eldwry",
     *   summary="get all points of user in eldwry",
     *   @OA\Parameter(
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
    public function public_points_eldwry(Request $request) { 
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
        $user = User::user_access_token($access_token, 1);
        if (isset($user->id)) {
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            $start_dwry = Subeldwry::get_startFirstSubDwry();
			// print_r($start_dwry);die;
			$response['data']=[];
           if (isset($start_dwry->id)) {
             $game = Game::checkregisterDwry($user->id, $start_dwry->eldwry_id, 1);
               if (isset($game->id)) {
    	            $response['data']=PointUser::CalMath_Finaltotal($user->id,$start_dwry->eldwry_id,$start_dwry->id,$game->id);
                }
	        }
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }

    /**
     * get data points_eldwry , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/points_eldwry
     *
     * @return response Json
     */

    /**
     * @OA\Get(
     *  path="/api/v1/points_eldwry",
     *   tags={"points"},
     *   operationId="points_eldwry",
     *   summary="get all points of user in eldwry",
     *   @OA\Parameter(
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
    public function points_eldwry(Request $request) { 
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
        $user = User::user_access_token($access_token, 1);
        if (isset($user->id)) {
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
			$start_dwry = Subeldwry::get_startFirstSubDwry();
			$response['data']=[]; //subeldwry_points
           if (isset($start_dwry->id)) {
	            $response['data']=Subeldwry::get_DataSubeldwry_CurrentPoint('eldwry_id',$start_dwry->eldwry_id,1,'DESC',0,-1,0,0,$user->id);
	        }
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }

/**
     * get data points_subeldwry , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/points_subeldwry
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/api/v1/points_subeldwry",
     *   tags={"points"},
     *   operationId="points_subeldwry",
     *   summary="get all points of user in subeldwry",
     *   @OA\Parameter(
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
     *    name="subeldwry_link",
     *    in= "query",
     *      @OA\Schema(
     *           type="string",
     *           default="subeldwry_link"
     *      ),
     *    required=true,
     *    description="link of subeldwry",
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
    public function points_subeldwry(Request $request) { 
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
        $subeldwry_link = isset($input['subeldwry_link']) ? $input['subeldwry_link'] : '';
        if ($access_token == "") {
           $fields['access_token'] = 'access-token';
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
            $response['data'] = $fields;
            return response()->json($response, 401);
        }

        if ($subeldwry_link == "") {
           $fields['subeldwry_link'] = 'subeldwry_link';
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang,2);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        $user = User::user_access_token($access_token, 1);
        if (isset($user->id)) {
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            $get_data=new Class_PointController();
            $all_data=$get_data->GETHistory_MYTeam($user,'link',$subeldwry_link,15,$lang,1);
            $response=array_merge($response,$all_data);
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }

    /**
     * get data pointplayersubeldwry , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/pointplayersubeldwry
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/api/v1/pointplayersubeldwry",
     *   tags={"points"},
     *   operationId="pointplayersubeldwry",
     *   summary="get details all points of user in sub eldwry",
     *   @OA\Parameter(
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
     *    name="subeldwry_link",
     *    in= "query",
     *      @OA\Schema(
     *           type="string",
     *           default="subeldwry_link"
     *      ),
     *    required=true,
     *    description="link of subeldwry",
     *  ),
     * @OA\Parameter(
     *    name="player_link",
     *    in= "query",
     *      @OA\Schema(
     *           type="string",
     *           default="player_link"
     *      ),
     *    required=true,
     *    description="link of player",
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
    public function pointplayersubeldwry(Request $request) { 
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
        $subeldwry_link = isset($input['subeldwry_link']) ? $input['subeldwry_link'] : '';
        $player_link = isset($input['player_link']) ? $input['player_link'] : '';

        if ($access_token == "") {
           $fields['access_token'] = 'access-token';
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
            $response['data'] = $fields;
            return response()->json($response, 401);
        }
        if ($subeldwry_link == "") {
           $fields['subeldwry_link'] = 'subeldwry_link';
        }
        if ($player_link == "") {
           $fields['player_link'] = 'player_link';
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang,2);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        $user = User::user_access_token($access_token, 1);
        if (isset($user->id)) {
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            $get_data=new Class_PointController();
            $response['data']=$get_data->statisticPlayer_inSubeldwry($user,$subeldwry_link,$player_link,$lang,1);
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }

}