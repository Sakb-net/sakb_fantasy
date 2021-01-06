<?php
namespace App\Http\Controllers\Api\V1;

//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\API_Controller;
use App\Models\User;
use App\Models\Eldwry;
use App\Models\Subeldwry;
use App\Http\Resources\SubeldwryResource;

class SubeldwryController extends API_Controller {

 /**
     * get data subeldwry , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/subeldwry
     *
     * @return response Json
     */

    /**
     * @OA\get(
     *  path="/api/v1/subeldwry",
     *   tags={"subeldwry_fixtures"},
     *   operationId="subeldwry",
     *   summary="get all subeldwry",
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
    public function subeldwry(Request $request) {
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
        // $input = $request->all();
        $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
        $eldwry =Eldwry::get_currentDwry();
        $subeldwry=[];
        $response['active_subeldwry_link']='';
        if(isset($eldwry->id)){
        	$subeldwry =Subeldwry::All_foundDataTwoCondition('eldwry_id',$eldwry->id,'is_active',1);
        	$active_subeldwry =Subeldwry::get_CurrentSubDwry();
        	if(isset($active_subeldwry->id)){
        		$response['active_subeldwry_link']=$active_subeldwry->link;
        	}
    	}
        return SubeldwryResource::collection($subeldwry)
            ->additional($response);

    }

 /**
     * get data active_subeldwry , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/active_subeldwry
     *
     * @return response Json
     */

    /**
     * @OA\get(
     *  path="/api/v1/active_subeldwry",
     *   tags={"subeldwry_fixtures"},
     *   operationId="active_subeldwry",
     *   summary="get current active subeldwry",
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
    public function active_subeldwry(Request $request) {
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
        $active_subeldwry =Subeldwry::get_CurrentSubDwry();
        if(isset($active_subeldwry->id)){
            $response['data']= new SubeldwryResource($active_subeldwry);
            return response()->json($response, 200);
        }else{
            $response = API_Controller::MessageData('NO_DATA_FOUND', $lang,15);
            return response()->json($response, 400);
        }  
    }

}
