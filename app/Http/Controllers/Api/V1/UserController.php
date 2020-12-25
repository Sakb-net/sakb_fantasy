<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\API_Controller;
use App\Http\Controllers\ClassSiteApi\Class_UserController;
use App\Models\User;
use App\Models\Watche;
use App\Models\Team;
use Hash;

class UserController extends API_Controller {
    /**
     * get data profile by access_token
     * get method
     * url : http://localhost:8000/api/v1/profile
     *
     * @return response Json
     */

    /**
     * @OA\Get(
     *   path="/profile",
     *   tags={"profile"},
     *   summary="get profile of current user",
     *   operationId="profile",
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
    public function profile(Request $request) {
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
        $data_user = User::user_access_token($access_token, 1);
        if (isset($data_user->id)) {
            $user = User::SelectCoulumUser($data_user,$lang,1);
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            $response['data'] = $user;
//            $get_user = new Class_UserController();
//            $response['count_user'] = $get_user->DataUser($data_user, 'Profile', 1, 1);
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }

    /**
     * update email  & phone & display_name & gender & image & address &fcm_token
     * post method
     * url : http://localhost:8000/api/v1/update_profile
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/update_profile",
     *   tags={"profile"},
     *   operationId="update profile current user",
     *   summary="v1/update_profile",
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
     *   @OA\Parameter(
     *    name="email",
     *    in= "query",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     *  @OA\Parameter(
     *    name="display_name",
     *    in= "query",
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
     *   @OA\Parameter(
     *    name="image",
     *    in= "query",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     *   @OA\Parameter(
     *    name="country",
     *    in= "query",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),   
     *   @OA\Parameter(
     *    name="city",
     *    in= "query",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),   
     *   @OA\Parameter(
     *    name="state",
     *    in= "query",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),   
     *   @OA\Parameter(
     *    name="gender",
     *    in= "query",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),   
     *   @OA\Parameter(
     *    name="fcm_token",
     *    in= "query",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),   
     *   @OA\Response(
     *    response=200,
     *    description="success",
     *    
     *   ),
     *   @OA\Response(
     *    response=400,
     *    description="error",
     *  )
     * )
     */
    public function update_profile(Request $request) {
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
        $display_name = isset($input['display_name']) ? $input['display_name'] : '';
        $email = isset($input['email']) ? $input['email'] : '';
        $phone = isset($input['phone']) ? $input['phone'] : '';
        $path_image = isset($input['image']) ? $input['image'] : '';
        $address = isset($input['country']) ? $input['country'] : '';
        $city = isset($input['city']) ? $input['city'] : '';
        $state = isset($input['state']) ? $input['state'] : '';
        $gender = isset($input['gender']) ? $input['gender'] : '';
        $best_team = isset($input['best_team']) ? $input['best_team'] : NULL;
        $fcm_token = isset($input['fcm_token']) ? $input['fcm_token'] : NULL;
        $state_fcm_token = isset($input['state_fcm_token']) ? $input['state_fcm_token'] : -1;

        //print_r('image');die;
        $response = [];
        $fields = [];
        if ($access_token == "") {
           $fields['access_token'] = 'access-token';
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
            $response['data'] = $fields;
            return response()->json($response, 401);
        }
        if (!empty($email)) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $response = API_Controller::MessageData('INVALID_EMAIL', $lang,22);
                return response()->json($response, 400);
            }
        }
        if (!empty($phone)) {
            if (!(preg_match("/^([+]?)[0-9]{8,16}$/", $phone))) { //if (!preg_match("/^[00+]{1,2}2\d{11,14}$/", $phone, $matches)) {  // ----->  0010207557338 or +10207557338
                $response = API_Controller::MessageData('INVALID_Phone', $lang,20);
                return response()->json($response, 400);
            }
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang,2);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
//        if (!empty($image)) {
//            $path_image = PathuploadImage($image);
//            if (empty($path_image)) {
//                $response = API_Controller::MessageData('NOT_IMAGE', $lang);
//                return response()->json($response, 400);
//            }
//        }
        // && $password != ''
//        if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false && $display_name != '' && $phone != '') {
        $user = User::user_access_token($access_token, 1);
        if (isset($user->id)) {
            if (!empty($email)) {
                if ($user->email != $email) {
                    $user_found = User::whereEmail($email)->first();
                    if (isset($user_found->id)) {
                        $response = API_Controller::MessageData('EMAIL_EXIST', $lang,22);
                        return response()->json($response, 400);
                    } else {
                        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                            $response = API_Controller::MessageData('INVALID_EMAIL', $lang,12);
                            return response()->json($response, 400);
                        }
                    }
                }
                $user->email = $email;
            }
            if (!empty($phone)) {
                if ($user->phone != $phone) {
                    $user_phone = User::where('phone', $phone)->first();
                    if (isset($user_phone->id)) {
                        $response = API_Controller::MessageData('PHONE_EXIST', $lang,13);
                        return response()->json($response, 400);
                    } else {
                        if (!(preg_match("/^([+]?)[0-9]{8,16}$/", $phone))) { //if (!preg_match("/^[00+]{1,2}2\d{11,14}$/", $phone, $matches)) {  // ----->  0010207557338 or +10207557338
                            $response = API_Controller::MessageData('INVALID_Phone', $lang,20);
                            return response()->json($response, 400);
                        }
                    }
                }
                $user->phone = $phone;
            }
            if (!empty($display_name)) {
                $user->display_name = $display_name;
            } else {
                $display_name = $user->display_name;
            }
            if (!empty($path_image)) {
                $user->image = $path_image;
            } else {
                if (empty($user->image) && empty($path_image)) {
                    $user->image = generateDefaultImage($display_name);
                }
            }
            if (!empty($best_team)) {
                $user->best_team=-1;
                $data_team=Team::foundData('link',$best_team);
                if(isset($data_team->id)){
                    $user->best_team=$data_team->id;
                }
            }
            if (!empty($address)) {
                $user->address = $address;
            }
            if (!empty($city)) {
                $user->city = $city;
            }
            if (!empty($state)) {
                $user->state = $state;
            }
            if (!empty($gender)) {
                $user->gender = $gender;
            }
            if (!empty($fcm_token)) {
                $user->fcm_token = $fcm_token;
            }
