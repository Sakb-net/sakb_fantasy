<?php

namespace App\Http\Controllers\ClassSiteApi;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Game;
use App\Models\Eldwry;
use App\Models\Subeldwry;
use App\Models\Team;
use App\Models\Match;
use App\Models\Player;
use App\Models\LocationPlayer;
use App\Models\GamePlayer;
use App\Models\GameSubstitutes;
use App\Models\GameTransaction;
use App\Models\PointUser;
use App\Models\Options;
use App\Models\DetailPlayerMatche;
use App\Models\DetailMatche;
use App\Models\PointPlayer;
use App\Http\Controllers\SiteController;
use App\Repositories\PlayerRepository;
use App\Http\Controllers\ClassSiteApi\Class_TransferController;

class Class_GameController extends SiteController {

    public function __construct() {
        parent::__construct();
    }
    
    function getFixtures($limit=0,$offset=-1,$lang='ar',$api=0,$val_view=0){
        $start_dwry = Eldwry::get_currentDwry();
        if (isset($start_dwry->id)) {
            $all_subdwry = Subeldwry::get_DataSubeldwry('eldwry_id', $start_dwry->id, 1, 'ASC', $limit,$offset,0,null,'');
            $match_public = Match::get_DataGroup($all_subdwry, $lang, 0);
            if ($val_view == 1 || $val_view == '1') {
                $array_data = array('match_public' => $match_public);
            } else {
               $array_data = $match_public;
            }
        }
        return $array_data;
    }

    function getFixtures_CurrentWeek($limit=0,$offset=-1,$lang='ar',$api=0,$val_view=0){
        $start_dwry = Eldwry::get_currentDwry();
        $array_data=[];
        if (isset($start_dwry->id)) {
            $all_subdwry = Subeldwry::get_DataSubeldwry_CurrentWeek('eldwry_id', $start_dwry->id, 1, 'ASC', $limit,$offset,1);
            $all_matches=Match::get_MatchActiveThisWeek(1);
           //TODO
            $match_public =Match::get_DataGroup_week($all_subdwry,$all_matches, $lang, $api);

            if ($val_view == 1 || $val_view == '1') {
                $array_data = array('match_public' => $match_public);
            } else {
               $array_data = $match_public;
            }
        }
        return $array_data;
    }
    
    function get_GameData($user_id,$lang='ar',$api=0){
        $start_dwry=$team_name=$value_lang='';  
        $end_change_date=$msg_condition=$msg_finish_dwry=$type='';
        $remide_sum_cost=$total_team=$total_team_play=$total_cost_play=0;
        $teams=$locations=[];
        $array_start_dwry = Subeldwry::get_startFirstSubDwry(3,1,'',1);
        $inside_dwry =$array_start_dwry['inside_dwry'];
        $start_dwry =$array_start_dwry['data'];
        if (isset($start_dwry->id)) {
            $value_lang = 'value_' . $lang;
            $msg_condition = trans('app.msg_condition');
            $end_change_date = date_lang_game($start_dwry->end_change_date, $lang);
            $total_team = 15;
            $total_team_play = 0;
            $remide_sum_cost = 0;
            $total_cost_play = GameTransaction::sum_Finaltotal($user_id, $start_dwry->eldwry_id, 1);
            $game = Game::checkregisterDwry($user_id, $start_dwry->eldwry_id, 1);
            $team_name = '';
            if (isset($game->id)) {
                $team_name = $game->team_name;
                $data_count_cost = GamePlayer::get_sum_Finaltotal($user_id, $start_dwry, $game->id, 1, $total_cost_play);
                $total_team_play = $data_count_cost['total_team_play'];
                $remide_sum_cost = $data_count_cost['remide_sum_cost'];
            }
            $locations = LocationPlayer::All_foundData('is_active', 1);
            $teams = Team::All_foundData('is_active', 1);
            //$data = [];

        } else {
            //finish dwry ---> because not found current dwry active
            $register_dwry = 0;
            $msg_finish_dwry = trans('app.msg_finish_dwry');
            if ($register_dwry == 0) {
                //finish dwry 
               // $data = [];
            }
        }

    return array('start_dwry'=>$start_dwry, 'team_name'=>$team_name, 'remide_sum_cost'=>  $remide_sum_cost, 'value_lang'=>$value_lang, 'locations'=>$locations, 'teams'=>$teams, 'lang'=>$lang, 'end_change_date'=>$end_change_date, 'total_cost_play'=>$total_cost_play, 'total_team_play'=>$total_team_play, 'total_team'=>$total_team, 'msg_condition'=>$msg_condition,'msg_finish_dwry'=>$msg_finish_dwry, 'data'=>$data, 'type'=>$type);
    }

