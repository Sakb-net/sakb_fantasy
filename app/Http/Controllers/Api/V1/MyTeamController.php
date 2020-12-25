<?php
namespace App\Http\Controllers\Api\V1;

//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\API_Controller;
use App\Models\User;
use App\Models\Game;
use App\Models\Player;
// use App\Models\Options;
use App\Http\Controllers\ClassSiteApi\Class_MyTeamController;

class MyTeamController extends API_Controller {

/**
     * get data player_myteam , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/player_myteam
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/player_myteam",
     *   tags={"myteam"},
     *   operationId="player_myteam",
     *   summary="get all players in my team",
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
    public function player_myteam(Request $request) { 
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
            $get_data=new Class_MyTeamController();
            $all_data=$get_data->GETMYTeam($user,15,$lang,1);
            $response=array_merge($response,$all_data);
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }

/**
     * get data player , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/add_captain_assist
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/add_captain_assist",
     *   tags={"myteam"},
     *   operationId="add_captain_assist",
     *   summary="add captain or assist captain",
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
     *    name="player_link",
     *    in= "query",
     *      @OA\Schema(
     *           type="string",
     *           default="player_link"
     *      ),
     *    required=true,
     *    description="link of player",
     *  ),
     * @OA\Parameter(
     *    name="type",
     *    in= "query",
     *      @OA\Schema(
     *           type="string",
     *           default="captain"
     *      ),
     *    required=true,
     *    description="default captain (captain - assist)",
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
    public function add_captain_assist(Request $request) { 
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
        $player_link = isset($input['player_link']) ? $input['player_link'] : '';
        $type = isset($input['type']) ? $input['type'] : 'captain';
        if ($access_token == "") {
           $fields['access_token'] = 'access-token';
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
            $response['data'] = $fields;
            return response()->json($response, 401);
        }
        if ($player_link == "") {
           $fields['player_link'] = 'player_link';
        }
        if (!in_array($type, ['captain','assist'])) {
           $fields['type'] = 'type';
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang,2);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        $user = User::user_access_token($access_token, 1);
        if (isset($user->id)) {
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            $get_data=new Class_MyTeamController();
            $data=$get_data->get_PlayerAddCatptain($user->id,$player_link,$type,$lang,1);
            $response['data'] = $data;
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }
/**
     * get data get_lineup , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/get_lineup
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/get_lineup",
     *   tags={"myteam"},
     *   operationId="get_lineup",
     *   summary="get all lineup",
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
    public function get_lineup(Request $request) { 
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
            $get_data=new Class_MyTeamController();
            $data=$get_data->get_datalineup($user->id,$lang,1);
            $response['data'] = $data;
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }

/**
     * get data add_lineup , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/add_lineup
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/add_lineup",
     *   tags={"myteam"},
     *   operationId="add_lineup",
     *   summary="add lineup",
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
     *    name="linup_link",
     *    in= "query",
     *      @OA\Schema(
     *           type="string",
     *           default="linup_link"
     *      ),
     *    required=true,
     *    description="link of linupe",
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
    public function add_lineup(Request $request) { 
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
         $linup_link = isset($input['linup_link']) ? $input['linup_link'] : '';
        if ($access_token == "") {
           $fields['access_token'] = 'access-token';
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
            $response['data'] = $fields;
            return response()->json($response, 401);
        }
        if ($linup_link == "") {
           $fields['linup_link'] = 'linup_link';
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang,2);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        $user = User::user_access_token($access_token, 1);
        if (isset($user->id)) {
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            $get_data=new Class_MyTeamController();
            $data=$get_data->get_add_linupMyteam($user->id,$linup_link,$lang,1);
            $response['data'] = $data;
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }

    /**
     * get data check_insideChange , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/check_insideChange
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/check_insideChange",
     *   tags={"myteam"},
     *   operationId="check_insideChange",
     *   summary="check Change 11 main player with 4 sub player",
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
     *    name="player_link",
     *    in= "query",
     *      @OA\Schema(
     *           type="string",
     *           default="player_link"
     *      ),
     *    required=true,
     *    description="link of player",
     *  ),
     * @OA\Parameter(
     *    name="ch_game_player_id_one",
     *    in= "query",
     *      @OA\Schema(
     *           type="number",
     *           default="0"
     *      ),
     *    description="this value return after run this api",
     *  ),
      * @OA\Parameter(
     *    name="ch_player_id_one",
     *    in= "query",
     *      @OA\Schema(
     *           type="number",
     *           default="0"
     *      ),
     *    description="this value return after run this api",
     *  ),
      * @OA\Parameter(
     *    name="ch_game_player_id_two",
     *    in= "query",
     *      @OA\Schema(
     *           type="number",
     *           default="0"
     *      ),
     *    description="this value return after run this api",
     *  ),
      * @OA\Parameter(
     *    name="ch_player_id_two",
     *    in= "query",
     *      @OA\Schema(
     *           type="number",
     *           default="0"
     *      ),
     *    description="this value return after run this api",
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
    public function check_insideChange(Request $request) { 
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
        $player_link = isset($input['player_link']) ? $input['player_link'] : '';

        $ch_game_player_id_one = isset($input['ch_game_player_id_one']) ? $input['ch_game_player_id_one'] : 0;
        $ch_player_id_one = isset($input['ch_player_id_one']) ? $input['ch_player_id_one'] : 0;
        $ch_game_player_id_two = isset($input['ch_game_player_id_two']) ? $input['ch_game_player_id_two'] : 0;
        $ch_player_id_two = isset($input['ch_player_id_two']) ? $input['ch_player_id_two'] : 0;

        if ($access_token == "") {
           $fields['access_token'] = 'access-token';
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
            $response['data'] = $fields;
            return response()->json($response, 401);
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
            $type_loc_player='';
            $msg_add = trans('app.add_fail');
            $ok_update=$all_hide=0;
            $get_data=new Class_MyTeamController();
            $array_data=$get_data->Inside_changePlayer($user->id,$player_link,$lang,1,$ch_game_player_id_one,$ch_game_player_id_two,$ch_player_id_one,$ch_player_id_two);

            $ok_update=$array_data['ok_add'];
            $msg_add =$array_data['msg_add'];
            $change=$array_data['change'];
            if($change==1 || $ok_update==1){ 
                if(!isset($array_data['all_hide'])){
                    $array_data['all_hide']=$all_hide;
                    $array_data['type_loc_player']=$type_loc_player;
                }           
                $data=$array_data;
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            $response['data'] = $data;
            return response()->json($response, 200);
            } else {
                $data=['msg_add'=>$msg_add,'ok_update'=>$ok_update,'change'=>$change];
                $response = API_Controller::MessageData('ERROR_MESSAGE', $lang,1);
                $response['data'] = $data;
                return response()->json($response, 400);
            }
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }
    /**
     * get data add_insideChange , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/add_insideChange
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/add_insideChange",
     *   tags={"myteam"},
     *   operationId="add_insideChange",
     *   summary="Change one main player with another sub player",
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
     *    name="ch_game_player_id_one",
     *    in= "query",
     *    required=true,
     *      @OA\Schema(
     *           type="number",
     *           default="0"
     *      ),
     *    description="this value return after run this api",
     *  ),
      * @OA\Parameter(
     *    name="ch_player_id_one",
     *    in= "query",
     *    required=true,
     *      @OA\Schema(
     *           type="number",
     *           default="0"
     *      ),
     *    description="this value return after run this api",
     *  ),
      * @OA\Parameter(
     *    name="ch_game_player_id_two",
     *    in= "query",
     *    required=true,
     *      @OA\Schema(
     *           type="number",
     *           default="0"
     *      ),
     *    description="this value return after run this api",
     *  ),
      * @OA\Parameter(
     *    name="ch_player_id_two",
     *    in= "query",
     *    required=true,
     *      @OA\Schema(
     *           type="number",
     *           default="0"
     *      ),
     *    description="this value return after run this api",
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
    public function add_insideChange(Request $request) { 
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
        $ch_game_player_id_one = isset($input['ch_game_player_id_one']) ? $input['ch_game_player_id_one'] : 0;
        $ch_player_id_one = isset($input['ch_player_id_one']) ? $input['ch_player_id_one'] : 0;
        $ch_game_player_id_two = isset($input['ch_game_player_id_two']) ? $input['ch_game_player_id_two'] : 0;
        $ch_player_id_two = isset($input['ch_player_id_two']) ? $input['ch_player_id_two'] : 0;

        if ($access_token == "") {
           $fields['access_token'] = 'access-token';
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
            $response['data'] = $fields;
            return response()->json($response, 401);
        }
        if ($ch_game_player_id_one <=0) {
           $fields['ch_game_player_id_one'] = 'ch_game_player_id_one';
        }
        if ($ch_player_id_one <=0) {
           $fields['ch_player_id_one'] = 'ch_player_id_one';
        }
        if ($ch_game_player_id_two <=0) {
           $fields['ch_game_player_id_two'] = 'ch_game_player_id_two';
        }
        if ($ch_player_id_two <=0) {
           $fields['ch_player_id_two'] = 'ch_player_id_two';
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang,2);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        $user = User::user_access_token($access_token, 1);
        if (isset($user->id)) {
            $type_loc_player='';
            $msg_add = trans('app.add_fail');
            $ok_update=$all_hide=0;
            $get_data=new Class_MyTeamController();
            $array_data=$get_data->ok_Inside_changePlayer($user->id,$lang,1,$ch_game_player_id_one,$ch_player_id_one,$ch_game_player_id_two,$ch_player_id_two);

            $ok_update=$array_data['ok_add'];
            $msg_add =$array_data['msg_add'];
            if($ok_update==1){ 
                $data=$array_data;
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            $response['data'] = $data;
            return response()->json($response, 200);
            } else {
                $data=['msg_add'=>$msg_add,'ok_update'=>$ok_update];
                $response = API_Controller::MessageData('ERROR_MESSAGE', $lang,1);
                $response['data'] = $data;
                return response()->json($response, 400);
            }
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }
    /**
     * get data add_direct_insideChange , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/add_direct_insideChange
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/add_direct_insideChange",
     *   tags={"myteam"},
     *   operationId="add_direct_insideChange",
     *   summary="Change one main player with another sub player",
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
     *    name="player_link_one",
     *    in= "query",
     *      @OA\Schema(
     *           type="string",
     *           default="player_link"
     *      ),
     *    required=true,
     *    description="link of first player",
     *  ),
     * @OA\Parameter(
     *    name="player_link_two",
     *    in= "query",
     *      @OA\Schema(
     *           type="string",
     *           default="player_link"
     *      ),
     *    required=true,
     *    description="link of second player",
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
    public function add_direct_insideChange(Request $request) { 
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
        $player_link_one = isset($input['player_link_one']) ? $input['player_link_one'] : '';
        $player_link_two = isset($input['player_link_two']) ? $input['player_link_two'] : '';

        if ($access_token == "") {
           $fields['access_token'] = 'access-token';
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
            $response['data'] = $fields;
            return response()->json($response, 401);
        }
        if ($player_link_one =='') {
           $fields['player_link_one'] = 'player_link_one';
        }
        if ($player_link_two =='') {
           $fields['player_link_two'] = 'player_link_two';
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang,2);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        $user = User::user_access_token($access_token, 1);
        if (isset($user->id)) {
        $ch_player_data_one = Player::get_PlayerCloum('link', $player_link_one, 1);
            if(isset($ch_player_data_one->id)){
                $ch_player_data_two = Player::get_PlayerCloum('link', $player_link_two, 1);
                if(isset($ch_player_data_two->id)){
                    $type_loc_player='';
                    $msg_add = trans('app.add_fail');
                    $ok_update=$all_hide=0;
                    $get_data=new Class_MyTeamController();
                    $array_data=$get_data->add_direct_insideChange($user->id,$lang,1,$ch_player_data_one,$ch_player_data_two);

                    $ok_update=$array_data['ok_add'];
                    $msg_add =$array_data['msg_add'];
                    if($ok_update==1){
                    $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
                        //get my team
                        $get_data=new Class_MyTeamController();
                        $all_data=$get_data->GETMYTeam($user,15,$lang,1);
                        $response=array_merge($response,$all_data);
                         $response=array_merge($response,$array_data);
                    return response()->json($response, 200);
                    } else {
                        $data=['msg_add'=>$msg_add,'ok_update'=>$ok_update];
                        $response = API_Controller::MessageData('ERROR_MESSAGE', $lang,1);
                        $response['data'] = $data;
                        return response()->json($response, 400);
                    }
                } else {
                    $response = API_Controller::MessageData('ERROR_MESSAGE', $lang,1);
                    return response()->json($response, 400);
                }
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