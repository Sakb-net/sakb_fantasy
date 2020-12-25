<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Game;
use App\Models\Player;
use App\Models\AllSetting;
use App\Models\GameTransaction;
use App\Models\GameSubstitutes;
use App\Models\PointPlayer;
use App\Models\PointUser;
use App\Models\Team;
use App\Models\Match;

class GamePlayer extends Model {
    protected $table = 'game_players';
    protected $fillable = [
        'update_by', 'game_id', 'type_id', 'player_id','type_coatch','order_id','myteam_order_id','cost', 'is_active','is_change','is_delete'
    ];
//type_coatch -----> 8:coatch or 9:sub_coatch
    public function games() {
        return $this->belongsTo(\App\Models\Game::class, 'game_id');
    }

    public function alltypes() {
        return $this->belongsTo(\App\Models\AllType::class, 'type_id');
    }

    public function typeCoatch() {
        return $this->belongsTo(\App\Models\AllType::class, 'type_coatch');
    }

    public function players() {
        return $this->belongsTo(\App\Models\Player::class, 'player_id');
    }

    public static function delete_AutoPlayers($eldwry, $user_id,$is_active = 1, $total_cont = 15) {
        $game = Game::checkregisterDwry($user_id, $eldwry->id, 1);
        $game_id =0;
        $msg_delete = trans('app.delete_fail');
        if (isset($game->id)) {
            $game_id = $game->id;
            //delete all old select
            $delete = static::updateOrderColumthree('game_id',$game_id,'is_active',0,'is_change',0,'is_delete',0);
            $result = array('delete_player' => 1, 'msg_delete' => trans('app.delete_scuss'), 'game_id' => $game_id);
        } else {
            $result = array('delete_player' => -1, 'msg_delete' => $msg_delete, 'game_id' => $game_id);
        }
        return $result;
    }     
    public static function add_AutoPlayers($eldwry, $user_id,$is_active = 1, $total_cont = 15) {
        $game = Game::checkregisterDwry($user_id, $eldwry->id, 1);
        $game_id =$change=0;
        if (isset($game->id)) {
            $game_id = $game->id;
            //delete all old select
            $delete = static::updateOrderColumthree('game_id',$game_id,'is_active',0,'is_change',0,'is_delete',0);
            //select 6 team randam
            $teams=Team::selectRandamTeam('is_active',1,6);
            //form each team select this player number rand
            $array_player=[2,2,3,2,3,3];
            $array_location_id=arrayLocationIdSix();
            $cost=$eldwry->cost;//0;
            $order_id=1;
            foreach ($teams as $key_team => $val_team) {
                $type_id=5; //main
                $players=Player::selectRandamTeam('is_active',1,$val_team->id,$array_location_id[$key_team],$cost,$array_player[$key_team]);
                //check cost and addplayer
                foreach ($players as $key_player => $val_player) {
                    $array_type_team_order=get_Type_myteam_order_id($order_id);
                    $input['update_by'] = $user_id;
                    $input['game_id'] = $game_id;
                    $input['type_id'] =$array_type_team_order['type_id'];
                    $input['order_id'] =$order_id;
                    $input['myteam_order_id'] =$array_type_team_order['myteam_order_id'];
                    $input['player_id'] = $val_player->id;
                    $input['cost'] = $val_player->cost;
                    $data = static::insertPlayer($input,1,$change,$game,[]);
                    $order_id+=1;
                    $cost=$cost-$val_player->cost;
                }
            }
            //add and change lineup
            $input_lineup=['lineup_id'=>1];
            $lineup=$game->update($input_lineup);
            $result = array('add_player' => 1, 'msg_add' => trans('app.add_scuss'), 'data_add' => $data, 'game_id' => $game_id);
        } else {
            $result = array('add_player' => 0, 'msg_add' => trans('app.add_fail'), 'data_add' => [], 'game_id' => $game_id);
        }
        return $result;
     }           

    public static function add_DataPlayer($eldwry, $user_id, $player, $is_active = 1, $total_cont = 15) {
        $game = Game::checkregisterDwry($user_id, $eldwry->id, 1);
        $game_id =$change=0;
        /// $game = Game::get_GameCloum('user_id', $user_id, 1);
        if (isset($game->id)) {
            $game_id = $game->id;
            //check num and cost
            $data_count_cost = static::sum_Finaltotal($game->id, 1);
            $count_row = $data_count_cost[0]->count_row;
            if ($count_row < $total_cont) {
                $sum_cost = $data_count_cost[0]->sum_cost;
                $remind = $eldwry->cost - $sum_cost;
                if ($remind >= $player->cost) {
                    //count number
                    $loc_typ_data = static::check_AllNumCountData($player, $game, 0);
                    if($loc_typ_data['ok_type']==1){
                         //check found or not
                        $check_data = static::checkFoundData($player->id, $game->id, 0);
                        if (!isset($check_data->id)) {
                            $old_gamePlayer=static::getOrderColumfour('game_id',$game->id,'order_id',$loc_typ_data['order_id'],'is_active', 0,'is_change', 1);
                            if(isset($old_gamePlayer->id)){
                                $change=1;
                                $loc_typ_data['myteam_order_id']=$old_gamePlayer->myteam_order_id;
                            }
                            $input['update_by'] = $user_id;
                            $input['game_id'] = $game->id;
                            $input['type_id'] =$loc_typ_data['type_palyer_id'];// $player->type_id;
                            $input['order_id'] =$loc_typ_data['order_id'];
                            if(isset($loc_typ_data['myteam_order_id'])){
                                $input['myteam_order_id'] =$loc_typ_data['myteam_order_id'];
                            }else{
                                $input['myteam_order_id'] =getmyteam_order_id($loc_typ_data['order_id']); 
                            }
                            $input['player_id'] = $player->id;
                            $input['cost'] = $player->cost;
                            $data = static::insertPlayer($input,1,$change,$game,[]);
                            $result = array('add_player' => 1, 'msg_add' => trans('app.add_scuss'), 'data_add' => $data, 'game_id' => $game_id); // $data['id'];
                        } elseif (isset($check_data->is_active) && $check_data->is_active == 0) {
                            $input['is_active'] = 1;
                            $data_update = $check_data->update($input);
                            $data = $check_data;
                            $result = array('add_player' => 1, 'msg_add' => trans('app.add_scuss'), 'data_add' => $data, 'game_id' => $game_id); // $data['id'];
                        } else {
                            $result = array('add_player' => -1, 'msg_add' => trans('app.add_fail_info'), 'data_add' => [], 'game_id' => $game_id);
                        }
                    } else {
                        $result = array('add_player' =>$loc_typ_data['add_player'], 'msg_add' =>$loc_typ_data['msg_add'], 'data_add' => [], 'game_id' => $game_id); //total count compelete
                    }
                } else {
                    $result = array('add_player' => -2, 'msg_add' => trans('app.remaining_not_enough'), 'data_add' => [], 'game_id' => $game_id); //total cost not enoungh
                }
            } else {
                $result = array('add_player' => -3, 'msg_add' => trans('app.num_player_complete'), 'data_add' => [], 'game_id' => $game_id); //count player compelete
            }
        } else {
            $result = array('add_player' => 0, 'msg_add' => trans('app.add_fail'), 'data_add' => [], 'game_id' => $game_id);
        }
        return $result;
    }

