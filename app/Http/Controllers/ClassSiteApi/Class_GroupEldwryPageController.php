<?php

namespace App\Http\Controllers\ClassSiteApi;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Page;
use App\Models\Game;
use App\Models\GameHistory;
use App\Models\Eldwry;
use App\Models\Subeldwry;
use App\Models\Options;
use App\Models\GroupEldwry;
use App\Http\Controllers\SiteController;

class Class_GroupEldwryPageController extends SiteController {

    public function __construct() {
        parent::__construct();
        emptySession();
    }

    public function Page_group_eldwry_Site($user_data,$ajax=0){

        $lang=$this->lang;
        $msg_finish_dwry =$redirect_route= '';
        // $page = Page::get_typeColum('group_eldwry',$lang);
        $page_title = trans('app.saudi_league_fantasy');//$page->title;
        $title = $page_title . " - " . $this->site_title;
        $logo_image = $this->logo_image;
        View::share('title', $title);
        View::share('activ_menu', 1);
        $found_point=GameHistory::check_foundData('user_id',$user_data->id);
        $type_page='group_eldwry';
        $start_dwry = Subeldwry::get_startFirstSubDwry();
        if (isset($start_dwry->id)) {
           $game = Game::checkregisterDwry($user_data->id, $start_dwry->eldwry_id, 1);
           if (isset($game->id)) {
                $current_url_page='';
                if($ajax==1){
                    $current_url_page=route('game.group_eldwry');
                    $url_name='site.group_eldwry.page';
                }else{
                    $url_name='site.group_eldwry.index';
                }
                $array_best_team=User::BestTeam(Auth::user());
                $team_image_fav=$array_best_team['team_image_fav'];
                $return_data= array('found_point'=>$found_point,'redirect_route'=>$redirect_route,'url_name'=>$url_name,'logo_image'=>$logo_image, 'lang'=>$lang,'type_page'=>$type_page,'current_url_page'=>$current_url_page,'team_image_fav'=>$team_image_fav);
            } else {
                $redirect_route='game';
                $return_data=array('found_point'=>$found_point,'redirect_route'=>$redirect_route);
            }
        } else {
            $redirect_route='home';
            $return_data=array('found_point'=>$found_point,'redirect_route'=>$redirect_route);
        }
        return $return_data;
    }

    //******************************************************  
    public function Page_create_groupEldwry($user_data,$type_page='create',$ajax=0){
        $lang=$this->lang;
        $current_url_page=$redirect_route= '';
        $found_point=0;
        // $page = Page::get_typeColum('group_eldwry',$lang);
        $page_title = trans('app.saudi_league_fantasy');//$page->title;
        $title = $page_title . " - " . $this->site_title;
        View::share('title', $title);
        View::share('activ_menu', 1);
        if($ajax==1){
            $current_url_page=route('game.group_eldwry.'.$type_page);
            $url_name='site.group_eldwry.'.$type_page;
        }else{
        	$found_point=GameHistory::check_foundData('user_id',$user_data->id);
            $url_name='site.group_eldwry.operation';
        }        
        $array_best_team=User::BestTeam(Auth::user());
        $team_image_fav=$array_best_team['team_image_fav'];
        return array('url_name'=>$url_name, 'lang'=>$lang,'type_page'=>$type_page,'current_url_page'=>$current_url_page,'redirect_route'=>$redirect_route,'found_point'=>$found_point,'team_image_fav'=>$team_image_fav);
    }

    public function Page_create_done_groupEldwry($user_data,$type_group='classic',$ajax=0){
        $lang=$this->lang;
        $current_url_page =$redirect_route= '';
        $found_point=0;
        // $page = Page::get_typeColum('group_eldwry',$lang);
        $page_title = trans('app.saudi_league_fantasy');//$page->title;
        $title = $page_title . " - " . $this->site_title;
        View::share('title', $title);
        View::share('activ_menu', 1);
        $type_page='create_done';
        if($ajax==1){
            $current_url_page=route('game.group_eldwry.create_done');
            $url_name='site.group_eldwry.create_done';
        }else{
        	$found_point=GameHistory::check_foundData('user_id',$user_data->id);
            $url_name='site.group_eldwry.operation';
        }       
        $array_best_team=User::BestTeam(Auth::user());
        $team_image_fav=$array_best_team['team_image_fav'];
        return array('url_name'=>$url_name, 'lang'=>$lang,'type_group'=>$type_group,'type_page'=>$type_page,'current_url_page'=>$current_url_page,'redirect_route'=>$redirect_route,'found_point'=>$found_point,'team_image_fav'=>$team_image_fav);
    }

