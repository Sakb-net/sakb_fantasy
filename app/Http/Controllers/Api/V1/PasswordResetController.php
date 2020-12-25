<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API_Controller;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\Models\User;
use App\Models\Role;
use App\Models\PasswordReset;
use Hash;
use Carbon\Carbon;

//use Mail;
//use URL;
class PasswordResetController extends API_Controller {
    /**
     * retrieve username and password exist or not and return user id and account gender (Facebook / email)
     * post method
     * url : http://localhost:8000/api/v1/forgetpassword
     * object of inputs {email:somevalue ,password:somevalue}
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/forgetpassword",
     *   tags={"auth"},
     *   operationId="forgetpassword",
     *   summary="forgetpassword by email",
     * @OA\Parameter(
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
    public function forgetpassword(Request $request) {
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
        $email = isset($input['email']) ? $input['email'] : '';
        $response = [];
        $fields = [];
        if ($email == "") {
            $fields['email'] = 'email';
        } else {
            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $response = API_Controller::MessageData('INVALID_EMAIL', $lang, 3);
                return response()->json($response, 400);
            }
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang, 2);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        $user = User::foundUser($email, 'email', 0);
        if (!isset($user->id)) {
            $response = API_Controller::MessageData('EMAIL_NOT_EXIST', $lang, 14);
            return response()->json($response, 400);
        }
        //$restToken = generateRandomToken();
        //$restToken_crypt = bcrypt($restToken);
        $restToken_crypt = get_randNum(6);
        $passwordReset = PasswordReset::updateOrCreate(
                        ['email' => $user->email], [
                    'email' => $user->email,
                    'token' => $restToken_crypt,
                    'created_at' => date('Y-m-d h:i:s'), //2019-12-17 22:01:25
                        ]
        );
        if ($user && $passwordReset) {
            ///send email by SAKBFANTASY
            // $data_send = $user->notify(
            //         new PasswordResetRequest($restToken_crypt)
            // );
            $response = API_Controller::MessageData('email_rest_link', $lang, 60);

        $url_rest = url('/api/v1/password/reset/'.$passwordReset['token']);
            $response['data'] = [
                'email' => $email,
                'token' => $passwordReset['token'],
                // 'url_rest' => $url_rest,
                'created_at' => $passwordReset['created_at'],
            ];
            $message_share= '<h2 style="text-align: center;">Rest Password</h2><p>You are receiving this email because we received a password reset request for your account.</p><p>Please code this code: <b>'.$passwordReset['token'].'</b></p>';

            $send_email=User::SendEmailTOUser($user->id,'restPassword', $message_share, [], 0.00, 0.00,'Rest Password');
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('ERROR_MESSAGE', $lang, 1);
            return response()->json($response, 400);
        }
    }

 /**
     * check validation code of token
     * post method
     * url : http://localhost:8000/api/v1/password/confirm_reset
     * object of inputs {email:somevalue ,password:somevalue}
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/password/confirm_reset",
     *   tags={"auth"},
     *   operationId="confirm_passwor_token",
     *   summary="check validation code of token ",
     * @OA\Parameter(
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
     *    required=true,
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     *   @OA\Parameter(
     *    name="token",
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
    public function confirm_passwor_token(Request $request) {
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
        $email = isset($input['email']) ? $input['email'] : '';
        $token = isset($input['token']) ? $input['token'] : '';
        $response = [];
        $fields = [];
        if ($token == "") {
            $fields['token'] = 'token';
        }
        if ($email == "") {
            $fields['email'] = 'email';
        } else {
            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $response = API_Controller::MessageData('INVALID_EMAIL', $lang, 3);
                return response()->json($response, 400);
            }
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang, 2);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        $user = User::foundUser($email, 'email', 0);
        if (!isset($user->id)) {
            $response = API_Controller::MessageData('EMAIL_NOT_EXIST', $lang, 14);
            return response()->json($response, 400);
        }
        $check_rest = PasswordReset::get_DataEmailTime('token', $token, 1,$email);
        if ($check_rest == 1) {
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang, 0);
            $response['data'] = $token;
            return response()->json($response, 200);
        } elseif ($check_rest == -1) {
            $response = API_Controller::MessageData('not_correct_token', $lang, 69);
            return response()->json($response, 400);
        } else {
            $response = API_Controller::MessageData('link_expired', $lang, 61);
            return response()->json($response, 400);
        }
    }

    /**
     * retrieve username and password exist or not and return user id and account gender (Facebook / email)
     * post method
     * url : http://localhost:8000/api/v1/restpassword
     * object of inputs {email:somevalue ,password:somevalue}
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/restpassword",
     *   tags={"auth"},
     *   operationId="restpassword",
     *   summary="restpassword by email",
     * @OA\Parameter(
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
     *    required=true,
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
    public function restpassword(Request $request) {
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
        $email = isset($input['email']) ? $input['email'] : '';
        $password = isset($input['password']) ? $input['password'] : '';
        $response = [];
        $fields = [];
        if ($password == "") {
            $fields['password'] = 'password';
        }
        if ($email == "") {
            $fields['email'] = 'email';
        } else {
            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $response = API_Controller::MessageData('INVALID_EMAIL', $lang, 3);
                return response()->json($response, 400);
            }
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang, 2);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        $check_rest = PasswordReset::get_DataEmailTime('email', $email, 1);
        if ($check_rest == 1) {
            $user = User::foundUser($email, 'email', 0);
            if (!empty($user) && isset($user->password) && isset($user->id)) {
                //update new_password  
                $new_password = bcrypt($password);
                User::where('id', $user->id)->update(['password' => $new_password]);
                $data_user = User::SelectCoulumUser($user);
                $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang, 0);
                $response['data'] = $data_user;
                return response()->json($response, 200);
            } else {
                $response = API_Controller::MessageData('EMAIL_NOT_EXIST', $lang, 14);
                return response()->json($response, 400);
            }
        } else {
            $response = API_Controller::MessageData('link_expired', $lang, 61);
            return response()->json($response, 400);
        }
    }

}
