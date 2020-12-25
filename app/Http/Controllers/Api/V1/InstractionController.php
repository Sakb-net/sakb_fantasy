<?php

namespace App\Http\Controllers\Site;

use App\Http\Requests\OrderFormRequest;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Page;
use App\Models\User;
use App\Models\Video;
use App\Models\CommentVideo;
use App\Models\Options;
use App\Http\Controllers\ClassSiteApi\Class_CommentController;
use App\Http\Controllers\SiteController;

class InstractionController extends SiteController {

    public function __construct() {
        $this_data = Options::Site_Option();
        $this->site_open = $this_data['site_open'];
        $this->site_title = $this_data['site_title'];
        $this->limit = $this_data['limit'];
        $this->logo_image = $this_data['logo_image'];
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
            $page = Page::get_typeColum('instraction');
            $page_title = $page->title;
            $title = $page_title . " - " . $this->site_title;
            $logo_image = $this->logo_image;
            View::share('title', $title);
            View::share('activ_menu', 6);
            $type = 'instraction';
            $data = [];
            return view('site.instractions.index', compact('logo_image', 'data', 'type', 'page_title'))->with('i', ($request->input('page', 1) - 1) * 5);
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
                return view('site.videos.single', $all_comment)->with('i', ($request->input('page', 1) - 1) * 5);
            } else {
                return redirect()->route('videos.index');
            }
        } else {
            return redirect()->route('close');
        }
    }

}