    function  get_data_player_public($type_key,$lang='ar',$val_view=0,$api=0,$user_id=0){
        $all_loction = LocationPlayer::get_DataAll(1,$type_key);
        $player_public = Player::get_DataGroup($all_loction, $lang, 0,$api,'','','','','',1,0,0,$user_id);
        if ($val_view == 1 || $val_view == '1') {
            $array_data = array('player_public' => $player_public);
        } else {
            $array_data= $player_public;
        }
        return $array_data;   
    }

   function get_dataFilterPlayer($link_team ='',$type_key='', $order_play ='', $word_search ='', $from_price ='', $to_price ='',$loc_player= '',$lang='ar',$val_view=0,$api=0,$user_id=0) {
        if($api==0){
            if ($filter_play == 'all' || empty($filter_play)) {
                $filter_play = '';
            } else {
                $array_filter_play = explode('/', $filter_play);
                if (count($array_filter_play) >= 2) {
                    if ($array_filter_play[1] == 'location') {
                        $type_key = $array_filter_play[0];
                    } elseif ($array_filter_play[1] == 'team') {
                        $link_team = $array_filter_play[0];
                    }
                }
            }
            if(empty($type_key)){
               $type_key=$loc_player;
            }
        }
        $all_loction = LocationPlayer::get_DataAll(1, $type_key);
        $player_public = Player::get_DataGroup($all_loction, $lang, 1, $api, $from_price, $to_price, $link_team, $order_play, $word_search,1,0,0,$user_id);
        if ($val_view == 1 || $val_view == '1') {
            $array_data = array('player_public' => $player_public);
        } else {
            $array_data = $player_public;
        }
        return $array_data;
    }
    function auto_selection_player($start_dwry,$current_id,$num_player=15,$lang='ar',$api=0,$is_active = 1) {
        $data_return=GamePlayer::add_AutoPlayers($start_dwry, $current_id,$is_active, $num_player);

        return $data_return;
    }  

    function reset_all_player($start_dwry,$current_id,$num_player=15,$lang='ar',$api=0,$is_active = 1) {
        $data_return=GamePlayer::delete_AutoPlayers($start_dwry, $current_id,$is_active, $num_player);

        return $data_return;
    }        

    function GetDataCreatMaster($current_id,$num_player=15,$lang='ar',$val_view=0,$api=0) {
        $all_data=$this->GetDataPlayer_Master($current_id,$num_player,$lang,$val_view,$api);
        $data=Game::MyTeam_MasterTransfer($current_id,$all_data['player_master'],[5,5,3],2,$api,0);
        $response['total_team_play'] = $all_data['total_team_play'];
        $response['remide_sum_cost'] = $all_data['remide_sum_cost'];
        $response['pay_total_cost'] = $all_data['pay_total_cost'];
        $response['total_cost_play'] = $all_data['total_cost_play'];
        $response['image_best_team'] = '';
        $response['lineup'] = [];
        $response['data'] = $data;
        return $response;
    }

    function GetDataPlayer_Master($current_id,$num_player,$lang='ar',$val_view=0,$api=0) {
        $total_team_play = $remide_sum_cost =$total_cost_play=$pay_total_cost= -1;
        $all_play_game = [];
        $start_dwry = Eldwry::get_currentDwry();
        if (isset($start_dwry->id)) {
            $current_game = Game::checkregisterDwry($current_id, $start_dwry->id, 1);
            if (isset($current_game->id)) {
                $all_play_game = GamePlayer::allPlayerGameUser('game_id', $current_game->id, 'is_active', 1, 'id', 'ASC');
                if($api==1){
                    //total cost
                    $data_count_cost = GamePlayer::get_ALL_Finaltotal($current_id, $start_dwry, $current_game->id,1,0);
                    $total_cost_play=$data_count_cost['total_cost_play'];
                    $total_team_play = $data_count_cost['total_team_play'];
                    $remide_sum_cost = $data_count_cost['remide_sum_cost'];
                    $pay_total_cost=$data_count_cost['pay_total_cost'];
                }
            }
        }
        $player_master = GamePlayer::get_DataGroup($all_play_game, $num_player, $lang,$api);
        if ($val_view == 1 || $val_view == '1') {
            $array_data = array('player_master' => $player_master,);
        }elseif($api==1){
            $array_data = array('player_master' => $player_master,'total_team_play' => $total_team_play, 'remide_sum_cost' => $remide_sum_cost,'total_cost_play'=>$total_cost_play,'pay_total_cost'=>$pay_total_cost);
        } else {
           $array_data =$player_master;
        }
        return $array_data;
    }