    public static function addPlayerAnotherOne($game_player_data,$add_player_data,$change_subeldwry=[]){
        $input['update_by'] = $game_player_data->update_by;
        $input['game_id'] = $game_player_data->game_id;
        $input['type_id'] =$game_player_data->type_id;
        $input['order_id'] =$game_player_data->order_id;
        $input['myteam_order_id'] =$game_player_data->myteam_order_id;
        $input['player_id'] = $add_player_data->id;
        $input['cost'] = $add_player_data->cost;
        $change=0;
        if(isset($change_subeldwry->id)){
            $change=1;
        }
        $game=$game_player_data->games;
        $data = static::insertPlayer($input,1,$change,$game,$change_subeldwry);
        return  $data;
    }
    
    public static function insertPlayer($input,$is_active=1,$change=0,$game=[],$change_subeldwry=[]){
        //start check to confirm change
        if($change==1){
            $player_substitute=static::getOrderColumfour('game_id',$input['game_id'],'order_id',$input['order_id'],'is_active', 0,'is_change', 1);
            //account points of substitute
            if(isset($player_substitute->id)){
                $array_data=GameSubstitutes::CheckSubstitutePoints($game->user_id,$change_subeldwry,$game,$input['player_id'],$player_substitute->player_id,1,$player_substitute->cost);
            }
        }
        //end check
        $update_lineup=static::updateOrderColumtwo('game_id',$input['game_id'],'order_id',$input['order_id'],'is_active', 0,'is_change', 0);

        $input['is_active'] = $is_active;
        $data = static::create($input);
        return  $data;
    }

    public static function ActiveCurrentLineup($game,$api=0){
        //refrech active lineup
        $lineup=$game->lineup->setting_etc;
        $current_lineup=json_decode($lineup,true);
        $update_order=static::add_lineup($game->id,$current_lineup);
        return $update_order;
    }

    public static function delete_DataPlayer($eldwry, $user_id, $player, $is_active = 1) {
        $game = Game::checkregisterDwry($user_id, $eldwry->id, 1);
        $game_id = 0;
        if (isset($game->id)) {
            $game_id = $game->id;
            $check_data = static::checkFoundData($player->id, $game->id, 0);
            if (isset($check_data->id)) {
                $input['is_active'] = 0;
                $data = $check_data->update($input);
                $result = array('delete_player' => 1, 'msg_delete' => trans('app.delete_scuss'), 'game_id' => $game_id);
            } else {
                $result = array('delete_player' => -1, 'msg_delete' => trans('app.delete_fail_info'), 'game_id' => $game_id);
            }
        } else {
            $result = array('delete_player' => 0, 'msg_delete' => trans('app.delete_fail'), 'game_id' => $game_id);
        }
        return $result;
    }

    public static function updateColum($id, $colum, $columValue) {
        $data = static::findOrFail($id);
        $data->$colum = $columValue;
        return $data->save();
    }

    public static function updateOrderColum($colum, $valueColum, $columUpdate, $valueUpdate) {
        return static::where($colum, $valueColum)->update([$columUpdate => $valueUpdate]);
    }

    public static function updateOrderColumtwo($colum, $valueColum,$colum2, $valueColum2, $columUpdate, $valueUpdate, $columUpdate2, $valueUpdate2) {
        return static::where($colum, $valueColum)->where($colum2, $valueColum2)->update([$columUpdate => $valueUpdate,$columUpdate2 => $valueUpdate2]);
    }

    public static function updateOrderColumthree($colum, $valueColum,$columUpdate, $valueUpdate, $columUpdate2, $valueUpdate2, $columUpdate3, $valueUpdate3) {
        return static::where($colum, $valueColum)->update([$columUpdate => $valueUpdate,$columUpdate2 => $valueUpdate2,$columUpdate3 => $valueUpdate3]);
    }
    public static function updateOrderColumfour($colum, $valueColum,$colum2, $valueColum2,$colum3, $valueColum3, $columUpdate, $valueUpdate) {
        return static::where($colum, $valueColum)->where($colum2, $valueColum2)->where($colum3, $valueColum3)->update([$columUpdate => $valueUpdate]);
    }

    public static function updateOrderColumfive($colum, $valueColum,$colum2, $valueColum2,$colum3, $valueColum3, $columUpdate, $valueUpdate,$columUpdate2, $valueUpdate2) {
        return static::where($colum, $valueColum)->where($colum2, $valueColum2)->where($colum3, $valueColum3)->update([$columUpdate => $valueUpdate,$columUpdate2 => $valueUpdate2]);
    }

    public static function updateOrderColumSix($colum, $valueColum,$colum2, $valueColum2,$colum3, $valueColum3, $columUpdate, $valueUpdate,$columUpdate2, $valueUpdate2,$columUpdate3, $valueUpdate3) {
        return static::where($colum, $valueColum)->where($colum2, $valueColum2)->where($colum3, $valueColum3)->update([$columUpdate => $valueUpdate,$columUpdate2 => $valueUpdate2,$columUpdate3 => $valueUpdate3]);
    }
    public static function update_change_TwoRow($game_id,$game_player_id_one,$game_player_two,$player_data_two,$is_active=1){
        //add data of two where id == one
        $result=static::where('game_id', $game_id)->where('id', $game_player_id_one)->where('is_active', $is_active)->update(['myteam_order_id' => $game_player_two->myteam_order_id,'type_coatch' => $game_player_two->type_coatch]);
       return $result;
    }

    public static function deleteColumtwo($colum, $valueColum,$colum2, $valueColum2) {
       return static::where($colum, $valueColum)->where($colum2, $valueColum2)->delete();
    }
    public static function getOrderColumtwo($colum, $valueColum,$colum2, $valueColum2) {
        return static::where($colum, $valueColum)->where($colum2, $valueColum2)->first();
    }

    public static function getOrderColumthree($colum, $valueColum,$colum2, $valueColum2,$colum3, $valueColum3) {
        $data=static::where($colum, $valueColum)->where($colum2, $valueColum2)->where($colum3, $valueColum3)->first();
        return $data;
    }
    public static function getOrderColumfour($colum, $valueColum,$colum2, $valueColum2,$colum3, $valueColum3,$colum4, $valueColum4) {
        $data=static::where($colum, $valueColum)->where($colum2, $valueColum2)->where($colum3, $valueColum3)->where($colum4, $valueColum4)->first();
        return $data;
    }
    
