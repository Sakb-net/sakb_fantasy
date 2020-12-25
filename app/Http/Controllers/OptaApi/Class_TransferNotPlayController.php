<?php

namespace App\Http\Controllers\OptaApi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
// use App\Models\Options;
use App\Models\Eldwry;
use App\Models\Subeldwry;
use App\Models\Match;
use App\Models\DetailPlayerMatche;
use App\Models\Player;
use App\Models\GameHistory;
use App\Models\GamePlayerHistory;
use App\Models\AllSetting;
use App\Models\GamePlayer;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\OptaApi\Class_PointController;

class Class_TransferNotPlayController extends AdminController {

	public function TransferAllSubEldwry(){
 		$eldwry=Eldwry::get_currentDwry();
        if(isset($eldwry->id)){
            $subeldwrys=Subeldwry::All_foundData('eldwry_id', $eldwry->id);
            foreach ($subeldwrys as $key => $value) {
				$this->TransferNotPlay($value->id);
            }
        }
        return true;    
	}

	public function TransferNotPlay($sub_eldwry_id){
        //get all players that play in current 	sub_eldwry	
		$array_playerid_play=DetailPlayerMatche::getPlayers_PlayInSubeldwry($sub_eldwry_id,'>',0,1,'player_id');

        //get all players that not play in current 	sub_eldwry	
		$array_playerid_Notplay=Player::get_PlayerByArray($array_playerid_play,1,'id');

		//get all player that not playe in users games
		$myteam_order_id=12;  
		$array_game_history_id=GamePlayerHistory::get_PlayerUserNotPlayMainTeam($sub_eldwry_id,$array_playerid_Notplay,$myteam_order_id,1,1,'game_history_id');

		foreach ($array_game_history_id as $key => $game_history_id) {
			$current_lineup=$this->LineupGameHistroy($game_history_id);

			$array_game_history=$this->Array_PlayerUserNotPlayActive($array_playerid_play,$array_playerid_Notplay,$game_history_id);

			$this->TransferMainAndSubPlayerToImprovPoint($array_game_history,$current_lineup);
		}
		//cal point user
        $get_point=new Class_PointController();
		$get_point->Cal_PointUser($sub_eldwry_id);
        return true;
    }

	public function LineupGameHistroy($game_history_id){
		$current_game=GameHistory::foundData('id', $game_history_id);
		$current_lineup=[4,4,2];
		if(isset($current_game->lineup->setting_value)){
            $current_lineup=json_decode($current_game->lineup->setting_value,true);
        }
        return $current_lineup;    
	}

	public function Array_PlayerUserNotPlayActive($array_playerid_play,$array_playerid_Notplay,$game_history_id){
		//get players that not play and found in mainteam of user (myteam_order_id < 12)
		$main_game_history=GamePlayerHistory::get_PlayerUserNotPlayActive($array_playerid_Notplay,'game_history_id', $game_history_id,'<',12,1);

		//get players that play and found in subteam of user (myteam_order_id>=12)
		$sub_game_history=GamePlayerHistory::get_PlayerUserNotPlayActive($array_playerid_play,'game_history_id', $game_history_id,'>=',12,1);

		$all_data=[];
		foreach ($main_game_history as $key_main => $val_main) {
			$all_data[$val_main['myteam_order_id']]= $val_main;
		}
		foreach ($sub_game_history as $key_sub => $val_sub) {
			$all_data[$val_sub['myteam_order_id']]= $val_sub;
		}
		return $all_data;
	}

