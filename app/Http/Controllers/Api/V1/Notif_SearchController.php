<?php

namespace App\Http\Controllers\Api\V1;

//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\API_Controller;
use App\Http\Controllers\ClassSiteApi\Class_TicketController;
use App\Models\User;
use App\Models\UserNotif;

class Notif_SearchController extends API_Controller {
    /**
     * get data about search   
     * get method
     * url : http://localhost:8000/api/v1/search
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/search",
     *   tags={"search"},
     *   operationId="search",
     *   summary="search",
     * @OA\Parameter(
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
     *    name="search",
     *    in= "query",
     *    required=true,
     *      @OA\Schema(
     *           type="string"
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
    public function search(Request $request) {
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
        $search = isset($input['search']) ? $input['search'] : '';
        $response = [];
        $fields = [];
        if ($search == "") {
            $fields['search'] = 'search';
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang,2);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        $user_id = 0;
        if (!empty($access_token)) {
            $data_user = User::user_access_token($access_token, 1);
            if (isset($data_user->id)) {
                $user_id = $data_user->id;
            }
        }
        $get_result = new Class_TicketController();
        $result_search = []; // $get_result->get_ResultSearchWord($search, $user_id, 1);
        //save word search and count num search about it
        // $add_search = $get_result->insert_SearchWord($request, $search, $user_id, 1);
        $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
        $response['data'] = $result_search;
        return response()->json($response, 200);
    }

    /**
     * get notifications of action   
     * get method
     * url : http://localhost:8000/api/v1/notifications
     *
     * @return response Json
     */

    /**
     * @OA\Get(
     *   path="/notifications",
     *   tags={"notifications"},
     *   summary="notifications",
     *   operationId="notifications",
     *   @OA\Parameter(
     *     name="access-token",
     *     in="header",
     *     required=true,
     *      @OA\Schema(
     *           type="string",
     *      ) 
     *   ),
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
     *   @OA\Response(response=200, description="successful operation"),
     *   @OA\Response(response=400, description="not acceptable"),
     *   @OA\Response(response=500, description="internal server error")
     * )
     *
     */
    public function notifications() {
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
        $response = [];
        $fields = [];
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
        if (!empty($access_token)) {
            $data_user = User::user_access_token($access_token, 1);
            if (isset($data_user->id)) {
                $user_id = $data_user->id;
                //data of notification
                //$get_notif = UserNotif::get_UserNotif($user_id, 1, 0);
                $data_notif = []; // UserNotif::SelectDataNotif($get_notif, 1);
                $count_notif = (string) count($data_notif);
                $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
                $response['count_notif'] = $count_notif;
                $response['data'] = $data_notif;
                return response()->json($response, 200);
            } else {
                $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
                return response()->json($response, 400);
            }
        } else {
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
            return response()->json($response, 400);
        }
    }

    /**
     * update notifications to read=1  
     * get method
     * url : http://localhost:8000/api/v1/update_notif
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/update_notif",
     *   tags={"notifications"},
     *   operationId="update_notif",
     *   summary="update notifications",
     * @OA\Parameter(
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
     *    name="id",
     *    in= "query",
     *    required=true,
     *    description="id of notification",
     *      @OA\Schema(
     *           type="number",
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
    public function update_notif(Request $request) {
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
        $id = isset($input['id']) ? $input['id'] : '';
        $response = [];
        $fields = [];
        if ($id == "") {
            $fields['id'] = 'id';
        }
        if ($access_token == "") {
           $fields['access_token'] = 'access-token';
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
            $response['data'] = $fields;
            return response()->json($response, 401);
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            $response['Message'] = API_Controller::MISSING_FIELD;
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        if (!empty($access_token)) {
            $data_user = User::user_access_token($access_token, 1);
            if (isset($data_user->id)) {
                $user_id = $data_user->id;
                //data of notification by id
                $data_notif = UserNotif::get_UserNotifID($id, $user_id, 1, 0, '');
                if (isset($data_notif->id)) {
//                    $get_class = new Class_TicketController();
//                    $get_class->Update_readArray($data_notif->posts->lang_id);
                    $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
                    return response()->json($response, 200);
                } else {
                    $response = API_Controller::MessageData('ERROR_MESSAGE', $lang,1);
                    return response()->json($response, 400);
                }
            } else {
                $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
                return response()->json($response, 400);
            }
        } else {
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
            return response()->json($response, 400);
        }
    }

}