    public static function foundData($colum, $val) {
        $link_found = static::where($colum, $val)->first();
        return $link_found;
    }

    public static function All_foundData($colum, $val) {
        $link_found = static::where($colum, $val)->get();
        return $link_found;
    }
    public static function All_foundDataActive($colum, $val,$is_active=1) {
        $link_found = static::where($colum, $val)->where('is_active', $is_active)->get();
        return $link_found;
    }

    public static function get_dataBetweenTwoVal($game_id,$colum, $first_val, $second_val,$is_active=1) {
        $data = static::where('game_id', $game_id)->where('is_active', $is_active)->whereBetween(DB::raw($colum), [$first_val, $second_val])->get();
        return $data;
    }

    public static function get_GamePlayerID($id, $colum, $all = 0) {
        $GamePlayer = static::where('id', $id)->first();
        if ($all == 0) {
            return $GamePlayer->$colum;
        } else {
            return $GamePlayer;
        }
    }

    public static function checkFoundData($player_id = '', $game_id = '', $check = 1,$is_active=1) {
        $data = static::where('player_id', $player_id)->where('game_id', $game_id);
        if($is_active !=-1){
            $check_data =$data->where('is_active', $is_active);
        }
        $check_data =$data->first();
        if ($check == 1) {
            if (isset($check_data->id)) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return $check_data;
        }
    }
    public static function getGameData_order($game_id,$is_active=1,$op_order='<=',$myteam_order_id=11,$col_order='myteam_order_id',$order='DESC',$col1='id',$op1='<>',$val1=''){
        $get_data = static::where('game_id', $game_id)->where('is_active', $is_active)->where('myteam_order_id',$op_order, $myteam_order_id)->wherenotIN('myteam_order_id',[1,12,13,14,15]);
        if(!empty($val1)){
            $data = $get_data->where($col1,$op1, $val1);
        }
        $data = $get_data->orderBy($col_order,$order)->get();
        return $data;
    }

    public static function getGameData($game_id,$is_active=1, $check = 1,$type_id='',$array=0,$player_id=0,$op='<>') {
        $get_data = static::where('game_id', $game_id)->where('is_active', $is_active);
        if(!empty($type_id)){
            if($array==1){
            $data = $get_data->whereIn('type_id', $type_id);
            }else{
             $data = $get_data->where('type_id', $type_id);
            }
        }
        if($player_id>0){
            $data = $get_data->where('player_id',$op, $player_id);
        }
        $data = $get_data->get();
        return $data;
    }

    public static function getGameDataLocation($game_id,$is_active=1,$location_id='',$array=0) {
        $get_data = static::leftJoin('players', 'players.id', '=', 'game_players.player_id')->where('game_players.game_id', $game_id)->where('game_players.is_active', $is_active);
        if(!empty($location_id)){
            if($array==1){
            $data = $get_data->whereIn('players.location_id', $location_id);
            }else{
             $data = $get_data->where('players.location_id', $location_id);
            }
        }
        $data = $get_data->get();
        return $data;
    }

    public static function getarray_GameData($data, $api = 0) {
        $array_location = $array_team = $array_type = $array_type_location=$array_order_location=$array_ID_delele= [];
        foreach ($data as $key => $val_cat) {
            $array_team[] = $val_cat->players->teams->id;
            $array_location[] = $val_cat->players->location_player->id;
            
            $array_type[] = $val_cat->type_id;
            $array_type_location[$val_cat->players->location_player->id][] = $val_cat->type_id; 

            $array_order_location[$val_cat->players->location_player->id][] = $val_cat->order_id;

            if($val_cat->is_delete==1){
                $array_ID_delele[$val_cat->order_id] = $val_cat->id;
            }
        }
        return array('array_ID_delele'=>$array_ID_delele,'array_order_location'=>$array_order_location,'array_type_location'=> $array_type_location,'array_location' => $array_location, 'array_team' => $array_team, 'array_type' => $array_type);
    }

    public static function checkNumTypeData($array_data_id, $player, $message = 0) {
        $count_type = NumItemCount($array_data_id, $player->type_id);
        $result = array('ok_type' => 1);
        if ($player->type_id == 5 && $count_type < 11) {
            $result = array('add_player' => -4, 'msg_add' => trans('app.finish_num_player_main'), 'ok_type' => 0);
        } elseif ($player->type_id == 6 && $count_type < 4) {
            $result = array('add_player' => -5, 'msg_add' => trans('app.finish_num_player_sub'), 'ok_type' => 0);
        }
        
        return $result;
    }
    public static function checkNumTeamData($array_data_id, $player, $message = 0) {
        $count_type = NumItemCount($array_data_id, $player->team_id);
        $result = array('ok_type' => 1);
        if ($count_type >= 3) {
            $result = array('add_player' => -6, 'msg_add' => trans('app.finish_num_player_choose_team'), 'ok_type' => 0);
        } 
        return $result;
    }
    
