<?php

namespace App\Http\Controllers\Site;

use App\Http\Requests\OrderFormRequest;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Page;
use App\Models\User;
use App\Models\Video;
use App\Http\Controllers\ClassSiteApi\Class_CommentController;
use App\Http\Controllers\SiteController;

class VideosController extends SiteController {

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
            //$page = Page::get_typeColum('video',$lang);
            $page_title = trans('app.videos');//$page->title;
            $title = $page_title . " - " . $this->site_title;
            $logo_image = $this->logo_image;
            View::share('title', $title);
            View::share('activ_menu', 43);
            $type = 'video';
            $data = Video::get_ALLVideoData(null, 'id', 'DESC', $this->limit, -1);
            $array_best_team=User::BestTeam(Auth::user());
            $team_image_fav=$array_best_team['team_image_fav'];
            return view('site.videos.index', compact('logo_image', 'data', 'type', 'page_title','team_image_fav'))->with('i', ($request->input('page', 1) - 1) * 5);
        } else {
            return redirect()->route('close');
        }
    }

    public function single(Request $request, $link) {
        if ($this->site_open == 1 || $this->site_open == "1") {
            $data = Video::get_videoColum('link', $link, 1);
            if (isset($data->id)) {
                Video::updateVideoViewCount($data->id);
                $page = Page::get_typeColum('video');
                $page_title = $page->title;
                $title = $page_title . " - " . $data->name . " - " . $this->site_title;
                $logo_image = $this->logo_image;
                View::share('title', $title);
                View::share('activ_menu', 43);
                $type = 'video';
                $upload = 0;
                $array_upload = explode('uploads', $data->video);
                if (count($array_upload) >= 2) {
                    $upload = 1;
                }
                $get_comment = new Class_CommentController();
                $all_comment = $get_comment->get_commentdata($data, 'video',$this->lang);
                $all_comment['page_title'] = $page_title;
                $all_comment['upload'] = $upload;
                $all_comment['share_link'] = route('videos.single', $all_comment['data']->link);
                $all_comment['share_image'] = $all_comment['data']->image;
                $all_comment['title'] = $title; //$all_comment['data']->name;
                $all_comment['share_description'] = $all_comment['data']->description;
                $array_best_team=User::BestTeam(Auth::user());
                $all_comment['team_image_fav']=$array_best_team['team_image_fav'];

                return view('site.videos.single', $all_comment)->with('i', ($request->input('page', 1) - 1) * 5);
            } else {
                return redirect()->route('videos.index');
            }
        } else {
            return redirect()->route('close');
        }
    }

}
