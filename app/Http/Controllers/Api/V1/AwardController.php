<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
// use \Illuminate\Support\Facades\View;
// use Illuminate\Support\Facades\Auth;
use App\Models\Page;
use App\Models\User;
use App\Models\Video;
use App\Models\CommentVideo;
use App\Models\Options;
use App\Models\PageContent;
use App\Http\Controllers\ClassSiteApi\Class_CommentController;
use App\Http\Controllers\API_Controller;

class AwardController extends API_Controller {

    public function __construct() {
        $this_data = Options::Site_Option();
        $this->site_open = $this_data['site_open'];
        $this->site_title = $this_data['site_title'];
        $this->limit = $this_data['limit'];
        $this->logo_image = $this_data['logo_image'];
        $this->lang = $this_data['lang'];
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
                return view('site.awards.index', compact('logo_image','data', 'type', 'page_title', 'content_items', 'content_roles', 'award_title'))->with('i', ($request->input('page', 1) - 1) * 5);
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
