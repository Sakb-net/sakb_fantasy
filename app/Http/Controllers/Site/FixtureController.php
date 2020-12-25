<?php

namespace App\Http\Controllers\Site;

use App\Http\Requests\OrderFormRequest;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Page;
use App\Models\Team;
use App\Models\Eldwry;
use App\Models\User;
use App\Http\Controllers\SiteController;

class FixtureController extends SiteController {

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
            //$page = Page::get_typeColum('fixture',$lang);
            $page_title = trans('app.fixtures');//$page->title;
            $title = $page_title . " - " . $this->site_title;
            $logo_image = $this->logo_image;
            $lang = $this->lang;
            View::share('title', $title);
            View::share('activ_menu', 2);
            $type = 'fixture';
            $array_best_team=User::BestTeam(Auth::user());
            $eldwry = Eldwry::get_currentDwry();

            $allTeams=Team::where(['is_active'=>1 , 'eldwry_id'=>$eldwry->id])->get();
            
            $team_image_fav=$array_best_team['team_image_fav'];
            return view('site.fixtures.index', compact('logo_image','lang','type', 'page_title','team_image_fav','allTeams'))->with('i', ($request->input('page', 1) - 1) * 5);
        } else {
            return redirect()->route('close');
        }
    }

}
