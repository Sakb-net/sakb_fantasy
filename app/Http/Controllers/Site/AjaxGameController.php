<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SiteController;
use App\Models\User;
use App\Models\Eldwry;
use App\Models\Subeldwry;
use App\Models\Match;
use App\Models\GameTransaction;
use App\Models\GameSubstitutes;
use App\Models\AllType;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Player;
use App\Models\LocationPlayer;
use App\Models\DetailPlayerMatche;
use App\Models\Team;
use App\Models\DetailMatche;
use App\Models\PointPlayer;
use App\Http\Controllers\ClassSiteApi\Class_StatisticController;
use App\Http\Controllers\ClassSiteApi\Class_GameController;

class AjaxGameController extends SiteController {

    public function __construct() {
        parent::__construct();
        if (isset(Auth::user()->id)) {
            $this->current_id = Auth::user()->id;
            $this->user_key = Auth::user()->name;
        }
    }

//*************************************************************
    public function get_data_match_public(Request $request) {
        if ($request->ajax()) {
            $count_pag=0;
            $response =1;
            $lang = $this->lang;
            $offset = $request->offset;
            $subdwry_limit =$request->limit;
            $num_week=$request->num_week;
            $limit=15;
            $final_offset=$offset-1;
            $gwla_final_offset=$subdwry_limit*$final_offset;
            $final_offset=$limit*$final_offset;
            $start_dwry = Eldwry::get_currentDwry();
            $teamId = 0;
            if($request->team != ''){
                $team = Team::where('link',$request->team)->first();
                $teamId = $team->id;
            }
            if (isset($start_dwry->id)) {
                $all_subdwry = Subeldwry::get_DataSubeldwry('eldwry_id', $start_dwry->id, 1, 'ASC', $subdwry_limit,$gwla_final_offset,'',$num_week,$teamId);
                $count_subdwry = Subeldwry::count_DataSubeldwry('eldwry_id', $start_dwry->id, 1,1);

                $count_pag=round($count_subdwry/$subdwry_limit,0);

                $match_public = Match::get_DataGroup($all_subdwry, $lang, 0,$limit,$final_offset);
                if($num_week<=0){ // && $subdwry_limit>1
                    if(isset($all_subdwry[0]->num_week)){
                        $num_week=$all_subdwry[0]->num_week;//round($all_subdwry[1]->num_week/$subdwry_limit);
                    }else{
                        $num_week=$subdwry_limit*$gwla_final_offset;
                    }
                    if($num_week<=0){
                        $num_week=1;
                    }  
                }
                return response()->json(['response' => $response, 'match_public' => $match_public,'count_pag'=>$count_pag,'num_week'=>$num_week]);
            }
        }
    }