    public static function confirm_DeletPlayer($array_ID_delele, $api = 0) {
        $order_id=0;
        foreach ($array_ID_delele as $key => $value) {
            $order_id=$key;
            //dele
            static::updateOrderColum('id', $value,'is_active', 0);
        }
        return $order_id;
   } 
    public static function checkNumlocationData($array_data_id,$array_ID_delele,$array_type_location,$array_order_location, $player, $message = 0, $api = 0) {
        $count_type = NumItemCount($array_data_id, $player->location_id);
        $result = array('ok_type' => 1,'type_palyer_id'=>5,'order_id'=>0);  
        if (in_array($player->location_id,[1]) && ($count_type <2|| !empty($array_ID_delele))) { //goalkeeper
            $result['order_id']=1;
            if(isset($array_type_location[1])){
                if($array_type_location[1][0]==5){ //basic
                    $result['type_palyer_id']=6; //spare
                    $result['order_id']=2;
                }
            }
            if($count_type >=2) { 
                $order_id=static::confirm_DeletPlayer($array_ID_delele,$api);
              if($order_id>0){
                $result['order_id']=$order_id;
              }
            }
        }elseif (in_array($player->location_id,[5,6,7]) && ($count_type <5 || !empty($array_ID_delele))) { 
            $arry_result=TypePlayer_item($array_type_location,$array_order_location,[5,6,7],3,7,4);
            $result['type_palyer_id']=$arry_result['type_palyer_id'];
            $result['order_id']=$arry_result['order_id'];
            if($count_type >=5) { 
                $order_id=static::confirm_DeletPlayer($array_ID_delele,$api);
              if($order_id>0){
                $result['order_id']=$order_id;
              }
            }
        }elseif (in_array($player->location_id,[8,9,10]) && ($count_type <5|| !empty($array_ID_delele))) { //center_line
            $arry_result=TypePlayer_item($array_type_location,$array_order_location,[8,9,10],8,12,4);
            $result['type_palyer_id']=$arry_result['type_palyer_id'];
            $result['order_id']=$arry_result['order_id'];
            if($count_type >=5) { 
                $order_id=static::confirm_DeletPlayer($array_ID_delele,$api);
              if($order_id>0){
                $result['order_id']=$order_id;
              }
            }
        }elseif (in_array($player->location_id,[2,3,4]) && ($count_type <3|| !empty($array_ID_delele))) { //attacking
            $arry_result=TypePlayer_item($array_type_location,$array_order_location,[2,3,4],13,15,2);
            $result['type_palyer_id']=$arry_result['type_palyer_id'];
            $result['order_id']=$arry_result['order_id'];
            if($count_type >=3) { 
                $order_id=static::confirm_DeletPlayer($array_ID_delele,$api);
              if($order_id>0){
                $result['order_id']=$order_id;
              }
            }
        }else{
            //not accept because more number
            $result = array('add_player' => -7, 'msg_add' => trans('app.finish_num_player_choose_location'), 'ok_type' => 0);
        }    
        return $result;
    }
    public static function check_AllNumCountData_WhenChangePlayer($add_player,$delete_player,$game, $check = 1) {
        //get all players except delete_player 
        if(isset($delete_player['id'])){
            $delete_player_id = $delete_player['id'];
        }else{
           $delete_player_id = $delete_player->id;
        }
        $game_players = static::getGameData($game->id,1,1,'',0,$delete_player_id,'<>');
        //get  count and statistic number
        $array_data = static::getarray_GameData($game_players);
        //check team
        $check_team = static::checkNumTeamData($array_data['array_team'], $add_player, 1);
        return $check_team;
    }
    public static function check_AllNumCountData($player, $game, $check = 1) {
        //get number players in my game of location of current player
        $game_players = static::getGameData($game->id,1);
        //get  count and statistic number
        $array_data = static::getarray_GameData($game_players);
        //check team
         $check_team = static::checkNumTeamData($array_data['array_team'], $player, 1);
        if($check_team['ok_type']==1){
            //check location
            $check_location = static::checkNumlocationData($array_data['array_location'],$array_data['array_ID_delele'],$array_data['array_type_location'],$array_data['array_order_location'], $player, 1);
            if($check_location['ok_type']==1){
                 //type_palyer_id
                    $check_location['array_location']=$array_data['array_location'];
                    $result=$check_location;  //this ok and add player
            }else{
                $result=$check_location;
            }
        }else{
            $result=$check_team;
        }
        return $result;
    }


    public static function get_GamePlayerRow($id, $colum = 'id', $game_id = '') {
        $GamePlayer = static::where($colum, $id)->where('game_id', $game_id)->first();
        return $GamePlayer;
    }

    public static function get_GamePlayerCloum($colum = 'id', $val = '', $game_id = '') {
        $GamePlayer = static::where($colum, $val)->where('game_id', $game_id)->orderBy('id', 'DESC')->first();
        return $GamePlayer;
    }

    public static function checkregisterDwry($game_id, $player_id, $type_id = 1) {
        $data = static::where('game_id', $game_id)->where('player_id', $player_id)->where('type_id', $type_id)->orderBy('id', 'DESC')->first();
        return $data;
    }

    public static function allPlayerGameUser($colum, $val_com, $colum2, $val_com2, $col_order = 'id', $val_order = 'ASC',$chang=0,$array=0,$colm='player_id') {
        if($chang!=1){
        //return player not confirm player
        static::updateOrderColumtwo($colum, $val_com,'is_change', 1,'is_active', 1,'is_change', 0);
        }
        ///end
        $data = static::where($colum, $val_com)->where($colum2, $val_com2);
        if($array==1){
            $result=$data->pluck($colm,$colm)->toArray();

        }else{
            $result=$data->orderBy($col_order, $val_order)->get();
        }
        return $result;
    }

    public static function sum_Finaltotal($game_id, $sum = 0, $is_active = 1) {
        if ($sum == 1) {//count and sum cost
            $data = static::select(DB::raw('sum(cost) as sum_cost'), DB::raw('count(*) as count_row'))->where('game_players.game_id', $game_id)->where('game_players.is_active', $is_active)->get();            
        } elseif ($sum == 2) { //count only
            $sum = static::select(DB::raw('count(*) as count_row'))->where('game_id', $game_id)->where('is_delete',0)->get();
            $data = $sum[0]->count_row;
        } else {
            $data = static::where('game_id', $game_id)->where('is_delete',0)->orderBy('id', 'DESC')->get();
        }
        return $data;
    }

    public static function SearchGamePlayer($search, $game_id = '', $limit = 0) {
        $data = static::Where('game_id', 'like', '%' . $search . '%')
                ->orWhere('player_id', 'like', '%' . $search . '%')
                ->orWhere('cost', 'like', '%' . $search . '%');

        if (!empty($game_id)) {
            $result = $data->where('game_id', $game_id);
        }
        if ($limit > 0) {
            $result = $data->paginate($limit);
        } elseif ($limit == -1) {
            $result = $data->pluck('id', 'id')->toArray();
        } else {
            $result = $data->get();
        }
        return $result;
    }

//********************function ************************
    public static function add_lineup($game_id,$current_lineup){
        $all_play_game = GamePlayer::allPlayerGameUser('game_id', $game_id, 'is_active', 1, 'myteam_order_id', 'ASC');
        $update= GamePlayer::addOrder_lineup($all_play_game,$current_lineup);
        return $update;
    }
    public static function addOrder_lineup($data,$current_lineup){
        $array_sort_player = array_sort_player();
        $type_loc_player = '';
        $goalkeeper=1;  //$goalkeeper_spare=12;

        $index_spare=13;
        $num_defender =$current_lineup[0];
        $num_line = $current_lineup[1];
        $num_attacker =$current_lineup[2];

        $order_defender=2;
        $order_line = $order_defender+$current_lineup[0];
        $order_attacker = $order_line+$current_lineup[1];
       //$order_spare = 1+$order_attacker+$current_lineup[2];
        foreach ($data as $key => $val_cat) {
            $type_key_location = $val_cat->players->location_player->type_key;
            $index_key = $val_cat->myteam_order_id;
            if (in_array($type_key_location, [$array_sort_player[0]])) {
                $type_loc_player = 'goalkeeper';
            } elseif (in_array($type_key_location, [$array_sort_player[1], $array_sort_player[2], $array_sort_player[3]])) {
                $type_loc_player = 'line';
                if($num_line>0){
                    $input['myteam_order_id']=$order_line;
                    $order_line +=1;
                    $num_line -=1;
                }else{
                    $input['myteam_order_id']=$index_spare;
                    $index_spare +=1;
                }
                $val_cat->update($input); 
            } elseif (in_array($type_key_location, [$array_sort_player[4], $array_sort_player[5], $array_sort_player[6]])) {                
                $type_loc_player = 'defender';
                if($num_defender>0){
                    $input['myteam_order_id']=$order_defender;
                    $order_defender +=1;
                    $num_defender -=1;
                }else{
                    $input['myteam_order_id']=$index_spare;
                    $index_spare +=1;
                }
                $val_cat->update($input);                    
            } elseif (in_array($type_key_location, [$array_sort_player[7], $array_sort_player[8], $array_sort_player[9]])) {
                $type_loc_player = 'attacker';
                if($num_attacker>0){
                    $input['myteam_order_id']=$order_attacker;
                    $order_attacker +=1;
                    $num_attacker -=1;
                }else{
                    $input['myteam_order_id']=$index_spare;
                    $index_spare +=1;
                }
                $val_cat->update($input); 
            }
        }
        return true;
    }
    public static function get_sum_Finaltotal($current_id, $eldwry_id, $game_id, $sum = 1, $total_cost_play = 0, $api = 0) {
        $data_count_cost = GamePlayer::sum_Finaltotal($game_id, $sum);
        $data['total_team_play'] = $data_count_cost[0]->count_row;
        $data['remide_sum_cost'] = 0;
        if (!empty($data_count_cost[0]->sum_cost)) {
            $data['remide_sum_cost'] = FormPrice($data_count_cost[0]->sum_cost); 
        }
        return $data;
    }

