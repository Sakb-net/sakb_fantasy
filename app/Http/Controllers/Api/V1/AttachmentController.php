<?php

namespace App\Http\Controllers\Api\V1;

//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\API_Controller;
use App\Models\User;

class AttachmentController extends API_Controller {
    /**
     * upload image   
     * get method
     * url : https://localhost:8000/api/v1/uploadImage
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/uploadImage",
     *   tags={"upload"},
     *   operationId="uploadImage",
     *   summary="upload image by using String Based46 ",
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
     *    name="image",
     *    in= "query",
     *    required=true,
     *      @OA\Schema(
     *           type="string"
     *      ),
     *    description="String Based46 of uploadImage",
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
    public function uploadImage(Request $request) {
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
        $image = isset($input['image']) ? $input['image'] : '';
        $response = [];
        $fields = [];
        if ($image == "") {
            $fields['image'] = 'image';
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang,2);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        if (!empty($access_token)) {
            $data_user = User::user_access_token($access_token, 1);
            if (isset($data_user->id)) {
                $name = generateRandomToken() . ".png";
                $path = 'uploads/photos/' . $name;
                if (file_put_contents($path, base64_decode($image))) {
                    $default_server = 'https://' . $_SERVER['SERVER_NAME'];
                    $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
                    $response['data'] = $default_server .'/'. $path;
                    return response()->json($response, 200);
                } else {
                    $response = API_Controller::MessageData('ERROR_MESSAGE', $lang,1);
                    return response()->json($response, 400);
                }
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
     * upload imagefile   
     * get method
     * url : https://localhost:8000/api/v1/uploadImageFile
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/uploadImageFile",
     *   tags={"upload"},
     *   operationId="uploadImageFile",
     *   summary="upload image by uploading image from your computer ",
     * @OA\Parameter(
     *    name="access-token",
     *    in= "header",
     *    required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *  ),
     *  @OA\Parameter(
     *     name="type-dev",
     *     in="header",
     *     required=true,
     *     description="ios or android",
    *      @OA\Schema(
     *           type="string",
     *           default="ios" 
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
     *    name="image",
     *    in= "query",
     *    required=true,
     *      @OA\Schema(
     *           type="file",
     *      ),
     *    description="file uploadImage",
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
    public function uploadImageFile(Request $request) {
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
//          print_r($_FILES['image']);die;
//        $image = isset($input['image']) ? $input['image'] : '';
        $image = isset($_FILES['image']) ? $_FILES['image'] : '';
        $response = [];
        $fields = [];
        if ($image == "") {
            $fields['image'] = 'image';
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang,2);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        if (!empty($access_token)) {
            $data_user = User::user_access_token($access_token, 1);
            if (isset($data_user->id)) {
//                $name = generateRandomToken() . ".png";
//                $path = 'uploads/' . $name;

                $media = '';
                $mediaFilename = '';
                $mediaName = '';

                if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {   //&& empty($mediaFilename)
                    $fileInfo = array(
                        'file' => $_FILES["image"]["tmp_name"],
                        'name' => $_FILES['image']['name'],
                        'size' => $_FILES["image"]["size"],
                        'type' => $_FILES["image"]["type"],
                        'types' => 'png,jpg,jpeg,gif,PNG,JPG,JPEG,GIF'
                    );
                    $media = Wo_ShareFile($fileInfo);
                    if (!empty($media)) {
                        $mediaFilename = $media['filename'];
                        $mediaName = $media['name'];
                        $path = 'https://' . $_SERVER['SERVER_NAME'].'/' . $mediaFilename;
                        $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
                        $response['name'] = $mediaName;
                        $response['data'] = $path;
                        return response()->json($response, 200);
                    } else {
                        $response = API_Controller::MessageData('ERROR_MESSAGE', $lang,1);
                        return response()->json($response, 400);
                    }
                } else {
                    $response = API_Controller::MessageData('NO_DATA_FOUND', $lang,15);
                    return response()->json($response, 400);
                }
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
     * upload video   
     * get method
     * url : https://localhost:8000/api/v1/uploadVideo
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/uploadVideo",
     *   tags={"upload"},
     *   operationId="uploadVideo",
     *   summary="upload video by using String Based46 ",
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
     *    name="video",
     *    in= "query",
     *    required=true,
     *      @OA\Schema(
     *           type="string"
     *      ),
     *    description="String Base46 of uploadVideo",
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
    public function uploadVideo(Request $request) {
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
        $video = isset($input['video']) ? $input['video'] : '';
        $response = [];
        $fields = [];
        if ($video == "") {
            $fields['video'] = 'video';
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang,2);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        if (!empty($access_token)) {
            $data_user = User::user_access_token($access_token, 1);
            if (isset($data_user->id)) {
                $name = generateRandomToken();
                $video_path = 'uploads/videos/' . $name . ".mp4";
                if (file_put_contents($video_path, base64_decode($video))) {
                    $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
                    $response['data'] = 'https://' . $_SERVER['SERVER_NAME'] .'/'. $video_path;
                    return response()->json($response, 200);
                } else {
                    $response = API_Controller::MessageData('ERROR_MESSAGE', $lang,1);
                    return response()->json($response, 400);
                }
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
     * upload audio   
     * get method
     * url : https://localhost:8000/api/v1/uploadAudio
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/uploadAudio",
     *   tags={"upload"},
     *   operationId="uploadAudio",
     *   summary="upload audio by using String Based46 ",
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
     *    name="audio",
     *    in= "query",
     *    required=true,
     *      @OA\Schema(
     *           type="string"
     *      ),
     *    description="String Base46 of uploadAudio",
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
    public function uploadAudio(Request $request) {
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
        $audio = isset($input['audio']) ? $input['audio'] : '';
        $response = [];
        $fields = [];
        if ($audio == "") {
            $fields['audio'] = 'audio';
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang,2);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        if (!empty($access_token)) {
            $data_user = User::user_access_token($access_token, 1);
            if (isset($data_user->id)) {
                $name = generateRandomToken() . ".m4a";
                $path = 'uploads/sounds/' . $name;
                if (file_put_contents($path, base64_decode($audio))) {
                    $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
                    $response['data'] = 'https://' . $_SERVER['SERVER_NAME'].'/' . $path;
                    return response()->json($response, 200);
                } else {
                    $response = API_Controller::MessageData('ERROR_MESSAGE', $lang,1);
                    return response()->json($response, 400);
                }
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
     * upload imagefile   
     * get method
     * url : https://localhost:8000/api/v1/uploadImageFile
     *
     * @return response Json
     */

