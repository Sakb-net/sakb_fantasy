<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Subeldwry;
use App\Models\Game;
use App\Models\GamePlayerHistory;
use App\Models\GameSubstitutes;

class PointUser extends Model {
    protected $table = 'point_users';
    protected $fillable = [
        'update_by','user_id', 'sub_eldwry_id','player_id', 'points', 'game_players_history_id'    
    ];
// type_id ---> 1 add, 7 sub
    public function user() {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function player() {
        return $this->belongsTo(\App\Models\Player::class, 'player_id');
    }

    public function subeldwry() {
        return $this->belongsTo(\App\Models\Subeldwry::class, 'sub_eldwry_id');
    }

    public function game_player_history() {
        return $this->belongsTo(\App\Models\GamePlayerHistory::class, 'game_players_history_id');
    }

    public static function AddUpdatePointUser($sub_eldwry_id,$detail_game,$val_point){
        $user_id=$detail_game->gameshistory->user_id;
        $data_pointPlayer=static::check_firstChangesubeldwry($user_id,$sub_eldwry_id,$detail_game->id,$detail_game->player_id,0);
        if(isset($data_pointPlayer->id)){
            $input['points']=$val_point;
            $data_pointPlayer->update($input);
            $result=$data_pointPlayer->id;
        }else{
            $result=static::insertPoint($user_id,$sub_eldwry_id,$detail_game->id,$detail_game->player_id,$val_point);
        }
        return true;
    }
        
    public static function insertPoint($user_id,$sub_eldwry_id,$game_players_history_id,$player_id,$points) {
        $input=[
            'user_id'=>$user_id,
            'update_by'=>$user_id,
            'sub_eldwry_id'=>$sub_eldwry_id,
            'game_players_history_id'=>$game_players_history_id,
            'player_id'=>$player_id,
            'points'=>$points
        ];

        $data=static::create($input);
        $result=0;
        if(isset($data['id'])){
            $result=$data['id'];
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

    public static function foundData($colum, $val) {
        $link_found = static::where($colum, $val)->first();
        return $link_found;
    }

    public static function All_foundData($colum, $val) {
        $link_found = static::where($colum, $val)->get();
        return $link_found;
    }

    public static function Sort_Finaltotal_Subeldwry($user_id,$colum='sub_eldwry_id',$val_colum='', $array = 0,$col_pluck='sum_points',$sort=0,$col_order='sum_points',$val_order='DESC') {
        $data = static::select(DB::raw('user_id,sum(points) as sum_points'))->where($colum,$val_colum)->groupBy('user_id')->orderBy($col_order,$val_order);
        if($array ==1){
            $result =$data->pluck($col_pluck,'user_id')->toArray(); //[user_id]=sum_points;
        }else{
            $result =$data->get();
        }
        if($sort==1){
            $result=array_merge($result,[]);
            $result = array_search($user_id, $result);
            $result=$result+1;
        }
        return $result;
    }

    public static function sum_Finaltotal($user_id,$colum,$val_colum, $state = 0,$colum2='',$val_colum2='') {
        if ($state == 1) {
            $sum = static::select(DB::raw('sum(points) as sum_points'))->where('user_id', $user_id)->where($colum,$val_colum)->get();
            $data=(int) $sum[0]->sum_points;
        }elseif ($state == 2) {
            $data = static::where('user_id', $user_id)->where($colum,$val_colum)->where($colum2,$val_colum2)->orderBy('id', 'DESC')->first();
        } else {
            $data = static::where('user_id', $user_id)->where($colum,$val_colum)->orderBy('id', 'DESC')->get();
        }
        return $data;
    }

    public static function CalMath_Finaltotal_User($user_id,$colum,$val_colum) {
        $data = static::select(DB::raw('sum(points) as sum_points,avg(points) as avg_points,count(points) as count_points,max(points) as max_points,min(points) as min_points'))->where('user_id', $user_id)->where($colum,$val_colum)->get();
        $total_sum=(int) $data[0]->sum_points;
        $total_avg=(int) round($data[0]->avg_points,0);
        $total_count=(int) $data[0]->count_points;
        $total_max=(int) $data[0]->max_points;
        return array('total_sum'=>$total_sum,'total_avg'=>$total_avg,'total_count'=>$total_count,'total_max'=>$total_max);
    }

    public static function Sum_Finaltotal_UserGroup($colum,$val_colum,$max_points=0) {
        $data = static::select(DB::raw('user_id,sum(points) as sum_points'))->where($colum,$val_colum)->groupBy('user_id')->orderBy('sum_points','DESC')->get();
        if($max_points==1){
             $result=0;
            if(isset($data[0]->sum_points)){
                $result= (int) $data[0]->sum_points;
            }
        }else{
            $result=$data;
        }
        return $result;
    }

    public static function Sum_TotalUser_Colum($colum,$val_colum) {
        $data = static::select(DB::raw('sum(points) as sum_points'))->where($colum,$val_colum)->get();
        return (int) $data[0]->sum_points;
    }
    public static function Count_TotalUser_Colum($colum,$val_colum) {
        $data = static::select('point_users.user_id')->where($colum,$val_colum)->distinct()->get();
        return count($data);
    }

    public static function Sum_Total_Eldwry($eldwry_id,$colum,$val_colum) {
        $data = static::select(DB::raw('sum(points) as sum_points'))->leftJoin('sub_eldwry', 'sub_eldwry.id', '=', 'point_users.sub_eldwry_id')
        ->where('sub_eldwry.eldwry_id', $eldwry_id)->where($colum,$val_colum)->get();
        
        return (int) $data[0]->sum_points;
    }

    public static function Count_TotalUser_Eldwry($eldwry_id) {
        $data = static::select('point_users.user_id')->leftJoin('sub_eldwry', 'sub_eldwry.id', '=', 'point_users.sub_eldwry_id')
        ->where('sub_eldwry.eldwry_id', $eldwry_id)->distinct()->get();
        return count($data);
    }

    public static function Sort_TotalUser_Eldwry($user_id,$eldwry_id,$array = 0,$col_pluck='sum_points',$sort=0,$col_order='sum_points',$val_order='DESC') {
        $data = static::select(DB::raw('user_id,sum(points) as sum_points'))->leftJoin('sub_eldwry', 'sub_eldwry.id', '=', 'point_users.sub_eldwry_id')
        ->where('sub_eldwry.eldwry_id', $eldwry_id)
        ->groupBy('user_id')->orderBy($col_order,$val_order);
        if($array ==1){
            $result =$data->pluck($col_pluck,'user_id')->toArray(); //[user_id]=sum_points;
        }else{
            $result =$data->get();
        }
        if($sort==1){
            if(count($result)>0){
                $result=array_merge($result,[]);
                if (array_key_exists($user_id, $result)){
                    $result = array_search($user_id, $result);
                    $result=$result+1;
                }else{
                    $result=(int) Game::Count_TotalUser_Eldwry($eldwry_id); //0;
                }
            }else{
                $result=0;
            }
        }
        return $result;
    }

    public static function CalMath_Finaltotal($user_id,$eldwry_id,$sub_eldwry_id=0,$game_id=0) {
        $data['sum_total_points'] =(int) static::Sum_Total_Eldwry($eldwry_id,'user_id',$user_id);
        $data['sort_final_users'] =(int) static::Sort_TotalUser_Eldwry($user_id,$eldwry_id,1,'user_id',1);
        $data['count_total_users'] =(int) Game::Count_TotalUser_Eldwry($eldwry_id); //static::Count_TotalUser_Eldwry
        $data['sum_total_subeldwry'] =(int) static::sum_Finaltotal($user_id,'sub_eldwry_id',$sub_eldwry_id,1);
        $data['count_free_weekgamesubstitute'] =GameSubstitutes::countFreePointSubstitute($user_id,$sub_eldwry_id,$game_id);
        $data['game_week_changes'] =GameSubstitutes::countFinaltotal($user_id,'sub_eldwry_id',$sub_eldwry_id);
        $data['total_changes'] = GameSubstitutes::countTotalUserEldwry($user_id,$eldwry_id);
        return $data;
    }

    public static function Home_Finaltotal($user_id,$colum,$val_colum){
        $data['total_user']=static::Count_TotalUser_Colum($colum,$val_colum);
        $data['total_sum']=static::Sum_TotalUser_Colum($colum,$val_colum);
        $data['total_avg']=0;
        if($data['total_user'] >0){
            $data['total_avg']=(int) round(($data['total_sum']/$data['total_user']),0);
        }
        $data['heigh_point']=static::Sum_Finaltotal_UserGroup($colum,$val_colum,1);
        $data['user_total_mypoint']=static::sum_Finaltotal($user_id,$colum,$val_colum,1);
        return $data;
    }

    public static function check_firstChangesubeldwry($user_id,$sub_eldwry_id,$game_players_history_id,$player_id,$check=1){
        $data=static::where('user_id',$user_id)->where('sub_eldwry_id',$sub_eldwry_id)->where('game_players_history_id',$game_players_history_id)->where('player_id',$player_id)->first();
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

    public static function User_ConditioCountPoint($sub_eldwry_id,$game_player,$val_point,$triple_card_game_history_id=[]){
        $val_add=2;
        if(in_array($game_player->game_history_id, $triple_card_game_history_id)){
            $val_add=3;
        }
        if($game_player->is_active==1 && $game_player->type_coatch==8){
            $val_point=$val_point * $val_add; //coatch
        }elseif ($game_player->is_active==1 && $game_player->type_coatch==9) {//sub_coatch
            //check if found coatch or not
            $checkCoatch=GamePlayerHistory::ChekFoundCoatch($sub_eldwry_id,$game_player->game_history_id,8,1,1);//8=type_coatch for coatch
            if($checkCoatch==-1){
                $val_point=$val_point * $val_add;
            }
        }
        return $val_point;
    }

}