    public static function get_ALL_Finaltotal($current_id, $start_dwry, $game_id, $sum = 1, $api = 0,$ch_point=0) {
        if(isset($start_dwry->eldwry_id)){
            $eldwry_id=$start_dwry->eldwry_id;
        }else{
            $eldwry_id=$start_dwry->id;
        }
        $total_cost_play = GameTransaction::sum_Finaltotal($current_id,$eldwry_id, 1);
        $data = GamePlayer::get_sum_Finaltotal($current_id, $eldwry_id, $game_id, 1, $total_cost_play);
        $data['pay_total_cost']=FormPrice($total_cost_play-$data['remide_sum_cost']);
        $data['total_cost_play']=$total_cost_play;
        $data['substitutes_points']=0;
        if($ch_point==1){
             //get value of substitutes points for game in eldwry
            $data['substitutes_points']=GameSubstitutes::sum_Finaltotal($current_id,'game_id',$game_id);
        }
        return $data;
    }

    public static function checkNUMTypePlayer($game, $player, $lang = 'en', $api = 0) {
        $game_id=$game->id;
        $type_key_location=$player->location_player->type_key;
        $count_type_player=0;
        $lineup='';
        $array_lineup=[0,0,0];
        if(isset($game->lineup->setting_value)){
            $lineup=$game->lineup->setting_value;
            $array_lineup=json_decode($lineup,true);
        }
        $array_sort_player =array_sort_player();
         $type_loc_player='';
         $all_hide=0;
        if (in_array($type_key_location, [$array_sort_player[0]])) {
            $type_loc_player = 'goalkeeper';
            $all_hide=1;
        } elseif (in_array($type_key_location, [$array_sort_player[1], $array_sort_player[2], $array_sort_player[3]])) {
            $type_loc_player = 'line';
            $count_type_player=$array_lineup[1];//count_location_player($lineup,$type_loc_player);
            if($count_type_player<=2){
                $all_hide=1;
            }
        } elseif (in_array($type_key_location, [$array_sort_player[4], $array_sort_player[5], $array_sort_player[6]])) {
            $type_loc_player = 'defender';
            $count_type_player=$array_lineup[0];//count_location_player($lineup,$type_loc_player);
            if($count_type_player<=3){
                $all_hide=1;
            }
        } elseif (in_array($type_key_location, [$array_sort_player[7], $array_sort_player[8], $array_sort_player[9]])) {

            $type_loc_player = 'attacker';
            $count_type_player=$array_lineup[2];//count_location_player($lineup,$type_loc_player);
            if($count_type_player<=1){
                $all_hide=1;
            }
        }
        $type_loc_hidden='';
        if($array_lineup[0]<=3 && $type_loc_player != 'defender'){
            $type_loc_hidden='defender';
        }elseif($array_lineup[1]<=2 && $type_loc_player != 'line'){
            $type_loc_hidden='line';
        }elseif($array_lineup[2]<=1 && $type_loc_player != 'attacker'){
            $type_loc_hidden='attacker';
        }    

        return array('type_loc_hidden'=> $type_loc_hidden,'type_loc_player'=> $type_loc_player,'all_hide'=>$all_hide);
    }
    public static function changeInsidePlayer($game,$ch_game_player_id_one,$ch_player_id_one,$ch_game_player_id_two,$ch_player_id_two, $api = 0) {
        $game_id=$game->id;
        $new_lineup='';
        $ch_player_data_one = Player::get_PlayerCloum('id', $ch_player_id_one, 1);
         $result=1;
        if(isset($ch_player_data_one->id)){
            $ch_player_data_two = Player::get_PlayerCloum('id', $ch_player_id_two, 1);
            if(isset($ch_player_data_two->id)){
                //get data player in game
                $game_player_one=static::foundData('id',$ch_game_player_id_one);
                $game_player_two=static::foundData('id',$ch_game_player_id_two);
                //update first player  
                $result=static::update_change_TwoRow($game_id,$ch_game_player_id_one,$game_player_two,$ch_player_data_two);
                //update second player
                $result=static::update_change_TwoRow($game_id,$ch_game_player_id_two,$game_player_one,$ch_player_data_one);
                //change lineup
            $new_lineup=static::ChangeLineUPAfterchange($game,$ch_game_player_id_two,$ch_player_data_one,$ch_player_data_two);

           // $new_lineup=static::ChangeLineUPAfterchange_old($game,$ch_player_data_one,$ch_player_data_two,$ch_player_id_two);
            }else{
                $result=-2; //not found another player
            }
         }else{
            $result=-2; //not found another player
         }
         return array('result'=>$result,'new_lineup'=>$new_lineup);
    }

