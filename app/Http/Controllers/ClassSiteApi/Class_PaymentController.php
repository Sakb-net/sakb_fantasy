<?php

namespace App\Http\Controllers\ClassSiteApi;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Subeldwry;
use App\Models\PriceCard;
use App\Models\GameSubstitutes;
use App\Models\Order;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\ClassSiteApi\Class_TransferController;
use App\Http\Controllers\ClassSiteApi\Class_PageGameController;
use App\Http\Controllers\ClassSiteApi\Class_NotifController;


class Class_PaymentController extends SiteController {

    public function __construct() {
        parent::__construct();
         //*************** live *******************
        // $this->url_pay_hyper = 'https://oppwa.com/v1/paymentWidgets.js?checkoutId=';
        // $this->url = "https://oppwa.com/v1/checkouts";
        // $this->entityId_pay = '8acda4ce6a9c168f016ab6a988380051';
        // $this->access_token_pay = 'OGFjZGE0Y2U2YTljMTY4ZjAxNmFiNmE5MWU5MTAwNDh8ckFhYkU4TlpBUg==';
        // $this->testMode = '';

         //***************test *******************
        $this->url_pay_hyper = 'https://test.oppwa.com/v1/paymentWidgets.js?checkoutId=';
        $this->url = "https://test.oppwa.com/v1/checkouts";
        $this->entityId_pay = '8ac7a4ca6a1c1fa8016a202f416c02bc';
        $this->access_token_pay = 'OGFjN2E0Y2E2YTFjMWZhODAxNmEyMDJlZWVkMTAyYjJ8MnRkdGt6Z0VobQ==';
        $this->testMode = 'EXTERNAL';
    }

    function Message_ResultPay($request='', $type = 0, $api = 0) {
        $mesage_pay = $back_color = null;
        if ($type == 1) {
            $back_color = '#87d667'; //'green';
            $mesage_pay = 'تم الدفع واستبدال الاعبين بنجاح نتمنى لك الاستمتاع معنا';
        } elseif ($type == 2) {
            $back_color = '#ec4f4f'; //;'red';
            $mesage_pay = 'فشلت عمليت الدفع يرجى التاكد من حسابك و اعادة المحاولة';
        } elseif ($type == 3) {
            $back_color = '#78acc5';
            $mesage_pay = 'يرجى اعادة اختيار الاعبين واجراء عملية الدفع مرة أخرى'; //
        } elseif ($type == 5) {
            $back_color = '#ec4f4f';
            $mesage_pay = 'يرجى اعادة الدفع مرة اخرى نظرا لحدوث خطا ما'; //
        } else {
            $back_color = '#d1d68c';
            $mesage_pay = 'يرجى اعادة اختيار الاعبين واعادة المحاولة';
        }
        return array('mesage_pay' => $mesage_pay, 'back_color' => $back_color);
    }

    function Payment_Order_CardSubDwry($current_user,$type_card,$discount=0,$source_pay='site',$method_pay='hyperpay',$type_id = 12,$lang='ar', $api = 0) {
        $array_data = ['ok_chechout' => 0];
        $subeldwry=Subeldwry::get_CurrentSubDwry();
        if(isset($subeldwry->id)){
            $current_substitute = GameSubstitutes::Usersub_eldwry($current_user->id,$subeldwry->id,0);
            if(isset($current_substitute->id)){
                $cost = PriceCard::get_priceCardSubeldwry($current_user,$subeldwry->id,'cost');
                $array_data=$this->Payment_Order($current_user,$subeldwry->id,$cost,$discount,$source_pay,$method_pay,$type_id,$type_card,$current_substitute->code_substitute, $api);
            }
        }
        return $array_data;
    }

    function Payment_Callback_CardSubDwry($current_user,$checkoutId, $resourcePath,$lang='ar', $api = 0) {
        $order = Order::get_DataByThreeCondition('user_id',$current_user->id,'checkoutId',$checkoutId,'is_active', 1);
        $res_response=['ok_pay'=>0];
        if(!isset($order->id)){
            $res_response = $this->payment_CallBack('',$current_user, $checkoutId, $resourcePath,$api);
        }else{
            $res_response=$this->Message_ResultPay('',1,0);
            $res_response['ok_pay']=1;
        }
        if ($res_response['ok_pay'] == 1) {
            //ok payment and confirm substitute Player
            $get_result= new Class_TransferController();
            $get_result->confirm_substitutePlayer($current_user->id,0,0,0,1);
        } else {
            //fail payment
            $get_result= new Class_PageGameController();
            $get_result->BeforeEnter_GameTransfer($current_user);
        }
        return $res_response;    
    }
//***************************Payment Order **********************
    function Payment_Order($current_user,$sub_eldwry_id,$cost,$discount=0,$source_pay='site',$method_pay='hyperpay',$type_id = 12,$data_name=null,$code_substitute=null, $api = 0) {
        $array_data = ['ok_chechout' => 0];
        if ($method_pay == 'hyperpay') {
            $array_data = $this->Hyperpay_Payment_Order($current_user,$sub_eldwry_id,$cost,$discount,$source_pay,$method_pay,$type_id,$data_name,$code_substitute,$api);
        } else {
            //by_hand
            $array_data = $this->By_hand_Payment_Order($current_user,$sub_eldwry_id,$cost,$discount,$source_pay,$method_pay,$type_id,$data_name,$code_substitute, $api);
        }
        return $array_data;
    }
    
