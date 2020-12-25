<?php

namespace App\Http\Controllers\ClassSiteApi;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Page;
use App\Models\LocationPlayer;
use App\Models\Team;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\GameTransaction;
use App\Models\GameHistory;
use App\Models\GameSubstitutes;
use App\Models\Eldwry;
use App\Models\Subeldwry;
use App\Models\PointUser;
use App\Models\Options;
use App\Http\Controllers\SiteController;

use App\Http\Controllers\ClassSiteApi\Class_GameController;

class Class_PageGameController extends SiteController {

    public function __construct() {
        parent::__construct();
    }

    public function LocationPlayer_Team(){
        $lang=$this->lang;
        $team_name = '';
        $value_lang = 'value_' . $lang;
        $locations = LocationPlayer::All_foundData('is_active', 1);
        $teams = Team::All_foundData('is_active', 1);
        return array('locations'=>$locations, 'teams'=>$teams, 'lang'=>$lang, 'value_lang'=>$value_lang,'team_name'=>$team_name);
    }   

    public function Page_game_Site($user_data){
        $lang=$this->lang;
        $page = Page::get_typeColum('game',$lang);
        $page_title =trans('app.saudi_league_fantasy');// $page->title;
        $title = $page_title . " - " . $this->site_title;
        $logo_image = $this->logo_image;
        View::share('title', $title);
        View::share('activ_menu', 1);
        $redirect_route='';
        $type_page = 'game';
        $type = 'game';
        $team_image_fav='';
        $return_data=$this->CheckTime_StopSubeldwry();
        $array_best_team=User::BestTeam(Auth::user());
        $image_best_team=$array_best_team['image_best_team'];
        $return_data['team_image_fav']=$team_image_fav=$array_best_team['team_image_fav'];
        if($return_data['check_stop_subeldwry']!=1){
            $return_data=[];
            $array_start_dwry = Subeldwry::get_startFirstSubDwry(3,1,'',1);
            $inside_dwry =$array_start_dwry['inside_dwry'];
            $start_dwry =$array_start_dwry['data'];     
            if (isset($start_dwry->id)) {
                $value_lang = 'value_' . $lang;
                $msg_condition = trans('app.msg_condition');
                $end_change_date = $this->DateTime_StopTransfer($start_dwry->start_date);
                $total_team = $this->num_player;
                $total_team_play = 0;
                $remide_sum_cost = 0;
                $total_cost_play =0;
                $pay_total_cost=0;
                $game = Game::checkregisterDwry($user_data->id, $start_dwry->eldwry_id, 1);
                $team_name = '';
                if (isset($game->id)) {
                    $team_name = $game->team_name;
                    $all_play_game = GamePlayer::allPlayerGameUser('game_id', $game->id, 'is_active', 1, 'id', 'ASC');
                        $count_player_team=count($all_play_game);
                    if(!empty($team_name)){// && $count_player_team==$this->num_player
                        $redirect_route='game.my_team'; //'game.game_transfer'
                        
                    }
                    //total cost
                    $data_count_cost = GamePlayer::get_ALL_Finaltotal($user_data->id, $start_dwry, $game->id,1,0);
                    $total_cost_play=$data_count_cost['total_cost_play'];
                    $total_team_play = $data_count_cost['total_team_play'];
                    $remide_sum_cost = $data_count_cost['remide_sum_cost'];
                    $pay_total_cost=$data_count_cost['pay_total_cost'];
                }
                $locations_teams=$this->LocationPlayer_Team();
                $data = [];
                $url_name='site.games.index';
                $return_data=array('redirect_route'=>$redirect_route,'url_name'=>$url_name,'logo_image'=>$logo_image,'start_dwry'=>$start_dwry, 'team_name'=>$team_name, 'remide_sum_cost'=>$remide_sum_cost,'lang'=>$lang,'pay_total_cost'=>$pay_total_cost, 'total_cost_play'=>$total_cost_play, 'total_team_play'=>$total_team_play, 'total_team'=>$total_team, 'end_change_date'=>$end_change_date, 'msg_condition'=>$msg_condition, 'data'=>$data, 'type'=>$type,'type_page'=>$type_page, 'page_title'=>$page_title,'team_image_fav'=>$team_image_fav);
                $return_data=array_merge($return_data,$locations_teams);
            } else {
                //finish dwry ---> because not found current dwry active
                $register_dwry = 0;
                $msg_finish_dwry = trans('app.msg_finish_dwry');
                    //finish dwry 
                $data = [];
                $url_name='site.my_team.finish';
                $return_data=array('url_name'=>$url_name,'logo_image'=>$logo_image, 'msg_finish_dwry'=>$msg_finish_dwry, 'lang'=>$lang, 'data'=>$data, 'type'=>$type, 'page_title'=>$page_title);
            }
        }
        return $return_data;
    }