    public static function DirectchangeInsidePlayer($game,$ch_player_data_one,$ch_player_data_two, $api = 0) {
        $game_id=$game->id;
        $new_lineup='';
        $result=1;
        $game_player_one=GamePlayer::checkFoundData($ch_player_data_one->id, $game_id,0,1);
        if(isset($game_player_one->id)){
            $ch_game_player_id_one=$game_player_one->id;
            $game_player_two=GamePlayer::checkFoundData($ch_player_data_two->id, $game_id,0,1);
            if(isset($game_player_two->id)){
                $ch_game_player_id_two=$game_player_two->id;
                //update first player  
                $result=static::update_change_TwoRow($game_id,$ch_game_player_id_one,$game_player_two,$ch_player_data_two);
                //update second player
                $result=static::update_change_TwoRow($game_id,$ch_game_player_id_two,$game_player_one,$ch_player_data_one);
                //change lineup
            $new_lineup=static::ChangeLineUPAfterchange($game,$ch_game_player_id_two,$ch_player_data_one,$ch_player_data_two);

            }else{
                $result=-2; //not found second player in game
            }
         }else{
            $result=-2; //not found first player in game
         }
         return array('result'=>$result,'new_lineup'=>$new_lineup);
    }

    public static function ChangeLineUPAfterchange($game,$ch_game_player_id_two,$ch_player_data_one,$ch_player_data_two, $api = 0) {
        //check location
        $player_location_id_one=$ch_player_data_one->location_id;
        $player_location_id_two=$ch_player_data_two->location_id;
        $arrray_one=getarrayLocation($player_location_id_one,1);
        $arrray_two=getarrayLocation($player_location_id_two,1);
        $array_diff=array_diff($arrray_one['array_location_id'], $arrray_two['array_location_id']);
        //get current lineup
        $current_lineup=[4,4,2];
        if(isset($game->lineup->setting_value)){
            $current_lineup=json_decode($game->lineup->setting_value,true);
        }
        if(count($array_diff)>0){
            //fixed myteam_order_id for player two
            $new_lineup=GamePlayer::updateMyteam_order_id($game,$api);
            // print_r($new_lineup);
            //update current lineup to new lineup
            $setting_value=json_encode($new_lineup,true);
            $lineup=AllSetting::get_ValSettingLike('lineup',$setting_value,1);
            // print_r($setting_value);
            // print_r($lineup);die;
            $update=Game::updateOrderColum('id',$game->id,'lineup_id',$lineup->id);
        }else{
            //two player same location
            $new_lineup=$current_lineup;
        } 
        return $new_lineup;

    }   

    public static function updateMyteam_order_id($game,$api=0){
        $update=0;
        $game_players=static::get_dataBetweenTwoVal($game->id,'myteam_order_id',2,11,1);
        $new_lineup=static::updateGame_Myteam_order_id($game_players,$api);
        return $new_lineup;
    }

    public static function updateGame_Myteam_order_id($game_players,$api=0){
        $index_order=2;
        $array_sort_player=array_sort_player();
        $array_player_line=$array_player_defender=$array_player_goalkeeper=$array_player_attacker=[];
        foreach ($game_players as $key => $val_cat) {
            $type_key_location = $val_cat->players->location_player->type_key;
            // if (in_array($type_key_location, [$array_sort_player[0]])) {
            //     $array_player_goalkeeper[] =$val_cat;
            // } else
            if (in_array($type_key_location, [$array_sort_player[1], $array_sort_player[2], $array_sort_player[3]])) {
                $array_player_line[]=$val_cat ;
            } elseif (in_array($type_key_location, [$array_sort_player[4], $array_sort_player[5], $array_sort_player[6]])) {  
                $array_player_defender[]=$val_cat ;

            } elseif (in_array($type_key_location, [$array_sort_player[7], $array_sort_player[8], $array_sort_player[9]])) {
                $array_player_attacker[]=$val_cat ;
            }
        }
        //update myteam_order_id
        foreach ($array_player_defender as $key => $val_game_p) {
            $input['myteam_order_id']=$index_order;
            $val_game_p->update($input);
            $index_order +=1;
        }
        foreach ($array_player_line as $key => $val_game_p) {
            $input['myteam_order_id']=$index_order;
            $val_game_p->update($input);
            $index_order +=1;
        }
        foreach ($array_player_attacker as $key => $val_game_p) {
            $input['myteam_order_id']=$index_order;
            $val_game_p->update($input);
            $index_order +=1;
        }
        $new_lineup=[count($array_player_defender),count($array_player_line),count($array_player_attacker)];
        return $new_lineup;
    }
        
    public static function get_DataGroup($data, $num_player = 15, $lang = 'ar', $api = 0) {
        $all_data = [];
        $type_loc_player = '';
        $array_sort_player =array_sort_player();
        $array_count = array_count_14();
        foreach ($data as $key => $val_cat) {
            $type_key_location = $val_cat->players->location_player->type_key;
            $index_key = $val_cat->order_id-1;
            if (in_array($type_key_location, [$array_sort_player[0]])) {
                $type_loc_player = 'goalkeeper';
            } elseif (in_array($type_key_location, [$array_sort_player[1], $array_sort_player[2], $array_sort_player[3]])) {
                $type_loc_player = 'line';
            } elseif (in_array($type_key_location, [$array_sort_player[4], $array_sort_player[5], $array_sort_player[6]])) {
                $type_loc_player = 'defender';
            } elseif (in_array($type_key_location, [$array_sort_player[7], $array_sort_player[8], $array_sort_player[9]])) {
                $type_loc_player = 'attacker';
            }
            unset($array_count[$index_key]);
            $all_data[$index_key] = static::single_DataGamePlayerUser($val_cat, $lang, $api, $type_loc_player);
        }
        foreach ($array_count as $key_count => $val_count) {
            if ($val_count <= 1) {
                $type_loc_player = 'goalkeeper';
            } elseif ($val_count > 1 && $val_count <= 6) {
                $type_loc_player = 'defender';
            } elseif ($val_count > 6 && $val_count <= 11) {
                $type_loc_player = 'line';
            } elseif ($val_count > 11 && $val_count <= 14) {
                $type_loc_player = 'attacker';
            }
            $all_data[$val_count] = static::Empty_single_DataGamePlayerUser($val_count, $lang, $api, $type_loc_player);
        }
        return $all_data;
    }

public static function get_DataGroup_lineup($data, $num_player = 15, $lang = 'ar', $api = 0,$current_lineup=[4,4,2],$sub_eldwry_id=0,$user_id=0) {
        $all_data = [];
        $goalkeeper = 0;
        $type_loc_player = '';
        //for lineup
        $order_defender=1;
        $order_line = $order_defender+$current_lineup[0];
        $order_attacker = $order_line+$current_lineup[1];
        $order_spare = $order_attacker+$current_lineup[2];
        $order_lineup=[$goalkeeper,$order_defender,$order_line,$order_attacker,$order_spare];
        //end lineup
        $array_sort_player =array_sort_player();
        $array_count = array_count_15();    
        foreach ($data as $key => $val_cat) {
            $type_key_location = $val_cat->players->location_player->type_key;
            $index_key = $val_cat->myteam_order_id;
            if (in_array($type_key_location, [$array_sort_player[0]])) {
                $type_loc_player = 'goalkeeper';
            } elseif (in_array($type_key_location, [$array_sort_player[1], $array_sort_player[2], $array_sort_player[3]])) {
                $type_loc_player = 'line';
            } elseif (in_array($type_key_location, [$array_sort_player[4], $array_sort_player[5], $array_sort_player[6]])) {                
                $type_loc_player = 'defender';
            } elseif (in_array($type_key_location, [$array_sort_player[7], $array_sort_player[8], $array_sort_player[9]])) {
                $type_loc_player = 'attacker';
            }
            unset($array_count[$index_key]);
            $all_data[$index_key] = static::single_DataGamePlayerUser($val_cat, $lang, $api, $type_loc_player,$sub_eldwry_id,$user_id);
        }
        //if there player empty
        foreach ($array_count as $key_count => $val_count) {
            if ($val_count == 1 || $val_count == 15) {
                $type_loc_player = 'goalkeeper';
            } elseif ($val_count==2 && $val_count < 6) {
                $type_loc_player = 'defender';
            } elseif ($val_count > 6 && $val_count < 11) {
                $type_loc_player = 'line';
            } elseif ($val_count == 11 && $val_count <= 14) {
                $type_loc_player = 'attacker';
            }
            $all_data[$val_count] = static::Empty_single_DataGamePlayerUser($val_count, $lang, $api, $type_loc_player,$sub_eldwry_id,$user_id);
        }
        //print_r($all_data);die; //eman
        return array('all_data'=>$all_data,'order_lineup'=>$order_lineup);
    }