    public function get_dataOnePlayer($player_link,$lang='ar',$api=0,$val_view=0) {
        $getplayer=new PlayerRepository();
        $all_details=$getplayer->DetailsPlayer('link', $player_link,$api,$lang);
        return  $all_details;
    }
    
    function game_addPlayer($current_id,$player_link = '',$lang='ar',$val_view=0,$api=0) {
        $total_team_play = $remide_sum_cost =$total_cost_play=$pay_total_cost= -1;
        $player_name = $val_player = '';
        $msg_add = trans('app.add_fail');
        $player_data = Player::get_PlayerCloum('link', $player_link, 1);
        if (isset($player_data->id)) {
            $start_dwry = Eldwry::get_currentDwry();
            if (isset($start_dwry->id)) {
                $data_return = GamePlayer::add_DataPlayer($start_dwry, $current_id, $player_data, 1);
                if ($data_return['game_id'] > 0) {
                    //total cost
                    $data_count_cost = GamePlayer::get_ALL_Finaltotal($current_id, $start_dwry,$data_return['game_id'],1,1);
                    $total_cost_play=$data_count_cost['total_cost_play'];
                    $total_team_play = $data_count_cost['total_team_play'];
                    $remide_sum_cost = $data_count_cost['remide_sum_cost'];
                    $pay_total_cost=$data_count_cost['pay_total_cost'];
                }
                $add_player = $data_return['add_player'];
                $msg_add = $data_return['msg_add'];
            }
        } else {
            $add_player = 0;
        }
        if ($val_view == 1 || $val_view == '1') {
            $array_data = array('player_data' => $player_data);
        } else {
            $array_data =['total_team_play' => $total_team_play, 'remide_sum_cost' => $remide_sum_cost,'total_cost_play'=>$total_cost_play,'pay_total_cost'=>$pay_total_cost, 'add_player' => $add_player, 'msg_add' => $msg_add, 'val_player' => $val_player, 'player_data' => $player_data];
        }
        return $array_data;
    }

    function game_deletePlayer($current_id,$eldwry_link = '',$player_link ='',$lang='ar',$api=0,$pay_total_cost=-1,$remide_sum_cost=-1,$total_team_play=-1,$substitutes_points='', $count_free_weekgamesubstitute='') {
        $total_cost_play= -1;
        $player_name = $link_substituteplayer='';
        $found_session=0;
        $msg_delete = trans('app.delete_fail');
        $val_player=[];
        if(!empty($$eldwry_link)){
            $start_dwry = Eldwry::get_EldwryRow($eldwry_link, 'link', 1);
        }else{
            $start_dwry = Eldwry::get_currentDwry(); 
        }
        if (isset($start_dwry->id)) {
            $player_data = Player::get_PlayerCloum('link', $player_link, 1);
            if (isset($player_data->id)) { 
                $link_substituteplayer=$player_data->link;
                if($api==0){
                    //delete player from session when substitutePlayer
                    $get_transfer=new Class_TransferController();
                    $data_return =$get_transfer->delete_substitutePlayer($current_id, $player_data,$api,$pay_total_cost,$remide_sum_cost,$total_team_play,$substitutes_points,$count_free_weekgamesubstitute);
                    $found_session=$data_return['found_session'];
                    $val_player=$data_return['val_player'];
                    $substitutes_points = $data_return['substitutes_points'];
                    $count_free_weekgamesubstitute=$data_return['count_free_weekgamesubstitute'];
                }
                if($found_session==0){
                    $data_return =$this->check_deletePlayer($start_dwry, $current_id, $player_data,1,$lang,$api);
                }
                $total_team_play = $data_return['total_team_play'];
                $total_cost_play=$data_return['total_cost_play'];
                $remide_sum_cost = $data_return['remide_sum_cost'];
                $pay_total_cost=$data_return['pay_total_cost'];
                $delete_player = $data_return['delete_player'];
                $msg_delete = $data_return['msg_delete'];                
            } else {
                $delete_player = 0;
            }
        } else {
            $delete_player = 0;
        }
        return ['total_team_play' => $total_team_play, 'remide_sum_cost' => $remide_sum_cost,'total_cost_play'=>$total_cost_play,'pay_total_cost'=>$pay_total_cost,'substitutes_points'=>$substitutes_points,'count_free_weekgamesubstitute'=>$count_free_weekgamesubstitute, 'delete_player' => $delete_player, 'msg_delete' => $msg_delete,'val_player'=>$val_player,'found_session'=>$found_session,'link_substituteplayer'=>$link_substituteplayer];
    }
    
