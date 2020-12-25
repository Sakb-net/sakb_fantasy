<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SiteController;
use App\Models\User;
use App\Http\Controllers\ClassSiteApi\Class_TransferController;
use App\Http\Controllers\ClassSiteApi\Class_PaymentController;

class AjaxTransferController extends SiteController {

    public function __construct() {
        parent::__construct();
        if (isset(Auth::user()->id)) {
            $this->current_id = Auth::user()->id;
            $this->user_key = Auth::user()->name;
        }
    }

//*************************************************************
    public function GetDataPlayer_MasterTransfer(Request $request) {
        if ($request->ajax()) {
            $response = 1;
            $lang = $this->lang;
            $num_player = $this->num_player;
            $current_id = Auth::user()->id;
            $get_data=new Class_TransferController();
            $array_data=$get_data->GetDataPlayer_MasterTransfer($current_id,$num_player,$lang,$request->val_view,0);

            if ($request->val_view == 1 || $request->val_view == '1') {
                $response = view('site.games.side_table', $array_data)->render();
                return response()->json(['response' => $response]);
            } else {
                return response()->json(['response' => $response, 'player_master' => $array_data['player_master'],'order_lineup'=>$array_data['order_lineup'],'current_lineup'=>$array_data['current_lineup']]);
            }
        }    
    }
    public function game_substitutePlayer(Request $request) {
        if ($request->ajax()) {
            $response = 1;
            $lang = $this->lang;
            $current_id = Auth::user()->id;
            foreach ($request->input() as $key => $value) {
                $$key = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            $get_data=new Class_TransferController();
            $array_data=$get_data->game_substitutePlayer($current_id,$player_link,$pay_total_cost,0);
            $array_data['response']=$response;  
            return response()->json($array_data);
        }
    }

    public function get_substitutePlayer(Request $request) {
        if ($request->ajax()) {
            $response = 1;
            $lang = $this->lang;
            $current_id = Auth::user()->id;
            $get_data=new Class_TransferController();
            $array_data=$get_data->get_substitutePlayer($current_id,0,0);
            if(count($array_data)<=0){
                $response = 0;
            }
            return response()->json(['response'=>$response,'data_players'=>$array_data]);
        }    
    }  
    public function confirm_substitutePlayer(Request $request) {
        if ($request->ajax()) {
            $lang = $this->lang;
            $current_id = Auth::user()->id;
            $get_data=new Class_TransferController();
            $array_data['response']=1;
            $array_data['substitutes_points']=$get_data->confirm_substitutePlayer($current_id,0,0);
            $array_data['msg_add']=trans('app.add_scuss');
            return response()->json($array_data);
        }    
    }  

    public function confirm_cardgray(Request $request) {
        if ($request->ajax()) {
            $lang = $this->lang;
            $current_id = Auth::user()->id;
            $get_data=new Class_TransferController();
            $array_data=$get_data->status_card($current_id,'gray',0);
            return response()->json($array_data);
        }    
    }  

    public function confirm_cardgold(Request $request) {
        if ($request->ajax()) {
            $get_payment=new Class_PaymentController();
           $array_data = ['ok_chechout' => 0];//$get_payment->Payment_Order_CardSubDwry(Auth::user(),'card_gold',0,'site','hyperpay',12,$this->lang, 0);
            // $array_data=$get_data->status_card($current_id,'gold',0);
            return response()->json($array_data);
        }    
    }      

}