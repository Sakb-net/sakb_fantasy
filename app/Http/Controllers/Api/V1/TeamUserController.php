<?php
namespace App\Http\Controllers\Api\V1;

//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\API_Controller;
use App\Models\User;
use App\Models\TeamUser;
use App\Models\Match;
// use App\Models\Options;
use App\Http\Controllers\ClassSiteApi\Class_MyTeamController;

class TeamUserController extends API_Controller {




    /**
     * get data player , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/addFollowingTeam
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/addFollowingTeam",
     *   tags={"followingTeams"},
     *   operationId="addFollowingTeam",
     *   summary="add following Team",
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
     *    name="teamFollow",
     *    in= "query",
     *      @OA\Schema(
     *           type="string",
     *           default="teamFollow"
     *      ),
     *    required=true,
     *    description="following teams",
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
    public function addFollowingTeam(Request $request) { 
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
        $teamFollow = isset($input['teamFollow']) ? $input['teamFollow'] : '';
        if ($access_token == "") {
           $fields['access_token'] = 'access-token';
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
            $response['data'] = $fields;
            return response()->json($response, 401);
        }
        if ($teamFollow == "") {
           $fields['teamFollow'] = 'teamFollow';
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang,2);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }

        $user = User::user_access_token($access_token, 1);
        if (isset($user->id)) {

            $teams = (json_decode($input['teamFollow']));

            $follows = TeamUser::addFollowTeams($user->id,$teams);
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0); 
            $response['data'] = true; 
            return response()->json($response, 200); 

        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }

    }




    /**
     * get data followingMatches , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/followingMatches
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/followingMatches",
     *   tags={"followingTeams"},
     *   operationId="followingMatches",
     *   summary="get followning teams matches",
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
     *    description=" limit is number following matches will send in each time default ( 12 )",
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



    public function followingMatches(Request $request) {

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
            $allMatches = Match::getFollowingMatches($followingTeams, $lang, $limit, $offset);
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            $response['data'] = $allMatches;
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
    
}
