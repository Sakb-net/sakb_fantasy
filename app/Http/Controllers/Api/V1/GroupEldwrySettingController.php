<?php
namespace App\Http\Controllers\Api\V1;

//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\API_Controller;
use App\Models\User;
// use App\Models\GroupEldwry;
use App\Http\Controllers\ClassSiteApi\Class_GroupEldwryController;

class GroupEldwrySettingController extends API_Controller {

 /**
     * get data group_eldwry after create , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/group_eldwry/create
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/api/v1/group_eldwry/create/{type_group}",
     *   tags={"group_eldwry"},
     *   operationId="group_eldwry/create",
     *   summary="create group eldwry of user",
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
     *    name="link_subeldwry",
     *    in= "query",
     *     required=true,
     *    description="link of subeldwry",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="name",
     *    in= "query",
     *     required=true,
     *    description="group eldwry name",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="type_group",
     *    in="path",
     *    description= "default type_group is classic ( classic , head)",
     *      @OA\Schema(
     *           type="string",
     *           default="classic"
     *      ),
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
    public function create(Request $request,$type_group) { 
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
        $input = $request->all();
        $response = [];
        $fields = [];
        if ($input['link_subeldwry'] == "") {
            $fields['link_subeldwry'] = 'link_subeldwry';
        }
        if ($input['name'] == "") {
            $fields['name'] = 'name';
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        $user = User::user_access_token($access_token, 1);
        if (isset($user->id)) {
            $input['update']=0;
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            $get_data = new Class_GroupEldwryController();
            if($type_group=='head'){
                $response['data']=$get_data->store_head_groupEldwry($user,$input,$lang,1);
            }else{
                $response['data']=$get_data->store_groupEldwry($user,$input,$lang,1);
            }
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }

    /**
     * get data update group_eldwry  , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/group_eldwry/update/link_group
     *
     * @return response Json
     */

    /**
     * @OA\Put(
     *  path="/api/v1/group_eldwry/update/{type_group}/{link_group}",
     *   tags={"group_eldwry"},
     *   operationId="group_eldwry/update",
     *   summary="update group eldwry of user",
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
     *    name="link_group",
     *    in= "path",
     *    required=true,
     *    description="link of group_eldwry",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="link_subeldwry",
     *    in= "query",
     *    required=true,
     *    description="link of subeldwry",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="name",
     *    in= "query",
     *     required=true,
     *    description="group eldwry name",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="type_group",
     *    in="path",
     *    description= "default type_group is classic ( classic , head)",
     *      @OA\Schema(
     *           type="string",
     *           default="classic"
     *      ),
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
    public function update(Request $request,$type_group,$link_group) { 
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
        $input = $request->all();
        $response = [];
        $fields = [];
        if ($link_group == "") {
            $fields['link_group'] = 'link_group';
        }
        if ($input['link_subeldwry'] == "") {
            $fields['link_subeldwry'] = 'link_subeldwry';
        }
        if ($input['name'] == "") {
            $fields['name'] = 'name';
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        $user = User::user_access_token($access_token, 1);
        if (isset($user->id)) {
            $input['link_group']=$link_group;
            $input['update']=1;
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            $get_data = new Class_GroupEldwryController();
            if($type_group=='head'){
                $response['data']=$get_data->store_head_groupEldwry($user,$input,$lang,1);
            }else{
                $response['data']=$get_data->store_groupEldwry($user,$input,$lang,1);
            }
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }

    /**
     * get data stop group_eldwry  , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/group_eldwry/stop/link_group
     *
     * @return response Json
     */

    /**
     * @OA\Put(
     *  path="/api/v1/group_eldwry/stop/{type_group}/{link_group}",
     *   tags={"group_eldwry"},
     *   operationId="group_eldwry/stop",
     *   summary="stop group eldwry of user",
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
     *    name="link_group",
     *    in= "path",
     *    required=true,
     *    description="link of group_eldwry",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="type_group",
     *    in="path",
     *    description= "default type_group is classic ( classic , head)",
     *      @OA\Schema(
     *           type="string",
     *           default="classic"
     *      ),
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
    public function stop(Request $request,$type_group,$link_group) { 
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
        $input = $request->all();
        $response = [];
        $fields = [];
        if ($link_group == "") {
            $fields['link_group'] = 'link_group';
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        $user = User::user_access_token($access_token, 1);
        if (isset($user->id)) {
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            $get_data = new Class_GroupEldwryController();
            $response['data']=$get_data->stop_group_eldwry($user,$link_group,1,$type_group);
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }

}