    public static function get_DataGamePlayerUser($data, $lang = 'ar', $api = 0,$sub_eldwry_id=0,$user_id=0) {
        $all_data = [];
        foreach ($data as $key => $val_cat) {
            $all_data[] = static::single_DataGamePlayerUser($val_cat, $lang, $api,'',$sub_eldwry_id,$user_id);
        }
        return $all_data;
    }

    public static function single_DataGamePlayerUser($val_cat, $lang = 'ar', $api = 0, $type_loc_player = '',$sub_eldwry_id=0,$user_id=0) {
        /////
        $value_lang = 'value_' . $lang;
        $array_data['found_player'] = 1;
        if($api == 0){
            $array_data['empty_class'] = '';
            $array_data['is_delete'] = 0;
        }
        $array_data['currency'] = trans('app.SAR');
        $array_data['eldwry_name'] ='';
        $array_data['eldwry_link'] ='';
        if(isset($val_cat->games->eldwry->id)){
            $array_data['eldwry_name'] =finalValueByLang($val_cat->games->eldwry->lang_name,$val_cat->games->eldwry->name,$lang);
            $array_data['eldwry_link'] =$val_cat->games->eldwry->link;
        }
        if(isset($val_cat->players)){
            $data_player=$val_cat->players;
        }else{
            $data_player=$val_cat;
        }
        $array_data['team'] = finalValueByLang($data_player->teams->lang_name,$data_player->teams->name,$lang);
        $array_data['teamCode'] = $data_player->teams->code;

        $array_data['player_id'] = $data_player->id;
        $array_data['link_player'] = $data_player->link;
        $array_data['image_player'] =finalValueByLang($data_player->image,'',$lang);
        $array_data['name_player'] =finalValueByLang($data_player->lang_name,$data_player->name,$lang);
        $array_data['cost_player'] = $data_player->cost;
        $array_data['sell_cost'] = $val_cat->cost;
        $array_data['buy_cost'] = $val_cat->cost;
        $array_data['cost_game_player'] = $val_cat->cost;
        //**********************
        $array_data['state_player'] = 'normal';
        $array_data['point_player'] =0;
        $array_data['match_name'] ='';
        $array_data['match_link'] = '';
        $array_data['fix'] = '-';
        if($sub_eldwry_id<=0 || empty($sub_eldwry_id)){
            $current_subeldwry=Subeldwry::get_CurrentSubDwry();
            $sub_eldwry_id=$current_subeldwry->id;
        }// else{ page point}    
        $point_player=0;
        if($val_cat->myteam_order_id >11){
            $point_player= PointPlayer::sum_Finaltotal_player($data_player->id,'sub_eldwry_id',$sub_eldwry_id,1);
        }else{
            $point_data=PointUser::sum_Finaltotal($user_id,'sub_eldwry_id',$sub_eldwry_id,2,'player_id',$data_player->id);
            if(isset($point_data->id)){
                $point_player=$point_data->points;
            }
        }
        $array_data['point_player'] = $point_player; 
        $array_data['total_points'] =$point_player;//PointPlayer::sum_Finaltotal_SinglePlayer($data_player->id);
        $match=Match::get_MatchTeamSubeldwry($sub_eldwry_id,$data_player->teams->id,1);
        if(isset($match->id)){
            $match_code=$match->teams_first->code;
            if($match->first_team_id==$data_player->teams->id){
                $match_code=$match->teams_second->code;
            }
            $array_data['match_name'] =$match_code;
            $array_data['fix'] = $match_code;
            $array_data['match_link'] = $match->link;
        }
    
        ///********************
        $array_data['form'] = 10.0;
        //***************************
        $array_data['location_key_player'] = $data_player->location_player->type_key;
        $public_cla='';
        if(in_array($array_data['location_key_player'], ['goalkeeper'])){
            $public_cla='goalkeeper';
        }elseif(in_array($array_data['location_key_player'], ['defender_center','defender_right','defender_left'])){
            $public_cla='defender'; 
        }elseif(in_array($array_data['location_key_player'], ['center_line','left_line','right_line'])){
            $public_cla='line'; 
        }elseif(in_array($array_data['location_key_player'], ['attacker_center','attacker_left','attacker_right'])){
            $public_cla='attacker';    
        }
        $array_data['public_cla'] =$public_cla;
        $array_data['location_player'] = $data_player->location_player->$value_lang;
        $array_data['type_player'] = $val_cat->alltypes->$value_lang;
        $array_data['type_coatch'] ='';
        $array_data['type_key_coatch'] ='';
        if(isset($val_cat->typeCoatch)&&!empty($val_cat->typeCoatch)){
            //type_coatch -----> 8:coatch or 9:sub_coatch
        $array_data['type_coatch'] = $val_cat->typeCoatch->$value_lang;
         $array_data['type_key_coatch'] = $val_cat->typeCoatch->type_key;
        }

        $array_data['myteam_order_id'] = $val_cat->myteam_order_id;
        $array_data['type_loc_player'] = $type_loc_player;
        $array_data['created_at'] = $val_cat->created_at->format('Y-m-d');
        return $array_data;
    }

