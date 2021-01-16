<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\PageContent;
use App\Models\User;
use App\Models\Options;
use App\Models\Language;
//use App\Models\Comment;
//use App\Models\Contact;
//use Carbon\Carbon;
//use DB;

class pageController extends AdminController {

//********************************pages of site**************************************
    public function homeOption() {
        if (!$this->user->can('access-all')) {
            return $this->pageUnauthorized();
        }
//        $all_title = Options::where('option_group', 'home')->get();'all_title',
//        $image_banner = Options::where('option_key', 'image_banner')->where('option_group', 'banner')->first();
        $image_back = Options::where('option_key', 'image_back')->where('option_group', 'home')->first();
        $image_link = $image_back->page_value;

        $post_active = 1;
        return view('admin.pages.home', compact('about_title', 'about_content', 'about_image', 'image_link', 'post_active'));
    }

    public function homeStore(Request $request) {
        if (!$this->user->can('access-all')) {
            return $this->pageUnauthorized();
        }
        //whereNotIn('page_value', 'home')->
        $delet = new Page();
        $delet->deleteOptionGroup('home');

        $input = $request->all();
        foreach ($input as $key => $value) {
            if ($key != 'titleHome' && $key != 'title_addHome') {
                $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
        }
//        Options::updateOption("image_banner", $input['image_banner'], 1, 'banner');
        Options::updateOption("image_back", $input['image_back'], 1, 'home');

        return redirect()->route('admin.pages.home')->with('success', 'Update successfully');
    }

    public function homeStore_try(Request $request) {
        if (!$this->user->can('access-all')) {
            return $this->pageUnauthorized();
        }
        //whereNotIn('page_value', 'home')->
        $delet = new Page();
        $delet->deleteOptionGroup('home');

        $input = $request->all();
        foreach ($input as $key => $value) {
            if ($key != 'titleHome' && $key != 'title_addHome') {
                $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
        }
        Page::updateOption("image_banner", $input['image_banner'], 1, 'banner');

        $title_Home = isset($_POST['titleHome']) ? $_POST['titleHome'] : array();
        $title_addHome = isset($_POST['title_addHome']) ? $_POST['title_addHome'] : array();
        if (!empty($title_Home)) {
            foreach ($title_Home as $title_Home_value) {
                $input['id'] = (int) $title_Home_value['title_id'];
                $input['name'] = trim(filter_var($title_Home_value['name'], FILTER_SANITIZE_STRING));
                $current_title_Home_id[] = $input['id'];
                if ($input['name'] != '') {
                    Page::updateOptionHome("home_title", $input['name'], 0, 'home');
                }
            }
        }

        $input = [];
        if (!empty($title_addHome)) {
            foreach ($title_addHome as $title_addHome_value) {
                $input['name'] = trim(filter_var($title_addHome_value['name'], FILTER_SANITIZE_STRING));
                if ($input['name'] != '') {
                    Page::updateOptionHome("home_title", $input['name'], 0, 'home');
                }
            }
        }

        return redirect()->route('admin.pages.home')->with('success', 'Home update successfully');
    }


    public function contactLang(Request $request) {
        if (!$this->user->can(['access-all', 'admin-panel', 'change_setting','change_setting-all', 'display_change_setting', 'update_change_setting', 'delete_change_setting', 'show_change_setting','add_change_setting'])) {
            return $this->pageUnauthorized();
        }
        $page_active = 1;
        $page_name = 'contact';
        $data = Page::get_PageType('contact', $this->limit);
        $mainLanguage = Language::get_Languag('is_active', 1, 'lang', 1);
        return view('admin.pages.pages_lang', compact('data', 'mainLanguage', 'page_name'))->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function contact($lang_id, $lang = 'ar') {
        if (!$this->user->can('access-all')) {
            return $this->pageUnauthorized();
        }
        $contact_page = $contact_content = $contact_title = $contact_email = $contact_phone = $address = $lat = $long = null;
        $type='contact';
        $All_pages = Page::get_typeColum($type,$lang);
        if (isset($All_pages->id)) {
            $contact_content = $All_pages->description;
            $contact_page = $All_pages->name;
            $contact_title = $All_pages->title;
            $content = PageContent::get_Content($All_pages->id, 'contact', 1);
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
        return view('admin.pages.contact', compact('type', 'lang', 'lang_id','lat', 'long', 'address', 'contact_email', 'contact_phone', 'contact_page', 'contact_title', 'contact_content'));
    }

    public function contactStore(Request $request) {
        if (!$this->user->can('access-all')) {
            return $this->pageUnauthorized();
        }

        $request->validate([
//            'contact_page' => 'required',
//            'contact_title' => 'required',
//            'contact_content' => 'required',
        ]);


        $input = $request->all();
        foreach ($input as $key => $value) {
            if ($key != 'contact_content') {
                $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
        }

        $input = $request->all();
        foreach ($input as $key => $value) {
            if ($key != 'contact_content') {
                $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
        }
        $page_id = Page::updatePage('contact', $input['contact_page'],$input['lang'], $input['contact_title'], $input['contact_content']);
        PageContent::updateContentKey($page_id, 'contact', 'phone_email', $input['contact_phone'], $input['contact_email'], '');
        PageContent::updateContentKey($page_id, 'contact', 'address', $input['address'], $input['longitude'], $input['latitude']);
        return redirect()->route('admin.pages.contact')->with('success', 'Update successfully');
    }
    
    public function aboutLang(Request $request) {
        if (!$this->user->can(['access-all', 'admin-panel', 'change_setting','change_setting-all', 'display_change_setting', 'update_change_setting', 'delete_change_setting', 'show_change_setting','add_change_setting'])) {
            return $this->pageUnauthorized();
        }
        $page_active = 1;
        $page_name = 'about';
        $data = Page::get_PageType('about', $this->limit);
        $mainLanguage = Language::get_Languag('is_active', 1, 'lang', 1);
        return view('admin.pages.pages_lang', compact('data', 'mainLanguage', 'page_name'))->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function about($lang_id, $lang = 'ar') {
        if (!$this->user->can('access-all')) {
            return $this->pageUnauthorized();
        }
        $all_title = [];
        $about_image = $about_content = $about_title = $about_content_two = $about_title_two = $about_page = NULL;
        $All_pages = Page::get_typeColum('about',$lang);
        if (isset($All_pages->id)) {
            $about_image = $All_pages->image;
            $about_content = $All_pages->description;
            $about_page = $All_pages->name;
            $about_title = $All_pages->title;
            $content = PageContent::get_Content($All_pages->id, 'about');
            if (isset($content->id)) {
                $about_title_two = $content->content_value;
                $about_content_two = $content->content_etc;
                if(!empty($content->content_other)){
                    $all_title = json_decode($content->content_other);
                }
            }
        } else {
            $page_ar = Page::get_PageLang('about', 'ar');
            $about_image = $page_ar->image;
        }
        $type='about';
        return view('admin.pages.about', compact('about_page', 'all_title', 'about_title', 'about_content', 'about_title_two', 'about_content_two', 'about_image','lang','lang_id','type'));
    }

    public function aboutStore(Request $request) {
        if (!$this->user->can('access-all')) {
            return $this->pageUnauthorized();
        }

        $request->validate([
            'about_page' => 'required',
            'about_title' => 'required',
            'about_content' => 'required',
//            'about_image' => 'required',
        ]);
        $input = $request->all();
        foreach ($input as $key => $value) {
            if ($key != 'about_content' && $key != 'titleHome' && $key != 'title_addHome') {
                $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
        }
        $input['name'] = $input['about_page'];
        $input['title'] = $input['about_title'];
        $input['description'] = $input['about_content'];
        $input['image'] = $input['about_image'];
        $page = Page::get_PageLang($input['type'], $input['lang']);
        if (isset($page->id)) {
            $page->update($input);
            $page_id = $page->id;
        } else {
            if (!isset($input['lang_id']) || empty($input['lang_id'])) {
                $input['lang_id'] = Null;
            }
            $input['view_count'] = 0;
            $page = Page::create($input);
            $page_id = $page['id'];
            if ($input['lang'] == 'ar') {
                Page::updateColum($page_id, 'lang_id', $page_id);
            }
        }
        $all_list = [];
        $title_Home = isset($_POST['titleHome']) ? $_POST['titleHome'] : array();
        $title_addHome = isset($_POST['title_addHome']) ? $_POST['title_addHome'] : array();
        if (!empty($title_Home)) {
            foreach ($title_Home as $title_Home_value) {
                $name_list = trim(filter_var($title_Home_value['name'], FILTER_SANITIZE_STRING));
                if ($name_list != '') {
                    $all_list[] = $name_list;
                }
            }
        }
        if (!empty($title_addHome)) {
            foreach ($title_addHome as $title_addHome_value) {
                $name_list = trim(filter_var($title_addHome_value['name'], FILTER_SANITIZE_STRING));
                if ($name_list != '') {
                    $all_list[] = $name_list;
                }
            }
        }
        $all_list = json_encode($all_list);
        PageContent::updateContent($page_id, 'about', 'content', $input['about_title_two'], $input['about_content_two'], $all_list);
        return redirect()->route('admin.pages.about')->with('success', 'Update successfully');
    }

    public function instractionsLang(Request $request) {
        if (!$this->user->can(['access-all', 'admin-panel', 'change_setting','change_setting-all', 'display_change_setting', 'update_change_setting', 'delete_change_setting', 'show_change_setting','add_change_setting'])) {
            return $this->pageUnauthorized();
        }
        $page_active = 1;
        $page_name = 'instractions';
        $data = Page::get_PageType('instraction', $this->limit);
        $mainLanguage = Language::get_Languag('is_active', 1, 'lang', 1);
        return view('admin.pages.pages_lang', compact('data', 'mainLanguage', 'page_name'))->with('i', ($request->input('page', 1) - 1) * 5);
    }
    
    public function instractions($lang_id, $lang = 'ar') {
        if (!$this->user->can('access-all')) {
            return $this->pageUnauthorized();
        }
        $all_title =$content_items=$content_roles= [];
        $instraction_image = $instraction_content = $instraction_title = $instraction_content_two = $instraction_title_two = $instraction_page = NULL;
        $type='instraction';
        $All_pages = Page::get_typeColum($type,$lang);
        if (isset($All_pages->id)) {
            $instraction_image = $All_pages->image;
            $instraction_content = $All_pages->description;
            $instraction_page = $All_pages->name;
            $instraction_title = $All_pages->title;
            $content = PageContent::get_Content($All_pages->id, 'instraction');
            if (isset($content->id)) {
                $instraction_title_two = $content->content_value;
                $instraction_content_two = $content->content_etc;
                // $all_title = json_decode($content->content_other);
            }
            //item or help
            $content_items = PageContent::get_Content($All_pages->id, 'instraction_item',1);
            //role
            $content_roles = PageContent::get_Content($All_pages->id, 'instraction_role',1);
        }
        return view('admin.pages.instractions', compact('type','lang','lang_id','instraction_page','content_items','content_roles', 'all_title', 'instraction_title', 'instraction_content', 'instraction_title_two', 'instraction_content_two', 'instraction_image'));
    }

    public function instractionsStore(Request $request) {
        if (!$this->user->can('access-all')) {
            return $this->pageUnauthorized();
        }

        $request->validate([
            'instraction_page' => 'required',
            'instraction_title' => 'required',
            // 'instraction_content' => 'required',
//            'instraction_image' => 'required',
        ]);


        $input = $request->all();
        foreach ($input as $key => $value) {
            if ($key != 'instraction_content' && $key != 'titleHome' && $key != 'title_addHome' && $key != 'titleinstraction' && $key != 'title_addinstraction') {
                $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
        }
        $page_id = Page::updatePage('instraction', $input['instraction_page'], $input['lang'], $input['instraction_title'], $input['instraction_content'], $input['instraction_image']);
        $all_list = [];
        $title_Home = isset($_POST['titleHome']) ? $_POST['titleHome'] : array();
        $title_addHome = isset($_POST['title_addHome']) ? $_POST['title_addHome'] : array();
        
        $title_instraction = isset($_POST['titleinstraction']) ? $_POST['titleinstraction'] : array();
        $title_addinstraction = isset($_POST['title_addinstraction']) ? $_POST['title_addinstraction'] : array();
        //delete
        PageContent::DeleteContentByType($page_id, 'instraction_item');        
        PageContent::DeleteContentByType($page_id, 'instraction_role');
        if (!empty($title_instraction)) {
            foreach ($title_instraction as $title_instraction_value) {
                $name_list = trim(filter_var($title_instraction_value['name'], FILTER_SANITIZE_STRING));
                $content_item = trim(filter_var($title_instraction_value['content_item'], FILTER_SANITIZE_STRING));
                if ($name_list != '' && $content_item != '') {
                    PageContent::AddContent($page_id, 'instraction_item', 'instraction_item', $name_list, $content_item);
                }
            }
        }
        if (!empty($title_addinstraction)) {
            foreach ($title_addinstraction as $title_addinstraction_value) {
                $name_list = trim(filter_var($title_addinstraction_value['name'], FILTER_SANITIZE_STRING));
                $content_item= trim(filter_var($title_addinstraction_value['content_item'], FILTER_SANITIZE_STRING));
                if ($name_list != ''&& $content_item != '') {
                    PageContent::AddContent($page_id, 'instraction_item', 'instraction_item', $name_list, $content_item);
                }
            }
        }
        if (!empty($title_Home)) {
            foreach ($title_Home as $title_Home_value) {
                $name_list = trim(filter_var($title_Home_value['name'], FILTER_SANITIZE_STRING));
                $content_item = trim(filter_var($title_Home_value['content_item'], FILTER_SANITIZE_STRING));
                if ($name_list != '' && $content_item != '') {
                    PageContent::AddContent($page_id, 'instraction_role', 'instraction_role', $name_list, $content_item);
                }
            }
        }
        if (!empty($title_addHome)) {
            foreach ($title_addHome as $title_addHome_value) {
                $name_list = trim(filter_var($title_addHome_value['name'], FILTER_SANITIZE_STRING));
                $content_item= trim(filter_var($title_addHome_value['content_item'], FILTER_SANITIZE_STRING));
                if ($name_list != ''&& $content_item != '') {
                    PageContent::AddContent($page_id, 'instraction_role', 'instraction_role', $name_list, $content_item);
                }
            }
        }

        PageContent::updateContent($page_id, 'instraction', 'content', $input['instraction_title_two'], $input['instraction_content_two']);
        return redirect()->route('admin.pages.instractions')->with('success', 'Update successfully');
    }
    
        public function awardsLang(Request $request) {
            if (!$this->user->can(['access-all', 'admin-panel', 'change_setting','change_setting-all', 'display_change_setting', 'update_change_setting', 'delete_change_setting', 'show_change_setting','add_change_setting'])) {
                return $this->pageUnauthorized();
            }
            $page_active = 1;
            $page_name = 'awards';
            $data = Page::get_PageType('award', $this->limit);
            $mainLanguage = Language::get_Languag('is_active', 1, 'lang', 1);
            return view('admin.pages.pages_lang', compact('data', 'mainLanguage', 'page_name'))->with('i', ($request->input('page', 1) - 1) * 5);
        }

        public function awards($lang_id, $lang = 'ar') {

            if (!$this->user->can('access-all')) {
                return $this->pageUnauthorized();
            }
            $all_title =$content_items=$content_roles= [];
            $award_image = $award_content = $award_title = $award_content_two = $award_title_two = $award_page = NULL;
            $type='award';
            $All_pages = Page::get_typeColum($type,$lang);
            if (isset($All_pages->id)) {
                $award_image = $All_pages->image;
                $award_content = $All_pages->description;
                $award_page = $All_pages->name;
                $award_title = $All_pages->title;
                $content = PageContent::get_Content($All_pages->id, 'award');
                if (isset($content->id)) {
                    $award_title_two = $content->content_value;
                    $award_content_two = $content->content_etc;
                    // $all_title = json_decode($content->content_other);
                }
                //detail
                $content_items = PageContent::get_Content($All_pages->id, 'award_details',1);
                //conditions
                $content_roles = PageContent::get_Content($All_pages->id, 'award_condition',1);
            }
            return view('admin.pages.awards', compact('type','lang','lang_id','award_page','content_items','content_roles', 'all_title', 'award_title', 'award_content', 'award_title_two', 'award_content_two', 'award_image'));

        }

        public function awardsStore(Request $request) {
            if (!$this->user->can('access-all')) {
                return $this->pageUnauthorized();
            }

            $request->validate([
                'award_page' => 'required',
                'award_title' => 'required',
                // 'instraction_content' => 'required',
    //            'instraction_image' => 'required',
            ]);


            $input = $request->all();
            foreach ($input as $key => $value) {
                if ($key != 'award_content' && $key != 'titleHome' && $key != 'title_addHome' && $key != 'titleaward' && $key != 'title_addaward') {
                    $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
                }
            }
            $page_id = Page::updatePage('award', $input['award_page'], $input['lang'], $input['award_title'], $input['award_content'], $input['award_image']);
            $all_list = [];
            $title_Home = isset($_POST['titleHome']) ? $_POST['titleHome'] : array();
            $title_addHome = isset($_POST['title_addHome']) ? $_POST['title_addHome'] : array();
            
            $title_award = isset($_POST['titleaward']) ? $_POST['titleaward'] : array();
            $title_addaward = isset($_POST['title_addaward']) ? $_POST['title_addaward'] : array();
            //delete
            PageContent::DeleteContentByType($page_id, 'award_details');
            PageContent::DeleteContentByType($page_id, 'award_conditions');
            if (!empty($title_award)) {
                foreach ($title_award as $title_award_value) {
                    $name_list = trim(filter_var($title_award_value['name'], FILTER_SANITIZE_STRING));
                    $content_item = trim(filter_var($title_award_value['content_item'], FILTER_SANITIZE_STRING));
                    if ($name_list != '' && $content_item != '') {
                        PageContent::AddContent($page_id, 'award_details', 'award_details', $name_list, $content_item);
                    }
                }
            }
            if (!empty($title_addaward)) {
                foreach ($title_addaward as $title_addaward_value) {
                    $name_list = trim(filter_var($title_addaward_value['name'], FILTER_SANITIZE_STRING));
                    $content_item= trim(filter_var($title_addaward_value['content_item'], FILTER_SANITIZE_STRING));
                    if ($name_list != ''&& $content_item != '') {
                        PageContent::AddContent($page_id, 'award_details', 'award_details', $name_list, $content_item);
                    }
                }
            }
            if (!empty($title_Home)) {
                foreach ($title_Home as $title_Home_value) {
                    $name_list = trim(filter_var($title_Home_value['name'], FILTER_SANITIZE_STRING));
                    $content_item = trim(filter_var($title_Home_value['content_item'], FILTER_SANITIZE_STRING));
                    if ($name_list != '' && $content_item != '') {
                        PageContent::AddContent($page_id, 'award_conditions', 'award_conditions', $name_list, $content_item);
                    }
                }
            }
            if (!empty($title_addHome)) {
                foreach ($title_addHome as $title_addHome_value) {
                    $name_list = trim(filter_var($title_addHome_value['name'], FILTER_SANITIZE_STRING));
                    $content_item= trim(filter_var($title_addHome_value['content_item'], FILTER_SANITIZE_STRING));
                    if ($name_list != ''&& $content_item != '') {
                        PageContent::AddContent($page_id, 'award_conditions', 'award_conditions', $name_list, $content_item);
                    }
                }
            }

            PageContent::updateContent($page_id, 'award', 'content', $input['award_title_two'], $input['award_content_two']);
            return redirect()->route('admin.pages.awards')->with('success', 'Update successfully');
        }


 public function goalLang(Request $request) {
        if (!$this->user->can(['access-all', 'admin-panel', 'change_setting','change_setting-all', 'display_change_setting', 'update_change_setting', 'delete_change_setting', 'show_change_setting','add_change_setting'])) {
            return $this->pageUnauthorized();
        }
        $page_active = 1;
        $page_name = 'goal';
        $data = Page::get_PageType('goal', $this->limit);
        $mainLanguage = Language::get_Languag('is_active', 1, 'lang', 1);
        return view('admin.pages.pages_lang', compact('data', 'mainLanguage', 'page_name'))->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function goal($lang_id, $lang = 'ar') {
        if (!$this->user->can('access-all')) {
            return $this->pageUnauthorized();
        }
        $goal_image = $goal_content = $goal_page = $goal_title = NULL;
        $type='goal';
        $All_pages = Page::get_typeColum($type, $lang);
        if (isset($All_pages->id)) {
            $goal_image = $All_pages->image;
            $goal_content = $All_pages->description;
            $goal_page = $All_pages->name;
            $goal_title = $All_pages->title;
        }
        return view('admin.pages.goal', compact('type','lang','lang_id','goal_page', 'goal_title', 'goal_content', 'goal_image'));
    }

    public function goalStore(Request $request) {
        if (!$this->user->can('access-all')) {
            return $this->pageUnauthorized();
        }

        $request->validate([
            'goal_page' => 'required',
            'goal_title' => 'required',
            'goal_content' => 'required',
//            'goal_image' => 'required',
        ]);


        $input = $request->all();
        foreach ($input as $key => $value) {
            if ($key != 'goal_content') {
                $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
        }
        Page::updatePage('goal', $input['goal_page'],$input['lang'], $input['goal_title'], $input['goal_content'], $input['goal_image']);

        return redirect()->route('admin.pages.goal')->with('success', 'Update successfully');
    }
    public function messageLang(Request $request) {
        if (!$this->user->can(['access-all', 'admin-panel', 'change_setting','change_setting-all', 'display_change_setting', 'update_change_setting', 'delete_change_setting', 'show_change_setting','add_change_setting'])) {
            return $this->pageUnauthorized();
        }
        $page_active = 1;
        $page_name = 'message';
        $data = Page::get_PageType('message', $this->limit);
        $mainLanguage = Language::get_Languag('is_active', 1, 'lang', 1);
        return view('admin.pages.pages_lang', compact('data', 'mainLanguage', 'page_name'))->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function message($lang_id, $lang = 'ar') {
        if (!$this->user->can('access-all')) {
            return $this->pageUnauthorized();
        }
        $message_image = $message_content = $message_page = $message_title = NULL;
        $type='message';
        $All_pages = Page::get_typeColum($type, $lang);
        if (isset($All_pages->id)) {
            $message_image = $All_pages->image;
            $message_content = $All_pages->description;
            $message_page = $All_pages->name;
            $message_title = $All_pages->title;
        }
        return view('admin.pages.message', compact('type','lang','lang_id','message_page', 'message_title', 'message_content', 'message_image'));
    }

    public function messageStore(Request $request) {
        if (!$this->user->can('access-all')) {
            return $this->pageUnauthorized();
        }

        $request->validate([
            'message_page' => 'required',
            'message_title' => 'required',
            'message_content' => 'required',
//            'message_image' => 'required',
        ]);


        $input = $request->all();
        foreach ($input as $key => $value) {
            if ($key != 'message_content') {
                $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
        }
        Page::updatePage('message', $input['message_page'],$input['lang'], $input['message_title'], $input['message_content'], $input['message_image']);

        return redirect()->route('admin.pages.message')->with('success', 'Update successfully');
    }

    public function termsLang(Request $request) {
        if (!$this->user->can(['access-all', 'admin-panel', 'change_setting','change_setting-all', 'display_change_setting', 'update_change_setting', 'delete_change_setting', 'show_change_setting','add_change_setting'])) {
            return $this->pageUnauthorized();
        }
        $page_active = 1;
        $page_name = 'terms';
        $data = Page::get_PageType('terms', $this->limit);
        $mainLanguage = Language::get_Languag('is_active', 1, 'lang', 1);
        return view('admin.pages.pages_lang', compact('data', 'mainLanguage', 'page_name'))->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function terms($lang_id, $lang = 'ar') {
        if (!$this->user->can('access-all')) {
            return $this->pageUnauthorized();
        }
        $terms_image = $terms_content = $terms_page = $terms_title = NULL;
        $type='terms';
        $All_pages = Page::get_typeColum($type,$lang);
        if (isset($All_pages->id)) {
            $terms_image = $All_pages->image;
            $terms_content = $All_pages->description;
            $terms_page = $All_pages->name;
            $terms_title = $All_pages->title;
        }
        return view('admin.pages.terms', compact('type','lang','lang_id','terms_page', 'terms_title', 'terms_content', 'terms_image'));
    }

    public function termsStore(Request $request) {
        if (!$this->user->can('access-all')) {
            return $this->pageUnauthorized();
        }

        $request->validate([
            'terms_page' => 'required',
            'terms_title' => 'required',
            'terms_content' => 'required',
//            'terms_image' => 'required',
        ]);

        $input = $request->all();
        foreach ($input as $key => $value) {
            if ($key != 'terms_content') {
                $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
        }
        Page::updatePage('terms', $input['terms_page'], $input['lang'], $input['terms_title'], $input['terms_content'], $input['terms_image']);

        return redirect()->route('admin.pages.terms')->with('success', 'Update successfully');
    }

}
