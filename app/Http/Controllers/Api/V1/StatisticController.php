<?php
namespace App\Http\Controllers\Api\V1;

//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\API_Controller;
use App\Models\User;
use App\Models\Game;
use App\Models\Player;
// use App\Models\Options;
use App\Http\Controllers\ClassSiteApi\Class_StatisticController;

class StatisticController extends API_Controller {

 /**
     * get data statistics , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/statistics
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/api/v1/statistics",
     *   tags={"statistics"},
     *   operationId="statistics",
     *   summary="get all details of statistics players in mathes",
     *   @OA\Parameter(
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
     *    name="link_team",
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
     *    description="heigh_price,low_price,heighest_point,lowest_point",
     *  ),
     * @OA\Parameter(
     *    name="loc_player",
     *    in= "query",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *    description="goalkeeper,defender,line,attacker",
     *  ),
     * @OA\Parameter(
     *    name="limit",
     *    in= "query",
     *      @OA\Schema(
     *           type="number",
     *           default="20",
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="offset",
     *    in= "query",
     *      @OA\Schema(
     *           type="number",
     *           default="0",
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
    public function statistics(Request $request) { 
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
        $order_play = isset($input['order_play']) ? $input['order_play'] : '';
        $loc_player = isset($input['loc_player']) ? $input['loc_player'] : '';
        $limit = isset($input['limit']) ? $input['limit'] :20;
        $offset = isset($input['offset']) ? $input['offset'] :0;
        $response = [];
        $fields = [];        
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang,2);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        // $user_id=0;
        // if (!empty($access_token)) {
	       //  $user = User::user_access_token($access_token, 1);
	       //  if (isset($user->id)) {
	       //  	$user_id=$user->id;
	       //  }
        // }	
        $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
		$get_data=new Class_StatisticController();
        $array_all_data=$get_data->get_data_player_public_statistics('1', $lang, $order_play, $link_team, $loc_player, $offset,1,1,$limit);
        $response['data'] = $array_all_data;
        return response()->json($response, 200);

    }

}