    public function Page_game_transfer_Site($user_data,$ajax=0){
        $this->BeforeEnter_GameTransfer($user_data);
        $lang=$this->lang;
        $register_dwry = 1;
        $msg_finish_dwry =$redirect_route= '';
        $substitutes_points=$count_free_weekgamesubstitute=0;
        $page = Page::get_typeColum('game',$lang);
        $page_title = trans('app.saudi_league_fantasy');//$page->title;
        $title = $page_title . " - " . $this->site_title;
        $logo_image = $this->logo_image;
        View::share('title', $title);
        View::share('activ_menu', 1);
        $type = 'game';
        $type_page='game_transfer';
        $team_image_fav='';
        $found_point=GameHistory::check_foundData('user_id',$user_data->id);
        $return_data=$this->CheckTime_StopSubeldwry();
        $array_best_team=User::BestTeam(Auth::user());
        $image_best_team=$array_best_team['image_best_team'];
        $return_data['team_image_fav']=$team_image_fav=$array_best_team['team_image_fav'];
        if($return_data['check_stop_subeldwry']!=1){
            $return_data=[];
            $array_start_dwry = Subeldwry::get_startFirstSubDwry(3,1,'',1);
            $inside_dwry =$array_start_dwry['inside_dwry'];
            $start_dwry =$array_start_dwry['data'];
            
            if (isset($start_dwry->id)) {
                $value_lang = 'value_' . $lang;
                $msg_condition = trans('app.msg_condition');
                $end_change_date = $this->DateTime_StopTransfer($start_dwry->start_date);
                $total_team = $this->num_player;
                $total_team_play = 0;
                $remide_sum_cost = 0;
                $pay_total_cost=0;
                $total_cost_play=0;
                // $total_cost_play = GameTransaction::sum_Finaltotal($user_data->id, $start_dwry->eldwry_id, 1);
                $game = Game::checkregisterDwry($user_data->id, $start_dwry->eldwry_id, 1);
                $team_name = '';
                if (isset($game->id)) {
                    $team_name = $game->team_name;
                    //total cost
                    $data_count_cost = GamePlayer::get_ALL_Finaltotal($user_data->id, $start_dwry, $game->id,1,0,1);
                    $total_cost_play=$data_count_cost['total_cost_play'];
                    $total_team_play = $data_count_cost['total_team_play'];
                    $remide_sum_cost = $data_count_cost['remide_sum_cost'];
                    $pay_total_cost=$data_count_cost['pay_total_cost'];
                    $substitutes_points=$data_count_cost['substitutes_points'];
                    $count_free_weekgamesubstitute =GameSubstitutes::countFreePointSubstitute(Auth::user()->id,$start_dwry->id,$game->id);
                }
                $locations_teams=$this->LocationPlayer_Team();
                $data = [];
                $current_url_page='';
                if($ajax==1){
                    $current_url_page=route('game.game_transfer');
                    $url_name='site.games.index_master';
                }else{
                    $url_name='site.games.transfer';
                }
                $return_data=array('found_point'=>$found_point,'current_url_page'=>$current_url_page,'redirect_route'=>$redirect_route,'url_name'=>$url_name,'substitutes_points'=>$substitutes_points,'count_free_weekgamesubstitute'=>$count_free_weekgamesubstitute,'logo_image'=>$logo_image,'start_dwry'=>$start_dwry, 'team_name'=>$team_name, 'remide_sum_cost'=>$remide_sum_cost,'lang'=>$lang, 'pay_total_cost'=>$pay_total_cost, 'total_cost_play'=>$total_cost_play, 'total_team_play'=>$total_team_play, 'total_team'=>$total_team,'end_change_date'=>$end_change_date, 'msg_condition'=>$msg_condition, 'data'=>$data, 'type'=>$type,'type_page'=>$type_page, 'page_title'=>$page_title,'team_image_fav'=>$team_image_fav);
                $return_data=array_merge($return_data,$locations_teams);
            } else {
                //finish dwry ---> because not found current dwry active
                $register_dwry = 0;
                $msg_finish_dwry = trans('app.msg_finish_dwry');
                //finish dwry 
                $data = [];
                $url_name='site.my_team.finish';
                $return_data=array('found_point'=>$found_point,'url_name'=>$url_name,'logo_image'=>$logo_image, 'msg_finish_dwry'=>$msg_finish_dwry, 'lang'=>$lang, 'data'=>$data, 'type'=>$type, 'page_title'=>$page_title);
            }
        }
        return $return_data;
    }

