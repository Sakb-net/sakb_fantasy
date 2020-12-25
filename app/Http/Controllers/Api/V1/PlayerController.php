<?php

namespace App\Http\Controllers\Api\V1;

//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\API_Controller;
use App\Models\User;
use App\Models\Game;
// use App\Models\Page;
// use App\Models\Options;
use App\Http\Controllers\ClassSiteApi\Class_GameController;

class PlayerController extends API_Controller {

/**
     * get data player , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/player
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/player",
     *   tags={"game"},
     *   operationId="player",
     *   summary="get data of player",
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
    public function player(Request $request) { 
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
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            $get_data=new Class_GameController();
            $data=$get_data->get_dataOnePlayer($player_link,$lang,1);
            $response['data'] = $data;
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }

/**
     * get data add_player , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/add_player
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/add_player",
     *   tags={"game"},
     *   operationId="add_player",
     *   summary="add player to game",
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
     *   @OA\Response(
     *    response=200,
     *    description="success Note:add_player= 1 ok add ,0 not add,-1 found ,-2 cost not enough,-3 complete number 15 players,-6 finish num from this team,-7 finish num from this location",
     *   ),
     *   @OA\Response(
     *    response=400,
     *    description="error",
     *  )
     * )
     */
    public function add_player(Request $request) { 
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
            $get_data=new Class_GameController();
            $data=$get_data->game_addPlayer($user->id,$player_link,$lang,0,1);
            if($data['add_player']!=0){
                //get players data
                $all_players=$get_data->GetDataPlayer_Master($user->id,15,$lang,0,1);
                $players=Game::MyTeam_MasterTransfer($user->id,$all_players['player_master'],[5,5,3],2,0,0);
                $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);  
                $response['image_best_team'] = '';
                $response['lineup'] = [];
                $response['players'] = $players;
                $response['data'] = $data;
                return response()->json($response, 200); 
            }else{
                $array_data['add_player']=$data['add_player'];
                $array_data['msg_add']=$data['msg_add'];
                $array_data['val_player']=$data['val_player'];
                $array_data['player_data']=$data['player_data'];
                $response = API_Controller::MessageData('ERROR_MESSAGE', $lang,1);
                $response['data'] = $array_data;
                return response()->json($response, 400);
            }
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }

/**
     * get data player , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/delete_player
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/delete_player",
     *   tags={"game"},
     *   operationId="delete_player",
     *   summary="delete player from game and also change player with another",
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
     *    name="eldwry_link",
     *    in= "query",
     *      @OA\Schema(
     *           type="string",
     *           default="eldwry_link"
     *      ),
     *    required=true,
     *    description="link of eldwry",
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
    public function delete_player(Request $request) { 
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
        $eldwry_link = isset($input['eldwry_link']) ? $input['eldwry_link'] : '';
        if ($access_token == "") {
           $fields['access_token'] = 'access-token';
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
            $response['data'] = $fields;
            return response()->json($response, 401);
        }
        if ($player_link == "") {
           $fields['player_link'] = 'player_link';
        } 
        if ($eldwry_link == "") {
           $fields['eldwry_link'] = 'eldwry_link';
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
            $data=$get_data->game_deletePlayer($user->id,$eldwry_link,$player_link,$lang,1);
            $response['data'] = $data;
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }

    /**
     * get data player , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/change_player
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/change_player",
     *   tags={"game"},
     *   operationId="change_player",
     *   summary="delete player from game and also change player with another",
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
     *    name="eldwry_link",
     *    in= "query",
     *      @OA\Schema(
     *           type="string",
     *            default="eldwry_link"
     *      ),
     *    required=true,
     *    description="link of eldwry",
     *  ),
     * @OA\Parameter(
     *    name="delet_player_link",
     *    in= "query",
     *      @OA\Schema(
     *           type="string",
     *           default="player_link"
     *      ),
     *    required=true,
     *    description="link of player delete",
     *  ),
     * @OA\Parameter(
     *    name="add_player_link",
     *    in= "query",
     *      @OA\Schema(
     *           type="string",
     *           default="player_link"
     *      ),
     *    required=true,
     *    description="link of player ",
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
    public function change_player(Request $request) { 
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
        $delet_player_link = isset($input['delet_player_link']) ? $input['delet_player_link'] : '';
        $add_player_link = isset($input['add_player_link']) ? $input['add_player_link'] : '';
        $eldwry_link = isset($input['eldwry_link']) ? $input['eldwry_link'] : '';
        if ($access_token == "") {
           $fields['access_token'] = 'access-token';
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
            $response['data'] = $fields;
            return response()->json($response, 401);
        }
        if ($delet_player_link == "") {
           $fields['delet_player_link'] = 'delet_player_link';
        } 
        if ($add_player_link == "") {
           $fields['add_player_link'] = 'add_player_link';
        } 
        if ($eldwry_link == "") {
           $fields['eldwry_link'] = 'eldwry_link';
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
            $result_change=$get_data->game_changePlayerApp($user->id,$eldwry_link,$delet_player_link,$add_player_link,$lang,1);
            //get data of master
            $all_data=$get_data->GetDataCreatMaster($user->id,15,$lang,0,1);
            $response =array_merge($response,$all_data);
            $response['result_change'] = $result_change;
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }


/**
     * get data player , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/checknum_myteam
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/checknum_myteam",
     *   tags={"game"},
     *   operationId="checknum_myteam",
     *   summary="check players number of your team",
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
    public function checknum_myteam(Request $request) { 
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
        $fields=$response=[];
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
            $data=$get_data->checknum_playerteam($user->id,$lang,1);
            $response['data'] = $data;
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }


/**
     * get data player , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/add_myteam
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/add_myteam",
     *   tags={"game"},
     *   operationId="add_myteam",
     *   summary="add name to your team",
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
     *    name="name_team",
     *    in= "query",
     *      @OA\Schema(
     *           type="string",
     *           default="nameTeam"
     *      ),
     *    required=true,
     *    description="name your team",
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
    public function add_myteam(Request $request) { 
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
        $name_team = isset($input['name_team']) ? $input['name_team'] : '';
        if ($access_token == "") {
           $fields['access_token'] = 'access-token';
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
            $response['data'] = $fields;
            return response()->json($response, 401);
        }
        if ($name_team == "") {
           $fields['name_team'] = 'name_team';
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
            $data=$get_data->submit_game_team($user->id,$name_team,$lang,1);
            $response['data'] = $data;
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }

}
