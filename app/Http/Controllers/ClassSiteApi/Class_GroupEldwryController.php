<?php

namespace App\Http\Controllers\ClassSiteApi;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Game;
use App\Models\Eldwry;
use App\Models\Subeldwry;
use App\Models\Options;
use App\Models\GroupEldwry;
use App\Models\GroupEldwryUser;
use App\Models\GroupEldwryInvite;
use App\Models\GroupEldwryStatic;
use App\Models\GroupEldwryTeamStatic;
use App\Models\HeadGroupEldwry;
use App\Models\HeadGroupEldwryUser;
use App\Models\HeadGroupEldwryTeamStatic;
use App\Models\HeadGroupEldwryMatch;
use App\Models\HeadGroupEldwryStatic;
use App\Http\Controllers\SiteController;
use App\Http\Resources\SubeldwryResource;
use App\Http\Resources\GroupEldwryResource;
use App\Http\Resources\GroupEldwryWithStaticResource;
use App\Http\Resources\GroupEldwryStaticResource;
use App\Http\Resources\GroupEldwryUserResource;
use App\Http\Resources\GroupEldwryTeamStaticResource;
use App\Http\Resources\HeadGroupEldwryStaticResource;
use App\Http\Resources\HeadGroupEldwryMatchResource;
use App\Http\Resources\HeadGroupEldwryTeamStaticResource;

class Class_GroupEldwryController extends SiteController {

    public function __construct() {
        parent::__construct();
    	emptySession();
    }

    //******************************************************
    //statistics_group_eldwry
    public function get_group_eldwry($user_data,$ajax=0,$lang='ar',$api=0,$type_group='classic'){
        $subeldwry = Subeldwry::get_BeforCurrentSubDwry();//get_BeforAndCurrentSubDwry(1,'',2);
        $group_eldwry_static=[];
        if(isset($subeldwry->id)){
            if($type_group=='head'){                
                $all_group_eldwry=HeadGroupEldwryStatic::Joingroup_Currenteldwry($user_data->id,[$subeldwry->id],1);
                $group_eldwry_static=HeadGroupEldwryStaticResource::collection($all_group_eldwry);
            }else{
                $all_group_eldwry=GroupEldwryStatic::Joingroup_Currenteldwry($user_data->id,[$subeldwry->id],1);
                $group_eldwry_static=GroupEldwryStaticResource::collection($all_group_eldwry);
            }
        }
        return array('group_eldwry'=>$group_eldwry_static);
    }
    
    //get standings
    public function get_data_group_eldwry($user_data,$link='',$sub_eldwry_link='',$lang='ar',$api=0,$type_group='classic'){
        $owner=0;
        $matches_group=[];
        if(empty($sub_eldwry_link)){
            $subeldwry = Subeldwry::get_BeforCurrentSubDwry();
        }else{
            $subeldwry = Subeldwry::foundDataTwoCondition('link',$sub_eldwry_link,'is_active', 1);  
        }
        $group_eldwry=$this->get_last_group_eldwry($user_data,$link,0,$type_group);
        if(isset($user_data->id) && $group_eldwry->user_id==$user_data->id){
            $owner=1;
        }
        if($type_group=='head'){  
            //get standings of head
            $static_users_group=HeadGroupEldwryTeamStatic::All_foundDataTwoCondition('head_group_eldwry_id',$group_eldwry->id,'sub_eldwry_id',$subeldwry->id,'points','DESC');
            $users_group=HeadGroupEldwryTeamStaticResource::collection($static_users_group);
            //get matches of head
            $static_matches_group=HeadGroupEldwryMatch::All_foundDataTwoCondition('head_group_eldwry_id',$group_eldwry->id,'sub_eldwry_id',$subeldwry->id,'sort','ASC');
            $matches_group=HeadGroupEldwryMatchResource::collection($static_matches_group);
        }else{
            //get standings of classic
            $static_users_group=GroupEldwryTeamStatic::All_foundDataTwoCondition('group_eldwry_id',$group_eldwry->id,'sub_eldwry_id',$subeldwry->id,'sort','ASC');
            $users_group=GroupEldwryTeamStaticResource::collection($static_users_group);
        }
       return array('group_eldwry'=>$group_eldwry,'owner'=>$owner,'users_group'=>$users_group,'matches_group'=>$matches_group);
    }

