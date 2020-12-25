<?php

namespace App\Http\Controllers\ClassSiteApi;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Page;
use App\Models\PageContent;
use App\Models\Contact;
use App\Models\Blog;
use App\Models\Video;
use App\Models\Match;
use App\Models\Team;
use App\Models\Game;
use App\Models\Eldwry;
use App\Models\Subeldwry;
use App\Models\PointUser;
use App\Models\Options;
use App\Models\GameHistory;

use App\Http\Controllers\SiteController;

use App\Http\Controllers\ClassSiteApi\Class_GameController;

class Class_PageController extends SiteController {

    public function __construct() {
        parent::__construct();
    }

    public function Page_Home($lang = 'ar', $limit = 6, $api = 0,$limit_fix=2,$offset=-1,$val_view=0,$user_id=0) {
        $lang = $this->lang;
        $choose_team=0;
        $home_points=[];
        $curent_subeldwry='';
        if($user_id > 0){
            $start_dwry = Subeldwry::get_startFirstSubDwry();
            if (isset($start_dwry->id)) {
                $curent_subeldwry = Subeldwry::single_DataSubeldwryUser($start_dwry,$api,$lang);
                $home_points=PointUser::Home_Finaltotal($user_id,'sub_eldwry_id',$start_dwry->id);
            }
            $game=Game::get_GameCloum('user_id',$user_id);
            if(isset($game->team_name) && !empty($game->team_name)){
                $choose_team=1;
            }
        }
        $get_match=new Class_GameController();
        $fixtures=$get_match->getFixtures_CurrentWeek(0,-1,$lang,$api,$val_view);
        if($api==0){
            $news = Blog::get_BlogActive(1, '', '', $lang, 0, $limit, 0);
            $videos = Video::get_ALLVideoData(null, 'id', 'DESC', $limit, 0);
            $return_data = array('choose_team' => $choose_team,'home_points'=>$home_points,'curent_subeldwry'=>$curent_subeldwry,'fixtures'=>$fixtures,'news' => $news,'videos' => $videos);
        }else{
            $data_stop_subeldwry=GameHistory::CheckTime_StopSubeldwry(); //for only api=1
            $return_data = array('choose_team' => $choose_team,'home_points'=>$home_points,'curent_subeldwry'=>$curent_subeldwry,'fixtures'=>$fixtures,'data_stop_subeldwry'=>$data_stop_subeldwry);
        }
        return $return_data;
    }

    public function Page_contactUs($type_page = 'contact', $lang = 'ar', $api = 0, $user_key = '', $user_email = '') {
        if ($api == 0) {
            $lang = $this->lang;
            $user_key = $this->user_key;
            $user_email = $this->user_email;
        }
        Page::updateColumWhere('type', $type_page, 'view_count', 0, $lang);

        $contact_page = $contact_content = $contact_title = $contact_email = $contact_phone = $address = $lat = $long = null;
        $page = Page::get_PageLang('contact', $lang);
        if (isset($page->id)) {
            $contact_content = $page->description;
            $contact_page = $page->name;
            $contact_title = $page->title;
            $content = PageContent::get_Content($page->id, 'contact', 1);
            foreach ($content as $key => $val_cont) {
                if ($val_cont->content_key == 'phone_email') {
                    $contact_phone = $val_cont->content_value;
                    $contact_email = $val_cont->content_etc;
                } elseif ($val_cont->content_key == 'address') {
                    $address = $val_cont->content_value;
                    $long = $val_cont->content_etc;
                    $lat = $val_cont->content_other;
                }
            }
        }
        $return_data = array('title' => $contact_title, 'content' => $contact_content,
            'phone' => $contact_phone, 'email' => $contact_email, 'address' => $address,
            'long' => $long, 'lat' => $lat);
        return $return_data;
    }

    public function Page_instraction($api = 0, $lang = 'ar') {
        if ($api == 0) {
            $lang = $this->lang;
        }

        $return_data['content_page']=$return_data['content_help'] =$return_data['content_role'] =[];
        Page::updateColumWhere('type', 'instraction', 'view_count', 0, $lang);
        $page = Page::get_PageLang('instraction', $lang);
        if (!isset($page->id)) {
            $page = Page::get_PageLang('instraction', 'ar');
        }
        if (isset($page->id)) {
            $lang_id = $page->lang_id;
            $return_data['content_page']['name'] = $page->name;
            $return_data['content_page']['title'] = $page->title;
            $return_data['content_page']['content'] = $page->description;
            //item or help
            $content_help = PageContent::get_Content($page->id, 'instraction_item',1);
            $return_data['content_help'] =PageContent::array_ContentData($content_help,$api);
            //role
            $instraction_role = PageContent::get_Content($page->id, 'instraction_role',1);

            $return_data['content_role'] =PageContent::array_ContentData($instraction_role,$api);
        }
        return $return_data;
    }
    
