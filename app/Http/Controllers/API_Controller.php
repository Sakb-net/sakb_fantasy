<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
//use App\Models\Apimessage;
use App;
//https://swagger.io/docs/specification/2-0/describing-parameters/

class API_Controller extends BaseController {
    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="SAKBFANTASY",
     *      description="Api SAKBFANTASY Documentation",
     *      @OA\Contact(
     *          email="info@sakb.com"
     *      ),
     *      @OA\License(
     *          name="Sakb",
     *          url="http://sakb-co.com.sa/"
     *      )
     * )
     *

     *
     *
     */
    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    public static function AuthAPI($type = 'ios', $value = '') {
        $data_ok = 0;
        if ($_SERVER['SERVER_NAME'] == '127.0.0.1') {
            $data_ok = 1;
        } else {
//        if ($type == 'ios' && $value == 'b1213s19') {
            if ($type == 'ios' && $value == 'I$&h6#565iOs5ioS#(*I$&h6#565iOs5ioS#') {
                $data_ok = 1;
            } elseif ($type == 'android' && $value == 'A$&h6#565aN$DrOiD#(*I$&h6#aN$&*rOiD#') {
                $data_ok = 1;
            }
        }
        return $data_ok;
    }
    public static function get_DataHeader($data) {
        $access_token = isset($data['Access-Token']) ? $data['Access-Token'] : '';
        if (empty($access_token)) {
            $access_token = isset($data['access-token']) ? $data['access-token'] : '';
        }
        $lang = isset($data['lang']) ? $data['lang'] : '';
        if (empty($lang)) {
            $lang = isset($data['Lang']) ? $data['Lang'] : '';
        }
        $lang = !empty($lang) ? $lang : 'ar';
        App::setLocale($lang);
        $type_dev = isset($data['type-dev']) ? $data['type-dev'] : '';
        $val_dev = isset($data['val-dev']) ? $data['val-dev'] : '';
        if (empty($val_dev)) {
            $val_dev = isset($data['Val-Dev']) ? $data['Val-Dev'] : '';
        }
        if (empty($type_dev)) {
            $type_dev = isset($data['Type-Dev']) ? $data['Type-Dev'] : '';
        }
        $type_dev = !empty($type_dev) ? $type_dev : 'ios';

        return array('type_dev' => $type_dev, 'val_dev' => $val_dev,
            'access_token' => $access_token, 'lang' => $lang);
    }

    public static function MessageData($type, $lang = 'ar', $StatusCode = 0) {
        $lang = !empty($lang) ? $lang : 'ar';
        App::setLocale($lang);
        $response['StatusCode'] = $StatusCode;
        $response['Message'] = trans('app.' . $type);
        $response['data'] = null;
        return $response;
    }

    public static function MessageData_old($type, $lang = 'ar') {
        $message = Apimessage::get_messageData($type, 'type');
        $col_name = $lang . '_message';
        if (isset($message->$col_name)) {
            $response['StatusCode'] = $message->id;
            $response['Message'] = $message->$col_name;
        } else {
            $response['StatusCode'] = 0;
            $response['Message'] = null;
        }
        $response['data'] = null;
        return $response;
    }

    private $_status_codes = [
        0 => "Success",
        1 => "Error",
        2 => "Missing Fields",
        3 => "Invalid Email",  //change
        4 => "Error Language",
        5 => "Error Page",
        6 => "Error URL",
        7 => "Error Field Data",
        8 => "Email or Password incorrect",
        9 => "User Name Already Exist",
        10 => "User Name Not Exist",
        11 => "User Not Found",
        12 => "Email Already Exist",
        13 => "Phone Already Exist",
        14 => "Email Not Exist",
        15 => "No Data Found",
        16 => "Login Faild",
        17 => "Email Send Error",
        18 => "User Found",
        19 => "New Password Match Old Password",
        20 => "Invalid Phone",
        21 => "Not saved",
        22 => "Invalid Email",
        23 => "Email send Before",
        24 => "Delete Success",
        25 => "Not delete",
        26 => "Not Save Image",
        27 => "New Password Not Match old Password",
        28 => 'Cart Not Free',
        29 => 'Cart Free',
        30 => "This account closed please communicate with the site management",
        31 => 'Password is weak',
        32 => 'Not data Register',
        33 => 'Register Not Complete',
        34 => 'Register and Share',
        35 => "Register and Not Share",
        37 => 'Not Save Video',
        39 => 'Invalid Data',
        40 => 'No Cart Found',
        41 => 'No access token Found',
        44 => 'Fail Payment',
        46 => 'Not Match Name Cart',
        47 => 'Section Not Found',
        48 => 'Not Owner',
        49=>'Not Found Payment Method',
    ];

    protected function responseSuccess($message = '') {
        return $this->response(true, $message);
    }

    protected function responseFail($message = '') {
        return $this->response(false, $message);
    }

    protected function response($status = false, $message = '',$data='') {
        return response()->json([
                    'StatusCode' => $status,
                    'Message' => $message,
                    'data' => $message,
        ]);
    }

}
