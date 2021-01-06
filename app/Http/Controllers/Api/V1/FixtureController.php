<?php
namespace App\Http\Controllers\Api\V1;

//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\API_Controller;
use App\Models\User;
use App\Models\Subeldwry;
use App\Models\Match;
// use App\Models\Options;
use App\Http\Controllers\ClassSiteApi\Class_GameController;
use App\Http\Resources\FixtureResource;
use App\Repositories\PlayerDetailsMatchRepository;

class FixtureController extends API_Controller {
    /**
     * get data fixtures , if found sent access_token
     * get method
     * url : http://localhost:8000/api/v1/subeldwry/link/fixtures
     *
     * @return response Json
     */

    /**
     * @OA\get(
     *  path="/api/v1/subeldwry/{link}/fixtures",
     *   tags={"subeldwry_fixtures"},
     *   operationId="/{link}/fixtures",
     *   summary="get fixtures by link subeldwry",
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
     *    name="link",
     *    in= "path",
     *    required=true,
     *      @OA\Schema(
     *           type="string"
     *      ),
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
    public function subeldwry_fixtures(Request $request,$link) {
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
        $subeldwry=Subeldwry::foundDataTwoCondition('link', $link,'is_active',1);
        if($subeldwry->id){
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            return FixtureResource::collection($subeldwry->matches)
                ->additional($response);
        }else{
            $response = API_Controller::MessageData('NO_DATA_FOUND', $lang,15);
            return response()->json($response, 200);
        }  
    }

 /**
     * get data fixtures , if found sent access_token
     * get method
     * url : http://localhost:8000/api/v1/fixtures
     *
     * @return response Json
     */

    /**
     * @OA\get(
     *  path="/api/v1/fixtures",
     *   tags={"subeldwry_fixtures"},
     *   operationId="fixtures",
     *   summary="get all fixtures",
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
     *    in= "path",
     *      @OA\Schema(
     *           type="number",
     *      ),
     *    description=" number of page strat from zero ( 0 )",
     *  ),
     *  @OA\Parameter(
     *    name="limit",
     *    in= "path",
     *      @OA\Schema(
     *           type="number",
     *      ),
     *    description=" limit is number fixtures will send in each time default (1)",
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
    public function fixtures(Request $request) {
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
        $limit = isset($input['limit']) ? $input['limit'] : 1;
        $offset = $num_page * $limit;
        $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
        $get_data=new Class_GameController();
        $response['data'] =$get_data->getFixtures($limit,$offset,$lang,1);
        return response()->json($response, 200);
    }
  /**
     * get data details fixtures , if found sent access_token
     * get method
     * url : http://localhost:8000/api/v1/fixtures/link
     *
     * @return response Json
     */

    /**
     * @OA\get(
     *  path="/api/v1/fixtures/{link}",
     *   tags={"subeldwry_fixtures"},
     *   operationId="/fixtures/{link}",
     *   summary="get details match/fixture by link fixture",
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
     *    name="link",
     *    in= "path",
     *    required=true,
     *      @OA\Schema(
     *           type="string"
     *      ),
     *    description="link of fixture",
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
    public function single_fixtures(Request $request,$link) {
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
        $match=Match::foundDataTwoCondition('link', $link,'is_active',1);
        if($match->id){
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            $response['data']=new FixtureResource($match);
            // Match::single_MatchDataUser($match, $lang,1);
            // $getplayer=new PlayerDetailsMatchRepository();
            // $response['details_match']=$getplayer->DetailsPlayerMatch($match);
            return response()->json($response, 200);
        }else{
            $response = API_Controller::MessageData('NO_DATA_FOUND', $lang,15);
            return response()->json($response, 200);
        }  
    }
  

}