    function check_deletePlayer($start_dwry, $current_id, $player, $is_active = 1,$lang='ar',$api=0) {
        $game = Game::checkregisterDwry($current_id, $start_dwry->id, 1);
        $game_id = $ok_delete=0;
        $msg_delete=trans('app.delete_fail_info');
        if (isset($game->id)) {
            $game_id = $game->id;
            $team_name = $game->team_name;
                //total cost
            $data_count_cost = GamePlayer::get_sum_Finaltotal($current_id, $start_dwry, $game_id,1); //get_ALL_Finaltotal
            $total_team_play = $data_count_cost['total_team_play'];
            $check_data = GamePlayer::checkFoundData($player->id, $game->id, 0);
            if(!empty($team_name) && $total_team_play==$this->num_player){
               //delete and account points
                //check allow change or not
                if (isset($check_data->id)) {
                    $ok_delete=1;
                    if($api==1){
                        $input['is_active'] =0;
                        $data = $check_data->update($input);
                    }else{
                    //add sesstion deletplayer
                    addSessionDeletPlayer($check_data->id);
                    //end sesstion deletplayer
                    }
                }
            }else{
               //delete only 
                if (isset($check_data->id)) {
                    $ok_delete=1;
                    if($api==1){
                        $input['is_active'] =0;
                        $data = $check_data->update($input);
                    }else{
                        //add sesstion deletplayer
                        addSessionDeletPlayer($check_data->id);
                        //end sesstion deletplayer
                    }
                }
            }
            if ($ok_delete==1) {
                $result = array('delete_player' => 1, 'msg_delete' => trans('app.delete_scuss'), 'game_id' => $game_id);
            } else {
                $result = array('delete_player' => -1, 'msg_delete' => $msg_delete, 'game_id' => $game_id);
            }
            $data_count_cost = GamePlayer::get_ALL_Finaltotal($current_id, $start_dwry, $game_id, 1,$api,1);
            $result=array_merge($result,$data_count_cost);
        } else {
            $result = array('delete_player' => 0, 'msg_delete' => trans('app.delete_fail'), 'game_id' => $game_id);
        }
        return $result;
    }
      
    function return_player_game($current_id,$eldwry_link = '',$player_link ='',$lang='ar',$api=0,$pay_total_cost=-1,$remide_sum_cost=-1,$total_team_play=-1,$substitutes_points='', $count_free_weekgamesubstitute='') {
        $total_team_play = $remide_sum_cost =$total_cost_play=$pay_total_cost= -1;
        $player_name = '';
        $msg_delete = trans('app.return_fail');
        if(!empty($$eldwry_link)){
            $start_dwry = Eldwry::get_EldwryRow($eldwry_link, 'link', 1);
        }else{
            $start_dwry = Eldwry::get_currentDwry(); 
        }
        if (isset($start_dwry->id)) {
            $player_data = Player::get_PlayerCloum('link', $player_link, 1);
            if (isset($player_data->id)) { 
                $data_return =$this->check_return_player($start_dwry, $current_id, $player_data,1,$lang,0);
                $total_team_play = $data_return['total_team_play'];
                $total_cost_play=$data_return['total_cost_play'];
                $remide_sum_cost = $data_return['remide_sum_cost'];
                $pay_total_cost=$data_return['pay_total_cost'];
                $delete_player = $data_return['delete_player'];
                $msg_delete = $data_return['msg_delete'];                
            } else {
                $delete_player = 0;
            }
        } else {
            $delete_player = 0;
        }
        return ['total_team_play' => $total_team_play, 'remide_sum_cost' => $remide_sum_cost,'total_cost_play'=>$total_cost_play,'pay_total_cost'=>$pay_total_cost, 'delete_player' => $delete_player, 'msg_delete' => $msg_delete];
    }
    
