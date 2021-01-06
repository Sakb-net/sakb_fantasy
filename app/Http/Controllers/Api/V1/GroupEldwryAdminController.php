<?php
namespace App\Http\Controllers\Api\V1;

//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\API_Controller;
use App\Models\User;
// use App\Models\GroupEldwry;
use App\Http\Controllers\ClassSiteApi\Class_GroupEldwryController;

class GroupEldwryAdminController extends API_Controller {

    /**
     * get data setting_admin for group_eldwry  , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/group_eldwry/setting_admin/link_group
     *
     * @return response Json
     */

    /**
     * @OA\Get(
     *  path="/api/v1/group_eldwry/setting_admin/{type_group}/{link_group}",
     *   tags={"group_eldwry"},
     *   operationId="group_eldwry/setting_admin",
     *   summary="setting_admin for group eldwry ",
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
    public function setting_admin(Request $request,$type_group,$link_group) { 
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
            $response['data']=$get_data->setting_admin_group_eldwry($user,$link_group,1,$type_group);
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }
    /**
     * get data add_admin in group_eldwry  , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/group_eldwry/add_admin/link_group
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/api/v1/group_eldwry/add_admin/{type_group}/{link_group}",
     *   tags={"group_eldwry"},
     *   operationId="group_eldwry/add_admin",
     *   summary="add_admin from group eldwry of user",
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
     *    name="user_name",
     *    in= "query",
     *    required=true,
     *    description="user_name of player in this group",
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
    public function add_admin(Request $request,$type_group,$link_group) { 
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
        if ($input['user_name'] == "") {
            $fields['user_name'] = 'user_name';
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
            $response['data']=$get_data->add_admin_group_eldwry($user,$link_group,$input['user_name'],1,$type_group);
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }

    /**
     * get data delete_player from group_eldwry  , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/group_eldwry/delete_player/link_group
     *
     * @return response Json
     */

    /**
     * @OA\Delete(
     *  path="/api/v1/group_eldwry/delete_player/{type_group}/{link_group}",
     *   tags={"group_eldwry"},
     *   operationId="group_eldwry/delete_player",
     *   summary="delete_player from group eldwry of user",
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
     *    name="user_name",
     *    in= "query",
     *    required=true,
     *    description="user_name of player in this group",
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
    public function delete_player(Request $request,$type_group,$link_group) { 
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
        if ($input['user_name'] == "") {
            $fields['user_name'] = 'user_name';
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
            $response['data']=$get_data->delete_player_group_eldwry($user,$link_group,$input['user_name'],1,$type_group);
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }

    /**
     * get data setting_invite for group_eldwry  , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/group_eldwry/setting_invite/link_group
     *
     * @return response Json
     */

    /**
     * @OA\Get(
     *  path="/api/v1/group_eldwry/setting_invite/{type_group}/{link_group}",
     *   tags={"group_eldwry"},
     *   operationId="group_eldwry/setting_invite",
     *   summary="setting_invite for group eldwry of user",
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
    public function setting_invite(Request $request,$type_group,$link_group) { 
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
            $response['data']=$get_data->setting_invite_group_eldwry($user,$link_group,1,$type_group);
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }

    /**
     * get data join from in  , if found sent access_token
     * post method
     * url : http://localhost:8000/api/v1/group_eldwry/join
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *  path="/api/v1/group_eldwry/join/{type_group}",
     *   tags={"group_eldwry"},
     *   operationId="group_eldwry/join",
     *   summary="join in group eldwry of user",
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
     *    in= "query",
     *    description="link of group_eldwry",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="val_code",
     *    in= "query",
     *    description="code of group eldwry",
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
    public function join(Request $request,$type_group) { 
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
        $input['link_group'] = isset($input['link_group']) ? $input['link_group'] : '';
        $input['val_code'] = isset($input['val_code']) ? $input['val_code'] : '';
         if ($input['link_group'] == "" && $input['val_code']=="") {
            $fields['link_group'] = 'link_group';
            $fields['val_code'] = 'val_code';
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        $user = User::user_access_token($access_token, 1);
        if (isset($user->id)) {
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            $input['val_type']='code';
            if(!empty($input['link_group'])){
                $input['val_type']='link';
            }
            $get_data = new Class_GroupEldwryController();
            $response['data']=$get_data->add_join_group_eldwry($user,$input,1,$type_group);
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }


}