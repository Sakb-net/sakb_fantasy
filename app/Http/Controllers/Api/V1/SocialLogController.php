<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\API_Controller;
use Illuminate\Validation\ValidationException;
use App\Repositories\SocialLoginRepository;
use Socialite;

class SocialLogController extends API_Controller
{

    private $SocialLoginRepository;
    public function __construct(SocialLoginRepository $SocialLoginRepository)
    {
        $this->SocialLoginRepository = $SocialLoginRepository;
    }
    
    /**
     * Handle an API login by social (twitter,google).
     * @param SocialRequest $request
     * @return array|void
     * @throws \Illuminate\Validation\ValidationException
     */
     /**
     * @OA\Post(
     *   path="/api/v1/social/login",
     *   tags={"auth"},
     *   operationId="/social/login",
     *   summary="social login by (google,twitter)",
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
     *    name="provider",
     *    in= "query",
     *    required=true,
     *    description="name social login (google,twitter,facebook)",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     *  @OA\Parameter(
     *    name="oauth_token",
     *    in= "query",
     *    description="oauth_token with each provider",
     *      @OA\Schema(
     *           type="string",
     *           default="oauth_token"
     *      ),
     *  ),
     *  @OA\Parameter(
     *    name="oauth_token_secret",
     *    in= "query",
     *    description="oauth_token_secret send only with twitter",
     *      @OA\Schema(
     *           type="string",
     *           default="oauth_token_secret"
     *      ),
     *  ),
     *  @OA\Parameter(
     *    name="best_team",
     *    in= "query",
     *    description="best_team",
     *      @OA\Schema(
     *           type="string",
     *           default=""
     *      ),
     *  ),
     *  @OA\Parameter(
     *    name="device_id",
     *    in= "query",
     *    description="device_id",
     *      @OA\Schema(
     *           type="string",
     *           default="123"
     *      ),
     *  ),
     *  @OA\Parameter(
     *    name="fcm_token",
     *    in= "query",
     *    description="fcm_token",
     *      @OA\Schema(
     *           type="string",
     *           default=""
     *      ),
     *  ),
     *   @OA\Response(
     *    response=200,
     *    description="created successfuly",
     *   ),
     *   @OA\Response(
     *    response=422,
     *    description="validation error",
     *   ),
     * )
     */
    public function findorCreate(Request $request)
    {   $data_header = API_Controller::get_DataHeader(getallheaders());
        $access_token = $data_header['access_token'];
        $val_dev = $data_header['val_dev'];
        $type_dev = $data_header['type_dev'];
        $lang = $data_header['lang'];
        $data_ok = API_Controller::AuthAPI($type_dev, $val_dev);
        if (empty($data_ok) || $data_ok == 0) {
            $response = API_Controller::MessageData('AUTH_NOTALLOW', $lang, 49);
            return response()->json($response, 400);
        }
        ///
        $provider = $request->provider;
        try {
            if($provider=='twitter'){
                $userSocial = Socialite::driver($provider)->userFromTokenAndSecret($request->oauth_token, $request->oauth_token_secret);
            }else{
                $userSocial = Socialite::driver($provider)->userFromToken($request->access_token);
            }
        } catch (\Throwable $th) {
            $response['StatusCode'] = 3;
            $response['Message'] = $th->getMessage();
            $response['Data'] = '';
            return response()->json($response, 401);
        }
        $device_id=null;
        if (isset($request->device_id)) {
            $device_id=$request->device_id;
        }
        $best_team=null;
        if (isset($request->best_team)) {
            $best_team=$request->best_team;
        }
        $fcm_token=null;
        if (isset($request->fcm_token)) {
            $fcm_token=$request->fcm_token;
        }
        try {
            $response=$this->SocialLoginRepository->SocialLogin($provider,$userSocial,$device_id,$fcm_token,$type_dev,$best_team,$lang,1);
            return response()->json($response, 200);
        } catch (\Throwable $th) {
            $response['StatusCode'] = 3;
            $response['Message'] = $th->getMessage();
            $response['Data'] = '';
            return response()->json($response, 401);
        }
    }
 
}
