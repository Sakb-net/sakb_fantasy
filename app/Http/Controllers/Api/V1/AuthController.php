<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API_Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Team;
use App\Models\Game;
use Hash;

//use Mail;
//use URL;
class AuthController extends API_Controller {
    /**
     * get data get_country  
     * get method
     * url : http://localhost:8000/api/v1/get_country
     *
     * @return response Json
     */

 /**
     * @OA\Get(
     *   path="/get_country",
     *   tags={"auth"},
     *   summary="get country",
     *   operationId="get_country",
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
    
    public function get_country(Request $request) {
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
        $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang, 0);
        if ($lang == 'en') {
            $response['data'] = ['key' => 'SA', 'name' => 'SA'];
        } else {
            $response['data'] = ['key' => 'SA', 'name' => 'السعودية']; //country_array();// $country = 'SA'; //AE
        }
        return response()->json($response, 200);
    }

    /**
     * get data get_city  
     * get method
     * url : http://localhost:8000/api/v1/get_city
     *
     * @return response Json
     */

     /**
     * get data city
     * get method
     * url : http://localhost:8000/api/v1/get_city
     *
     * @return response Json
     */

    /**
     * @OA\Get(
     *   path="/get_city",
     *   tags={"auth"},
     *   summary="get city",
     *   operationId="get_city",
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
    public function get_city(Request $request) {
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
        $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang, 0);
        $response['data'] = cityName_API(1, $lang);
        return response()->json($response, 200);
    }
    /**
     * @OA\Get(
     *   path="/get_teams",
     *   tags={"auth"},
     *   summary="get Teams",
     *   operationId="get_teams",
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
    public function get_teams(Request $request) {
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
        $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang, 0);
        $response['data'] = Team::getTeam_data(1,$lang,1);
        return response()->json($response, 200);
    }

    /**
     * insert email & name & password & account gender & address
     * post method
     * url : http://localhost:8000/api/v1/register
     * object of inputs {email:somevalue,$display_name:somevalue,password:somevalue,phone:somevalue,fcm_token:somevalue}
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/register",
     *   tags={"auth"},
     *   operationId="register",
     *   summary="creat new account",
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
     *   @OA\Parameter(
     *    name="email",
     *    in= "query",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     *   @OA\Parameter(
     *    name="password",
     *    in= "query",
     *    required=true,
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     *   @OA\Parameter(
     *    name="display_name",
     *    in= "query",
     *    required=true,
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     *   @OA\Parameter(
     *    name="phone",
     *    in= "query",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     *  @OA\Parameter(
     *    name="best_team",
     *    in= "query",
     *      @OA\Schema(
     *           type="string",
     *           default="1424e73e6d6f0269c9b6a28b2ae0974779" 
     *      ),
     *    required=false,
     *    description="link of team ",
     *  ),
     *  @OA\Parameter(
     *    name="country",
     *    in= "query",
     *    required=false,
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),   
     *   @OA\Parameter(
     *    name="city",
     *    in= "query",
     *    required=false,
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),   
     *   @OA\Parameter(
     *    name="state",
     *    in= "query",
     *    required=false,
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),  
     *  @OA\Parameter(
     *    name="reg_site",
     *    in= "query",
     *    required=true,
    *      @OA\Schema(
     *           type="string",
     *          default="ios" 
     *      ) 
     *  ),   
     *  @OA\Parameter(
     *    name="device_id",
     *    in= "query",
     *    required=false,
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     *  @OA\Parameter(
     *    name="fcm_token",
     *    in= "query",
     *    required=false,
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
    public function register(Request $request) {
        $data_header = API_Controller::get_DataHeader(getallheaders());
        //$access_token = $data_header['access_token'];
        $val_dev = $data_header['val_dev'];
        $type_dev = $data_header['type_dev'];
        $lang = $data_header['lang'];
        $data_ok = API_Controller::AuthAPI($type_dev, $val_dev);
        if (empty($data_ok) || $data_ok == 0) {
            $response = API_Controller::MessageData('AUTH_NOTALLOW', $lang, 49);
            return response()->json($response, 400);
        }
        $input = $request->all();
        $display_name = isset($input['display_name']) ? $input['display_name'] : '';
        $email = isset($input['email']) ? $input['email'] : '';
        $password = isset($input['password']) ? $input['password'] : '';
        $phone = isset($input['phone']) ? $input['phone'] : '';
        $address = isset($input['country']) ? $input['country'] : '';
        $city = isset($input['city']) ? $input['city'] : '';
        $state = isset($input['state']) ? $input['state'] : '';
        $reg_site = isset($input['reg_site']) ? $input['reg_site'] : '';
        $device_id = isset($input['device_id']) ? $input['device_id'] : NULL;
        $fcm_token = isset($input['fcm_token']) ? $input['fcm_token'] : NULL;
        $best_team = isset($input['best_team']) ? $input['best_team'] : NULL;
        $response = [];
        $fields = [];
        if ($email == "" && $phone=="") {
            $fields['email'] = 'email'; // or user_name
            $fields['phone'] = 'phone';
        } 
        if ($email != "") {
            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $response = API_Controller::MessageData('INVALID_EMAIL', $lang,22);
                return response()->json($response, 400);
            }else{
                $user_found = User::whereEmail($email)->first();
                if (isset($user_found->id)) {
                    $response = API_Controller::MessageData('EMAIL_EXIST', $lang,21);
                    return response()->json($response, 400);
                }
            }
        }

        if ($phone != "") {
            if (!(preg_match("/^([+]?)[0-9]{8,16}$/", $phone))) { //if (!preg_match("/^[00+]{1,2}2\d{11,14}$/", $phone, $matches)) {  // ----->  0010207557338 or +10207557338
                $response = API_Controller::MessageData('INVALID_Phone', $lang,20);
                return response()->json($response, 400);
            }else{
                $user_phone = User::where('phone', $phone)->first();
                if (isset($user_phone->id)) {
                    $response = API_Controller::MessageData('PHONE_EXIST', $lang,13);
                    return response()->json($response, 400);
                }
            }
        }
        if ($display_name == "") {
            $fields['display_name'] = 'display_name';
        }
        if ($password == "") {
            $fields['password'] = 'password';
        }
        // if ($best_team == "") {
        //     $fields['best_team'] = 'best_team';
        // }
        // if ($address == "") {
        //     $fields['country'] = 'country';
        // }
        // if ($city == "") {
        //     $fields['city'] = 'city';
        // }
        // if ($state == "") {
        //     $fields['state'] = 'state';
        // }
        if ($reg_site == "") {
            $fields['reg_site'] = 'reg_site';
        }
//        if ($device_id == "") {
//            $fields['device_id'] = 'device_id';
//        }
        
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang,2);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        if ($display_name != '' && $password != '') { 
            $user_reg = User::addCreate($request, '', $display_name, $email, $password, $phone, $fcm_token, $device_id, $reg_site, $address, $city, $state,$best_team);
            if ($user_reg) {
                //register in current dwary
                User::registerNewdwry($user_reg->id,1,1,1,1);
                $user = User::SelectCoulumUser($user_reg,$lang,1);
                $user['new_fcm_token'] = $fcm_token;
                User::SendEmailTOUser($user_reg->id, 'register', '');
                $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
                $response['data'] = $user;
                return response()->json($response, 200);
            } else {
                $response = API_Controller::MessageData('ERROR_MESSAGE', $lang,1);
                return response()->json($response, 400);
            }
        }
    }

    /**
     * retrieve username and password exist or not and return user id and account gender (Facebook / email)
     * post method
     * url : http://localhost:8000/api/v1/login/email
     * object of inputs {email:somevalue ,password:somevalue}
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/login/email",
     *   tags={"auth"},
     *   operationId="loginEmail",
     *   summary="login by email",
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
     *   @OA\Parameter(
     *    name="email_user_name",
     *    in= "query",
     *    required=true,
     *    description= "enter phone or email",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     *   @OA\Parameter(
     *    name="password",
     *    in= "query",
     *    required=true,
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     *   @OA\Parameter(
     *    name="device_id",
     *    in= "query",
     *    required=false,
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     *   @OA\Parameter(
     *    name="fcm_token",
     *    in= "query",
     *    required=false,
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
    public function loginEmail(Request $request) {
        $data_header = API_Controller::get_DataHeader(getallheaders());
        //$access_token = $data_header['access_token'];
        $val_dev = $data_header['val_dev'];
        $type_dev = $data_header['type_dev'];
        $lang = $data_header['lang'];
        $data_ok = API_Controller::AuthAPI($type_dev, $val_dev);
        if (empty($data_ok) || $data_ok == 0) {
            $response = API_Controller::MessageData('AUTH_NOTALLOW', $lang, 49);
            return response()->json($response, 400);
        }
        $input = $request->all();
        $email_user_name = isset($input['email_user_name']) ? $input['email_user_name'] : '';
        $password = isset($input['password']) ? $input['password'] : '';
        $device_id = isset($input['device_id']) ? $input['device_id'] : NULL;
        $fcm_token = isset($input['fcm_token']) ? $input['fcm_token'] : NULL;
        $response = [];
        $fields = [];
        if ($password == "") {
            $fields['password'] = 'password';
        }
//        if ($device_id == "") {
//            $fields['device_id'] = 'device_id';
//        }
        if ($email_user_name == "") {
            $fields['email_user_name'] = 'email or phone'; // or user_name
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang,2);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        if ($email_user_name != "" && $password != "") {
            $password_hash = bcrypt($password);
            $data = User::where('email', $email_user_name)->orwhere('phone', $email_user_name)->first();   //if (Auth::attempt(array('email' => $email, 'password' => $password)))
            if (!empty($data) && isset($data->password) && isset($data->id)) {
                if (isset($data->is_active) && $data->is_active == 1) {
                    if (Hash::check($password, $password_hash) && Hash::check($password, $data->password)) {
                        //update access_token  auth()->login($user)
                        $access_token = generateRandomToken();
                        $old_fcm_token = $data->fcm_token;
                        $session_user = generateRandomValue();
                        if (empty($fcm_token) && empty($device_id)) {
                            User::updateColumTwo($data->id, 'access_token', $access_token, 'session', $session_user);
                        } elseif (empty($fcm_token) && empty(!$device_id)) {
                            User::updateColumTwo($data->id, 'access_token', $access_token, 'session', $session_user, 'device_id', $device_id);
                        } elseif (!empty($fcm_token) && empty($device_id)) {
                            User::updateColumThree($data->id, 'access_token', $access_token, 'session', $session_user, 'fcm_token', $fcm_token);
                        }
                        //register in current dwary
                        User::registerNewdwry($data->id,1,0,1,1);
                        $user = User::SelectCoulumUser($data,$lang,1);
                        $user['access_token'] = $access_token;
                        $user['new_fcm_token'] = $fcm_token;
                        $user['old_fcm_token'] = $old_fcm_token;
                        $user['choose_team']=0;
                        // $user['user_total_mypoint']=0;
                        $game=Game::get_GameCloum('user_id',$data->id);
                        if(isset($game->team_name) && !empty($game->team_name)){
                            $user['choose_team']=1;
                            // $user['user_total_mypoint']=static::sum_Finaltotal($data->id,'sub_eldwry_id',$start_dwry->id,1);
                        }

                        $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
                        $response['data'] = $user;
                        return response()->json($response, 200);
                    } else {
                        $response = API_Controller::MessageData('AUTH_FAIL', $lang,8);
                        return response()->json($response, 400);
                    }
                } else {
                    $response = API_Controller::MessageData('Account_CLOSE', $lang,30);
                    return response()->json($response, 400);
                }
            } else {
                $response = API_Controller::MessageData('AUTH_FAIL', $lang,8);
                return response()->json($response, 400);
            }
        }
    }
    /**
     * retrieve access_token and return true or false
     * post method
     * url : http://localhost:8000/api/v1/logout
     * object of inputs {access_token:somevalue}
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/logout",
     *   tags={"auth"},
     *   operationId="logout",
     *   summary="logout",
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
    public function logout(Request $request) {
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
        $user = User::user_access_token($access_token, 1);
//        $user = Auth::guard('api')->user();
        if (isset($user->id)) {
            //User logged out
            $user->access_token = null;
            $user->session = null;
            $user->save();
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }

//*******************************************************
    public function sendEmailReminder() {
        $user = User::findOrFail(53);

//        $us=Mail::send('admin.send_email', ['user' => $user], function ($m) use ($user) {
//            $m->from('no-reply@Master.com', 'Your Application');
//            $m->to($user->email, $user->user_name)->subject('Your Reminder!');
//        });



        $us = Mail::send('admin.send_email', ['user' => $user], function ($message) use ($user) {
                    $message->to($user->email, $user->user_name)->subject('Baims dd!');
                });
        print_r($us . 'gg');
        die;
    }

    public function forgetpassword() {
        $fields = [];
        $input = \Input::all();
        $email = \Input::get('email') ? $input['email'] : '';

        if (isset($email) && $email != '') {
            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $fields['invalid_email'] = API_Controller::INVALID_EMAIL;
            }
            if (!empty($fields)) {
                $response['StatusCode'] = 3;
                $response['Message'] = API_Controller::INVALID_EMAIL;
                return response()->json($response);
            }
            $response = [];
            $user = User::where('email', '=', $email)->get(['id', 'user_name', 'mobile', 'email']);
            if (!$user->isEmpty()) {
                $response['StatusCode'] = 0;
                $current_time = time();

                $data = PasswordReset::where([['email', "=", $email], ['status', '=', 0]])->get();
                if (!$data->isEmpty()) {
                    $create = strtotime($data[0]->created_at);

                    if ($current_time < ($create + (6 * 60 * 60))) {
                        $response['StatusCode'] = 24;
                        $response['Message'] = API_Controller::EMAIL_SEND_BEFORE;
                        return response()->json($response);
                    }
                }
                $token = generateRandomToken();
                $password = new PasswordReset();
                $password->email = $user[0]->email;
                $password->token = $token;
                $password->status = 0;
                $password->created_at = date('Y-m-d H:i:s');
                $save = $password->save();
                if ($save) {
                    $response['StatusCode'] = 0;
                    $response['Message'] = API_Controller::SUCCESS_MESSAGE;
                    $subject = "Reset Password Baims App";
                    $send_email = array(
                        'subject' => $subject,
                    );

                    $link = URL::to('/') . "/resetpassword/$token";
//                    $response['data'] = $link;

                    $contents = "Hello " . $user[0]->user_name . ",
                    To reset your password please follow the link below: $link
                    Thanks! 
                    Baims";

                    $myfile = fopen("../resources/views/admin/send_email.blade.php", "w") or die("Unable to open file!");
                    fwrite($myfile, $contents);
                    fclose($myfile);

                    Mail::send('emails.reset_pass', [$send_email], function ($message) use ($user, $send_email) {
                        $message->to($user[0]->email, $user[0]->user_name)->subject($send_email['subject']);
                    });

                    return response()->json($response);
                } else {
                    $response['StatusCode'] = 21;
                    $response['Message'] = API_Controller::NOT_SAVED;
                    return response()->json($response);
                }
//                $response['data'] = $user;
            } else {
                $response['StatusCode'] = 11;
                $response['Message'] = API_Controller::USER_NOT_Found;
                return response()->json($response);
            }
        } else {
            $fields['email'] = 'email';
            $response['StatusCode'] = 2;
            $response['Message'] = API_Controller::MISSING_FIELD;
            $response['data'] = $fields;
            return response()->json($response);
        }
    }

}