    public function Page_my_team_Site($user_data,$ajax=0){
        emptySession();
        $lang=$this->lang;
        $register_dwry = 1;
        $msg_finish_dwry =$redirect_route= '';
        $page = Page::get_typeColum('game_transfer',$lang);
        $page_title = trans('app.saudi_league_fantasy');//$page->title; 
        $title = $page_title . " - " . $this->site_title;
        $logo_image = $this->logo_image;
        View::share('title', $title);
        View::share('activ_menu', 1);
        $type = 'game';
        $type_page='my_team';
        $team_image_fav='';
        $found_point=GameHistory::check_foundData('user_id',$user_data->id);
        $return_data=$this->CheckTime_StopSubeldwry();
        $array_best_team=User::BestTeam(Auth::user());
        $image_best_team=$array_best_team['image_best_team'];
        $return_data['team_image_fav']=$team_image_fav=$array_best_team['team_image_fav'];
        if($return_data['check_stop_subeldwry']!=1){
            $return_data=[];
            $start_dwry = Subeldwry::get_startFirstSubDwry();
            $team_name ='';
            if (isset($start_dwry->id)) {
                $msg_condition = trans('app.deadline_end_game_week');
                $end_date_gameweek = date_lang_game($start_dwry->end_date, $lang);
               $game = Game::checkregisterDwry(Auth::user()->id, $start_dwry->eldwry_id, 1);
               if (isset($game->id)) {
                    $team_name = $game->team_name;
                    //total cost
                    $data_count_cost = GamePlayer::get_ALL_Finaltotal(Auth::user()->id, $start_dwry, $game->id,1,0);
                    $total_cost_play=$data_count_cost['total_cost_play'];
                    $total_team_play = $data_count_cost['total_team_play'];
                    $remide_sum_cost = $data_count_cost['remide_sum_cost'];
                    $pay_total_cost=$data_count_cost['pay_total_cost'];
                    if(empty($team_name)){// ||  $total_team_play<$this->num_player
                        $redirect_route='game.index';
                    }
                    $all_comment = [];
                    $current_url_page='';
                    if($ajax==1){
                        $current_url_page=route('game.my_team');
                        $url_name='site.my_team.page';
                    }else{
                        $url_name='site.my_team.index';
                    }
                    $array_total_points=PointUser::CalMath_Finaltotal(Auth::user()->id,$start_dwry->eldwry_id,$start_dwry->id,$game->id);
                    // 'data'=>$data, 
                    $return_data= array('start_dwry'=>$start_dwry,'end_date_gameweek'=>$end_date_gameweek,'found_point'=>$found_point,'current_url_page'=>$current_url_page,'redirect_route'=>$redirect_route,'url_name'=>$url_name,'logo_image'=>$logo_image, 'lang'=>$lang,'type'=>$type,'type_page'=>$type_page,'total_team_play'=>$total_team_play,'pay_total_cost'=>$pay_total_cost,'remide_sum_cost'=>$remide_sum_cost,'total_cost_play'=>$total_cost_play, 'page_title'=>$page_title,'image_best_team'=>$image_best_team,'team_name'=>$team_name,'msg_condition'=>$msg_condition,'team_image_fav'=>$team_image_fav);
                    $return_data=array_merge($array_total_points,$return_data);
                } else {
                    $redirect_route='game.index';
                    $return_data=array('start_dwry'=>$start_dwry,'found_point'=>$found_point,'redirect_route'=>$redirect_route);
                }
            } else {
                $redirect_route='home';
                $return_data=array('found_point'=>$found_point,'redirect_route'=>$redirect_route);
            }
        }
        return $return_data;
    }