    /**
     * @OA\Post(
     *   path="/uploadAudioFile",
     *   tags={"upload"},
     *   operationId="uploadAudioFile",
     *   summary="upload audio by uploading audio from your computer",
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
     *    name="audio",
     *    in= "query",
     *    required=true,
     *      @OA\Schema(
     *           type="file",
     *      ),
     *    description="file uploadAudio",
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
    public function uploadAudioFile(Request $request) {
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
        $audio = isset($_FILES['audio']) ? $_FILES['audio'] : '';
        $response = [];
        $fields = [];
        if ($audio == "") {
            $fields['audio'] = 'audio';
        }
        if (!empty($fields)) {
            $response = API_Controller::MessageData('MISSING_FIELD', $lang,2);
            $response['data'] = $fields;
            return response()->json($response, 400);
        }
        if (!empty($access_token)) {
            $data_user = User::user_access_token($access_token, 1);
            if (isset($data_user->id)) {
//                $name = generateRandomToken() . ".png";
//                $path = 'uploads/sounds/' . $name;

                $media = '';
                $mediaFilename = '';
                $mediaName = '';

                if (isset($_FILES['audio']['name']) && !empty($_FILES['audio']['name'])) {   //&& empty($mediaFilename)
                    $fileInfo = array(
                        'file' => $_FILES["audio"]["tmp_name"],
                        'name' => $_FILES['audio']['name'],
                        'size' => $_FILES["audio"]["size"],
                        'type' => $_FILES["audio"]["type"],
                        'types' => 'mp3,wav,m4a'            //'mp4,m4v,webm,flv,mov,mpeg'
                    );
                    $media = Wo_ShareFile($fileInfo);
                    if (!empty($media)) {
                        $mediaFilename = $media['filename'];
                        $mediaName = $media['name'];
                        $path = 'https://' . $_SERVER['SERVER_NAME'].'/' . $mediaFilename;
                        if (!empty($path)) {
                            $response = API_Controller::MessageData('SUCCESS_MESSAGE', $lang,0);
                            $response['name'] = $mediaName;
                            $response['data'] = $path;
                            return response()->json($response, 200);
                        } else {
                            $response = API_Controller::MessageData('ERROR_MESSAGE', $lang,1);
                            $response['data'] = 'Upload Path Not Found';
                            return response()->json($response, 400);
                        }
                    } else {
                        $response = API_Controller::MessageData('ERROR_MESSAGE', $lang,1);
                        return response()->json($response, 400);
                    }
                } else {
                    $response = API_Controller::MessageData('NO_DATA_FOUND', $lang,15);
                    return response()->json($response, 400);
                }
            } else {
                $response = API_Controller::MessageData('USER_NOT_Found', $lang,11);
                return response()->json($response, 401);
            }
        } else {
            $response = API_Controller::MessageData('ACCESSTOKEN_NOT_Found', $lang,41);
            return response()->json($response, 401);
        }
    }

//***************************

    public static function uploadImageUser($image, $user_id, $column_name = 'image') {
        $user = User::GetByColumValue('id', $user_id, 1)->first();
        if (isset($user->id)) {
            $name = generateRandomToken() . ".png";
            $path = 'uploads/' . $name;
            if (file_put_contents($path, base64_decode($image))) {
                //save path to column
                $path_image = 'https://' . $_SERVER['SERVER_NAME'] .'/'. $path;
                $updated = User::updateColum($user_id, $column_name, $path_image, 0);
                if ($updated)
                    return $path_image;
                else
                    return "";
            }else {
                return "";
            }
        } else {
            return "";
        }
    }

}
