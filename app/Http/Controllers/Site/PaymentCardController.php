<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Order;
use Hash;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\ClassSiteApi\Class_PaymentController;
use App\Http\Controllers\ClassSiteApi\Class_TransferController;
use App\Http\Controllers\ClassSiteApi\Class_PageGameController;

class PaymentCardController extends SiteController {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        if ($this->site_open == 1 || $this->site_open == "1") {
            $lang=$this->lang;
            $page_title = trans('app.payment');
            $title = $page_title . " - " . $this->site_title;
            View::share('title', $title);
            View::share('activ_menu', 1);
            $array_best_team=User::BestTeam(Auth::user());
            $team_image_fav=$array_best_team['team_image_fav'];

            // $already_usePlayer=session()->get('already_usePlayer');
            // $array_substitutePlayer=session()->get('array_substitutePlayer');
            // print_r($already_usePlayer);
            // print_r($array_substitutePlayer);
            // die;
            $get_payment=new Class_PaymentController();
            $array_data = $get_payment->Payment_Order_CardSubDwry(Auth::user(),'card_gold',0,'site','hyperpay',12,$this->lang, 0);
            if ($array_data['ok_chechout'] == 1) {
                $array_data['page_title']=$page_title;
                $array_data['team_image_fav']=$team_image_fav;
                return view('site.payment.index', $array_data);
            } else {
                //there error in payment
                $back_color = '#79c5f1';
                $mesage_pay = trans('app.ERROR_Payment');
                $array_data = array('mesage_pay' => $mesage_pay, 'back_color' => $back_color,'page_title'=>$page_title,'team_image_fav'=>$team_image_fav);
                return view('site.payment.callback', $array_data);
            }

        } else {
            return redirect()->route('close');
        }
    }

    public function callback(Request $request) {
        $page_title = trans('app.payment');
        $title = $page_title . " - " . $this->site_title;
        View::share('title', $title);
        View::share('activ_menu', 1);
        $array_best_team=User::BestTeam(Auth::user());
        $team_image_fav=$array_best_team['team_image_fav'];
        $get_data = new Class_PaymentController();
        $res_response = $get_data->Payment_Callback_CardSubDwry(Auth::user(),$_REQUEST['id'], $_REQUEST['resourcePath'],$this->lang, 0);
        $res_response['page_title']=$page_title;
        $res_response['team_image_fav']=$team_image_fav;
        return view('site.payment.callback', $res_response);
    }

    //Ex:http://127.0.0.1:9000/payment/callback?id=671ABA08356B3AF4B4EBEC65842BEB31.uat01-vm-tx04&resourcePath=%2Fv1%2Fcheckouts%2F671ABA08356B3AF4B4EBEC65842BEB31.uat01-vm-tx04%2Fpayment
}
