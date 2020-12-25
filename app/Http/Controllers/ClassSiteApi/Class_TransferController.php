<?php
namespace App\Http\Controllers\ClassSiteApi;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Game;
use App\Models\Eldwry;
use App\Models\Subeldwry;
use App\Models\Match;
use App\Models\Player;
use App\Models\LocationPlayer;
use App\Models\GamePlayer;
use App\Models\Team;
use App\Models\GameSubstitutes;
use App\Models\Options;
use App\Http\Resources\GameSubstitutesResource;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\ClassSiteApi\Class_GameController;
use App\Http\Controllers\ClassSiteApi\Class_PointController;

class Class_TransferController extends SiteController {

    public function __construct() {
        parent::__construct();
    }

    public function GetDataPlayer_MasterTransfer($current_id,$num_player,$lang='ar',$val_view=0,$api=0) {
        $all_play_game = [];
        $start_dwry = Eldwry::get_currentDwry();
        $current_lineup=[4,4,2];

        if (isset($start_dwry->id)) {
            $current_game = Game::checkregisterDwry($current_id, $start_dwry->id, 1);
            if (isset($current_game->id)) {
                if(isset($current_game->lineup->setting_value)){
                    $current_lineup=json_decode($current_game->lineup->setting_value,true);
                }
                $all_play_game = GamePlayer::allPlayerGameUser('game_id', $current_game->id, 'is_active', 1, 'id', 'ASC');
            }
        }
        //current subeldwry
        $start_dwry = Subeldwry::get_startFirstSubDwry();
        $subeldwry_id=0;
        if(isset($start_dwry->id)){
            $subeldwry_id=$start_dwry->id;
        }
        $array_player_master = GamePlayer::get_DataGroup_lineup($all_play_game, $num_player, $lang,$api,$current_lineup,$subeldwry_id,$current_id);
        $player_master =$array_player_master['all_data'];
        $order_lineup=$array_player_master['order_lineup'];

        $array_data = array('player_master' => $player_master,'current_lineup'=>$current_lineup);
        if($api==0){
            $array_data['order_lineup']=$order_lineup;
        }
        return $array_data;
    }
    
    public function game_substitutePlayer($current_id,$player_link='',$pay_total_cost=-1,$api=0){
        // $total_team_play = $remide_sum_cost =$pay_total_cost= -1;
        $count_free_weekgamesubstitute=0;
        $remove_id=$remove_cost=$add_cost=0;
        $substitutes_points =$player_name = $val_player =$link_substituteplayer= '';
        $msg_add = trans('app.add_fail');
        $player_data = Player::get_PlayerCloum('link', $player_link, 1);
        if (isset($player_data->id)) {
            $start_sub_dwry = Subeldwry::get_startFirstSubDwry(3,1,'',0);
            if (isset($start_sub_dwry->id)) {
                $current_game = Game::checkregisterDwry($current_id, $start_sub_dwry->eldwry_id, 1);
                $array_player=session()->get('array_substitutePlayer');
                if(!is_array($array_player)){
                    $array_player=[];
                }
                $already_usePlayer=session()->get('already_usePlayer');
                if(!is_array($already_usePlayer)){
                    $already_usePlayer=[];
                }
                foreach ($array_player as $key => $value) {
                    if(!in_array($player_data->id, $already_usePlayer) && $value['location']==$player_data->location_player->type_key){
                        //check avaliable transfer or not
                        $arraycheck_data = $this->checksubstitutePlayerONot($start_sub_dwry,$player_data,$value, $current_game,$pay_total_cost);
                        if($arraycheck_data['check_data'] ==1){
                            //add in table GameSubstitutes
                            GameSubstitutes::CheckSubstitutePoints($current_id,$start_sub_dwry,$current_game,$player_data->id,$value['id'],1,$value['cost'],0);
                            $remove_id=$value['id'];
                            $link_substituteplayer=$value['link'];
                            $remove_cost=$value['cost'];
                            $add_cost=$player_data->cost;
                            $already_usePlayer[]=$player_data->id;
                            $already_usePlayer=array_diff($already_usePlayer, [$value['id']] );
                            session()->put('already_usePlayer',$already_usePlayer);
                        }elseif($arraycheck_data['found_befor_player'] == 1){
                            //this user found before
                            $link_substituteplayer=$player_data->link;
                            $remove_id=$player_data->id;
                            $remove_cost=$player_data->cost;
                            $add_cost=$player_data->cost;
                        }else{
                            $msg_add = $arraycheck_data['msg_add'];
                        }
                        break;
                    }
                }
            }
        } 
        if($remove_id > 0){
            $new_array_player=[];
            foreach ($array_player as $key_array => $val_array) {
                if($val_array['id'] !=$remove_id){
                    $new_array_player[]=$val_array;
                }
            }
            session()->put('array_substitutePlayer',$new_array_player);
            $pay_total_cost = $pay_total_cost - $add_cost;// -$remove_cost;
            $substitutes_points =GameSubstitutes::sum_Finaltotal($current_id,'game_id',$current_game->id);
            $count_free_weekgamesubstitute =GameSubstitutes::countFreePointSubstitute($current_id,$start_sub_dwry->id,$current_game->id);
            $add_player =1;
            $msg_add =trans('app.add_scuss');
            $val_player = GamePlayer::single_DataGamePlayerUser($player_data, $this->lang, 0, $player_data->location_player->type_key);
        }else {
            $add_player = 0;
        }
        return array('remove_cost'=>$remove_cost,'add_cost'=>$add_cost,'pay_total_cost'=>$pay_total_cost,'substitutes_points'=>$substitutes_points,'count_free_weekgamesubstitute'=>$count_free_weekgamesubstitute, 'add_player' => $add_player, 'msg_add' => $msg_add,'link_substituteplayer'=>$link_substituteplayer,'val_player' => $val_player);
    }