    public function get_current_sub_eldwry($user_data,$link_group='',$type_group='classic'){
    	$return_data=[];
        $eldwry = Eldwry::get_currentDwry();
        if(isset($eldwry->id)){
            $start_sub_eldwry_id = 0;
            if(!empty($link_group)){
                if($type_group=='head'){
                    $group_eldwry=HeadGroupEldwry::foundDataTwoCondition('link',$link_group,'is_active', 1);
                }else{
                    $group_eldwry=GroupEldwry::foundDataTwoCondition('link',$link_group,'is_active', 1);
                }
                if(isset($group_eldwry->id)){
                    $start_sub_eldwry_id = $group_eldwry->start_sub_eldwry_id;  
                }
            }
            $subeldwry = Subeldwry::getSubeldwryByLargeEqual($eldwry->id, 1,$start_sub_eldwry_id);
	       	$return_data= SubeldwryResource::collection($subeldwry);
	    }
        return $return_data;
    }

    public function store_groupEldwry($user_data,$input,$lang='ar',$api=0){
    	$status=0;
        $type_group='classic';
    	$current_url_page=$url_name='';
        $group_eldwry='';
    	$input['user_id'] =$user_data->id;
       	$subeldwry = Subeldwry::foundDataTwoCondition('link',$input['link_subeldwry'],'is_active', 1);
        if(isset($subeldwry->id)){
			$game = Game::checkregisterDwry($user_data->id, $subeldwry->eldwry_id, 1);
    		$input['game_id'] =$game->id;
            //link_group
            if($input['update']==1){
                //update
                $data_status=GroupEldwry::updateGroup($input,$subeldwry,$lang);
                $group_eldwry=$data_status['data'];
                $status=$data_status['status'];
            }else{
                //add
    	    	$group_eldwry=GroupEldwry::insertGroup($input,$subeldwry,$lang);
    	    	$status=1;
                $current_url_page=route('game.group_eldwry.create_done',$type_group);
                $url_name='site.group_eldwry.create_done';
            }    
	    }
    	return array('status'=>$status,'current_url_page'=>$current_url_page,'url_name'=>$url_name,'group_eldwry'=>$group_eldwry,'type_group'=>$type_group);
    }

    public function store_head_groupEldwry($user_data,$input,$lang='ar',$api=0){
        $status=0;
        $type_group='head';
        $current_url_page=$url_name='';
        $group_eldwry='';
        $input['user_id'] =$user_data->id;
        $subeldwry = Subeldwry::foundDataTwoCondition('link',$input['link_subeldwry'],'is_active', 1);
        if(isset($subeldwry->id)){
            $game = Game::checkregisterDwry($user_data->id, $subeldwry->eldwry_id, 1);
            $input['game_id'] =$game->id;
            //link_group
            if($input['update']==1){
                //update
                $data_status=HeadGroupEldwry::updateGroup($input,$subeldwry,$lang);
                $group_eldwry=$data_status['data'];
                $status=$data_status['status'];
            }else{
                //add
                $data_status=HeadGroupEldwry::insertGroup($input,$subeldwry,$lang);
                $group_eldwry=$data_status['data'];
                $status=$data_status['status'];
                $current_url_page=route('game.group_eldwry.create_done',$type_group);
                $url_name='site.group_eldwry.create_done';
            }    
        }
        return array('status'=>$status,'current_url_page'=>$current_url_page,'url_name'=>$url_name,'group_eldwry'=>$group_eldwry,'type_group'=>$type_group);
    }
    public function send_invite_emailphone($user_data,$input,$api,$type_group='classic'){
        $status=$send=0;
        $val_msg=$input['email_reciver']=$input['phone_reciver']='';
        $input['user_id']=$user_data->id;
        if (filter_var($input['emailphone'], FILTER_VALIDATE_EMAIL) === false) {
            if (preg_match("/^([+]?)[0-9]{8,16}$/", $input['emailphone'])) {
                //send whatsapp message
                $send=1;
                $input['phone_reciver']=$input['emailphone'];
            }else{
                $status=-1;
                $val_msg=trans('app.please_enter_correct_value'); //email_not_correct //please_phone_correct
            }
        }else{
            //send email
            $send=1;
            $input['email_reciver']=$input['emailphone'];
        }
        $group_eldwry=GroupEldwry::foundDataTwoCondition('link',$input['link_group'],'is_active', 1);
        if(isset($group_eldwry->id) && $send==1){
            GroupEldwryInvite::InsertInvite($input,$group_eldwry);
            $status=1;
        }
        return array('status'=>$status,'val_msg'=>$val_msg);
    }
    
