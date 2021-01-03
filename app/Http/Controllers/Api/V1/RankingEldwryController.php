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
     * get data league_ranking , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/league_ranking
     *
     * @return response Json
     */

    /**
     * @OA\get(
     *  path="/league_ranking",
     *   tags={"league_ranking"},
     *   operationId="league_ranking",
     *   summary="get league_ranking",
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
     *    description=" limit is number league_ranking will send in each time default ( 12 )",
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
    public function league_ranking(Request $request) {

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
        $response['data']= $this->RankingEldwryRepository->get_RankingEldwry(0,$limit,$offset);
        return response()->json($response, 200);
    }


}