    public function Page_award($api = 0, $lang = 'ar') {
        if ($api == 0) {
            $lang = $this->lang;
        }

        $return_data['content_page']=$return_data['content_items']=[];
        Page::updateColumWhere('type', 'award', 'view_count', 0, $lang);
        $page = Page::get_PageLang('award', $lang);
        if (!isset($page->id)) {
            $page = Page::get_PageLang('award', 'ar');
        }
        if (isset($page->id)) {
            $lang_id = $page->lang_id;
            $return_data['content_page']['name'] = $page->name;
            $return_data['content_page']['title'] = $page->title;
            $return_data['content_page']['content'] = $page->description;

            //detail
            $content_items = PageContent::get_Content($page->id, 'award_details',1);

            //item or help
            //$content_help = PageContent::get_Content($page->id, 'instraction_item',1);
            $return_data['content_items'] =PageContent::array_ConditionContentData($content_items,$api);
            //role
            //$instraction_role = PageContent::get_Content($page->id, 'instraction_role',1);

        }
        return $return_data;
    }



    public function Page_about($api = 0, $lang = 'ar') {
        if ($api == 0) {
            $lang = $this->lang;
        }
        Page::updateColumWhere('type', 'about', 'view_count', 0, $lang);
        $all_title = [];
        $lang_id = $about_name = $about_title = $about_content = $about_image = $about_title_two = $about_content_two = NULL;
        $page = Page::get_PageLang('about', $lang);
        if (!isset($page->id)) {
            $page = Page::get_PageLang('about', 'ar');
        }
        if (isset($page->id)) {
            $lang_id = $page->lang_id;
            $about_name = $page->name;
            $about_title = $page->title;
            $about_content = $page->description;
            $about_image = $page->image;
            $page_content = PageContent::get_Content($page->id, 'about');
            if (isset($page_content->content_value)) {
                $about_title_two = $page_content->content_value;
                $about_content_two = $page_content->content_etc;
                $all_title = json_decode($page_content->content_other);
            }
        }
        if ($api == 1) {
            $about_content = strip_tags($about_content);
            $about_content_two = strip_tags($about_content_two);
        }
        $return_data = array('all_list' => $all_title, 'name' => $about_name, 'title_one' => $about_title, 'content_one' => $about_content, 'title_two' => $about_title_two, 'content_two' => $about_content_two, 'image' => $about_image);
        if ($api == 0) {
            $return_data['lang_id'] = $lang_id;
        }
        return $return_data;
    }

    public function PageContent($type_page = 'home', $lang = 'ar', $api = 0) {
        if ($api == 0) {
            $lang = $this->lang;
        }
        Page::updateColumWhere('type', $type_page, 'view_count', 0, $lang);
        $lang_id = $title = $content = $image = $title_content = NULL;
        $page = Page::get_PageLang($type_page, $lang);
        if (!isset($page->id)) {
            $page = Page::get_PageLang($type_page, 'ar');
        }
        if (isset($page->id)) {
            $lang_id = $page->lang_id;
            $title = $page->name;
            $title_content = $page->title;
            $content = $page->description;
            $image = $page->image;
        }
        if ($api == 1) {
            $content = strip_tags($content);
        }
        $return_data = array('title' => $title, 'title_content' => $title_content, 'content' => $content, 'image' => $image);
        if ($api == 0) {
            $return_data['lang_id'] = $lang_id;
        }
        return $return_data;
    }

    public function add_contact_Us($input, $user_id = 0, $api = 0) {
        $state_add = 0;
        if ($api == 0) {
            if (isset($this->user) && !empty($this->user)) {
                $user_id = $this->user->id;
                $input['name'] = $this->user->display_name;
                $input['email'] = $this->user->email;
            }
        }
        $input['attachment'] = NULL;
        $input['is_read'] = 0;
        $input['is_reply'] = 0;
        $contact = Contact::create($input);
        if (!empty($contact)) { //$contact['id']
            User::SendEmailTOUser($user_id, 'contact_form', $input['content'], $input);
            $state_add = 1;
        }
        return array('state_add' => $state_add);
    }
}