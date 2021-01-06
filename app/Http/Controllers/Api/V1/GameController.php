<?php
namespace App\Http\Controllers\Api\V1;

//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\API_Controller;
use App\Models\User;
use App\Models\Game;
use App\Models\Eldwry;
// use App\Models\Options;
use App\Http\Controllers\ClassSiteApi\Class_GameController;

class GameController extends API_Controller {

 /**
     * get data eldwry_locations , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/eldwry_locations
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/api/v1/eldwry_locations",
     *   tags={"game"},
     *   operationId="eldwry_locations",
     *   summary="get current eldwry and locations",
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
    public function eldwry_locations(Request $request) { 
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
            $get_data=new Class_GameController();
            $data=$get_data->get_GameData($user->id,$lang,1);
            $response['data'] = $data;
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }

/**
     * get data fixtures , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/players_by_type
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/api/v1/players_by_type",
     *   tags={"game"},
     *   operationId="players_by_type",
     *   summary="get all players by type",
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
     *    name="type_key",
     *    in= "query",
     *      @OA\Schema(
     *           type="string",
     *          default="goalkeeper"
     *      ),
     *    required=true,
     *    description="get value from api eldwry_locations and default is goalkeeper (goalkeeper,defender,line,attacker)",
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
    public function players_by_type(Request $request) { 
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
        $type_key = isset($input['type_key']) ? $input['type_key'] : 'goalkeeper';
        if ($access_token == "") {
           $fields['access_token'] = 'access-token';
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
            $response['data'] = $fields;
            return response()->json($response, 401);
        }
        if ($type_key == "") {
           $fields['type_key'] = 'type_key';
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang,2);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        $user = User::user_access_token($access_token, 1);
        if (isset($user->id)) {
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            $get_data=new Class_GameController();
            $data=$get_data->get_data_player_public($type_key,$lang,0,1,$user->id);
            $response['data'] = $data;
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }

   
   /**
     * get data fixtures , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/filterPlayer
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/api/v1/filterPlayer",
     *   tags={"game"},
     *   operationId="filterPlayer",
     *   summary="get all Players after filter",
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
     *    name="link_team",
     *    in= "query",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *    description="",
     *  ), 
     * @OA\Parameter(
     *    name="type_key",
     *    in= "query",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *    description="get value from api eldwry_locations and default is goalkeeper (goalkeeper,defender,line,attacker)",     *  ), 
     * @OA\Parameter(
     *    name="word_search",
     *    in= "query",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *    description="",
     *  ),
     * @OA\Parameter(
     *    name="order_play",
     *    in= "query",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *    description="heigh_price or low_price or goon or point",
     *  ),
     * @OA\Parameter(
     *    name="from_price",
     *    in= "query",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *    description="",
     *  ),
     * @OA\Parameter(
     *    name="to_price",
     *    in= "query",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *    description="",
     *  ),
     * @OA\Parameter(
     *    name="loc_player",
     *    in= "query",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *    description="",
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
    public function filterPlayer(Request $request) { 
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
        $link_team = isset($input['link_team']) ? $input['link_team'] : '';
        $type_key = isset($input['type_key']) ? $input['type_key'] : 'goalkeeper';
        $order_play = isset($input['order_play']) ? $input['order_play'] : '';
        $word_search = isset($input['word_search']) ? $input['word_search'] : '';
        $from_price = isset($input['from_price']) ? $input['from_price'] : '';
        $to_price = isset($input['to_price']) ? $input['to_price'] : '';
        $loc_player = isset($input['loc_player']) ? $input['loc_player'] : '';
        if ($access_token == "") {
           $fields['access_token'] = 'access-token';
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
            $response['data'] = $fields;
            return response()->json($response, 401);
        }
        if ($type_key == ""&&$link_team==""&&$order_play==""&&$word_search==""&&$from_price==""&&$to_price==""&&$loc_player=="") {
           $fields['type_key'] = 'type_key';
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang,2);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        $user = User::user_access_token($access_token, 1);
        if (isset($user->id)) {
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            $get_data=new Class_GameController();
            $data=$get_data->get_dataFilterPlayer($link_team,$type_key,$order_play , $word_search, $from_price,$to_price,$loc_player,$lang,0,1,$user->id);
            $response['data'] = $data;
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }
/**
     * get data player_master , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/player_master
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/api/v1/player_master",
     *   tags={"game"},
     *   operationId="player_master",
     *   summary="get all players of your game",
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
    public function player_master(Request $request) { 
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
            $get_data=new Class_GameController();
            $all_data=$get_data->GetDataCreatMaster($user->id,15,$lang,0,1);
            $response =array_merge($response,$all_data);
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }

/**
     * get data auto_selection_player , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/auto_selection_player
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/api/v1/auto_selection_player",
     *   tags={"game"},
     *   operationId="auto_selection_player",
     *   summary="add auto selection player",
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
    public function auto_selection_player(Request $request) { 
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
            $start_dwry = Eldwry::get_currentDwry();
            if (isset($start_dwry->id)) {
                $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
                $get_data=new Class_GameController();
                $data_return = $get_data->auto_selection_player($start_dwry, $user->id,15,$lang,1);
                //get Players of master
                $all_data=$get_data->GetDataCreatMaster($user->id,15,$lang,0,1);
                $response =array_merge($response,$all_data);
                return response()->json($response, 200);
            } else {
                $response = API_Controller::MessageData('ERROR_MESSAGE', $lang,1);
                return response()->json($response, 400);
            }
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }

/**
     * get data reset_all_player , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/reset_all_player
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/api/v1/reset_all_player",
     *   tags={"game"},
     *   operationId="reset_all_player",
     *   summary="delete all player",
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
    public function reset_all_player(Request $request) { 
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
            $start_dwry = Eldwry::get_currentDwry();
            if (isset($start_dwry->id)) {
                $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
                $get_data=new Class_GameController();
                $data_return = $get_data->reset_all_player($start_dwry, $user->id,15,$lang,1);
                
                //get Players of master
                $all_data=$get_data->GetDataCreatMaster($user->id,15,$lang,0,1);
                $response =array_merge($response,$all_data);
                return response()->json($response, 200);
            } else {
                $response = API_Controller::MessageData('ERROR_MESSAGE', $lang,1);
                return response()->json($response, 400);
            }
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }
}
