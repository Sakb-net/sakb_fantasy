<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\API_Controller;
use App\Repositories\RankingEldwryRepository;  
use App\Models\User;

class RankingEldwryController extends API_Controller {

    
    public function __construct() {
        $this->RankingEldwryRepository =new RankingEldwryRepository();
    }

    /**
     * get data ranking_eldwry , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/ranking_eldwry
     *
     * @return response Json
     */

    /**
     * @OA\get(
     *  path="/api/v1/ranking_eldwry",
     *   tags={"ranking_eldwry"},
     *   operationId="ranking_eldwry",
     *   summary="get ranking_eldwry",
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
     *    name="subeldwry_link",
     *    in= "path",
     *      @OA\Schema(
     *           type="string",
     *      ),
     *    description="link of subeldwry if it is empty will return current ",
     *  ),
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
     *    description=" limit is number ranking_eldwry will send in each time default ( 18 )",
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
    public function ranking_eldwry(Request $request,$subeldwry_link='') {

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
        $num_page = isset($request->num_page) ? $request->num_page : 0;
        $limit = isset($request->limit) ? $request->limit : 18;
        $offset = $num_page * $limit;
        $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
        $response['data']= $this->RankingEldwryRepository->get_RankingEldwry('link',$subeldwry_link,$limit,$offset);
        return response()->json($response, 200);
    }

    /**
     * get data subeldwry , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/ranking_eldwry/subeldwry
     *
     * @return response Json
     */

    /**
     * @OA\get(
     *  path="/api/v1/ranking_eldwry/subeldwry",
     *   tags={"ranking_eldwry"},
     *   operationId="subeldwry_ranking_eldwry",
     *   summary="get all subeldwry of ranking_eldwry",
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
    public function subeldwry_ranking_eldwry(Request $request) {
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
        $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
        $response['data']=$this->RankingEldwryRepository->get_subeldwry_ranking_eldwry();
        return response()->json($response, 200);
    }


}
