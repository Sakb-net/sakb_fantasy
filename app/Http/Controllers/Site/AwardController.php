<?php

namespace App\Http\Controllers\Site;

use App\Http\Requests\OrderFormRequest;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Page;
use App\Models\User;
use App\Models\PageContent;
use App\Http\Controllers\SiteController;

class AwardController extends SiteController {

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
            $award_title = NULL;
            $content_items=$content_roles= [];
            //$page = Page::get_typeColum('award',$lang);
            $page_title = trans('app.awards');//$page->title;
            $title = $page_title . " - " . $this->site_title;
            $logo_image = $this->logo_image;
            View::share('title', $title);
            View::share('activ_menu', 5);
            $type = 'award';
            $data=[];
            $All_pages = Page::get_typeColum($type,$lang);
                if (isset($All_pages->id)) {
                    $award_title = $All_pages->title;
                }
                //detail
                $content_items = PageContent::get_Content($All_pages->id, 'award_details',1);
                //conditions
                $content_roles = PageContent::get_Content($All_pages->id, 'award_condition',1);
                $array_best_team=User::BestTeam(Auth::user());
                $team_image_fav=$array_best_team['team_image_fav'];
                return view('site.awards.index', compact('logo_image','data', 'type', 'page_title', 'content_items', 'content_roles', 'award_title','team_image_fav'))->with('i', ($request->input('page', 1) - 1) * 5);
        } else {
            return redirect()->route('close');
        }
    }


}