    public function get_last_group_eldwry($user_data,$link='',$api=0,$type_group='classic'){
        $colum='link';
        $value=$link;
        if(empty($link)){
             //last group
            $colum='user_id';
            $value=$user_data->id;
        }
        if($type_group=='head'){
            $group_eldwry=HeadGroupEldwry::foundDataTwoCondition($colum,$value,'is_active', 1);
        }else{
            $group_eldwry=GroupEldwry::foundDataTwoCondition($colum,$value,'is_active', 1);
        }
        return new GroupEldwryResource($group_eldwry);
    }
    
    public function setting_invite_group_eldwry($user_data,$link='',$api=0,$type_group='classic'){
        $group_eldwry=$this->get_last_group_eldwry($user_data,$link,$api,$type_group);
       return array('group_eldwry'=>$group_eldwry);
    }
    
    public function setting_admin_group_eldwry($user_data,$link='',$api=0,$type_group='classic'){
        $group_eldwry=$this->get_last_group_eldwry($user_data,$link,$api,$type_group);
        if($type_group == 'head'){
            $all_users_group=HeadGroupEldwryUser::get_data_group_eldwry($group_eldwry->id,1,0);
        }else{
            $all_users_group=GroupEldwryUser::get_data_group_eldwry($group_eldwry->id,1,0);
        }
        $users_group=GroupEldwryUserResource::collection($all_users_group);
       return array('group_eldwry'=>$group_eldwry,'users_group'=>$users_group);
    }

    public function stop_group_eldwry($user_data,$link='',$api=0,$type_group='classic'){
        $colum='link';
        $value=$link;
        if(empty($link)){
             //last group
            $colum='user_id';
            $value=$user_data->id;
        }
        if($type_group=='head'){
            $group_eldwry=HeadGroupEldwry::foundDataTwoCondition($colum,$value,'is_active', 1);
        }else{
            $group_eldwry=GroupEldwry::foundDataTwoCondition($colum,$value,'is_active', 1);
        }
        return $group_eldwry->update(['is_active'=>0]);
    }

    public function add_admin_group_eldwry($user_data,$link='',$user_name='',$api=0,$type_group='classic'){
        $update=0;
        $colum='link';
        $value=$link;
        if(empty($link)){
             //last group
            $colum='user_id';
            $value=$user_data->id;
        }
        if($type_group=='head'){
            $group_eldwry=HeadGroupEldwry::foundDataTwoCondition($colum,$value,'is_active', 1);
        }else{
            $group_eldwry=GroupEldwry::foundDataTwoCondition($colum,$value,'is_active', 1);
        }
        $user=User::GetByColumValue('name',$user_name);
        if(isset($user->id)){
            $update=$group_eldwry->update(['user_id'=>$user->id]);
        }
        return $update;
    }

    public function delete_player_group_eldwry($user_data,$link='',$user_name='',$api=0,$type_group='classic'){
        $colum='link';
        $value=$link;
        if(empty($link)){
             //last group
            $colum='user_id';
            $value=$user_data->id;
        }
        $user=User::GetByColumValue('name',$user_name);
        if($type_group=='head'){
            $array_data=$this->delete_player_groupHead($user,$colum,$value,1);
        }else{
            $array_data=$this->delete_player_groupClassic($user,$colum,$value,1);
        }
       return $array_data; 
    }

