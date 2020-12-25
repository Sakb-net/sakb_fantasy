<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Game;
use App\Models\Player;
use App\Models\AllSetting;
use App\Models\GameTransaction;
use App\Models\PointUser;
use App\Models\PointPlayer;
use App\Models\Team;

class GamePlayerHistory extends Model {
    protected $table = 'game_players_history';
    protected $fillable = [
        'update_by', 'game_history_id', 'type_id', 'player_id','type_coatch','order_id','myteam_order_id','cost','bench_card','triple_card','is_active','is_change','is_delete','is_improv',
    ];
//type_coatch -----> 8:coatch or 9:sub_coatch

    public function gameshistory() {
        return $this->belongsTo(\App\Models\GameHistory::class, 'game_history_id');
    }

    public function alltypes() {
        return $this->belongsTo(\App\Models\AllType::class, 'type_id');
    }

    public function playerPoints() {
        return $this->belongsTo(\App\Models\PointPlayer::class,'player_id','player_id');
    }

    public function typeCoatch() {
        return $this->belongsTo(\App\Models\AllType::class, 'type_coatch');
    }

    public function players() {
        return $this->belongsTo(\App\Models\Player::class, 'player_id');
    }

    public static function updateOrderColum($colum, $valueColum, $columUpdate, $valueUpdate) {
        return static::where($colum, $valueColum)->update([$columUpdate => $valueUpdate]);
    }

    public static function updateOrderColumTwo($colum, $valueColum, $columUpdate, $valueUpdate, $columUpdate2, $valueUpdate2) {
        return static::where($colum, $valueColum)->update([$columUpdate => $valueUpdate,$columUpdate2 => $valueUpdate2]);
    }
    public static function updateOrderColumThree($colum, $valueColum, $columUpdate, $valueUpdate, $columUpdate2, $valueUpdate2, $columUpdate3, $valueUpdate3) {
        return static::where($colum, $valueColum)->update([$columUpdate => $valueUpdate,$columUpdate2 => $valueUpdate2,$columUpdate3 => $valueUpdate3]);
    }
    public static function get_dataBetweenTwoVal($game_history_id,$colum, $first_val, $second_val,$is_active=1) {
        $data = static::where('game_history_id', $game_history_id)->where('is_active', $is_active)->whereBetween(DB::raw($colum), [$first_val, $second_val])->get();
        return $data;
    }
    public static function foundData($colum, $val) {
        $link_found = static::where($colum, $val)->first();
        return $link_found;
    }
    public static function All_foundDataActive($colum, $val,$is_active=1) {
        $link_found = static::where($colum, $val)->where('is_active', $is_active)->get();
        return $link_found;
    }

    public static function foundDataTwoCondition($colum, $val,$colum2, $val2) {
        $GameHistory = static::where($colum, $val)->where($colum2, $val2)->first();
        return $GameHistory;
    }
    
    public static function foundDataThreeCondition($colum, $val,$colum2, $val2,$colum3, $val3) {
        $GameHistory = static::where($colum, $val)->where($colum2, $val2)->where($colum3, $val3)->first();
        return $GameHistory;
    }

    public static function get_benchPlayers($sub_eldwry_id,$game_history_id) {
        $GameHistory = static::where('game_history_id', $game_history_id)
        ->where('is_active', 1)
        ->where('myteam_order_id', '>',11)
        ->where('is_delete', 0)
        ->get();
        return $GameHistory;
    }

