<?php

namespace App\Http\Controllers\Api\V1;

//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\API_Controller;
use App\Http\Controllers\ClassSiteApi\Class_TransferController;
use App\Http\Controllers\ClassSiteApi\Class_PaymentController;
use App\Models\User;
use App\Models\Order;

class PaymentCardController extends API_Controller {
    /**
     * check register in cart
     * get method
     * url : http://localhost:8000/api/v1/payment/card.
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/api/v1/payment/card",
     *   tags={"substitute"},
     *   operationId="payment_card",
     *   summary="payment for card",
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
     *    name="access-token",
     *    in= "header",
     *    required=true,
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="type_card",
     *    in= "query",
     *      @OA\Schema(
     *           type="string",
     *           default="card_gold"
     *      ),
     *    description="card_gold ",
     *  ),
     * @OA\Parameter(
     *    name="array_players",
     *    in= "query",
     *    required=true,
     *      @OA\Schema(
     *           type="string"
     *      ),
     *     description="[{newplayer_id:272,substituteplayer_id:562,substituteplayer_cost:5},{newplayer_id:345,substituteplayer_id:272,substituteplayer_cost:5}]"
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

    //Ex: array_players=[{"newplayer_id":272,"substituteplayer_id":562,"substituteplayer_cost":5},{"newplayer_id":345,"substituteplayer_id":272,"substituteplayer_cost":5}]
    public function payment_card(Request $request) {
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
        $type_card = isset($input['type_card']) ? $input['type_card'] : 'card_gold';
        $array_players = isset($input['array_players']) ? $input['array_players'] : [];
        $array_players=json_decode($array_players,true);
        $response = $fields =[];
        if (empty($array_players)) {
            $fields['array_players'] = 'array_players';
        } else {
            if (!is_array($array_players)) {
                $fields['array_players'] = 'Not Array array_players';
            }
        }
        if ($type_card == '' || !in_array($type_card, ['card_gold'])) {
            $fields['type_card'] = 'type_card';
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang, 2);
            $response['data'] = $fields;

            return response()->json($response, 400);
        }
        $user = User::user_access_token($access_token, 1);
        if (isset($user->id)) {
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            $get_data=new Class_TransferController();
            $ok_substitutes_points=$get_data->game_substitutePlayer_api($user->id,$array_players,0,1);
            $response['data'] = ['checkoutId' => '','shopperResultUrl'=>''];
            if($ok_substitutes_points==1){
                $get_payment=new Class_PaymentController();
                $array_payment = $get_payment->Payment_Order_CardSubDwry($user,$type_card,0,$type_dev,'hyperpay',12,$lang,1);
                if ($array_payment['ok_chechout'] == 1) {
                    $response['data'] = ['checkoutId' =>$array_payment['checkoutId'],'shopperResultUrl'=>$array_payment['shopperResultUrl']];
                } else {
                    if ($array_payment['ok_chechout'] == -1) {
                        $response = API_Controller::MessageData('ERROR_Payment', $lang, 44);
                        return response()->json($response, 400);
                    }
                }
            }else{
                $response = API_Controller::MessageData('ERROR_MESSAGE', $lang, 1);
                return response()->json($response, 400);
            }
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }

    /**
     * check register in cart
     * get method
     * url : http://localhost:8000/api/v1/confirmPayment/card.
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/api/v1/confirmPayment/card",
     *   tags={"substitute"},
     *   operationId="confirmPayment_card",
     *   summary="confirm Payment of card for substitute",
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
     *    name="access-token",
     *    in= "header",
     *    required=true,
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="resourcePath",
     *    in= "query",
     *    required=true,
     *    description="resourcePath from url callback from hyper",
     *      @OA\Schema(
     *           type="string"
     *      ),
     *  ),
     * @OA\Parameter(
     *    name="checkout_id",
     *    in= "query",
     *    required=true,
     *    description="checkout_id from url callback from hyper",
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
    public function confirmPayment_card(Request $request) {
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
        $resourcePath = isset($input['resourcePath']) ? $input['resourcePath'] : '';
        $checkout_id = isset($input['checkout_id']) ? $input['checkout_id'] : '';
        $response =$fields = [];
        if ($access_token == "") {
           $fields['access_token'] = 'access-token';
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
            $response['data'] = $fields;
            return response()->json($response, 401);
        }
        if ($resourcePath == '') {
            $fields['resourcePath'] = 'resourcePath';
        }
        if ($checkout_id == '') {
            $fields['checkout_id'] = 'checkout_id';
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang, 2);
            $response['Message'] = API_Controller::MISSING_FIELD;
            $response['data'] = $fields;

            return response()->json($response, 400);
        }
        $user = User::user_access_token($access_token, 1);
        if (isset($user->id)) {
            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
            $get_data = new Class_PaymentController();
            $response['data'] = $get_data->Payment_Callback_CardSubDwry($user,$checkout_id, $resourcePath,$lang,1);
            return response()->json($response, 200);
        } else {
            $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
            return response()->json($response, 401);
        }
    }

}