	public function TransferMainAndSubPlayerToImprovPoint($array_game_history,$lineup){
		//mainTeam (myteam_order_id<12) is keyarray
		//subTeam (myteam_order_id>=12) is keyarray

		//transfer goalkeeper
		if(isset($array_game_history[1]) && isset($array_game_history[12])){
			$this->TransferMainAndSubPlayer($array_game_history[1],$array_game_history[12]);
		}
		$array_user_sub_id=[];
		for ($i=2; $i <12 ; $i++) { 
			if(isset($array_game_history[$i])){
			
				$sub_game_history=[];
				if(!in_array(13, $array_user_sub_id)&&isset($array_game_history[13])){
					$sub_game_history=$array_game_history[13];
					$array_user_sub_id[13]=13;
				}elseif(!in_array(14, $array_user_sub_id)&&isset($array_game_history[14])){
					$sub_game_history=$array_game_history[14];
					$array_user_sub_id[14]=14;
				}elseif(!in_array(15, $array_user_sub_id)&&isset($array_game_history[15])){
					$sub_game_history=$array_game_history[15];
					$array_user_sub_id[15]=15;
				}
				if(isset($sub_game_history->id)){
					$data_player=$array_game_history[$i]->players;
					$sub_data_player=$sub_game_history->players;
					$array_location=getarrayLocation($data_player->location_id,1);

					$array_sub_location=getarrayLocation($sub_data_player->location_id,1);

					//same location between main player and sub player
					if($array_location['key_type_loc']==$array_sub_location['key_type_loc']){
						$this->TransferMainAndSubPlayer($array_game_history[$i],$sub_game_history);
					}else{
					//different location between main player and sub player
						$this->DifferentLocationMainSubPlayer($array_game_history[$i],$sub_game_history,$array_location,$array_sub_location,$lineup);
					}
				}
			}	
		}
		return true;
	}

    public function DifferentLocationMainSubPlayer($array_game_history,$sub_game_history,$array_location,$array_sub_location,$lineup){
		$defender=$lineup[0];
		$line=$lineup[1];
		$attacker=$lineup[2];
		if($array_location['key_type_loc']=='defender'){
			$defender=$lineup[0]-1;
			if($lineup[1]<5 && $array_sub_location['key_type_loc']=='line'){
				$line=$lineup[1]+1;
			}elseif($lineup[2]<3 && $array_sub_location['key_type_loc']=='attacker'){
				$attacker=$lineup[2]+1;
			}
		}elseif($array_location['key_type_loc']=='line'){
			$line=$lineup[1]-1;
			if($lineup[0]<5 && $array_sub_location['key_type_loc']=='defender'){
				$defender=$lineup[0]+1;
			}elseif($lineup[2]<3 && $array_sub_location['key_type_loc']=='attacker'){
				$attacker=$lineup[2]+1;
			}
		}elseif($array_location['key_type_loc']=='attacker'){
			$attacker=$lineup[2]-1;
			if($lineup[1]<5 && $array_sub_location['key_type_loc']=='line'){
				$line=$lineup[1]+1;
			}elseif($lineup[0]<5 && $array_sub_location['key_type_loc']=='defender'){
				$defender=$lineup[0]+1;
			}

		}
		$final_lineup=[$defender,$line,$attacker];
		$this->TransferMainAndSubPlayer($array_game_history,$sub_game_history);

		$this->updateLineUp($array_game_history->game_history_id,$final_lineup);
		return $final_lineup;

   	}
   	 	
    public function updateLineUp($game_history_id,$final_lineup){
     	$final_lineup=$this->updateMyteam_order_id($game_history_id);
		$setting_value=json_encode($final_lineup,true);
        $lineup=AllSetting::get_ValSettingLike('lineup',$setting_value,1);
        if(isset($lineup->id)){
        $update=GameHistory::updateOrderColum('id',$game_history_id,'lineup_id',$lineup->id);
        }

		return $final_lineup;
    }

	public function TransferMainAndSubPlayer($main_game_history,$sub_game_history){
		//transfer from sub to main 
		GamePlayerHistory::updateOrderColumThree('id',$main_game_history['id'],'myteam_order_id', $sub_game_history['myteam_order_id'],'type_coatch', $sub_game_history['type_coatch'],'is_improv',1);

		//transfer from main to sub 
		GamePlayerHistory::updateOrderColumThree('id',$sub_game_history['id'],'myteam_order_id', $main_game_history['myteam_order_id'],'type_coatch', $main_game_history['type_coatch'],'is_improv',1);

		return true;
	}
	public function updateMyteam_order_id($game_history_id){
        $update=0;
        $game_players=GamePlayerHistory::get_dataBetweenTwoVal($game_history_id,'myteam_order_id',2,11,1);
        $new_lineup=GamePlayer::updateGame_Myteam_order_id($game_players);
        return $new_lineup;
    } 

}
