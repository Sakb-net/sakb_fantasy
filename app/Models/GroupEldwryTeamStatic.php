<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PointUser;
use DB;
class GroupEldwryTeamStatic extends Model {

    protected $table = 'group_eldwry_teamstatics';
    protected $fillable = [
        'group_eldwry_id','game_id','sub_eldwry_id','sort','points'
    ];

    public function subeldwry() {
        return $this->belongsTo(\App\Models\Subeldwry::class,'sub_eldwry_id');
    }

    public function game() {
        return $this->belongsTo(\App\Models\Game::class,'game_id');
    }

    public function group_eldwry() {
        return $this->belongsTo(\App\Models\GroupEldwry::class,'group_eldwry_id');
    }
    public static function InsertGroupStatic($group_eldwry_id,$game_id,$user_id,$sub_eldwry_id,$arraypoint_user,$sort=1){
        $data=static::foundDataThreeCondition('group_eldwry_id', $group_eldwry_id,'sub_eldwry_id', $sub_eldwry_id,'game_id',$game_id);
        if(!isset($data->id)){
            if(isset($arraypoint_user[$user_id])){
                $points=$arraypoint_user[$user_id];
            }else{
                $points=PointUser::sum_Finaltotal($user_id,'sub_eldwry_id',$sub_eldwry_id,1);
            }
            $input=[
                'group_eldwry_id'=>$group_eldwry_id,
                'game_id'=>$game_id,
                'sub_eldwry_id'=>$sub_eldwry_id,
                'sort'=>$sort,
                'points'=>$points
            ];
            $data=static::create($input);
        }else{
            $points=$data->points;
        }
        return $points;
    }


    public static function DeleteGroupStatic($group_eldwry_id,$game_id,$user_id,$sub_eldwry_id){
        return static::where('group_eldwry_id', $group_eldwry_id)->where('game_id',$game_id)->where('user_id',$user_id)->where('sub_eldwry_id',$sub_eldwry_id)->delete();
    }

    public static function foundData($colum, $val,$col_order='id',$val_order='asc') {
        return static::where($colum, $val)->orderBy($col_order,$val_order)->first();
    }

    public static function foundDataTwoCondition($colum, $val,$colum2, $val2,$col_order='id',$val_order='DESC') {
        return static::where($colum, $val)->where($colum2, $val2)->orderBy($col_order,$val_order)->first();
    }
    
    public static function foundDataThreeCondition($colum, $val,$colum2, $val2,$colum3, $val3,$col_order='id',$val_order='DESC') {
        return static::where($colum, $val)->where($colum2, $val2)->where($colum3, $val3)->orderBy($col_order,$val_order)->first();
    }
    
    public static function All_foundData($colum, $val,$col_order='id',$val_order='DESC') {
       return static::where($colum, $val)->orderBy($col_order,$val_order)->get();
    }

    public static function All_foundDataTwoCondition($colum, $val,$colum2, $val2,$col_order='id',$val_order='DESC') {
        return static::where($colum, $val)->where($colum2, $val2)->orderBy($col_order,$val_order)->get();
    }
    
    public static function Sort_Finaltotal_group($colum='sub_eldwry_id',$val_colum='', $array = 0,$col_pluck='sum_points',$col_order='sum_points',$val_order='DESC') {
        $data = static::select(DB::raw('group_eldwry_id,sum(points) as sum_points'))->where($colum,$val_colum)->groupBy('group_eldwry_id')->orderBy($col_order,$val_order);
        if($array ==1){
            $result =$data->pluck($col_pluck,'group_eldwry_id')->toArray(); 
        }else{
            $result =$data->get();
        }
        return $result;
    }

    public static function Finaltotal_subeldwry($sub_eldwry_id,$group_eldwry_id,$game_id, $sum = 1) {
        $result = static::select(DB::raw('group_eldwry_id,sum(points) as sum_points'))->where('sub_eldwry_id','<=',$sub_eldwry_id)->where('group_eldwry_id',$group_eldwry_id)->where('game_id',$game_id)->groupBy('group_eldwry_id')->get();
        if($sum ==1){
            return (int) $result[0]->sum_points;
        }else{
            return $result;
        }
    }
}
