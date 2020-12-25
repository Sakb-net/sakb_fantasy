<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointBounsSystem extends Model {

    protected $table = 'point_bouns_systems';
    protected $fillable = [
        'update_by', 'sub_eldwry_id','match_id', 'team_id', 'player_id','point_id', 'points','is_active'
    ];

    public function user() {
        return $this->belongsToMany(\App\Models\User::class,'update_by');
    }

    public function sub_eldwry() {
        return $this->belongsTo(\App\Models\Subeldwry::class, 'sub_eldwry_id', 'id');
    }

    public function teams() {
        return $this->belongsTo(\App\Models\Team::class);
    }

    public function player() {
        return $this->belongsTo(\App\Models\Player::class);
    }

    public function match() {
        return $this->belongsTo(\App\Models\Match::class);
    }

    public static function AddUpdateBounsPlayer($input){
        $data=static::check_foundPoint($input['player_id'],$input['sub_eldwry_id'],$input['match_id'],$input['team_id'],0);
        if(isset($data->id)){
            $data->update($input);
            $result=$data->id;
        }else{
            $data=static::create($input);
            $result=0;
            if(isset($data['id'])){
                $result=$data['id'];
            }
        }
        return $result;
    }
        

    public static function updateColum($colum, $valueColum, $columUpdate, $valueUpdate) {
        return static::where($colum, $valueColum)->update([$columUpdate => $valueUpdate]);
    }

    public static function foundData($colum, $val) {
        $link_found = static::where($colum, $val)->first();
        return $link_found;
    }

    public static function All_foundData($colum, $val) {
        $link_found = static::where($colum, $val)->get();
        return $link_found;
    }

    public static function check_foundPoint($player_id,$sub_eldwry_id,$match_id,$team_id,$check=0){
        $data=static::where('player_id',$player_id)->where('sub_eldwry_id',$sub_eldwry_id)->where('match_id',$match_id)->where('team_id',$team_id)->first();
        if($check==1){
            $result=0;
            if(isset($data->id)){
                $result=1;
            }
        }else{
            $result=$data;
        }
        return $result;
    }
    public static function get_pointBounsPlayer($match_id,$sub_eldwry_id,$team_id,$is_active=1,$limit=0,$col_order='points',$type_order='DESC'){
        $data=static::where('sub_eldwry_id',$sub_eldwry_id)->where('match_id',$match_id)->where('team_id',$team_id)->orderBy($col_order, $type_order);

        if($limit>0){
            $result=$data->limit($limit)->get();
        }else{
            $result=$data->get();
        }
        return $result;
    }
//********************function ************************
    // public static function get_DataPoint($data, $api = 0) {
    //     $all_data = [];
    //     foreach ($data as $key => $val_cat) {
    //         $all_data[] = static::single_DataPoint($val_cat, $api);
    //     }
    //     return $all_data;
    // }

    // public static function single_DataPoint($val_cat, $api = 0) {
    //     $array_data['type_key'] = $val_cat->type_key;
    //     $array_data['action'] = $val_cat->action;
    //     $array_data['point'] = $val_cat->point;
    //     $array_data['created_at'] = $val_cat->created_at->format('Y-m-d');
    //     return $array_data;
    // }

    public static function Cal_PlayerBonusPoints($team_players){
        $all_data = [];
        $sum = 0;
        $passComplation=0;
        if(isset($team_players)){
            foreach ($team_players as $player) {
                $sum = 0;
                if($player['minsPlayed'] <= 60){
                    $sum = $sum+3;
                }
                if($player['minsPlayed'] > 60){
                    $sum = $sum+6;
                }
                if($player['location_id'] = 1 | $player['location_id'] = 5){
                    $sum = $sum+($player['goals'] * 12);
                }
                if($player['location_id'] = 8){
                    $sum = $sum+($player['goals'] * 18);
                }
                if($player['goalAssist'] > 0){
                    $sum = $sum+($player['goalAssist'] * 9);
                }
                if($player['cleanSheet'] > 0){
                    $sum = $sum+($player['cleanSheet'] * 12);
                }
                if($player['penaltySave'] > 0){
                    $sum = $sum+($player['penaltySave'] * 15);
                }
                if($player['saves'] > 0){
                    $sum = $sum+($player['saves'] * 2);
                }
                if($player['totalClearance'] > 0){
                    $sum = $sum+($player['totalClearance'] * 1);
                }
                if($player['wonTackle'] > 0){
                    $sum = $sum+($player['wonTackle'] * 2);
                }
                if($player['attPenMiss'] > 0){
                    $sum = $sum+($player['attPenMiss'] * -6);
                }
                if($player['yellowCard'] > 0){
                    $sum = $sum+($player['yellowCard'] * -3);
                }
                if($player['redCard'] > 0){
                    $sum = $sum+($player['redCard'] * -9);
                }
                if($player['ownGoals'] > 0){
                    $sum = $sum+($player['ownGoals'] * -6);
                }
                if($player['fouls'] > 0){
                    $sum = $sum+($player['fouls'] * -1);
                }
                if($player['totalOffside'] > 0){
                    $sum = $sum+($player['totalOffside'] * -1);
                }
                if($player['shotOffTarget'] > 0){
                    $sum = $sum+($player['shotOffTarget'] * -1);
                }
                if ($player['totalPass'] > 29){
                    $passComplation=($player['accuratePass']/$player['totalPass'])*100;
                }
                if($passComplation>70 && $passComplation<80){
                    $sum=$sum+2;
                }else if($passComplation>80 && $passComplation<90){
                    $sum=$sum+4;
                }else if($passComplation>90){
                    $sum=$sum+6;
                }
                $data['player_name'] = $player['player_name'];
                $data['bonus_points'] = $sum;
                $all_data[] = $data;
            }

        }
        return $all_data;
    }

}