//                if ($state_fcm_token == 0 || $state_fcm_token == 1) {
//                    $user->state_fcm_token = $state_fcm_token;
//                }
            $save = $user->save();
            if ($save) {
                $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
                $response['data'] = User::SelectCoulumUser($user,$lang,1);
                return response()->json($response, 200);
            } else {
                $response = API_Controller::MessageData('ERROR_MESSAGE', $lang,1);
                return response()->json($response, 400);
            }
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
//        } else {
//                $response = API_Controller::MessageData('INVALID_DATA', $lang);
//            return response()->json($response, 400);
//        }
    }

    /**
     * @OA\Post(
     *   path="/mypoint",
     *   tags={"profile"},
     *   operationId="mypoint",
     *   summary="get point of current user",
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
    public function mypoint(Request $request) {
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
        $data_user = User::user_access_token($access_token, 1);
        if (isset($data_user->id)) {
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            $get_user = new Class_UserController();
           // $bills = $get_user->UserBillSeat($data_user->id, 1, 1, 'accept', 1);
            $response['data'] = [];//$get_user->get_DataBill($data_user->id, $bills, 'mypoint', 1);
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }

    /**
     * update fcm token access_token,fcm_token and return true and user date or false
     * post method
     * url : http://localhost:8000/api/v1/update_fcmtoken
     * object of inputs {access_token:somevalue,fcm_token:somevalue}
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/update_fcmtoken",
     *   tags={"profile"},
     *   operationId="update_fcmtoken",
     *   summary=" update fcmtoken for firebase",
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
     *   @OA\Parameter(
     *    name="fcm_token",
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
    public function update_fcmtoken(Request $request) {
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
        $fcm_token = isset($input['fcm_token']) ? $input['fcm_token'] : '';
        $response = [];
        $fields = [];
        if ($access_token == "") {
           $fields['access_token'] = 'access-token';
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
            $response['data'] = $fields;
            return response()->json($response, 401);
        }
        if ($fcm_token == "") {
            $fields['fcm_token'] = 'fcm_token';
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang,2);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        if (!empty($access_token)) {
            $data_user = User::user_access_token($access_token, 1);
            if (isset($data_user->id)) {
                User::where('id', $data_user->id)->update(['state_fcm_token' => 1, 'fcm_token' => $fcm_token]);
                $user = User::SelectCoulumUser($data_user,$lang,1);
                $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
                $response['data'] = $user;
                return response()->json($response, 200);
            } else {
                $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
                return response()->json($response, 401);
            }
        } else {
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
            return response()->json($response, 401);
        }
    }

    /**
     * retrieve access_token,new_password,old_password and return true and user date or false
     * post method
     * url : http://localhost:8000/api/v1/change_password
     * object of inputs {access_token:somevalue,new_password:somevalue,old_password:somevalue}
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/change_password",
     *   tags={"profile"},
     *   operationId="change_password",
     *   summary="change password",
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
     *   @OA\Parameter(
     *    name="new_password",
     *    in= "query",
     *    required=true,
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     *   @OA\Parameter(
     *    name="old_password",
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
    public function change_password(Request $request) {
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
        $new_password = isset($input['new_password']) ? $input['new_password'] : '';
        $old_password = isset($input['old_password']) ? $input['old_password'] : '';
        $response = [];
        $fields = [];
        if ($access_token == "") {
           $fields['access_token'] = 'access-token';
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
            $response['data'] = $fields;
            return response()->json($response, 401);
        }
        if ($new_password == "") {
            $fields['new_password'] = 'new_password';
        }
        if ($old_password == "") {
            $fields['old_password'] = 'old_password';
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang,2);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        if ($access_token != "" && $old_password != "" && $new_password != "") {
            $password_hash = bcrypt($old_password);
            $data = User::user_access_token($access_token, 1);
            if (!empty($data) && isset($data->password) && isset($data->id)) {
                if (Hash::check($old_password, $password_hash) && Hash::check($old_password, $data->password)) {
                    //update new_password  
                    $new_password = bcrypt($new_password);
                    User::where('id', $data->id)->update(['password' => $new_password]);
                    $user = User::SelectCoulumUser($data,$lang,1);
                    $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
                    $response['data'] = $user;
                    return response()->json($response, 200);
                } else {
                    $response = API_Controller::MessageData('PASSWORD_NOT_MATCH', $lang,27);
                    return response()->json($response, 400);
                }
            } else {
                $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
                return response()->json($response, 401);
            }
        }
    }

}
