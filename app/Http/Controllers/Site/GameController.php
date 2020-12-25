<?php

namespace App\Http\Controllers\Site;

use App\Http\Requests\OrderFormRequest;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Page;
use App\Models\User;
use App\Models\LocationPlayer;
use App\Models\Team;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\GameTransaction;
use App\Models\Eldwry;
use App\Models\Subeldwry;
use App\Http\Controllers\ClassSiteApi\Class_GameController;
use App\Http\Controllers\ClassSiteApi\Class_PageGameController;
use App\Http\Controllers\SiteController;

class GameController extends SiteController {

    public function __construct() {
        parent::__construct();
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
            emptySessionDeletPlayer();
            $register_dwry = 1;
            $msg_finish_dwry = '';
            if (isset(Auth::user()->id)) {
                $get_data =new Class_PageGameController();
                $page_data= $get_data->Page_game_Site(Auth::user());
                if(!empty($page_data['redirect_route'])){
                    return redirect()->route($page_data['redirect_route']);
                }
                return view($page_data['url_name'],$page_data);
            } else {
                return redirect()->route('login');
            }
        } else {
            return redirect()->route('close');
        }
    }

    public function game_transfer(Request $request) {
       if ($this->site_open == 1 || $this->site_open == "1") {
            if (isset(Auth::user()->id)) {
                $get_data =new Class_PageGameController();
                $page_data= $get_data->Page_game_transfer_Site(Auth::user());
                if(!empty($page_data['redirect_route'])){
                    return redirect()->route($page_data['redirect_route']);
                }
                return view($page_data['url_name'],$page_data);
            } else {
                return redirect()->route('login');
            }
        } else {
            return redirect()->route('close');
        }
    }

    public function my_team(Request $request) {
        if ($this->site_open == 1 || $this->site_open == "1") {
            if (isset(Auth::user()->id)) {
                $get_data =new Class_PageGameController();
                $page_data= $get_data->Page_my_team_Site(Auth::user());
                if(!empty($page_data['redirect_route'])){
                    return redirect()->route($page_data['redirect_route']);
                }
                return view($page_data['url_name'],$page_data);
            } else {
                return redirect()->route('home');
            }
        } else {
            return redirect()->route('close');
        }
    }

    public function my_point(Request $request) {
        if ($this->site_open == 1 || $this->site_open == "1") {
            if (isset(Auth::user()->id)) {
                $get_data =new Class_PageGameController();
                $page_data= $get_data->Page_my_point_Site(Auth::user());
                if(!empty($page_data['redirect_route'])){
                    return redirect()->route($page_data['redirect_route']);
                }
                return view($page_data['url_name'],$page_data);
            } else {
                return redirect()->route('home');
            }
        } else {
            return redirect()->route('close');
        }
    }

}