    function check_return_player($start_dwry, $current_id, $player, $is_active = 1,$lang='ar',$api=0) {
        $game = Game::checkregisterDwry($current_id, $start_dwry->id, 1);
        $game_id = $ok_delete=0;
        $msg_delete=trans('app.return_fail');
        if (isset($game->id)) {
            $game_id = $game->id;
            $team_name = $game->team_name;
                //total cost
            $data_count_cost = GamePlayer::get_sum_Finaltotal($current_id, $start_dwry, $game_id,1); //get_ALL_Finaltotal
            
            $total_team_play = $data_count_cost['total_team_play'];
            $check_data = GamePlayer::checkFoundData($player->id, $game->id, 0,1);
            if(!empty($team_name) && $total_team_play==$this->num_player){
               //delete and account points
                //check allow change or not
                $change_subeldwry=Subeldwry::get_CurrentSubDwry();
                if (isset($change_subeldwry->id)) {
                    if (isset($check_data->id)) {
                        $ok_delete=1;
                        //remove from session
                        RemoveSessionDeletPlayer($check_data->id);
                        //account points
                    }
                }
            }else{
               //delete only 
                if (isset($check_data->id)) {
                    $ok_delete=1;
                    //remove from session
                    RemoveSessionDeletPlayer($check_data->id);
                }
            }
            if ($ok_delete==1) {
                $result = array('delete_player' => 1, 'msg_delete' => trans('app.return_success'), 'game_id' => $game_id);
            } else {
                $result = array('delete_player' => -1, 'msg_delete' => $msg_delete, 'game_id' => $game_id);
            }
            $data_count_cost = GamePlayer::get_ALL_Finaltotal($current_id, $start_dwry, $game_id, 1,$api,1);
             
            $result=array_merge($result,$data_count_cost);
        } else {
            $result = array('delete_player' => 0, 'msg_delete' => trans('app.return_fail'), 'game_id' => $game_id);
        }
        return $result;
    }

    function game_changePlayerApp($current_id,$eldwry_link,$delet_player_link,$add_player_link,$lang='ar',$api=0){
        $player_name = '';
        $msg_delete = trans('app.change_fail');
        $delet_player_data = Player::get_PlayerCloum('link', $delet_player_link, 1);
        if (isset($delet_player_data->id)) {
             $add_player_data = Player::get_PlayerCloum('link', $add_player_link, 1);
            if (isset($add_player_data->id)) {
                if(!empty($$eldwry_link)){
                    $start_dwry = Eldwry::get_EldwryRow($eldwry_link, 'link', 1);
                }else{
                    $start_dwry = Eldwry::get_currentDwry(); 
                }
                if (isset($start_dwry->id)) {
                    $data_return =$this->add_change_player_game($start_dwry, $current_id, $delet_player_data,$add_player_data,1,$lang,$api);
                    $delete_player = $data_return['delete_player'];
                    $msg_delete = $data_return['msg_delete'];
                } else {
                    $msg_delete = $data_return['not_found_eldwry'];
                    $delete_player = 0;
                }
            } else {
                $delete_player = 0;
            }
        } else {
            $delete_player = 0;
        }
        return [ 'delete_player' => $delete_player, 'msg_delete' => $msg_delete];
    }
    function change_player_game($current_id,$eldwry_link = '',$player_link ='',$lang='ar',$api=0,$pay_total_cost=-1,$remide_sum_cost=-1,$total_team_play=-1,$substitutes_points='', $count_free_weekgamesubstitute='') {
        $data_return=[];
        $player_name = '';
        $msg_delete = trans('app.change_fail');
        if(!empty($$eldwry_link)){
            $start_dwry = Eldwry::get_EldwryRow($eldwry_link, 'link', 1);
        }else{
            $start_dwry = Eldwry::get_currentDwry(); 
        }
        if (isset($start_dwry->id)) {
            $player_data = Player::get_PlayerCloum('link', $player_link, 1);
            if (isset($player_data->id)) {
                if($api==0){
                    $this->AddPlayerSession($player_data);
                }
                $data_return =$this->check_change_player_game($start_dwry, $current_id, $player_data,1,$lang,$api);
                $delete_player = $data_return['delete_player'];
                $msg_delete = $data_return['msg_delete'];
            } else {
                $delete_player = 0;
            }
        } else {
            $delete_player = 0;
        }
        $final_res=[ 'delete_player' => $delete_player, 'msg_delete' => $msg_delete];
        $final_res=array_merge($final_res,$data_return);
        return $final_res;
    }
  
