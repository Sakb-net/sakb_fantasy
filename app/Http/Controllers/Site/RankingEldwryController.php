<?php

namespace App\Http\Controllers\Site;

use App\Http\Requests\OrderFormRequest;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SiteController;
use App\Repositories\RankingEldwryRepository;  
use App\Models\User;
// use App\Models\Page;


class RankingEldwryController extends SiteController {

    public function __construct() {
        parent::__construct();
        // $this->RankingEldwryRepository =new RankingEldwryRepository();
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
            //$page = Page::get_typeColum('ranking_eldwry',$lang);
            $page_title = trans('app.ranking_eldwry');//$page->title;
            $title = $page_title . " - " . $this->site_title;
            $logo_image = $this->logo_image;
            View::share('title', $title);
            View::share('activ_menu', 41);
            $type = 'ranking_eldwry';
            $data = [];
            $array_best_team=User::BestTeam(Auth::user());
            $team_image_fav=$array_best_team['team_image_fav'];
            return view('site.ranking_eldwry.index', compact('logo_image', 'data', 'type', 'page_title','team_image_fav'))->with('i', ($request->input('page', 1) - 1) * 5);
        } else {
            return redirect()->route('close');
        }
    }

}
