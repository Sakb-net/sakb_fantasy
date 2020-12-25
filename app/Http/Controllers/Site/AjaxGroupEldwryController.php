<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\ClassSiteApi\Class_GroupEldwryController;
use App\Http\Controllers\ClassSiteApi\Class_GroupEldwryPageController;

class AjaxGroupEldwryController extends SiteController {

    public function __construct() {
        parent::__construct();
        $this->Class_GroupEldwry =new Class_GroupEldwryController();
        if (isset(Auth::user()->id)) {
            $this->current_id = Auth::user()->id;
            $this->user_key = Auth::user()->name;
        }
    }
    public function get_normal_eldwry(Request $request) {
        if ($request->ajax()) {
            $response ='' ;
            $data= $this->Class_GroupEldwry->get_group_eldwry(Auth::user(),0,$this->lang,0,'classic');
            return response()->json(['user_id' =>Auth::user()->id,'data'=>$data]);
        }
    } 

    public function get_head_eldwry(Request $request) {
        if ($request->ajax()) {
            $response ='' ;
            $data= $this->Class_GroupEldwry->get_group_eldwry(Auth::user(),0,$this->lang,0,'head');
            return response()->json(['user_id' =>Auth::user()->id,'data'=>$data]);
        }
    }
    public function tab_menu_groupEldwry(Request $request) {
        if ($request->ajax()) {
            $input = $request->all();
            $type_page = stripslashes(trim(filter_var($input['type_page'], FILTER_SANITIZE_STRING)));
            $link = stripslashes(trim(filter_var($input['link'], FILTER_SANITIZE_STRING)));
            $type_group = stripslashes(trim(filter_var($input['type_group'], FILTER_SANITIZE_STRING)));
            if (empty($type_page)){
                $type_page='create';
            } 
            $get_data =new Class_GroupEldwryPageController();
            if(in_array($type_page,['create','create_classic','create_head'])){
            	$page_data= $get_data->Page_create_groupEldwry(Auth::user(),$type_page,1);
            }elseif($type_page=='create_done'){
                $page_data= $get_data->Page_create_done_groupEldwry(Auth::user(),$type_group,1); 
            }elseif($type_page=='invite'){
                $page_data= $get_data->Page_invite_groupEldwry(Auth::user(),'invite',1,$link,$type_group);
            }elseif(in_array($type_page,['join','join_classic','join_head'])){
                $page_data= $get_data->Page_join_groupEldwry(Auth::user(),1,$type_page,$type_group); 
            }elseif($type_page=='group'){
                $page_data= $get_data->Page_group_groupEldwry(Auth::user(),1,$link,$type_group); 
            }elseif($type_page=='admin'){
                $page_data= $get_data->Page_admin_groupEldwry(Auth::user(),1,$link,$type_group); 
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

    public function get_current_sub_eldwry(Request $request) {
        if ($request->ajax()) {
            $response = $link_group = $type_group ='' ;
            $input = $request->all();
            foreach ($input as $key => $value) {
                $$key= stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            $page_data= $this->Class_GroupEldwry->get_current_sub_eldwry(Auth::user(),$link_group,$type_group);
            return response()->json(['status' =>1, 'response' => $response,'data'=>$page_data]);
        }
    }

    public function store_groupEldwry(Request $request) {
        if ($request->ajax()) {
           	$response ='' ;
            $input = $request->all();
            foreach ($input as $key => $value) {
                $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            $page_data= $this->Class_GroupEldwry->store_groupEldwry(Auth::user(),$input,$this->lang);
            
            if(!empty($page_data['url_name'])){
                $response = view($page_data['url_name'],$page_data)->render();
            }            
            return response()->json(['status' =>$page_data['status'],'group_eldwry'=>$page_data['group_eldwry'],'current_url_page'=>$page_data['current_url_page'], 'response' => $response]);
        }
    }
    public function store_head_groupEldwry(Request $request) {
        if ($request->ajax()) {
            $response ='' ;
            $input = $request->all();
            foreach ($input as $key => $value) {
                $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            $page_data= $this->Class_GroupEldwry->store_head_groupEldwry(Auth::user(),$input,$this->lang);
            
            if(!empty($page_data['url_name'])){
                $response = view($page_data['url_name'],$page_data)->render();
            }            
            return response()->json(['status' =>$page_data['status'],'group_eldwry'=>$page_data['group_eldwry'],'current_url_page'=>$page_data['current_url_page'], 'response' => $response]);
        }
    }
    public function send_invite_emailphone(Request $request) {
        if ($request->ajax()) {
            $response ='' ;
            $input = $request->all();
            $input['type_group']='classic';
            foreach ($input as $key => $value) {
                $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            $page_data= $this->Class_GroupEldwry->send_invite_emailphone(Auth::user(),$input,0,$input['type_group']);
            
            return response()->json(['status' =>$page_data['status'],'val_msg'=>$page_data['val_msg'],'response' => $response]);
        }
    }

    public function get_last_group_eldwry(Request $request) {
        if ($request->ajax()) {
            $response ='' ;
            $input = $request->all();
            $type_group = stripslashes(trim(filter_var($input['type_group'], FILTER_SANITIZE_STRING)));
            $page_data= $this->Class_GroupEldwry->get_last_group_eldwry(Auth::user(),'',0,$type_group);
            return response()->json(['status' =>1, 'response' => $response,'group_eldwry'=>$page_data]);
        }
    }

    public function setting_invite_group_eldwry(Request $request) {
        if ($request->ajax()) {
            $response =$link=$type_group='' ;
            $input = $request->all();
            foreach ($input as $key => $value) {
                $$key = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            $page_data= $this->Class_GroupEldwry->setting_invite_group_eldwry(Auth::user(),$link,0,$type_group);
            return response()->json(['status' =>1, 'response' => $response,'data'=>$page_data]);
        }
    }

    public function setting_admin_group_eldwry(Request $request) {
        if ($request->ajax()) {
            $response =$link=$type_group='' ;
            $input = $request->all();
            foreach ($input as $key => $value) {
                $$key = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            $page_data= $this->Class_GroupEldwry->setting_admin_group_eldwry(Auth::user(),$link,0,$type_group);
            return response()->json(['status' =>1, 'response' => $response,'data'=>$page_data]);
        }
    }

    public function operation_group_eldwry(Request $request) {
        if ($request->ajax()) {
            $status=0;
            $users_group=[];
            $response =$link=$type_page=$type_group=$user_name='' ;
            $input = $request->all();
            foreach ($input as $key => $value) {
                $$key = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }   
            if($type_page =='stop'){
                $status= $this->Class_GroupEldwry->stop_group_eldwry(Auth::user(),$link,0,$type_group);
            }elseif($type_page =='leave'){
                $status= $this->Class_GroupEldwry->leave_group_eldwry(Auth::user(),$link,0,$type_group);
            }elseif($type_page =='add_admin'){
                $status= $this->Class_GroupEldwry->add_admin_group_eldwry(Auth::user(),$link,$user_name,0,$type_group);
            }elseif($type_page =='delete_player'){
              $array_data = $this->Class_GroupEldwry->delete_player_group_eldwry(Auth::user(),$link,$user_name,0,$type_group);
                $status = $array_data['update'];
                $users_group = $array_data['users_group'];
            }       
            return response()->json(['status' =>$status,'users_group'=>$users_group, 'response' => $response]);
        }
    }

    public function add_join_group_eldwry(Request $request) {
        if ($request->ajax()) {
            $response ='' ;
            $input = $request->all();
            foreach ($input as $key => $value) {
                $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            $page_data= $this->Class_GroupEldwry->add_join_group_eldwry(Auth::user(),$input,0,$input['type_group']);
            return response()->json(['status' =>$page_data['status'],'response' => $response]);
        }
    }

    public function get_data_group_eldwry(Request $request) {
        if ($request->ajax()) {
            $response =$link =$sub_eldwry_link=$type_group='' ;
            $input = $request->all();
            if(isset($input['link'])){
                $link = stripslashes(trim(filter_var($input['link'], FILTER_SANITIZE_STRING)));
            }
            $type_group = stripslashes(trim(filter_var($input['type_group'], FILTER_SANITIZE_STRING)));
            if(isset($input['sub_eldwry_link'])){
                $sub_eldwry_link = stripslashes(trim(filter_var($input['sub_eldwry_link'], FILTER_SANITIZE_STRING)));
            }
            $data= $this->Class_GroupEldwry->get_data_group_eldwry(Auth::user(),$link,$sub_eldwry_link,$this->lang,0,$type_group);
            return response()->json(['user_id' =>Auth::user()->id,'data'=>$data]);
        }
    }


}
