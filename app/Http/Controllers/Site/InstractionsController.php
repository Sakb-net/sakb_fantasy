<?php

namespace App\Http\Controllers\Site;

use App\Http\Requests\OrderFormRequest;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Page;
use App\Models\User;
use App\Http\Controllers\ClassSiteApi\Class_PageController;
use App\Http\Controllers\SiteController;

class InstractionsController extends SiteController {

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
            $lang=$this->lang;
            //$page = Page::get_typeColum('instraction',$lang);
            $page_title = trans('app.instractions');//$page->title;
            $title = $page_title . " - " . $this->site_title;
            $logo_image = $this->logo_image;
            View::share('title', $title);
            View::share('activ_menu', 6);
            $type = 'instraction';
            $data_page = new Class_PageController();
            $retun_data = $data_page->Page_instraction(0,$lang);
            $retun_data['page_title'] =$page_title;
            $array_best_team=User::BestTeam(Auth::user());
            $retun_data['team_image_fav']=$array_best_team['team_image_fav'];
            return view('site.instractions.index', $retun_data);
        } else {
            return redirect()->route('close');
        }
    }

    public function single(Request $request, $link) {
        if ($this->site_open == 1 || $this->site_open == "1") {
            $data = Video::get_videoColum('link', $link, 1);
            if (isset($data->id)) {
                $all_comment;
                $array_best_team=User::BestTeam(Auth::user());
                $all_comment['team_image_fav']=$array_best_team['team_image_fav'];
                return view('site.instractions.single', $all_comment)->with('i', ($request->input('page', 1) - 1) * 5);
            } else {
                return redirect()->route('instractions.index');
            }
        } else {
            return redirect()->route('close');
        }
    }

}