    public function delete_substitutePlayer($user_id, $player_data,$api=0,$pay_total_cost=-1,$remide_sum_cost=-1,$total_team_play=-1,$substitutes_points='', $count_free_weekgamesubstitute=''){
      //delete player from session when substitutePlayer
        $data_return =[
                'found_session'=>0,
                'delete_player' =>0,
                'game_id' => 0,
                'msg_delete' => trans('app.delete_fail'),
                'pay_total_cost'=>$pay_total_cost,
                'total_team_play'=>$total_team_play,
                'total_cost_play'=>-1,
                'remide_sum_cost'=>$remide_sum_cost,
                'substitutes_points'=>$substitutes_points,
                'count_free_weekgamesubstitute'=>$count_free_weekgamesubstitute,
                'link_substituteplayer'=>$player_data->link,
                'val_player'=>[]
            ];
        $already_usePlayer=session()->get('already_usePlayer');
        if(!is_array($already_usePlayer)){
            $already_usePlayer=[];
        }
        if(in_array($player_data->id,$already_usePlayer)){
            $data_substitutes=GameSubstitutes::GetUserLastPlayer($user_id,$player_data->id,0);
            GameSubstitutes::DeleteColum('id',$data_substitutes->id);
            $main_player=$data_substitutes->player_substitute;
            $data_return['val_player']= GamePlayer::single_DataGamePlayerUser($main_player, $this->lang, 0, $main_player->location_player->type_key);

            $array_player=session()->get('array_substitutePlayer');
            if(!is_array($array_player)){
                $array_player=[];
            }
            $new_array_player=[];
            foreach ($array_player as $key_array => $val_array) {
                if($val_array['id'] != $player_data->id){
                    $new_array_player[]=$val_array;
                }
            }
            session()->put('array_substitutePlayer',$new_array_player);
            $already_usePlayer=array_diff($already_usePlayer,[$player_data->id]);
            session()->put('already_usePlayer',$already_usePlayer);
            $data_return ['found_session']=1;
            $data_return ['delete_player']=1;
            $data_return ['msg_delete']=trans('app.delete_scuss');
            $data_return ['pay_total_cost']=$pay_total_cost + $player_data->cost;
            $data_return ['remide_sum_cost']=$remide_sum_cost;// + $player_data->cost;
        }
        return $data_return;
    }
    public function get_substitutePlayer($user_id,$is_active=0,$api=0){
        $data=GameSubstitutes::GetUserActive($user_id,$is_active);
        return GameSubstitutesResource::collection($data);
        // ->additional($response);
    }

    public function game_substitutePlayer_api($user_id,$array_player,$is_active=0,$api=0){
        $ok_substitutes_points=0;
        $start_sub_dwry = Subeldwry::get_startFirstSubDwry(3,1,'',0);
        if (isset($start_sub_dwry->id)) {
            $current_game = Game::checkregisterDwry($user_id, $start_sub_dwry->eldwry_id, 1);
            foreach ($array_player as $key => $value) {
                $check_found=GameSubstitutes::check_substituteFoundBefor($value['newplayer_id'],$value['substituteplayer_id'],0);
                if(!isset($check_found->id)){
                    //add in table GameSubstitutes
                    GameSubstitutes::CheckSubstitutePoints($user_id,$start_sub_dwry,$current_game,$value['newplayer_id'],$value['substituteplayer_id'],1,$value['substituteplayer_cost'],0);
                }
            }
            $ok_substitutes_points=1;
        }
        return $ok_substitutes_points;
    }