    public function delete_player_groupHead($user,$colum,$value,$is_active=1){
        $update=0;
        $users_group=[];
        $group_eldwry=HeadGroupEldwry::foundDataTwoCondition($colum,$value,'is_active',$is_active);
        if(isset($user->id)){
            $update=HeadGroupEldwryUser::updateGroup($group_eldwry->id,$user->id,0,1);
            $all_users_group=HeadGroupEldwryUser::get_data_group_eldwry($group_eldwry->id,1,0);

            $users_group=HeadGroupEldwryUserResource::collection($all_users_group);
        }
        return array('update'=>$update,'users_group'=>$users_group);
    }

    public function delete_player_groupClassic($user,$colum,$value,$is_active=1){
        $update=0;
        $users_group=[];
        $group_eldwry=GroupEldwry::foundDataTwoCondition($colum,$value,'is_active',$is_active);
        if(isset($user->id)){
            $update=GroupEldwryUser::updateGroup($group_eldwry->id,$user->id,0,1);
            $all_users_group=GroupEldwryUser::get_data_group_eldwry($group_eldwry->id,1,0);

            $users_group=GroupEldwryUserResource::collection($all_users_group);
        }
        return array('update'=>$update,'users_group'=>$users_group);
    }
        
    public function leave_group_eldwry($user_data,$link='',$api=0,$type_group='classic'){
        $update=0;
        $colum='link';
        $value=$link;
        if(empty($link)){
             //last group
            $colum='user_id';
            $value=$user_data->id;
        }
        if($type_group=='head'){
            $group_eldwry=HeadGroupEldwry::foundDataTwoCondition($colum,$value,'is_active', 1);
            if(isset($group_eldwry->id)){
                $update=HeadGroupEldwryUser::updateGroup($group_eldwry->id,$user_data->id,0,0);
                $current_subeldwry = Subeldwry::get_CurrentSubDwry();
                $team_static = HeadGroupEldwryTeamStatic::leaveTeamMatchStaticUser($group_eldwry,$current_subeldwry,$user_data->id);
            }  
        }else{
            $group_eldwry=GroupEldwry::foundDataTwoCondition($colum,$value,'is_active', 1);
            if(isset($group_eldwry->id)){
                $update=GroupEldwryUser::updateGroup($group_eldwry->id,$user_data->id,0,0);
            }        
        }
        return $update;
    }

    public function add_join_group_eldwry($user_data,$input,$api=0,$type_group='classic'){
        $status=0;
        $val_colum=$input['link_group'];
        if($input['val_type']=='code'){
           $val_colum=$input['val_code'];
        } 
        // J1604924194
        if($type_group=='head'){
            $group_eldwry=HeadGroupEldwry::foundDataTwoCondition($input['val_type'], $val_colum,'is_active',1);
        }else{
            $group_eldwry=GroupEldwry::foundDataTwoCondition($input['val_type'], $val_colum,'is_active',1);
        }
        if(isset($group_eldwry->id)){
            $status = $this->insertJoinGroupEldwry($user_data,$group_eldwry,$type_group);
        }    
        return array('status'=>$status);
    }
    public function insertJoinGroupEldwry($user_data,$group_eldwry,$type_group='classic'){
        $status=-1;
        if($group_eldwry->user_id != $user_data->id){
            $game=Game::get_GameCloum('user_id',$user_data->id,1);
            if($type_group=='head'){
                $subeldwry = Subeldwry::get_CurrentSubDwry();
                $status=HeadGroupEldwryUser::insertGroup($group_eldwry,$user_data->id,$subeldwry,$game->id);
            }else{
                $subeldwry = Subeldwry::get_BeforCurrentSubDwry();
                $status=GroupEldwryUser::insertGroup($group_eldwry,$user_data->id,$game->id,$subeldwry->id);
            }
        }
        return $status;   
    }

}