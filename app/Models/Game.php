<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\GamePlayer;
use DB;

class Game extends Model {

    protected $table = 'games';
    protected $fillable = [
        'update_by', 'eldwry_id', 'user_id', 'team_name','lineup_id','num_cardgold','num_cardgray','bench_card','triple_card', 'is_active'
    ];
//bench_card ----> 0 can use, 1 active, -1 not use
//triple_card ----> 0 can use, 1 active, -1 not use

    public function user() {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function eldwry() {
        return $this->belongsTo(\App\Models\Eldwry::class, 'eldwry_id');
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

    public static function foundData($colum, $val) {
        $link_found = static::where($colum, $val)->first();
        return $link_found;
    }

    public static function All_foundData($colum, $val) {
        $link_found = static::where($colum, $val)->get();
        return $link_found;
    }

    public static function get_GameID($id, $colum, $all = 0) {
        $Game = static::where('id', $id)->first();
        if ($all == 0) {
            return $Game->$colum;
        } else {
            return $Game;
        }
    }

    public static function get_GameRow($id, $colum = 'id', $is_active = 1) {
        $Game = static::where($colum, $id)->where('is_active', $is_active)->first();
        return $Game;
    }

    public static function get_GameCloum($colum = 'id', $val = '', $is_active = 1) {
        $Game = static::where($colum, $val)->where('is_active', $is_active)->orderBy('id', 'DESC')->first();
        return $Game;
    }

    public static function checkregisterDwry($user_id, $eldwry_id, $is_active = 1) {
        $data = static::where('user_id', $user_id)->where('eldwry_id', $eldwry_id)->where('is_active', $is_active)->orderBy('id', 'DESC')->first();
        return $data;
    }

    public static function SearchGame($search, $is_active = '', $limit = 0) {
        $data = static::Where('user_id', 'like', '%' . $search . '%')
                ->orWhere('eldwry_id', 'like', '%' . $search . '%');

        if (!empty($is_active)) {
            $result = $data->where('is_active', $is_active);
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

    public static function Count_TotalUser_Eldwry($eldwry_id) {
        $data = static::select('user_id')->where('eldwry_id', $eldwry_id)->distinct()->get();
        return count($data);
    }

//********************function ************************
    public static function ADD_teaMName($user_id, $eldwry, $team_name = null, $api = 0,$total_num_player=15) {
        $game = Game::checkregisterDwry($user_id, $eldwry->id, 1);
        $data = [];
        $game_id = 0;
        $no_add=0;
        if (isset($game->id) && !empty($team_name)) {
            $game_id = $game->id;
            $all_play_game = GamePlayer::allPlayerGameUser('game_id', $game_id, 'is_active', 1, 'id', 'ASC');
            $count_player_team=count($all_play_game);
            if($count_player_team==$total_num_player){
                $no_add=1;
                $input['team_name'] = $team_name;
                $data_update = $game->update($input);
                $data = $game;
                $result = array('add_team' => 1, 'msg_add' => trans('app.add_scuss'), 'data_add' => $data, 'game_id' => $game_id); // $data['id'];
            }
        }
        if($no_add==0){
            $result = array('add_team' => 0, 'msg_add' => trans('app.add_fail'), 'data_add' => [], 'game_id' => $game_id);
        }
        return $result;
    }

    public static function get_DataGameUser($data, $api = 0) {
        $all_data = [];
        foreach ($data as $key => $val_cat) {
            $all_data[] = static::single_DataGameUser($val_cat, $api);
        }
        return $all_data;
    }

    public static function single_DataGameUser($val_cat, $api = 0) {
        $array_data['link'] = $val_cat->link;
        $array_data['user_id'] = $val_cat->user_id;
        $array_data['eldwry'] = $val_cat->eldwry->name;
        $array_data['created_at'] = $val_cat->created_at->format('Y-m-d');
        return $array_data;
    }

    public static function MyTeam_MasterTransfer($current_id,$players,$lineup,$num_goalkeeper=1,$api=0,$start_index=0) {
        $final_players=$goalkeeper=$defender=$line_team=$attacker=$sub_team=[];
        $num_sub_player=4;
        //**************
        if(count($players)>0){
            $pub_index=$start_index;
            for ($goal=$pub_index; $goal < $num_goalkeeper ; $goal++) { 
                $goalkeeper[]=$players[$goal];
               $pub_index=$goal;
            }
            $final_players[]=$goalkeeper;
            //****************
            $pub_index=$pub_index+1;
            $num_def=$lineup[0]+$pub_index;
            for ($def=$pub_index; $def <$num_def ; $def++) { 
                $defender[]=$players[$def];
                $pub_index=$def;
            }
            $final_players[]=$defender;
            //*********************
            $pub_index=$pub_index+1;
            $num_line=$lineup[1]+$pub_index;
            for ($line=$pub_index; $line <$num_line ; $line++) { 
                $line_team[]=$players[$line];
                $pub_index=$line;
            }
            $final_players[]=$line_team;
            //*************************
            $pub_index=$pub_index+1;
            $num_attack=$lineup[2]+$pub_index;
            for ($attack=$pub_index; $attack <$num_attack ; $attack++) { 
                $attacker[]=$players[$attack];
                $pub_index=$attack;
            }
            $final_players[]=$attacker;
            //***************
            if($start_index==1){
                $pub_index=$pub_index+1;
                $num_sub=$num_sub_player+$pub_index;
                for ($sub=$pub_index; $sub <$num_sub ; $sub++) { 
                    $sub_team[]=$players[$sub];
                    $pub_index=$sub;
                }
            }
            $final_players[]=$sub_team;
        }    
        return $final_players;
    }
    public static function Mobile_lineup($current_lineup,$api=0) {
        $lineup=[];
        foreach ($current_lineup as $key => $value) {
            $array_data['value']=$value;
            $lineup[]=$array_data;
        }
        return $lineup;
    }

    public static function count_Total_Games() {
        $sum = static::select(DB::raw('count(*) as total_games'))->get();
        $data=$sum[0]->total_games;
    return $data;
    }
}