    public function Page_my_point_Site($user_data,$ajax=0){
        emptySession();
        $lang=$this->lang;
        $register_dwry = 1;
        $msg_finish_dwry =$redirect_route= '';
        $page = Page::get_typeColum('game_transfer',$lang);
        $page_title = trans('app.saudi_league_fantasy');//$page->title;
        $title = $page_title . " - " . $this->site_title;
        $logo_image = $this->logo_image;
        View::share('title', $title);
        View::share('activ_menu', 1);
        $type = 'game';
        $type_page='my_point';
        $team_image_fav='';
        $found_point=GameHistory::check_foundData('user_id',$user_data->id);
        $return_data=$this->CheckTime_StopSubeldwry();
        $array_best_team=User::BestTeam(Auth::user());
        $image_best_team=$array_best_team['image_best_team'];
        $return_data['team_image_fav']=$team_image_fav=$array_best_team['team_image_fav'];
        if($return_data['check_stop_subeldwry']!=1){
            $return_data=[];
            $start_dwry = Subeldwry::get_startFirstSubDwry();
            $team_name ='';
           if (isset($start_dwry->id)) {
                $msg_condition = trans('app.deadline_end_game_week');
               $game = Game::checkregisterDwry(Auth::user()->id, $start_dwry->eldwry_id, 1);
               if (isset($game->id)) {
                    $team_name = $game->team_name;
                    //total cost
                    $data_count_cost = GamePlayer::get_ALL_Finaltotal(Auth::user()->id, $start_dwry, $game->id,1,0);
                    $total_cost_play=$data_count_cost['total_cost_play'];
                    $total_team_play = $data_count_cost['total_team_play'];
                    $remide_sum_cost = $data_count_cost['remide_sum_cost'];
                    $pay_total_cost=$data_count_cost['pay_total_cost'];
                    if(empty($team_name)){// ||  $total_team_play<$this->num_player
                        $redirect_route='game.index';
                    }
                    $array_total_points=PointUser::CalMath_Finaltotal(Auth::user()->id,$start_dwry->eldwry_id,$start_dwry->id,$game->id);
                    $all_comment = [];
                    $current_url_page='';
                    if($ajax==1){
                        $current_url_page=route('game.my_point');
                        $url_name='site.my_team.point.page';
                        // $url_name='site.my_team.point.index';
                    }else{
                        $url_name='site.my_team.index_point';
                    }
                    $return_data= array('found_point'=>$found_point,'current_url_page'=>$current_url_page,'redirect_route'=>$redirect_route,'url_name'=>$url_name,'logo_image'=>$logo_image, 'lang'=>$lang,'type'=>$type,'type_page'=>$type_page,'total_team_play'=>$total_team_play,'pay_total_cost'=>$pay_total_cost,'remide_sum_cost'=>$remide_sum_cost,'total_cost_play'=>$total_cost_play, 'page_title'=>$page_title,'image_best_team'=>$image_best_team,'team_name'=>$team_name,'msg_condition'=>$msg_condition,'team_image_fav'=>$team_image_fav);
                    $return_data=array_merge($array_total_points,$return_data);
                } else {
                    $redirect_route='game.index';
                    $return_data=array('found_point'=>$found_point,'redirect_route'=>$redirect_route);
                }
            } else {
                $redirect_route='home';
                $return_data=array('found_point'=>$found_point,'redirect_route'=>$redirect_route);
            }
        }    
        return $return_data;
    }
//********************************************
    public function BeforeEnter_GameTransfer($user_data){
        emptySessionDeletPlayer();
        GameSubstitutes::DeleteUserActive($user_data->id,0);
        return true;
    }

    public function DateTime_StopTransfer($date_time){
        $time_stop_subeldwry=Options::get_RowOption('time_stop_subeldwry','option_value',0);
        $date=subTimeOnDate($date_time,$time_stop_subeldwry,'minutes');
        return date_lang_game($date,$this->lang);
    }

    public function CheckTime_StopSubeldwry(){
        $check_data=GameHistory::CheckTime_StopSubeldwry();
        //stop data
        if($check_data['check_stop_subeldwry']==1){
            $check_data['url_name']='site.my_team.stop';
            return $check_data;
        }else{
            return $check_data;
        }
    }

}