    public function get_data_player_public(Request $request) {
        if ($request->ajax()) {
            $response = $offset=1;
            $lang = $this->lang;
            $type_key='';
            foreach ($request->input() as $key => $value) {
                $$key = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            $all_loction = LocationPlayer::get_DataAll(1);
            $array_all_data = Player::get_DataAllPlayer('1', $lang);
            $player_public=$array_all_data['all_data'];
            $count_pag=0;//$array_all_data['count_pag'];
            if ($request->val_view == 1 || $request->val_view == '1') {
                $array_data = array('player_public' => $player_public);
                $response = view('site.games.side_table', $array_data)->render();
                return response()->json(['response' => $response]);
            } else {
                return response()->json(['response' => $response, 'player_public' => $player_public,'count_pag'=>$count_pag,'offset'=>$offset]);
            }
        }
    }

    public function get_data_player_public_statistics(Request $request) {
        if ($request->ajax()) {
            $response = 1;
            $offset = $request->offset;
            $order_play = $request->order_play;
            $lang = $this->lang;
            $link_team = $type_key = '';
            //$type_key=$request->type_key;
            foreach ($request->input() as $key => $value) {
                $$key = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            if ($filter_play == 'all' || empty($filter_play)) {
                $filter_play = '';
            } else {
                $array_filter_play = explode('/', $filter_play);
                if (count($array_filter_play) >= 2) {
                    $count_pag=3; 
                    if ($array_filter_play[1] == 'location') {
                        $type_key = $array_filter_play[0];
                    } elseif ($array_filter_play[1] == 'team') {
                        $link_team = $array_filter_play[0];
                    }
                }
            }
            $get_data=new Class_StatisticController();
            $array_all_data=$get_data->get_data_player_public_statistics('1', $lang, $order_play, $link_team, $type_key, $offset);

            $player_public=$array_all_data['all_data'];
            $count_pag=$array_all_data['count_pag'];
            $count = $array_all_data['count'];
            if ($request->val_view == 1 || $request->val_view == '1') {
                $array_data = array('player_public' => $player_public);
                $response = view('site.games.side_table', $array_data)->render();
                return response()->json(['response' => $response]);
            } else {
                return response()->json(['response' => $response, 'player_public' => $player_public,'count_pag'=>$count_pag,'count'=>$count,'offset'=>$offset, 'link_team'=>$link_team, 'type_key'=>$type_key]);
            }
        }
    }

    public function get_data_player_public_transformation(Request $request) {
        if ($request->ajax()) {
            $response =1;
            $offset = $request->offset;
            $order_play = $request->order_play;
            $lang = $this->lang;
            $type_key=$request->type_key;
            $link_team = '';
            foreach ($request->input() as $key => $value) {
                $$key = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            $all_loction = LocationPlayer::get_DataAll(1);
            $array_all_data = Player::get_DataGroup($all_loction, $lang, 0, 0,'','',$link_team,$order_play, $type_key,'',$offset,1);
            $player_public=$array_all_data['all_data'];
            $count_pag=$array_all_data['count_pag'];
            if ($request->val_view == 1 || $request->val_view == '1') {
                $array_data = array('player_public' => $player_public);
                $response = view('site.games.side_table', $array_data)->render();
                return response()->json(['response' => $response]);
            } else {
                return response()->json(['response' => $response, 'player_public' => $player_public,'count_pag'=>$count_pag,'offset'=>$offset]);
            }
        }
    }

    public function get_dataFilterPlayer(Request $request) {
        if ($request->ajax()) {
            $count_pag=5;
            $response =$offset= 1;
            $lang = $this->lang;
            $link_team = $type_key = '';
            $filter_play = $order_play = $word_search = $from_price = $to_price =$loc_player= '';
            foreach ($request->input() as $key => $value) {
                $$key = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            if ($filter_play == 'all' || empty($filter_play)) {
                $filter_play = '';
            } else {
                $array_filter_play = explode('/', $filter_play);
                if (count($array_filter_play) >= 2) {
                    $count_pag=3; 
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
            $all_loction = LocationPlayer::get_DataAll(1, $type_key); //,[1,2,3,4,5,6,7,8,9,10]
            $array_player_public = Player::get_DataAllPlayer('1', $lang, $order_play, $link_team, $type_key, $offset);

            $player_public =$array_player_public['all_data'];
            $count_pag =$array_player_public['count_pag'];
            $count = $array_player_public['count'];
            if ($request->val_view == 1 || $request->val_view == '1') {
                $array_data = array('player_public' => $player_public);
                $response = view('site.games.side_table', $array_data)->render();
                return response()->json(['response' => $response]);
            } else {
                return response()->json(['response' => $response, 'player_public' => $player_public,'type_key'=>$type_key,'count_pag'=>$count_pag,'count'=>$count,'offset'=>$offset]);
            }
        }
    }
    
    public function get_dataFilterPlayerTransformation(Request $request) {
        if ($request->ajax()) {
            $count_pag=5;
            $response =1;
            $lang = $this->lang;
            $current_id = Auth::user()->id;
            $link_team = $type_key = '';
            $response =1;
            $limit=5;
            $offset = $request->offset;
            $order_play = $request->order_play;
            $filter_play = $word_search = $from_price = $to_price =$loc_player= '';
            foreach ($request->input() as $key => $value) {
                $$key = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            if ($filter_play == 'all' || empty($filter_play)) {
                $filter_play = '';
            } else {
                $array_filter_play = explode('/', $filter_play);
                if (count($array_filter_play) >= 2) {
                    $count_pag=3; 
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
            $all_loction = LocationPlayer::get_DataAll(1, $type_key); //,[1,2,3,4,5,6,7,8,9,10]
            $array_player_public = Player::get_DataGroup($all_loction, $lang, 1, 0, $from_price, $to_price, $link_team, $order_play, $type_key,  $word_search,$offset,1,$count_pag,$current_id,$limit);

            $player_public =$array_player_public['all_data'];
            $count_pag =$array_player_public['count_pag'];
            if ($request->val_view == 1 || $request->val_view == '1') {
                $array_data = array('player_public' => $player_public);
                $response = view('site.games.side_table', $array_data)->render();
                return response()->json(['response' => $response]);
            } else {
                return response()->json(['response' => $response, 'player_public' => $player_public,'type_key'=>$type_key,'count_pag'=>$count_pag,'offset'=>$offset]);
            }
        }
    }

    public function get_dataOrderByPlayerLocation(Request $request) {
        if ($request->ajax()) {
            $count_pag=5;
            $response =1;
            $offset = $request->offset;
            $order_play = $request->order_play;
            $type_key=$request->type_key;
            $lang = $this->lang;
            $link_team = $type_key = '';
            $filter_play = $word_search = $from_price = $to_price =$loc_player= '';
            foreach ($request->input() as $key => $value) {
                $$key = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            if ($filter_play == 'all' || empty($filter_play)) {
                $filter_play = '';
            } else {
                $array_filter_play = explode('/', $filter_play);
                if (count($array_filter_play) >= 2) {
                    $count_pag=3; 
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
            $all_loction = LocationPlayer::get_DataAll(1, $type_key); //,[1,2,3,4,5,6,7,8,9,10]
            $array_player_public = Player::get_DataGroupForOneLocation($all_loction, $lang, 0, $link_team, $order_play, $type_key, $offset,1,$count_pag);

            $player_public =$array_player_public['all_data'];
            $count_pag =$array_player_public['count_pag'];
            if ($request->val_view == 1 || $request->val_view == '1') {
                $array_data = array('player_public' => $player_public);
                $response = view('site.games.side_table', $array_data)->render();
                return response()->json(['response' => $response]);
            } else {
                return response()->json(['response' => $response, 'player_public' => $player_public,'type_key'=>$type_key,'count_pag'=>$count_pag,'offset'=>$offset]);
            }
        }
    }

    public function GetDataPlayer_Master(Request $request) {
        if ($request->ajax()) {
            $response = 1;
            $lang = $this->lang;
            $num_player = $this->num_player;
            $current_id = Auth::user()->id;
            $all_play_game = [];
            $chang=0;
            foreach ($request->input() as $key => $value) {
                $$key = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            $start_dwry = Eldwry::get_currentDwry();
            if (isset($start_dwry->id)) {
                $current_game = Game::checkregisterDwry($current_id, $start_dwry->id, 1);
                if (isset($current_game->id)) {
                    $all_play_game = GamePlayer::allPlayerGameUser('game_id', $current_game->id, 'is_active', 1, 'id', 'ASC',$chang);
                }
            }
            $player_master = GamePlayer::get_DataGroup($all_play_game, $num_player, $lang, 0);
            if ($request->val_view == 1 || $request->val_view == '1') {
                $array_data = array('player_master' => $player_master);
                $response = view('site.games.side_table', $array_data)->render();
                return response()->json(['response' => $response]);
            } else {
                return response()->json(['response' => $response, 'player_master' => $player_master]);
            }
        }
    }

    public function get_dataOnePlayer(Request $request) {
        if ($request->ajax()) {
            $response = 1;
            $match_details = $player_details = $all_team_data = $returned_json = [];
            $lang = $this->lang;
            foreach ($request->input() as $key => $value) {
                $$key = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            $player_data = Player::get_DataOnePlayer('link', $player_link, 1, $lang, 1);

            $player_details = DetailPlayerMatche::get_DetailPlayerCloum('player_id', $player_data['id']);
            foreach ($player_details as $player_detail) {
                $match_detail = Match::get_MatchData('id', $player_detail['match_id'], $lang, 1);
                if($player_detail['team_id'] == $match_detail['first_team_id']){
                    $againest_team_data = Team::get_TeamRow($match_detail['second_team_id'], 'id',1);
                    $againest_team_result = isset($match_detail['second_goon']) ? $match_detail['second_goon'] : 0;
                    $own_team_result = isset($match_detail['first_goon']) ? $match_detail['first_goon'] : 0;
                }else{
                    $againest_team_data = Team::get_TeamRow($match_detail['first_team_id'], 'id',1);
                    $againest_team_result = isset($match_detail['first_goon']) ? $match_detail['first_goon'] : 0;
                    $own_team_result = isset($match_detail['second_goon']) ? $match_detail['second_goon'] : 0;
                }
                $detail_player_matche = DetailMatche::get_DetailMatche('match_id', $player_detail['match_id'], 'team_id', $player_detail['team_id'], 'player_id', $player_detail['player_id']);
                $json['againestTeam'] = isset($againest_team_data->name) ? $againest_team_data->name : '';
                $json['againestTeamResult'] = $againest_team_result;
                $json['ownTeamResult'] = $own_team_result;
                $json['minsPlayed'] = isset($player_detail->minsPlayed) ? $player_detail->minsPlayed : 0;
                $json['goals'] = isset($player_detail->goals) ? $player_detail->goals : 0;
                $json['penalitySave'] = isset($player_detail->attPenTarget) ? $player_detail->attPenTarget : 0;
                $json['penalityLost'] = isset($player_detail->attPenMiss) ? $player_detail->attPenMiss : 0;
                $json['reverseGoal'] = isset($player_detail->ownGoals) ? $player_detail->ownGoals : 0;
                $json['goalAssist'] = isset($player_detail->goalAssist) ? $player_detail->goalAssist : 0;
                $json['saves'] = isset($player_detail->saves) ? $player_detail->saves : 0;
                $json['cleanSheet'] = isset($player_detail->cleanSheet) ? $player_detail->cleanSheet : 0;
                $json['redCard'] = isset($player_detail->redCard) ? $player_detail->redCard : 0;
                $json['yellowCard'] = isset($player_detail->yellowCard) ? $player_detail->yellowCard : 0;

                $player_sum_in_match = PointPlayer::sum_Finaltotal_SinglePlayerAndMatch($player_detail['player_id'], $player_detail['match_id']);
                $json['points'] = isset($player_sum_in_match) ? $player_sum_in_match : 0; 

                if($match_detail != '')
                    $json['week'] = isset($match_detail['week']) ? $match_detail['week'] : 0;
                else
                    $json['week'] = 0;

                if($detail_player_matche != ''){
                    $json['keyPass'] = $detail_player_matche['keyPass'];
                    
                }else{
                    $json['keyPass'] = 0;
                }
                $json['extraPoints'] = 0;
                $all_team_data[] = $againest_team_data;
                $match_details[] = $match_detail;
                $returned_json[] = $json;
            }
            
            if ($val_view == 1 || $val_view == '1') {
                $all_details = array('returned_json'=>$returned_json,'player_data'=>$player_data);
                $response = view('site.games.modal.info_modal', $all_details)->render();
                return response()->json(['response' => $response]);
            } else {
                $all_details = array('returned_json'=>$returned_json,'player_data'=>$player_data);
                return response()->json(['response' => $response, 'all_details' => $all_details]);
            }
        }
    }
    public function auto_selection_player(Request $request) {
        if ($request->ajax()) {
            $response = 1;
            $lang = $this->lang;
            $num_player=$this->num_player;
            $current_id = Auth::user()->id;
            $total_team_play = $remide_sum_cost =$pay_total_cost= -1;
            $add_player=0;
            $msg_add = trans('app.add_fail');
            foreach ($request->input() as $key => $value) {
                $$key = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            $start_dwry = Eldwry::get_currentDwry();
            if (isset($start_dwry->id)) {
                $get_data=new Class_GameController();
                $data_return = $get_data->auto_selection_player($start_dwry, $current_id,$num_player,$lang,0);
                if ($data_return['game_id'] > 0) {
                    $data_count_cost = GamePlayer::get_ALL_Finaltotal($current_id, $start_dwry, $data_return['game_id'], 1,0);
                    $total_team_play = $data_count_cost['total_team_play'];
                    $remide_sum_cost =$data_count_cost['remide_sum_cost'];
                    $pay_total_cost = $data_count_cost['pay_total_cost'];
                }
                $add_player = $data_return['add_player'];
                $msg_add = $data_return['msg_add'];
            }
            
            return response()->json(['response' => $response, 'total_team_play' => $total_team_play, 'remide_sum_cost' => $remide_sum_cost,'pay_total_cost'=>$pay_total_cost, 'add_player' => $add_player, 'msg_add' => $msg_add]);
        }
    }
    public function reset_all_player(Request $request) {
        if ($request->ajax()) {
            $response = 1;
            $lang = $this->lang;
            $num_player=$this->num_player;
            $current_id = Auth::user()->id;
            $total_team_play = $remide_sum_cost =$pay_total_cost= -1;
            $delete_player=0;
            $msg_delete = trans('app.delete_fail');
            foreach ($request->input() as $key => $value) {
                $$key = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            $start_dwry = Eldwry::get_currentDwry();
            if (isset($start_dwry->id)) {
                $get_data=new Class_GameController();
                $data_return = $get_data->reset_all_player($start_dwry, $current_id,$num_player,$lang,0);
                if ($data_return['game_id'] > 0) {
                    $data_count_cost = GamePlayer::get_ALL_Finaltotal($current_id, $start_dwry, $data_return['game_id'], 1,0);
                    $total_team_play = $data_count_cost['total_team_play'];
                    $remide_sum_cost =$data_count_cost['remide_sum_cost'];
                    $pay_total_cost = $data_count_cost['pay_total_cost'];
                }
                $delete_player = $data_return['delete_player'];
                $msg_delete = $data_return['msg_delete'];
            }
            
            return response()->json(['response' => $response, 'total_team_play' => $total_team_play, 'remide_sum_cost' => $remide_sum_cost,'pay_total_cost'=>$pay_total_cost, 'delete_player' => $delete_player, 'msg_delete' => $msg_delete]);
        }
    }
    public function game_addPlayer(Request $request) {
        if ($request->ajax()) {
            $response = 1;
            $lang = $this->lang;
            $current_id = Auth::user()->id;
            $total_team_play = $remide_sum_cost =$pay_total_cost= -1;
            $count_free_weekgamesubstitute=0;
            $substitutes_points=$player_link = $player_name = $val_player = '';
            $msg_add = trans('app.add_fail');
            foreach ($request->input() as $key => $value) {
                $$key = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            $player_data = Player::get_PlayerCloum('link', $player_link, 1);
            if (isset($player_data->id)) {
                $start_dwry = Eldwry::get_currentDwry();
                if (isset($start_dwry->id)) {
                    $data_return = GamePlayer::add_DataPlayer($start_dwry, $current_id, $player_data, 1);
                    if ($data_return['game_id'] > 0) {
                        $data_count_cost = GamePlayer::get_ALL_Finaltotal($current_id, $start_dwry, $data_return['game_id'], 1,0,1);
                        $total_team_play = $data_count_cost['total_team_play'];
                        $remide_sum_cost =$data_count_cost['remide_sum_cost'];
                        $pay_total_cost = $data_count_cost['pay_total_cost'];
                        $substitutes_points = $data_count_cost['substitutes_points'];
                        $count_free_weekgamesubstitute =GameSubstitutes::countFreePointSubstitute($current_id,$start_dwry->id,$data_return['game_id']);
                    }
                    $add_player = $data_return['add_player'];
                    $msg_add = $data_return['msg_add'];
                }
            } else {
                $add_player = 0;
            }
            return response()->json(['response' => $response, 'total_team_play' => $total_team_play, 'remide_sum_cost' => $remide_sum_cost,'pay_total_cost'=>$pay_total_cost,'substitutes_points'=>$substitutes_points,'count_free_weekgamesubstitute'=>$count_free_weekgamesubstitute, 'add_player' => $add_player, 'msg_add' => $msg_add, 'val_player' => $val_player, 'player_data' => $player_data]);
        }
    }

    public function game_deletePlayer(Request $request) {
        if ($request->ajax()) {
            $response = 1;
            $lang = $this->lang;
            $current_id = Auth::user()->id;
            $total_team_play = $remide_sum_cost = -1;
            $player_link = $player_name = $eldwry_link = '';
            $msg_delete = trans('app.delete_fail');
            $pay_total_cost=$remide_sum_cost=$total_team_play=-1;
            $substitutes_points= $count_free_weekgamesubstitute='';
            foreach ($request->input() as $key => $value) {
                $$key = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            $get_data =new Class_GameController();
            $array_data =$get_data->game_deletePlayer($current_id,$eldwry_link,$player_link,$lang,0,$pay_total_cost,$remide_sum_cost,$total_team_play,$substitutes_points,$count_free_weekgamesubstitute);

            if ($val_view == 1 || $val_view == '1') {
                $array_data['player_master'] =[];
                $response = view('site.games.side_table', $array_data)->render();
                return response()->json(['response' => $response]);
            } else { //total_points
                $array_data['response'] =$response;
                return response()->json($array_data);
            }
        }
    }

    public function return_player_game(Request $request) {
        if ($request->ajax()) {
            $response = 1;
            $lang = $this->lang;
            $current_id = Auth::user()->id;
            $total_team_play = $remide_sum_cost = -1;
            $player_link = $player_name = $eldwry_link = '';
            $msg_delete = trans('app.return_fail');
            $pay_total_cost=$remide_sum_cost=$total_team_play=-1;
            $substitutes_points= $count_free_weekgamesubstitute='';
            foreach ($request->input() as $key => $value) {
                $$key = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            $get_data =new Class_GameController();
            $array_data =$get_data->return_player_game($current_id,$eldwry_link,$player_link,$lang,0,$pay_total_cost,$remide_sum_cost,$total_team_play,$substitutes_points,$count_free_weekgamesubstitute);

            if ($val_view == 1 || $val_view == '1') {
                $array_data['player_master'] =[];
                $response = view('site.games.side_table', $array_data)->render();
                return response()->json(['response' => $response]);
            } else { //total_points
                $array_data['response'] =$response;
                return response()->json($array_data);
            }
        }
    }

    public function change_player_game(Request $request) {
        if ($request->ajax()) {
            $response = 1;
            $lang = $this->lang;
            $current_id = Auth::user()->id;
            $total_team_play = $remide_sum_cost = -1;
            $player_link = $player_name = $eldwry_link = '';
            $msg_delete = trans('app.change_fail');
            $pay_total_cost=$remide_sum_cost=$total_team_play=-1;
            $substitutes_points= $count_free_weekgamesubstitute='';
            foreach ($request->input() as $key => $value) {
                $$key = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            $get_data =new Class_GameController();
            $array_data =$get_data->change_player_game($current_id,$eldwry_link,$player_link,$lang,0,$pay_total_cost,$remide_sum_cost,$total_team_play,$substitutes_points,$count_free_weekgamesubstitute);

            $array_data['response'] =$response;
            return response()->json($array_data);
        }
    }

    public function checknum_playerteam(Request $request) {
        if ($request->ajax()) {
            $response = 1;
            $lang = $this->lang;
            $current_id = Auth::user()->id;
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
            return response()->json(['response' => $response, 'count_player_team' => $count_player_team,'total_num_player'=>$total_num_player, 'msg_add' => $msg_add]);
        }
    }

    public function submit_game_team(Request $request) {
        if ($request->ajax()) {
            $response = 1;
            $lang = $this->lang;
            $team_name = null;
            foreach ($request->input() as $key => $value) {
                $$key = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            $get_data=new Class_GameController();
            $data_return=$get_data->submit_game_team(Auth::user()->id,$team_name,$lang,0);
            $add_team = $data_return['add_team'];
            $msg_add = $data_return['msg_add'];
            if ($val_view == 1 || $val_view == '1') {
                $player_master = [];
                $array_data = array('player_master' => $player_master);
                $response = view('site.games.side_table', $array_data)->render();
                return response()->json(['response' => $response]);
            } else {
                return response()->json(['response' => $response, 'add_team' => $add_team, 'msg_add' => $msg_add]);
            }
        }
    }

}