    public function confirm_substitutePlayer($user_id,$is_active=0,$api=0,$active_cardgray=0,$active_cardgold=0){
        $data=GameSubstitutes::GetUserActive($user_id,0);
        foreach ($data as $key => $value) {
            $this->add_substitutePlayerInGame($value,1);
        }
        if($api==0){
            $active_cardgray=session()->get('active_cardgray');
        }
        $substitutes_points='';
        if($active_cardgray > 0){
            $current_game = Game::get_GameCloum('user_id', $user_id);
            $current_game->update(['num_cardgray'=>1]);
            session()->put('active_cardgray',0);
            $substitutes_points=$this->FreeSubstitutes($user_id,$current_game,13); 
        }elseif($active_cardgold > 0){
            $current_game = Game::get_GameCloum('user_id', $user_id);
            $num_cardgold = $current_game->num_cardgold +1;
            $current_game->update(['num_cardgold'=>$num_cardgold]);
            session()->put('active_cardgold',0);
            $substitutes_points=$this->FreeSubstitutes($user_id,$current_game,12);
        }else{
            GameSubstitutes::updateOrderColumCondTwo('user_id', $user_id,'is_active',0,'is_active',1);
        }
        return $substitutes_points;
    }
    public function FreeSubstitutes($user_id,$current_game,$card_type_id=13){
        GameSubstitutes::updateOrderColumThree('user_id', $user_id,'is_active',0,'is_active',1,'points',0,'card_type_id',$card_type_id);
        return  GameSubstitutes::sum_Finaltotal($user_id,'game_id',$current_game->id);  
    }
    public function add_substitutePlayerInGame($value,$is_active=1){
        $game_player_data=GamePlayer::getOrderColumthree('game_id',$value->game_id,'player_id',$value->player_substitute_id,'is_active',1);
        if(!isset($game_player_data->id)){
            $game_player_data=GamePlayer::getOrderColumfour('game_id',$value->game_id,'player_id',$value->player_substitute_id,'is_active',0,'is_change',1);
        }
        if(isset($game_player_data->id)){
            $input['update_by'] = $game_player_data->update_by;
            $input['game_id'] = $game_player_data->game_id;
            $input['type_id'] =$game_player_data->type_id;
            $input['order_id'] =$game_player_data->order_id;
            $input['myteam_order_id'] =$game_player_data->myteam_order_id;
            $input['player_id'] = $value->player_id;
            $input['is_active'] = $is_active;
            //end check
            $update_lineup=GamePlayer::updateOrderColumtwo('game_id',$value->game_id,'player_id',$value->player_substitute_id,'is_active', 0,'is_change', 0);
            GamePlayer::create($input);
            return true;
        }
        return false;
    } 
 //*****************Card gray and gold*****************
    public function confirm_cardgray($current_game){
        $active_card = 0;
        if($current_game->num_cardgray<=0){
            //active card gray
            $active_card = 1;
            session()->put('active_cardgray',$active_card);
        }    
        return $active_card;
    }

    public function confirm_cardgold($current_game){
        $active_card=1;
        session()->put('active_cardgold',$active_card);  
        return $active_card;
    }  
    public function status_card($user_id,$type='gray',$api=0){
        $start_sub_dwry = Subeldwry::get_startFirstSubDwry();
        $array_data=[];
        $active_card=0;
        if (isset($start_sub_dwry->id)) {
            $current_game = Game::checkregisterDwry($user_id, $start_sub_dwry->eldwry_id, 1);
            $array_data=GamePlayer::get_ALL_Finaltotal($user_id, $start_sub_dwry->eldwry, $current_game->id,1,0,1);
            $get_point= new Class_PointController();
            $status_card=$get_point->statisticCardSubeldwry($user_id,$start_sub_dwry->id,$current_game,1);
            $array_data=array_merge($array_data,$status_card);
            if($type=='gold'){
                $active_card=$this->confirm_cardgold($current_game);
            }else{
                $active_card=$this->confirm_cardgray($current_game);
            }
        }
        $array_data['active_card']= $active_card;    
        return $array_data;
    }  
///********************************
    public function count_team_players($user_id,$api=0){
        $array_data=[];
        $start_dwry = Eldwry::get_currentDwry();
        $game = Game::checkregisterDwry($user_id, $start_dwry->id, 1);
        $game_players = GamePlayer::getGameData($game->id,1);
        $array_team=[];
        foreach ($game_players as $key => $val_game) {
            if(!isset($array_team[$val_game->players->teams->id])){
                $array_team[$val_game->players->teams->id]=0;
            }
            $array_team[$val_game->players->teams->id] +=1;
            $array_data[$val_game->players->teams->id]=[
                'teamCode'=>$val_game->players->teams->code,
                'count_team'=>$array_team[$val_game->players->teams->id]
            ]; 
        }
        $array_data=array_merge($array_data,[]);
        return $array_data;
    }       
//**********************************
    public function checksubstitutePlayerONot($start_sub_dwry,$player,$delete_player, $game,$pay_total_cost){
        $check_data =0; 
        $found_befor_player=0;
        $msg_add = trans('app.add_fail');
        // $remind = $start_sub_dwry->eldwry->cost - $sum_cost;
        if ($pay_total_cost >= $player->cost) {
            //count number
            $loc_typ_data = GamePlayer::check_AllNumCountData_WhenChangePlayer($player,$delete_player,$game,1);
            if($loc_typ_data['ok_type']==1){
                 //check found or not
                $found_player = GamePlayer::checkFoundData($player->id, $game->id, 0);
                $found_befor_player=1;
                if (!isset($found_player->id)) {
                    $check_data= 1;
                    $found_befor_player=0;
                }
            }else{
                $msg_add=$loc_typ_data['msg_add'];
            }
        }    
        return array('check_data'=>$check_data,'found_befor_player'=>$found_befor_player,'msg_add'=>$check_data);    
    }

}