    function check_change_player_game($start_dwry, $current_id, $player, $is_active = 1,$lang='ar',$api=0) {
        $game = Game::checkregisterDwry($current_id, $start_dwry->id, 1);
        $game_id = $ok_delete=0;
        $msg_delete=trans('app.change_fail');
        if (isset($game->id)) {
            $game_id = $game->id;
            $team_name = $game->team_name;
                //total cost
            $data_count_cost = GamePlayer::get_sum_Finaltotal($current_id, $start_dwry, $game_id,1); //get_ALL_Finaltotal
            
            $total_team_play = $data_count_cost['total_team_play'];
            $check_data = GamePlayer::checkFoundData($player->id, $game->id, 0,1);
            if(!empty($team_name) && $total_team_play==$this->num_player){
               //delete and account points
                //check allow change or not
                $change_subeldwry=Subeldwry::get_CurrentSubDwry();
                if (isset($change_subeldwry->id)) {
                    if (isset($check_data->id)) {
                        $ok_delete=1;
                        $input['is_change'] = 1;
                        $input['is_active'] = 0;
                        $data = $check_data->update($input);
                        session()->put('deletplayer_id',$check_data->id);
                        //remove from session
                        RemoveSessionDeletPlayer($check_data->id);
                        //account points
                    }
                }
            }else{
               //delete only 
                if (isset($check_data->id)) {
                    $ok_delete=1;
                    $input['is_change'] = 1;
                    $input['is_active'] = 0;
                    $data = $check_data->update($input);
                    session()->put('deletplayer_id',$check_data->id);
                    //remove from session
                    RemoveSessionDeletPlayer($check_data->id);
                }
            }
            if ($ok_delete==1) {
                $result = array('delete_player' => 1, 'msg_delete' => trans('app.return_success'), 'game_id' => $game_id);
            } else {
                $result = array('delete_player' => -1, 'msg_delete' => $msg_delete, 'game_id' => $game_id);
            }
            $data_count_cost = GamePlayer::get_ALL_Finaltotal($current_id, $start_dwry, $game_id, 1,$api,1);
            $data_count_cost['count_free_weekgamesubstitute'] =GameSubstitutes::countFreePointSubstitute($current_id,$start_dwry->id,$game_id);
            $result=array_merge($result,$data_count_cost);
        } else {
            $result = array('delete_player' => 0, 'msg_delete' => trans('app.return_fail'), 'game_id' => $game_id);
        }
        return $result;
    }