    public static function All_foundData($colum, $val) {
        $link_found = static::where($colum, $val)->get();
        return $link_found;
    }
    public static function GetHistoryPlayUser_Subeldwry($sub_eldwry_id,$is_active=1,$order='DESC',$limit=0,$offset=-1,$op='<',$val_op=12)
    {
        $data = static::leftJoin('game_history', 'game_history.id', '=', 'game_players_history.game_history_id')
        ->select('game_players_history.*', 'game_history.sub_eldwry_id')
        ->where('game_history.sub_eldwry_id', $sub_eldwry_id)
        ->where('game_players_history.is_active',$is_active)
        ->where('game_players_history.myteam_order_id',$op,$val_op)
        ->where('game_players_history.is_delete', 0)
        ->orderBy('game_players_history.id',$order);
        if($limit > 0 && $offset !=-1){
            $result=$data->limit($limit)->offset($offset)->get();
        }else{
            $result=$data->get();
        }
        return $result;
    }

    public static function GetHistorySubPlayUser_Subeldwry($sub_eldwry_id,$is_active=1,$order='DESC',$limit=0,$offset=-1,$op='<',$val_op=12,$bench_card_game_history_id=[])
    {
        $data = static::leftJoin('game_history', 'game_history.id', '=', 'game_players_history.game_history_id')
        ->select('game_players_history.*', 'game_history.sub_eldwry_id')
        ->where('game_history.sub_eldwry_id', $sub_eldwry_id)
        ->where('game_players_history.is_active',$is_active)
        ->where('game_players_history.myteam_order_id',$op,$val_op)
        ->whereIn('game_players_history.game_history_id',$bench_card_game_history_id)
        ->where('game_players_history.is_delete', 0)
        ->orderBy('game_players_history.id',$order);
        if($limit > 0 && $offset !=-1){
            $result=$data->limit($limit)->offset($offset)->get();
        }else{
            $result=$data->get();
        }
        return $result;
    }

    public static function allPlayerGameUser($colum, $val_com, $colum2, $val_com2, $col_order = 'id', $val_order = 'ASC',$chang=0,$array=0,$colm='player_id') {
        $data = static::where($colum, $val_com)->where($colum2, $val_com2);
        if($array==1){
            $result=$data->pluck($colm,$colm)->toArray();

        }else{
            $result=$data->orderBy($col_order, $val_order)->get();
        }
        return $result;
    }

    public static function ChekFoundCoatch($sub_eldwry_id, $game_history_id, $type_coatch=8,$is_active=1,$check=1) {
        $data = static::leftJoin('game_history', 'game_history.id', '=', 'game_players_history.game_history_id')
        ->select('game_players_history.*', '.sub_eldwry_id')
        ->where('game_history.sub_eldwry_id',$sub_eldwry_id)->where('game_players_history.game_history_id',$game_history_id)->where('game_players_history.type_coatch',$type_coatch)->where('game_players_history.is_active',$is_active)->first();
        if($check==1){
            $result=-1; //not found coatch if type_coatch=8
            if(isset($data->id)){
                $result=1;
            }
        }else{
            $result=$data;
        }
        return $result;
    }

    public static function get_PlayerUserNotPlayMainTeam($sub_eldwry_id,$array_player_id,$myteam_order_id=12,$is_active=1,$array =0,$col_pluck=''){
        $data = static::leftJoin('game_history', 'game_history.id', '=', 'game_players_history.game_history_id')
        ->where('game_history.sub_eldwry_id',$sub_eldwry_id)
        ->whereIn('game_players_history.player_id',$array_player_id)
        ->where('game_players_history.myteam_order_id','<',$myteam_order_id)
        ->where('game_players_history.is_active',$is_active);
        if($array ==1){
            $result =$data->pluck($col_pluck,'game_history_id')->toArray(); 
        }else{
            $result =$data->get();
        }
        return $result;
    }

    public static function get_PlayerUserNotPlayActive($array_player_id,$colum='',$val_col='',$myteam_order_op,$myteam_order_id,$is_active=1,$array =0){
        $data = static::whereIn('player_id',$array_player_id)->where($colum,$val_col)->where('myteam_order_id',$myteam_order_op,$myteam_order_id)->where('is_active',$is_active);
        if($array ==1){
            $result =$data->get()->toArray();
        }else{
            $result =$data->get();
        }
        return $result;
    }
}