    public function Page_invite_groupEldwry($user_data,$type_page='invite',$ajax=0,$link='',$type_group='classic'){
        $lang=$this->lang;
        $current_url_page =$redirect_route= $url_name='';
        $found_point=0;
        // $page = Page::get_typeColum('group_eldwry',$lang);
        $page_title = trans('app.saudi_league_fantasy');//$page->title;
        $title = $page_title . " - " . $this->site_title;
        View::share('title', $title);
        View::share('activ_menu', 1);
        if($ajax==1){
            $current_url_page=route('game.group_eldwry.'.$type_page,[$type_group,$link]);
            $url_name='site.group_eldwry.'.$type_page;
        }else{
            if($type_page == 'accept_invite'){
                $start_dwry = Eldwry::get_currentDwry();
                if (isset($start_dwry->id)) {
                    $current_game = Game::checkregisterDwry($user_data->id, $start_dwry->id, 1);
                    if(!isset($current_game->id)||(isset($current_game->id) && empty($current_game->team_name))){
                        $redirect_route='game.index';
                    }
                }else{
                    $redirect_route='home';
                }    
            }
            if(empty($redirect_route)){
                $found_point=GameHistory::check_foundData('user_id',$user_data->id);
                $url_name='site.group_eldwry.operation';
            }
        }
        $array_best_team=User::BestTeam(Auth::user());
        $team_image_fav=$array_best_team['team_image_fav'];
        return array('url_name'=>$url_name, 'lang'=>$lang,'type_page'=>$type_page,'current_url_page'=>$current_url_page,'redirect_route'=>$redirect_route,'found_point'=>$found_point,'team_image_fav'=>$team_image_fav);
    }

    public function Page_join_groupEldwry($user_data,$ajax=0, $type_page='join',$type_group='classic'){
        $lang=$this->lang;
        $current_url_page =$redirect_route= '';
        $found_point=0;
        // $page = Page::get_typeColum('group_eldwry',$lang);
        $page_title = trans('app.saudi_league_fantasy');//$page->title;
        $title = $page_title . " - " . $this->site_title;
        View::share('title', $title);
        View::share('activ_menu', 1);
        if($ajax==1){
            $current_url_page=route('game.group_eldwry.'.$type_page);
            $url_name='site.group_eldwry.'.$type_page;
        }else{
        	$found_point=GameHistory::check_foundData('user_id',$user_data->id);
            $url_name='site.group_eldwry.operation';
        }       
        $array_best_team=User::BestTeam(Auth::user());
        $team_image_fav=$array_best_team['team_image_fav'];
        return array('url_name'=>$url_name, 'lang'=>$lang,'type_page'=>$type_page,'current_url_page'=>$current_url_page,'redirect_route'=>$redirect_route,'found_point'=>$found_point,'team_image_fav'=>$team_image_fav,'type_group'=>$type_group);
    }

    public function Page_group_groupEldwry($user_data,$ajax=0,$link='',$type_group='classic'){
        $lang=$this->lang;
        $current_url_page =$redirect_route= '';
        $found_point=0;
        // $page = Page::get_typeColum('group_eldwry',$lang);
        $page_title = trans('app.saudi_league_fantasy');//$page->title;
        $title = $page_title . " - " . $this->site_title;
        View::share('title', $title);
        View::share('activ_menu', 1);
        $type_page='group';
        if($ajax==1){
            $current_url_page=route('game.group_eldwry.group',[$type_group,$link]);
            $url_name='site.group_eldwry.group';
        }else{
        	$found_point=GameHistory::check_foundData('user_id',$user_data->id);
            $url_name='site.group_eldwry.operation';
        }   
        $array_best_team=User::BestTeam(Auth::user());
        $team_image_fav=$array_best_team['team_image_fav'];
        return array('url_name'=>$url_name, 'lang'=>$lang,'type_page'=>$type_page,'current_url_page'=>$current_url_page,'redirect_route'=>$redirect_route,'found_point'=>$found_point,'team_image_fav'=>$team_image_fav);
    }

    public function Page_admin_groupEldwry($user_data,$ajax=0,$link='',$type_group='classic'){
        $lang=$this->lang;
        $current_url_page =$redirect_route= '';
        $found_point=0;
        // $page = Page::get_typeColum('group_eldwry',$lang);
        $page_title = trans('app.saudi_league_fantasy');//$page->title;
        $title = $page_title . " - " . $this->site_title;
        View::share('title', $title);
        View::share('activ_menu', 1);
        $type_page='admin';
        if($ajax==1){
            $current_url_page=route('game.group_eldwry.adminlink',[$type_group,$link]);
            $url_name='site.group_eldwry.admin';
        }else{
        	$found_point=GameHistory::check_foundData('user_id',$user_data->id);
            $url_name='site.group_eldwry.operation';
        }        
        $array_best_team=User::BestTeam(Auth::user());
        $team_image_fav=$array_best_team['team_image_fav'];
       return array('link'=>$link,'url_name'=>$url_name, 'lang'=>$lang,'type_page'=>$type_page,'current_url_page'=>$current_url_page,'redirect_route'=>$redirect_route,'found_point'=>$found_point,'team_image_fav'=>$team_image_fav);
    }

}