    function Hyperpay_Payment_Order($current_user,$sub_eldwry_id,$total_cost,$discount=0,$source_pay='site',$method_pay='hyperpay',$type_id = 12,$data_name=null,$code_substitute=null,$api = 0) {
        $ok_chechout = 0;
        if (!empty($total_cost) && $total_cost > 0) {
            $testMode =$this->testMode;
            $url_pay_hyper = $this->url_pay_hyper;
            $merchantTransactionId = $current_user->id . time() . rand(100, 99999);
            $customer_email = $current_user->email;
            $billing_street1 = 'alrowad street'; //'street address of customer' ;         
            $billing_city = 'riyadh'; //'should be city of customer';          
            $billing_state = 'alrowad'; //'should be state of customer' ;  
            if (!empty($current_user->city)) {
                $billing_city = $current_user->city;
            }
            if (!empty($current_user->state)) {
                $billing_state = $current_user->state;
            }
            $country = 'SA'; //AE
            if (!empty($current_user->address)) {
                $country = $current_user->address;
            }
            $billing_country = $country; //'Saudi Arabia';  //' should be country of customer  (Alpha-2 codes with Format A2[A-Z]{2})';
            $billing_postcode = '123456';
            $customer_givenName = $current_user->display_name;
            $customer_surname = $current_user->display_name;
        //************
            $url = $this->url;
            $entityId_pay = $this->entityId_pay;
            $access_token_pay = $this->access_token_pay;
//Payment Methods: VISA, MASTER
// "&testMode=$testMode" . 
            $total_cost= round($total_cost,0);
            $data = "entityId=$entityId_pay" .
                "&amount=$total_cost" .
                "&currency=SAR" .
                "&paymentType=DB";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                           'Authorization:Bearer '.$access_token_pay));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $responseData = curl_exec($ch);
            if(curl_errno($ch)) {
                return curl_error($ch);
            }
            curl_close($ch);
            $array_response = json_decode($responseData);
            $ok_chechout = -1;
            if (isset($array_response->id)) {
                $ok_chechout = 1;
                $checkoutId = $array_response->id;
                if ($api == 1) {
                    $shopperResultUrl = route('home').'api/v1/confirmPayment/card';//route('confirmPayment');
                } else {
                    $shopperResultUrl = route('payment_card.callback'); // 'https://hyperpay.docs.oppwa.com/tutorials/integration-guide';
                }
                $insert_order = Order::insertOrder($current_user->id,$sub_eldwry_id,NULL, $type_id ,$data_name, $method_pay, $source_pay, $total_cost,$discount,$code_substitute,$checkoutId);
                $array_data = array('ok_chechout' => $ok_chechout, 'url_pay_hyper' => $url_pay_hyper, 'customer_givenName' => $customer_givenName, 'billing_postcode' => $billing_postcode, 'customer_surname' => $customer_surname,
                    'billing_country' => $billing_country, 'billing_state' => $billing_state, 'billing_city' => $billing_city,
                    'billing_street1' => $billing_street1, 'customer_email' => $customer_email,
                    'testMode' => $testMode, 'merchantTransactionId' => $merchantTransactionId,
                    'checkoutId' => $checkoutId, 'shopperResultUrl' => $shopperResultUrl);
            } else {
                $array_data = array('ok_chechout' => $ok_chechout);
            }
        } else {
            $array_data = array('ok_chechout' => $ok_chechout);
        }
        return $array_data;
    }

    function By_hand_Payment_Order($current_user,$sub_eldwry_id,$total_cost,$discount=0,$source_pay='admin',$method_pay='by_hand',$type_id = 12,$data_name=null,$code_substitute=null,$api = 0) {
        $ok_chechout = 0;
        if (!empty($total_price_cart) && $total_price_cart > 0) {
            $ok_chechout = 1;

            $insert_order = Order::insertOrder($user_id,$sub_eldwry_id,NULL, $type_id ,$data_name, $method_pay, $source_pay, $total_cost,$discount);

        }
        $array_data = array('ok_chechout' => $ok_chechout, 'checkoutId' => '');
        return $array_data;
    }

    function payment_CallBack($request, $current_user, $checkout_id, $resourcePath, $api = 0) {
        $array_data = array('mesage_pay' => '', 'back_color' => '', 'ok_pay' => 0);
        //$url = "https://test.oppwa.com" . $resourcePath; 
        $url = $this->url."/" . $checkout_id . "/payment"; 
        $url .= "?entityId=".$this->entityId_pay;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer '.$this->access_token_pay));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        $array_response = json_decode($responseData);
        // print_r($array_response);die;
        $ok = 0;
        $description = $code = null;
        if (isset($array_response->result->code)) {
            $code = $array_response->result->code; //'000.100.110';
            $description = $array_response->result->description;
            if (preg_match("/^(000\.000\.|000\.100\.1|000\.[36])/", $code)) { ///^([+]?)[0-9]{8,16}$/
                $ok = 1;
            } elseif (preg_match("/^(000\.400\.0[^3]|000\.400\.100)/", $code)) {
                $ok = 1;
            }
        }
        if ($ok == 1) { //(isset($array_response->risk->score) && $array_response->amount == $price_order && ($array_response->risk->score == 100 || $array_response->risk->score == "100")) {
            //active order
            $update = Order::All_checkout_UpdatePayment($current_user->id, $checkout_id, 1, 'hyperpay', 'accept', $description, $code);
            $array_data = $this->Message_ResultPay($request, 1, $api);
            $array_data['ok_pay'] = 1;
            //send notification
            $send_noti = new Class_NotifController();
            $send_noti->insert_NotifOrder($current_user, $checkout_id, $api);
        } else {
            //save decription of reason payment not compelet
            $update = Order::All_checkoutFail_UpdatePayment($current_user->id, $checkout_id, 0, $description);
            $array_data = $this->Message_ResultPay($request, 2, $api);
            $array_data['ok_pay'] = 0;
        }
        $array_data['reson_description'] = $description;
        return $array_data;
    }