    function add_change_player_game($start_dwry, $current_id, $delet_player_data,$add_player_data,$is_active =1,$lang='ar',$api=0){
        $game = Game::checkregisterDwry($current_id, $start_dwry->id, 1);
        $game_id = $ok_delete=0;
        $msg_delete=trans('app.change_fail');
        if (isset($game->id)) {
            $game_id = $game->id;
            $check_numteam =GamePlayer::check_AllNumCountData_WhenChangePlayer($add_player_data,$delet_player_data, $game);
            if($check_numteam['ok_type']==1){
                $add_check_data = GamePlayer::checkFoundData($add_player_data->id, $game->id, 0,1);
                if(!isset($add_check_data->id)){
                    $team_name = $game->team_name;
                        //total cost
                    $data_count_cost = GamePlayer::get_sum_Finaltotal($current_id, $start_dwry, $game_id,1); //get_ALL_Finaltotal
                    $total_team_play = $data_count_cost['total_team_play'];
                    $delet_check_data = GamePlayer::checkFoundData($delet_player_data->id, $game->id, 0,1);
                    if(!empty($team_name) && $total_team_play==$this->num_player){
                       //delete and account points
                        //check allow change or not
                        $change_subeldwry=Subeldwry::get_CurrentSubDwry();
                        if (isset($change_subeldwry->id)) {
                            if (isset($delet_check_data->id)) {
                                $ok_delete=1;
                                $input['is_change'] = 0;  //not 1 beacuse this confirm from mobile App
                                $input['is_active'] = 0;
                                $data = $delet_check_data->update($input);
                                //add another player
                                GamePlayer::addPlayerAnotherOne($delet_check_data,$add_player_data,$change_subeldwry);
                                //account points
                            }
                        }
                    }else{
                       //delete only 
                        if (isset($delet_check_data->id)) {
                            $ok_delete=1;
                            $input['is_change'] = 0;  //not 1 beacuse this confirm from mobile App
                            $input['is_active'] = 0;
                            $data = $delet_check_data->update($input);
                            //add another player
                            GamePlayer::addPlayerAnotherOne($delet_check_data,$add_player_data);
                        }
                    }
                    if ($ok_delete==1) {
                        $result = array('delete_player' => 1, 'msg_delete' => trans('app.return_success'), 'game_id' => $game_id);
                    } else {
                        $result = array('delete_player' => -1, 'msg_delete' => $msg_delete, 'game_id' => $game_id);
                    }
                    $data_count_cost = GamePlayer::get_ALL_Finaltotal($current_id, $start_dwry, $game_id, 1,$api,1);
                     
                    $result=array_merge($result,$data_count_cost);

                } else {
                //new player found in game
                    $result = array('delete_player' => 0, 'msg_delete' => trans('app.add_fail_info'), 'game_id' => $game_id);
                }
            } else {
                //account num of player from same team >3
                $result = array('delete_player' => 0, 'msg_delete' => $check_numteam['msg_add'], 'game_id' => $game_id);
            }
        } else {
            $result = array('delete_player' => 0, 'msg_delete' => trans('app.return_fail'), 'game_id' => $game_id);
        }
        return $result;
    }

    function checknum_playerteam($current_id,$lang='ar',$api=0) {
        if($api==0){
            $lang = $this->lang;
            $current_id = $this->current_id;
        }
        $msg_add = trans('app.plz_complete_numplayer');
        $count_player_team=0; 
        $total_num_player=$this->num_player;  
        $start_dwry = Eldwry::get_currentDwry();
        if (isset($start_dwry->id)) {
            $current_game = Game::checkregisterDwry($current_id, $start_dwry->id, 1);
            if (isset($current_game->id)) {
                $all_play_game = GamePlayer::allPlayerGameUser('game_id', $current_game->id, 'is_active', 1, 'id', 'ASC');
                $count_player_team=count($all_play_game);
            }
        }
        if($count_player_team==$total_num_player){
            $msg_add = trans('app.complete_numplayer');
        }
        return ['total_num_player' => $total_num_player,'count_player_yourteam'=>$count_player_team, 'msg_add' => $msg_add];
    }

    function submit_game_team($current_id,$team_name,$lang='ar',$api=0) {
        $total_num_player=$this->num_player;  
        $msg_add = trans('app.add_fail');
        $add_team = 0;
        $start_dwry = Eldwry::get_currentDwry();
        if (isset($start_dwry->id)) {
            $data_return = Game::ADD_teaMName($current_id, $start_dwry, $team_name, $api,$total_num_player);
            //delete all player not active
            GamePlayer::deleteColumtwo('game_id',$data_return['game_id'],'is_active', 0);
            $add_team = $data_return['add_team'];
            $msg_add = $data_return['msg_add'];
        }
        return ['add_team' => $add_team, 'msg_add' => $msg_add];
    }
///*************************************************
    function AddPlayerSession($player_data){
        $array_player=session()->get('array_substitutePlayer');
        if(!is_array($array_player)){
            $array_player=[];
        }
        $array_player[]=['id'=>$player_data->id,'image'=>$player_data->image,'name'=>$player_data->name,'cost'=>$player_data->cost,'link'=>$player_data->link,'location'=>$player_data->location_player->type_key];
        session()->put('array_substitutePlayer',$array_player);
        return true;
    }
}