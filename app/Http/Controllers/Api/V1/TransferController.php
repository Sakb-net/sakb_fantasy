<?php
namespace App\Http\Controllers\Api\V1;

//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\API_Controller;
use App\Models\User;
use App\Models\Game;
use App\Models\Player;
// use App\Models\Options;
use App\Http\Controllers\ClassSiteApi\Class_TransferController;

class TransferController extends API_Controller {


    /**
     * @OA\Get(
     *   path="/api/v1/count_team_players",
     *   tags={"substitute"},
     *   operationId="count_team_players",
     *   summary="get count user players in each team with team",
     * @OA\Parameter(
     *    name="access-token",
     *    in= "header",
     *      @OA\Schema(
     *           type="string",
     *           default="sakb" 
     *      ),
     *     required=true,
     *    description="get from login",
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
     *   @OA\Response(
     *    response=200,
     *    description="success",
     *   ),
     *  @OA\Response(
     *    response=400,
     *    description="error",
     *   ),
     * )
     *
     */
    public function count_team_players(Request $request) {
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
        if ($access_token == "") {
           $fields['access_token'] = 'access-token';
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
            $response['data'] = $fields;
            return response()->json($response, 401);
        }
        $user = User::user_access_token($access_token, 1);
        if (isset($user->id)) {
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            $get_data=new Class_TransferController();
            $response['data']=$get_data->count_team_players($user->id,1);
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }
    /**
     * @OA\Get(
     *   path="/api/v1/status_card/{type}",
     *   tags={"substitute"},
     *   operationId="status_card",
     *   summary="status_card",
     * @OA\Parameter(
     *    name="access-token",
     *    in= "header",
     *      @OA\Schema(
     *           type="string",
     *           default="sakb"
     *      ),
     *     required=true,
     *    description="get from login",
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
     *    name="type",
     *    in= "path",
     *    required=true,
     *      @OA\Schema(
     *           type="string",
     *           default="gray"
     *      ),
     *    description= "default type is gray  ( gray , gold)",
     *  ),
     *   @OA\Response(
     *    response=200,
     *    description="success",
     *   ),
     *  @OA\Response(
     *    response=400,
     *    description="error",
     *   ),
     * )
     *
     */
    public function status_card(Request $request,$type) {
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
        $fields=[];
        if ($access_token == "") {
           $fields['access_token'] = 'access-token';
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
            $response['data'] = $fields;
            return response()->json($response, 401);
        }
        if (!in_array($type,['gray','gold'])) {
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
            $get_data=new Class_TransferController();
            $data=$get_data->status_card($user->id,$type,1);
            $response['data']=$data;
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }
 /**
     * get data confirm_substitutePlayer , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/confirm_substitutePlayer
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/api/v1/confirm_substitutePlayer",
     *   tags={"substitute"},
     *   operationId="confirm_substitutePlayer",
     *   summary="confirm substitute all Players",
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
     *   @OA\Parameter(
     *    name="access-token",
     *    in= "header",
     *    required=true,
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="array_players",
     *    in= "query",
     *    required=true,
     *      @OA\Schema(
     *           type="string"
     *      ),
     *     description="[{newplayer_id:272,substituteplayer_id:562,substituteplayer_cost:5},{newplayer_id:345,substituteplayer_id:272,substituteplayer_cost:5}]"
     *  ),
     * @OA\Parameter(
     *    name="active_cardgray",
     *    in= "query",
     *      @OA\Schema(
     *           type="number",
     *           default="0"
     *      ),
     *    description="status card gray Active=1 or notActive=0 ",
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

    //Ex: array_players=[{"newplayer_id":272,"substituteplayer_id":562,"substituteplayer_cost":5},{"newplayer_id":345,"substituteplayer_id":272,"substituteplayer_cost":5}]
    public function confirm_substitutePlayer(Request $request) { 
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
        $array_players = isset($input['array_players']) ? $input['array_players'] : [];
        $array_players=json_decode($array_players,true);
        $active_cardgray = isset($input['active_cardgray']) ? $input['active_cardgray'] : 0;
        $active_cardgold = 0;
        $response = [];
        $fields = [];
        if ($access_token == "") {
           $fields['access_token'] = 'access-token';
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
            $response['data'] = $fields;
            return response()->json($response, 401);
        }
        if (empty($array_players)) {
            $fields['array_players'] = 'array_players';
        } else {
            if (!is_array($array_players)) {
                $fields['array_players'] = 'Not Array array_players';
            }
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang,2);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        $user = User::user_access_token($access_token, 1);
        if (isset($user->id)) {
        	////////
        	if($active_cardgold > 0){
        		$response = API_Controller::MessageData('not_found_pay_method', $lang,49);
	            return response()->json($response, 400);
        	}
        	////////
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            $get_data=new Class_TransferController();
            $ok_substitutes_points=$get_data->game_substitutePlayer_api($user->id,$array_players,0,1);
            $data['substitute']='';
            if($ok_substitutes_points==1){
                $data['substitute']= $get_data->confirm_substitutePlayer($user->id,0,1,$active_cardgray,$active_cardgold);
            }
            $data['msg_add']=trans('app.add_scuss');
            $response['data'] = $data;
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }

}