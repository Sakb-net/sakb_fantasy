<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\API_Controller;
use App\Http\Controllers\ClassSiteApi\Class_GameController;
use App\Http\Controllers\ClassSiteApi\Class_PageController;
use App\Models\User;

class PageController extends API_Controller {
    /**
     * add new version   
     * get method
     * url : http://localhost:8000/api/v1/version
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/api/v1/version",
     *   tags={"page"},
     *   operationId="version",
     *   summary="get version",
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
    public function version(Request $request) {
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
        $version = array('v_ios' => 1, 'v_android' => 1);
        $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
        $response['data'] = $version;
        return response()->json($response, 200);
    }

    /**
     * add new home   
     * get method
     * url : http://localhost:8000/api/v1/home
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/api/v1/home",
     *   tags={"page"},
     *   operationId="home",
     *   summary="get data of home",
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
     *   @OA\Parameter(
     *    name="access-token",
     *    in= "header",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
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
     *    name="limit",
     *    in= "query",
     *    description="default = 5" ,
     *      @OA\Schema(
     *           type="number",
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="limit_fix",
     *    in= "query",
     *    description="default = 2" ,
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
    public function home(Request $request) {
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
        $limit = isset($input['limit']) ? $input['limit'] : 5;
        $limit_fix = isset($input['limit_fix']) ? $input['limit_fix'] : 2;
        $user_id=0;
        if(!empty($access_token)){
            $user = User::user_access_token($access_token, 1);
            if (isset($user->id)) {
                $user_id=$user->id;
            } else {
                $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
                return response()->json($response, 401);
            }
        }
        $data_page = new Class_PageController();
        $return_data =$data_page->Page_Home($lang, $limit, 1,$limit_fix,-1,0,$user_id);
        $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
        $response['data'] = $return_data;
        return response()->json($response, 200);
    }
/**
     * add new instraction   
     * get method
     * url : http://localhost:8000/api/v1/instraction
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/api/v1/instraction",
     *   tags={"page"},
     *   operationId="instraction",
     *   summary="instraction",
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
    public function instraction(Request $request) {
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
        $data_page = new Class_PageController();
        $return_data = $data_page->Page_instraction(1,$lang);
        $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
        $response['data'] = $return_data;
        return response()->json($response, 200);
    }
    
    /**
     * add new award
     * get method
     * url : http://localhost:8000/api/v1/award
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/api/v1/award",
     *   tags={"page"},
     *   operationId="award",
     *   summary="award",
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

    public function award(Request $request) {
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
        $data_page = new Class_PageController();
        $return_data = $data_page->Page_award(1,$lang);
        $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
        $response['data'] = $return_data;
        return response()->json($response, 200);
    }


    /**
     * add new about   
     * get method
     * url : http://localhost:8000/api/v1/about
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/api/v1/about",
     *   tags={"page"},
     *   operationId="about",
     *   summary="about",
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
    public function about(Request $request) {
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
        $data_page = new Class_PageController();
        $return_data = $data_page->Page_about(1);
        $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
        $response['data'] = $return_data;
        return response()->json($response, 200);
    }

    /**
     * add new terms   
     * get method
     * url : http://localhost:8000/api/v1/terms
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/api/v1/terms",
     *   tags={"page"},
     *   operationId="terms",
     *   summary="terms",
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
    public function terms(Request $request) {
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
        $data_page = new Class_PageController();
        $return_data = $data_page->PageContent('terms', $lang, 1);
        $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
        $response['data'] = $return_data;
        return response()->json($response, 200);
    }

    /**
     * add new contact_us   
     * get method
     * url : http://localhost:8000/api/v1/contact_us
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/api/v1/contact_us",
     *   tags={"page"},
     *   operationId="contact_us",
     *   summary="contact_us",
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
    public function contact_us(Request $request) {
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
        $data_page = new Class_PageController();
        $return_data = $data_page->Page_contactUs('contact', $lang, 1);
        $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
        $response['data'] = $return_data;
        return response()->json($response, 200);
    }

    /**
     * add new contactUs   
     * get method
     * url : http://localhost:8000/api/v1/add_contact_us
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/api/v1/add_contact_us",
     *   tags={"page"},
     *   operationId="add_contact_us",
     *   summary="add message of contact_us",
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
     *    name="content",
     *    in= "query",
     *    required=true,
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="user_email",
     *    in= "query",
     *    description="should send it when access_token is empty" ,
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="user_name",
     *    in= "query",
     *    description="should send it when access_token is empty ",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="visitor",
     *    in= "query",
     *    description="This Ip of device",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="type",
     *    in= "query",
     *    description="default type is contact and can use (contact)",
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
    public function add_contact_us(Request $request) {
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
        $content = isset($input['content']) ? $input['content'] : '';
        $user_name = isset($input['user_name']) ? $input['user_name'] : '';
        $user_email = isset($input['user_email']) ? $input['user_email'] : '';
        $visitor = isset($input['visitor']) ? $input['visitor'] : 'ip';
        $type = isset($input['type']) ? $input['type'] : 'contact';
        $response = [];
        $fields = [];

        if ($content == "") {
            $fields['content'] = 'content';
        }
       // if ($access_token == "") {
       //     $fields['access_token'] = 'access-token';
       //      $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
       //      $response['data'] = $fields;
       //      return response()->json($response, 401);
       //  }
        if ($access_token == "") {
            if ($user_name == "") {
                $fields['user_name'] = 'user_name';
            }
            if ($user_email == "") {
                $fields['user_email'] = 'user_email';
            } else {
                if (filter_var($user_email, FILTER_VALIDATE_EMAIL) === false) {
                    $response = API_Controller::MessageData('INVALID_EMAIL', $lang,22);
                    return response()->json($response, 400);
                }
            }
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
                $user_name = $data_user->display_name;
                $user_email = $data_user->email;
            } else {
                $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
                return response()->json($response, 401);
            }
        }
        $input = [
            'name' => $user_name,
            'email' => $user_email,
            'type' => $type,
            'content' => $content,
            'visitor' => $visitor
        ];
        $insert_contact = new Class_PageController();
        $contact_us = $insert_contact->add_contact_Us($input, $user_id, 1);
        if ($contact_us['state_add'] == 1) {
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            $response['state_add'] = $contact_us['state_add'];
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('NOT_SAVED', $lang,21);
            return response()->json($response, 400);
        }
    }

}
