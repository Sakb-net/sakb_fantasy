<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Subeldwry;
use App\Models\GameHistory;
use App\Models\Game;

class GameSubstitutes extends Model {
    protected $table = 'game_substitutes';
    protected $fillable = [
        'update_by','user_id','game_id','sub_eldwry_id','code_substitute','player_id', 'player_substitute_id','type_id','cost', 'points','card_type_id','is_active'  
    ];
// type_id ---> 3 start, 7 sub
//card_type_id --> 12 cardgold , 13 cardgray    
    public function user() {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function subeldwry() {
        return $this->belongsTo(\App\Models\Subeldwry::class, 'sub_eldwry_id');
    }

    public function game() {
        return $this->belongsTo(\App\Models\Game::class, 'game_id');
    }

    public function player() {
        return $this->belongsTo(\App\Models\Player::class, 'player_id');
    }

    public function player_substitute() {
        return $this->belongsTo(\App\Models\Player::class, 'player_substitute_id');
    }

    public function all_type() {
        return $this->belongsTo(\App\Models\AllType::class, 'type_id');
    }

    public function cardtypes() {
        return $this->belongsTo(\App\Models\AllType::class, 'card_type_id');
    }
    public static function insertSubstitute($user_id,$update_by,$sub_eldwry_id,$game_id,$player_id,$player_substitute_id,$type_id,$points,$cost,$is_active=1,$code_substitute=null) {
        $input=[
            'user_id'=>$user_id,
            'update_by'=>$update_by,
            'game_id'=>$game_id,
            'sub_eldwry_id'=>$sub_eldwry_id,
            'code_substitute'=>$code_substitute,
            'player_id'=>$player_id,
            'player_substitute_id'=>$player_substitute_id,
            'type_id'=>$type_id,
            'points'=>$points,
            'cost'=>$cost,
            'is_active'=>$is_active
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

    public static function updateOrderColumCondTwo($colum, $valueColum,$colum2, $valueColum2, $columUpdate, $valueUpdate) {
        return static::where($colum, $valueColum)->where($colum2, $valueColum2)->update([$columUpdate => $valueUpdate]);
    }

    public static function updateOrderColumTwo($colum, $valueColum,$colum2, $valueColum2, $columUpdate, $valueUpdate, $columUpdate2, $valueUpdate2) {
        return static::where($colum, $valueColum)->where($colum2, $valueColum2)->update([$columUpdate => $valueUpdate,$columUpdate2 => $valueUpdate2]);
    }
    
    public static function updateOrderColumThree($colum, $valueColum,$colum2, $valueColum2, $columUpdate, $valueUpdate, $columUpdate2, $valueUpdate2, $columUpdate3, $valueUpdate3) {
        return static::where($colum, $valueColum)->where($colum2, $valueColum2)->update([$columUpdate => $valueUpdate,$columUpdate2 => $valueUpdate2,$columUpdate3 => $valueUpdate3]);
    }

    public static function DeleteUserActive($user_id,$is_active=0) {
        return static::where('user_id', $user_id)->where('is_active', $is_active)->delete();
    }

    public static function DeleteColum($colum, $val) {
        return static::where($colum, $val)->delete();
    }
    public static function GetUserLastPlayer($user_id,$player_id,$is_active=0) {
        return static::where('user_id', $user_id)->where('player_id', $player_id)->where('is_active', $is_active)->orderBy('id','DESC')->first();
    }

    public static function Usersub_eldwry($user_id,$sub_eldwry_id,$is_active=0) {
        return static::where('user_id', $user_id)->where('sub_eldwry_id',$sub_eldwry_id)->where('is_active', $is_active)->first();
    }

    public static function check_substituteFoundBefor($player_id,$player_substitute_id,$is_active=0) {
        return static::where('player_id', $player_id)->where('player_substitute_id',$player_substitute_id)->where('is_active', $is_active)->first();
    }

    public static function foundData($colum, $val) {
        $link_found = static::where($colum, $val)->first();
        return $link_found;
    }

    public static function All_foundData($colum, $val) {
        $link_found = static::where($colum, $val)->get();
        return $link_found;
    }

    public static function GetUserActive($user_id,$is_active=0) {
        return static::where('user_id', $user_id)->where('is_active', $is_active)->get();
    }

    public static function check_firstChangesubeldwry($user_id,$sub_eldwry_id,$game_id,$type_id=3,$check=1){
        $data=static::where('user_id',$user_id)->where('sub_eldwry_id',$sub_eldwry_id)->where('game_id',$game_id)->where('type_id',$type_id)->first();
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

    public static function CheckFoundSubeldwry($user_id,$game_id,$sub_eldwry_id,$sub_eldwry_op='=',$type_id=3,$limit_freePoint=1,$col_order='id',$val_order='DESC'){
        $data=static::where('user_id',$user_id)->where('sub_eldwry_id',$sub_eldwry_op,$sub_eldwry_id)->where('game_id',$game_id)->where('type_id',$type_id)->orderBy($col_order,$val_order)->limit($limit_freePoint)->get();
        return $data;
    }

    public static function CheckSubstitutePoints($user_id,$change_subeldwry,$game,$player_id=null,$player_substitute_id=null,$limit_freePoint=1,$cost=0,$is_active=1){
        if(empty($change_subeldwry)){
            $change_subeldwry=Subeldwry::get_CurrentSubDwry();
        }
        $check_first_change = static::CheckFoundFreeChangeCurrentSubeldwryAndBefor($user_id,$change_subeldwry->id,$game->id,3,$limit_freePoint,'id','DESC');

        $array_type_point=DefautValPointsSubstitute($check_first_change,$change_subeldwry->change_point);
        //get code_substitute
        $last_substitute=static::Usersub_eldwry($user_id,$change_subeldwry->id,0);
        $code_substitute = generateRandomValue();
        if(isset($last_substitute->id)){
            $code_substitute = $last_substitute->code_substitute;
        }
        //add point
       $add_data=static::insertSubstitute($user_id,$user_id,$change_subeldwry->id,$game->id,$player_id,$player_substitute_id,$array_type_point['type_id'],$array_type_point['points'],$cost,$is_active,$code_substitute);
        return array('add_data'=>$add_data,'points'=>$array_type_point['points']);
    }

    public static function DataSubstitutesSubeldwry($user_id,$sub_eldwry_id,$colum='',$val_colum='',$is_active=1,$colum_1='card_type_id',$colum_2='code_substitute',$limit=0) {
        $data = static::where('user_id', $user_id)->where('sub_eldwry_id', $sub_eldwry_id)->where('is_active',$is_active);
        if(!empty($colum)){
            $result = $data->where($colum,$val_colum);
        }
        if ($limit == -1) {
            $result = $data->pluck($colum_1,$colum_2)->toArray();
        }elseif ($limit == -2) {
            $result = $data->count();
        } else {
            $result = $data->get();
        }
        return $result;
    }
    public static function count_DataColum($user_id,$colum,$val_colum,$colum_count='card_type_id') {
        $data = static::select(DB::raw('count('.$colum_count.') as data_count'))->where('user_id', $user_id)->where($colum,$val_colum)->get();
        return (int) $data[0]->data_count;
    }

    public static function sum_costtotal($user_id,$colum,$val_colum) {
        $sum = static::select(DB::raw('sum(cost) as sum_cost'))->where('user_id', $user_id)->where($colum,$val_colum)->get();
        return (int) $sum[0]->sum_cost;
    }

    public static function sum_Finaltotal($user_id,$colum,$val_colum) {
        $sum = static::select(DB::raw('sum(points) as sum_points'))->where('user_id', $user_id)->where($colum,$val_colum)->get();
        return (int) $sum[0]->sum_points;
    }


    public static function sum_count_FinalPoints($user_id,$colum,$val_colum) {
        return static::select(DB::raw('sum(points) as sum_points,count(id) as count_transfer'))->where('user_id', $user_id)->where($colum,$val_colum)->get();
    }

    public static function sumTotalUserEldwry($user_id,$eldwry_id) {
        $sum = static::select(DB::raw('sum(points) as sum_points'))->leftJoin('sub_eldwry', 'sub_eldwry.id', '=', 'game_substitutes.sub_eldwry_id')
        ->where('sub_eldwry.eldwry_id', $eldwry_id)
        ->where('user_id', $user_id)->get();
        return (int) $sum[0]->sum_points;
    }
    public static function CheckFoundFreeChangeCurrentSubeldwryAndBefor($user_id,$sub_eldwry_id,$game_id,$type_id=3,$limit_freePoint=1,$col_order='id',$val_order='DESC'){
        $num_free_transfer=2;
        $check_found_befor=GameHistory::check_foundData('game_id', $game_id);
        if(!$check_found_befor){
            $num_free_transfer=1;
        } 
        $data=static::CheckFoundSubeldwry($user_id,$game_id,$sub_eldwry_id,'=',$type_id,$num_free_transfer,$col_order,$val_order);
        $result=0; //free
        if(count($data)==1){
            if($num_free_transfer==1){
                $result=1;
            }else{
                $data=static::CheckFoundSubeldwry($user_id,$game_id,$sub_eldwry_id,'<',$type_id,$limit_freePoint,$col_order,$val_order);
                if(count($data)==1){
                    $result=1;
                }   
            }
        }elseif(count($data)==2){
            $result=1;
        } 
        return $result;
    }
    public static function countFreePointSubstitute($user_id,$sub_eldwry_id,$game_id,$type_id=3,$limit_freePoint=1,$col_order='id',$val_order='DESC') {
        $num_free_transfer=2;
        $check_found_befor=GameHistory::check_foundData('game_id', $game_id);
        if(!$check_found_befor){
            $num_free_transfer=1;
        }
        $data_one=static::CheckFoundSubeldwry($user_id,$game_id,$sub_eldwry_id,'=',$type_id,2,$col_order,$val_order);

        $data_two=static::CheckFoundSubeldwry($user_id,$game_id,$sub_eldwry_id,'<',$type_id,$limit_freePoint,$col_order,$val_order);

        $data_return=$num_free_transfer-count($data_one)+count($data_two);
        if($data_return < 0 || $data_return > 2){
            $data_return = 0;
        }
        return $data_return;
    }

    public static function countFinaltotal($user_id,$colum,$val_colum) {
        $data = static::select('id')->where('user_id', $user_id)->where($colum,$val_colum)->get();
        return count($data);
    }

    public static function countTotalUserEldwry($user_id,$eldwry_id) {
        $data = static::select('game_substitutes.id')->leftJoin('sub_eldwry', 'sub_eldwry.id', '=', 'game_substitutes.sub_eldwry_id')
        ->where('sub_eldwry.eldwry_id', $eldwry_id)
        ->where('user_id', $user_id)->get();
        return count($data);
    }

}