    public static function Empty_single_DataGamePlayerUser($index = 1, $lang = 'ar', $api = 0, $type_loc_player = '',$sub_eldwry_id=0,$user_id=0) {
        //$value_lang='value_'.$lang;
        $array_data['found_player'] = 0;
        if($api == 0){
            $array_data['is_delete'] = 0; 
            $array_data['empty_class'] = 'empty empty_player_choose_' . $index . $type_loc_player;
           
        }
        $array_data['eldwry_name'] = '';
        $array_data['eldwry_link'] = '';
        $array_data['currency'] = trans('app.SAR');
        $array_data['team'] ='';
        $array_data['teamCode'] ='';
        $array_data['name_player'] = trans('app.name_player');
        $array_data['cost_player'] = 0.00;
        $array_data['sell_cost'] = 0.00;
        $array_data['buy_cost'] = 0.00;
        //**********************
        $array_data['point_player'] = 0;
        $array_data['state_player'] = 'normal';
        $array_data['match_name'] = '';
        $array_data['match_link'] = '';
        //***************************
        $array_data['total_points'] =0;
        $array_data['fix'] = '-';
        $array_data['form'] = 0.0;
        //****************************
        $array_data['image_player'] = '/images/full-shirt.png'; //images/empty-shirt.png //images/full-shirt.png
        $array_data['link_player'] = '';
        $array_data['location_key_player'] = '';
        $array_data['public_cla'] ='';
        $array_data['location_player'] = '';
        $array_data['type_player'] = '';
        $array_data['type_coatch'] ='';
        $array_data['type_key_coatch'] ='';
        $array_data['type_loc_player'] = $type_loc_player;
        $array_data['created_at'] = '';
        $array_data['myteam_order_id'] =$index;//0;
        return $array_data;
    }
//**************************************
    public static function get_DataGroup_old($data, $num_player = 15, $lang = 'ar', $api = 0) {
    $all_data = [];
    $goalkeeper = -1;
    $player_defender = 1;
    $player_line = 6;
    $player_attacker = 11;
    $type_loc_player = '';
    $array_sort_player = array_sort_player();
    $array_count = array_count_14();
    foreach ($data as $key => $val_cat) {
        $type_key_location = $val_cat->players->location_player->type_key;
        $index_key = 0;
        if (in_array($type_key_location, [$array_sort_player[0]])) {
            $goalkeeper+=1;
            $index_key = $goalkeeper;
            $type_loc_player = 'goalkeeper';
        } elseif (in_array($type_key_location, [$array_sort_player[1], $array_sort_player[2], $array_sort_player[3]])) {
            $player_line+=1;
            $index_key = $player_line;
            $type_loc_player = 'line';
        } elseif (in_array($type_key_location, [$array_sort_player[4], $array_sort_player[5], $array_sort_player[6]])) {
            $player_defender+=1;
            $index_key = $player_defender;
            $type_loc_player = 'defender';
        } elseif (in_array($type_key_location, [$array_sort_player[7], $array_sort_player[8], $array_sort_player[9]])) {
            $player_attacker+=1;
            $index_key = $player_attacker;
            $type_loc_player = 'attacker';
        }
        unset($array_count[$index_key]);
        $all_data[$index_key] = static::single_DataGamePlayerUser($val_cat, $lang, $api, $type_loc_player);
    }
    foreach ($array_count as $key_count => $val_count) {
        if ($val_count <= 1) {
            $type_loc_player = 'goalkeeper';
        } elseif ($val_count > 1 && $val_count <= 6) {
            $type_loc_player = 'defender';
        } elseif ($val_count > 6 && $val_count <= 11) {
            $type_loc_player = 'line';
        } elseif ($val_count > 11 && $val_count <= 14) {
            $type_loc_player = 'attacker';
        }
        $all_data[$val_count] = static::Empty_single_DataGamePlayerUser($val_count, $lang, $api, $type_loc_player);
    }
    return $all_data;
}

public static function get_DataGroup_lineup_old($data, $num_player = 15, $lang = 'ar', $api = 0,$current_lineup=[4,4,2]) {
        $all_data = [];
        $goalkeeper = 0;
        $player_defender = 1;
        $player_line =5;
        $player_attacker = 9;
        $player_spare =11;
        $type_loc_player = '';
        //for lineup
        $order_defender=1;
        $order_line = $order_defender+$current_lineup[0];
        $order_attacker = $order_line+$current_lineup[1];
        $order_spare = $order_attacker+$current_lineup[2];
        $order_lineup=[$goalkeeper,$order_defender,$order_line,$order_attacker,$order_spare];
        //end lineup
        $array_sort_player = [0 => 'goalkeeper', 1 => 'center_line', 2 => 'left_line', 3 => 'right_line'
            , 4 => 'defender_center', 5 => 'defender_left', 6 => 'defender_right', 7 => 'attacker_center', 8 => 'attacker_left', 9 => 'attacker_right'];
        foreach ($data as $key => $val_cat) {
            $type_key_location = $val_cat->players->location_player->type_key;
            $index_key = 0;
            if (in_array($type_key_location, [$array_sort_player[0]])) {
                    if($val_cat->type_id==5){  //basic
                        $goalkeeper+=1;
                        $index_key = $goalkeeper;
                    }else{
                        $player_spare+=1;
                        $index_key=$player_spare;
                    }
                $type_loc_player = 'goalkeeper';
            } elseif (in_array($type_key_location, [$array_sort_player[1], $array_sort_player[2], $array_sort_player[3]])) {
                if($val_cat->type_id==5){  //basic
                    $player_line+=1;
                    $index_key = $player_line;
                }else{
                    $player_spare+=1;
                    $index_key=$player_spare;
                }
                $type_loc_player = 'line';
            } elseif (in_array($type_key_location, [$array_sort_player[4], $array_sort_player[5], $array_sort_player[6]])) {
                if($val_cat->type_id==5){  //basic
                    $player_defender+=1;
                    $index_key = $player_defender;
                }else{
                    $player_spare+=1;
                    $index_key=$player_spare;
                }
                $type_loc_player = 'defender';
            } elseif (in_array($type_key_location, [$array_sort_player[7], $array_sort_player[8], $array_sort_player[9]])) {
                if($val_cat->type_id==5){  //basic
                    $player_attacker+=1;
                    $index_key = $player_attacker;
                }else{
                    $player_spare+=1;
                    $index_key=$player_spare;
                }
                $type_loc_player = 'attacker';
            }
            $all_data[$index_key] = static::single_DataGamePlayerUser($val_cat, $lang, $api, $type_loc_player);
        }
        return array('all_data'=>$all_data,'order_lineup'=>$order_lineup);
    }

    public static function count_Users_Selected_Player($player_id) {
        $sum = static::select(DB::raw('count(*) as games_count'))->where('player_id', $player_id)->get();
        $data=$sum[0]->games_count;
    return $data;
    }

}
