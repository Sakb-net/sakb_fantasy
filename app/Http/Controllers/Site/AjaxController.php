<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Options;
use App\Models\User;
use App\Models\Category;
use App\Models\Blog;
use App\Models\Language;
use DB;
use Session;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\ClassSiteApi\Class_PageGameController;
use App\Http\Controllers\ClassSiteApi\Class_GroupEldwryController;
use App\Http\Controllers\ClassSiteApi\Class_GroupEldwryPageController;
use App\Http\Controllers\ClassSiteApi\Class_PageController;

class AjaxController extends SiteController {

    public function __construct() {
        parent::__construct();
//        $this->middleware('auth');
//        $this->middleware(function ($request, $next) {
//            $this->user = auth()->user();
//            return $next($request);
//        });

        // $this_data = Options::Site_Option();
        // $this->site_open = $this_data['site_open'];
        // $this->site_title = $this_data['site_title'];
        // $this->limit = $this_data['limit'];
        // $this->current_id = $this_data['current_id'];
        if (isset(Auth::user()->id)) {
            $this->current_id = Auth::user()->id;
            $this->user_key = Auth::user()->name;
        }
    }

    public function changeLanguage(Request $request) {
        if ($request->ajax()) {
            Language::ADDLanguageActive($request->locale);
            //save in table user->lang
            if (Auth::user()) {
                User::updateColum(Auth::user()->id, 'lang', $request->locale);
            }
        }
//        print_r(session()->get('locale'));die;
        return response()->json(['status' => '1']);
    }

    public function tab_menu_gameTeam(Request $request) {
        if ($request->ajax()) {
            $input = $request->all();
            $type_page = stripslashes(trim(filter_var($input['type_page'], FILTER_SANITIZE_STRING)));
            if (empty($type_page)){
                $type_page='my_team';
            } 
            $get_data =new Class_PageGameController();
            if($type_page=='game_transfer'){
            $page_data= $get_data->Page_game_transfer_Site(Auth::user(),1);
            }elseif($type_page=='my_point'){
                $page_data= $get_data->Page_my_point_Site(Auth::user(),1); 
            }elseif($type_page=='group_eldwry'){
                $get_group = new Class_GroupEldwryPageController();
                $page_data= $get_group->Page_group_eldwry_Site(Auth::user(),1);
            }else{ //$type_page=='my_team'
                $page_data= $get_data->Page_my_team_Site(Auth::user(),1);
            }
            if(empty($page_data['redirect_route'])){
                $response = view($page_data['url_name'],$page_data)->render();
                return response()->json(['status' =>1, 'response' => $response,'redirect_route'=>$page_data['redirect_route'],'current_url_page'=>$page_data['current_url_page']]);
            }else{
                $response =0;
                return response()->json(['status' =>1, 'response' => $response,'redirect_route'=>$page_data['redirect_route']]);
            }
        }
    }

//**************************************page:validation register ******************************************************
    public function check_found_email(Request $request) {
        if ($request->ajax()) {
            $response = 1;
            $input = $request->all();
            $user_email = stripslashes(trim(filter_var($input['user_email'], FILTER_SANITIZE_STRING)));
            if (!filter_var($user_email, FILTER_VALIDATE_EMAIL) === false || $user_email == '') {
                if ($user_email != '') {
                    $user_id = User::foundUser($user_email, 'email');
                    if ($user_id > 0) {
                        $response = 0; //email use
                    }
                }
            } else {
                $response = 2;
            }
            return response()->json(['status' => '1', 'response' => $response]);
        }
    }

    public function check_found_phone(Request $request) {
        if ($request->ajax()) {
            $response = 1;
            $input = $request->all();
            $user_phone = stripslashes(trim(filter_var($input['user_phone'], FILTER_SANITIZE_STRING)));
            if (preg_match("/^[0-9]{8,16}$/", $user_phone) || $user_phone == '') {
                if ($user_phone != '') {
                    $user_id = User::foundUser($user_phone, 'phone');
                    if ($user_id > 0) {
                        $response = 0; //phone use
                    }
                }
            } else {
                $response = 2;
            }
            return response()->json(['status' => '1', 'response' => $response]);
        }
    }

//**************************************page:profile user ******************************************************
    public function add_image_user(Request $request) {
        if ($request->ajax() && isset(Auth::user()->id)) {
            $response = 0;
            $add_image = new AttachmentController();
            $path = $add_image->ImageUpload($request, 'profiles');
            if (!empty($path)) {
                $response = 1;
                //$this->current_id
                //plz,remove old
                //save image user
                $update_image = User::updateColum(Auth::user()->id, 'image', $path);
//                $path=GetSize_Image($path,61,61);
            } else {
                $path = '';
            }
            return response()->json(['response' => $response, 'path' => $path]);
        }
    }

//***********************************************************
    public function add_contact_Us(Request $request) {
        if ($request->ajax()) {
            $response = '';
            $input['type'] = 'contact';
            $input['visitor'] = $request->ip(); //'ip';
            foreach ($request->input() as $key => $value) {
                $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            $insert_commment = new Class_PageController();
            $result_commment = $insert_commment->add_contact_Us($input);
            $state_add = $result_commment['state_add'];
            if ($state_add == 1) {
                $all_comment = array('correct' => trans('app.send_success'));
            } else {
                $all_comment = array('wrong' => trans('app.send_failed'));
            }
            $response = view('site.layouts.correct_wrong', $all_comment)->render();
            return response()->json(['state_add' => $state_add, 'response' => $response]);
        }
    }

//***********For EXample Test***********************
    public function ajax_pagination(Request $request) {
        if ($request->ajax()) {
            $input = $request->all();
            $limit=3;
            $offset=$input['offset']-1;
            $offset=$limit*$offset;
            // print_r($input['flage']);
            if($input['flage']==0){
                $data = Blog::paginate($limit);
                $response = view('site.test.presult', compact('data'))->render();
            }else{
                $data = Blog::offset($offset)->limit($limit)->get();
               $response = view('site.test.ajax', compact('data'))->render();
            }
            return response()->json(['status' => '1', 'response' => $response]);
        }
    }

    public function search_champions(Request $request) {
        if ($request->ajax()) {
            $data = [];
            $all = 0;
            $type = 'champion';
            $input = $request->all();
            if (!empty($input['up_link'])) {
                $category_main = Category::get_categoryCloum('link', $input['up_link'], 1);
                if (isset($category_main->id)) {
                    $lang = 'ar';
                    if (isset($category_main->lang)) {
                        $lang = $category_main->lang;
                    }
                    if (!empty($input['team_link'])) {
                        $category_sub = Category::get_categoryCloum('link', $input['team_link'], 1);
                        if (isset($category_sub->id)) {
                            $data = $category_sub->childrens->where('type', $type);
                        } else {
                            $all = 1;
                        }
                    } else {
                        $all = 1;
                    }
                    if ($all == 1) {
                        $data = Category::get_DataChild_child($category_main->id, $category_main->childrens, $type);
                    }
                }
            }
            $response = view('site.pages.champions_loop', compact('data'))->render();
            return response()->json(['status' => '1', 'response' => $response]);
        }
    }

//*******************************************
}

//return response()->json