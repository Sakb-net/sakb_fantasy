<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\API_Controller;
use App\Http\Controllers\ClassSiteApi\Class_CommentController;
use App\Models\Draft;

class DraftController extends API_Controller
{
    
       /**
     * get user draft
     * get method
     * url : http://localhost:8000/api/v1/checkDraft
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/api/v1/checkDraft",
     *   tags={"checkDraft"},
     *   operationId="checkDraft",
     *   summary="check user draft",
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
    public function checkDraft(Request $request) {
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

        $user_id = 0;
        if (!empty($access_token)) {
            $data_user = User::user_access_token($access_token, 1);
            if (isset($data_user->id)) {
                $user_id = $data_user->id;
                $checkDraft = DraftUsers::checkUserDraft(Auth::user()->id);
                $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang);
                $response['data'] = $checkDraft;
                return response()->json($response, 200);


                $draftUser = DraftUsers::selectUserDraft(Auth::user()->id);
                $draft = Draft::selectDraft($draftUser->draft_id);
                

            } else {
                $response = API_Controller::MessageData('USER_NOT_Found', $lang);
                return response()->json($response, 400);
            }
        }


    }

}