///********************for test hyperpay****************
    function Get_checkOutID($current_user, $total_price_cart, $api = 0) { 
        $testMode = 'EXTERNAL';
        $merchantTransactionId = $current_user->id . time() . rand(100, 99999);
        $customer_email = $current_user->email;
        $billing_street1 = 'alrowad street'; //'street address of customer' ;         
        $billing_city = 'riyadh'; //'should be city of customer';          
        $billing_state = 'alrowad'; //'should be state of customer' ;  
        if (!empty($current_user->city)) {
            $billing_city = $current_user->city;
        }
        if (!empty($current_user->state)) {
            $billing_state = $current_user->state;
        }
        $country = 'SA'; //AE
        if (!empty($current_user->address)) {
            $country = $current_user->address;
        }
        $billing_country = $country; //'Saudi Arabia';  //' should be country of customer  (Alpha-2 codes with Format A2[A-Z]{2})';
        $billing_postcode = '1234';
        $customer_givenName = $current_user->display_name;
        $customer_surname = $current_user->display_name;
        //************
        $ok_chechout = 0;
        if (!empty($total_price_cart) && $total_price_cart > 0) {
            $url = "https://test.oppwa.com/v1/checkouts";
//            $data = "entityId=8ac7a4ca6a1c1fa8016a202f416c02bc" .
//                    "&amount=$total_price_cart" .
//                    "&currency=SAR" .
//                    "&paymentType=DB";
            $total_price_cart = round($total_price_cart, 0);
            $data = "entityId=8ac7a4ca6a1c1fa8016a202f416c02bc" .
                    "&amount=$total_price_cart" .
                    "&currency=SAR" .
                    "&paymentType=DB" .
                    "&testMode=$testMode" .
                    "&merchantTransactionId=$merchantTransactionId" .
                    "&customer.email=$customer_email" .
                    "&billing.street1=$billing_street1" .
                    "&billing.city=$billing_city" .
                    "&billing.state=$billing_state" .
                    "&billing.country=$billing_country" .
                    "&billing.postcode=$billing_postcode" .
                    "&customer.givenName=$customer_givenName" .
                    "&customer.surname=$customer_surname";
            // print_r($data);die;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization:Bearer OGFjN2E0Y2E2YTFjMWZhODAxNmEyMDJlZWVkMTAyYjJ8MnRkdGt6Z0VobQ=='));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $responseData = curl_exec($ch);
            if (curl_errno($ch)) {
                return curl_error($ch);
            }
            curl_close($ch);
            //return $responseData;
            //print_r($responseData);die;
            $array_response = json_decode($responseData);
            //print_r($array_response);die;
            $ok_chechout = 0;
            if (isset($array_response->id)) {
                $ok_chechout = 1;
                $checkoutId = $array_response->id;

                //end cart
                $array_data = array('ok_chechout' => $ok_chechout, 'customer_givenName' => $customer_givenName, 'billing_postcode' => $billing_postcode, 'customer_surname' => $customer_surname,
                    'billing_country' => $billing_country, 'billing_state' => $billing_state, 'billing_city' => $billing_city,
                    'billing_street1' => $billing_street1, 'customer_email' => $customer_email,
                    'testMode' => $testMode, 'merchantTransactionId' => $merchantTransactionId,
                    'checkoutId' => $checkoutId);
            } else {
                $array_data = array('ok_chechout' => $ok_chechout);
            }
        } else {
            $array_data = array('ok_chechout' => $ok_chechout);
        }
        return $array_data;
    }

}
