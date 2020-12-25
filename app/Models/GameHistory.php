<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Options;
use App\Models\Subeldwry;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\GamePlayerHistory;

class GameHistory extends Model {

    protected $table = 'game_history';
    protected $fillable = [
        'update_by', 'sub_eldwry_id','user_id','game_id', 'team_name','lineup_id','num_cardgold','num_cardgray','bench_card','triple_card','is_active'
    ];

    public function user() {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function game() {
        return $this->belongsTo(\App\Models\Game::class, 'game_id');
    }

    public function sub_eldwry() {
        return $this->belongsTo(\App\Models\Subeldwry::class, 'sub_eldwry_id');
    }

    public function lineup() {
        return $this->belongsTo(\App\Models\AllSetting::class, 'lineup_id');
    }

    public static function updateColum($id, $colum, $columValue) {
        $data = static::findOrFail($id);
        $data->$colum = $columValue;
        return $data->save();
    }

    public static function updateOrderColum($colum, $valueColum, $columUpdate, $valueUpdate) {
        return static::where($colum, $valueColum)->update([$columUpdate => $valueUpdate]);
    }

    public static function check_foundData($colum, $val) {
        $data = static::where($colum, $val)->orderBy('id', 'DESC')->first();
        $result=0;
        if(isset($data->id)){
            $result=1;
        }
        return $result;
    }

    public static function foundData($colum, $val) {
        $link_found = static::where($colum, $val)->orderBy('id', 'DESC')->first();
        return $link_found;
    }

    public static function All_foundData($colum, $val) {
        $link_found = static::where($colum, $val)->get();
        return $link_found;
    }

    public static function foundDataTwoCondition($colum, $val,$colum2, $val2) {
        $GameHistory = static::where($colum, $val)->where($colum2, $val2)->first();
        return $GameHistory;
    }

    public static function register_GameHistory($user_id, $sub_eldwry_id, $is_active = 1) {
        $data = static::where('user_id', $user_id)->where('sub_eldwry_id', $sub_eldwry_id)->where('is_active', $is_active)->orderBy('id', 'DESC')->first();
        return $data;
    }

    public static function CheckTime_StopSubeldwry(){
        $check_stop_subeldwry=0;
        $time_stop_subeldwry=Options::get_RowOption('time_stop_subeldwry','option_value',0);
        //check current date with current subeldwry
        $current_date=date("Y-m-d");
        $current_date_time=date("Y-m-d H:i:s");
        $start_date_subeldwry=null;
        $current_date_subeldwry=subTimeOnDate($current_date_time,$time_stop_subeldwry,'minutes');
        
        $sub_eldwry=Subeldwry::getSubeldwry_ByDate($current_date);

        if(isset($sub_eldwry->id)){
           $start_date_subeldwry= $sub_eldwry->start_date;
            $sub_timeStop_from_timeStart=subTimeOnDate($start_date_subeldwry,$time_stop_subeldwry,'minutes');
            if($start_date_subeldwry > $current_date_subeldwry && $current_date_subeldwry >= $sub_timeStop_from_timeStart){
                //stop all work in game
                $check_stop_subeldwry=1;
                //take copy and move data to history table
                static::Copy_Game_GameHistory($sub_eldwry->id);
            }
        }
        return array('check_stop_subeldwry'=>$check_stop_subeldwry,'start_date_subeldwry'=>$start_date_subeldwry,'time_stop_subeldwry'=>$time_stop_subeldwry);
    }

    public static function Copy_Game_GameHistory($sub_eldwry_id){
        // $chec_add=static::foundData('sub_eldwry_id', $sub_eldwry_id);
        // if(!isset($chec_add->id)){
            $games=Game::get();
            foreach ($games as $key_game => $val_game) {
                if(!empty($val_game->team_name)){
                    $input=[
                        'sub_eldwry_id'=>$sub_eldwry_id,
                        'game_id'=>$val_game->id,
                        'user_id'=>$val_game->user_id,
                        'team_name'=>$val_game->team_name,
                        'lineup_id'=>$val_game->lineup_id,
                        'num_cardgold'=>$val_game->num_cardgold,
                        'num_cardgray'=>$val_game->num_cardgray,
                        'bench_card'=>$val_game->bench_card,
                        'triple_card'=>$val_game->triple_card,
                        'is_active'=>$val_game->is_active,
                        'update_by'=>$val_game->update_by,
                    ];
                    if($val_game->bench_card==1){
                        $game->update(['bench_card'=>-1]);
                    }elseif($val_game->triple_card==1){
                        $game->update(['triple_card'=>-1]);
                    }
                    $check_found=static::foundDataTwoCondition('sub_eldwry_id',$sub_eldwry_id,'game_id',$val_game->id);
                    if(isset($check_found->id)){
                        $game_history_id=$check_found->id;
                        $check_found->update($input);
                    }else{
                        $add=static::create($input);
                        $game_history_id=$add['id'];
                    }
                    $game_players=GamePlayer::where('game_id',$val_game->id)->get();
                    foreach ($game_players as $key_player => $val_player) {

                        $input_play=[
                            'game_history_id'=>$game_history_id,
                            'type_id'=>$val_player->type_id,
                            'player_id'=>$val_player->player_id,
                            'type_coatch'=>$val_player->type_coatch,
                            'order_id'=>$val_player->order_id,
                            'myteam_order_id'=>$val_player->myteam_order_id,
                            'is_active'=>$val_player->is_active,
                            'is_change'=>$val_player->is_change,
                            'is_delete'=>$val_player->is_delete,
                            'update_by'=>$val_player->update_by,
                        ];
                        $check_foundPlay=GamePlayerHistory::foundDataThreeCondition('game_history_id',$game_history_id,'player_id',$val_player->player_id,'is_active',$val_player->is_active);
                        if(isset($check_foundPlay->id)){
                            $check_foundPlay->update($input_play);
                        }else{
                            GamePlayerHistory::create($input_play);
                        }
                    }
                }
            }
        // }
            
        //delete and empty table GamePlayer
        //$delete=GamePlayer::delete();
        return true;
    }
}
