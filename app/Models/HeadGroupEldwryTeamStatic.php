<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\HeadGroupEldwry;
use App\Models\HeadGroupEldwryMatch;
use App\Models\PointUser;
use DB;
class HeadGroupEldwryTeamStatic extends Model {

    protected $table = 'head_group_eldwry_teamstatics';
    protected $fillable = [
        'head_group_eldwry_id','sub_eldwry_id','user_id','points','bouns','is_active'
    ];

    public function user() {
        return $this->belongsTo(\App\Models\User::class,'user_id');
    }
    public function group_eldwry() {
        return $this->belongsTo(\App\Models\HeadGroupEldwry::class,'head_group_eldwry_id');
    }
    public function subeldwry() {
        return $this->belongsTo(\App\Models\Subeldwry::class,'sub_eldwry_id');
    }

    public static function InsertGroupTeamStatic($head_group_eldwry_id,$sub_eldwry_id,$user_id,$points=0,$bouns=0){
        $data=static::foundDataThreeCondition('head_group_eldwry_id', $head_group_eldwry_id,'sub_eldwry_id', $sub_eldwry_id,'user_id', $user_id);
        $input=[
                'head_group_eldwry_id'=>$head_group_eldwry_id,
                'sub_eldwry_id'=>$sub_eldwry_id,
                'user_id'=>$user_id,
                'points'=>$points,
                'bouns'=>$bouns,
                'is_active'=>1
            ];
        if(!isset($data->id)){
            $data=static::create($input);
        }else{
            // if(isset($arraypoint_user[$user_id])){
            //     $points=$arraypoint_user[$user_id];
            // }else{
                $points=PointUser::sum_Finaltotal($user_id,'sub_eldwry_id',$sub_eldwry_id,1);
            // }
            $input['points']=$points;
            $data->update($input);
        }
        return $data;
    }

    public static function InsertPointGroupTeamStatic($head_group_eldwry_id,$sub_eldwry_id) {
        $data=static::All_foundDataTwoCondition('head_group_eldwry_id', $head_group_eldwry_id,'sub_eldwry_id', $sub_eldwry_id);
        $array_user_point=PointUser::Sort_Finaltotal_Subeldwry(0,'sub_eldwry_id',$sub_eldwry_id,1,'sum_points');
        foreach ($data as $key => $value) {
            if(isset($array_user_point[$value->user_id])){
                $value->update(['points'=>$array_user_point[$value->user_id]]);
            }
        }
        return true;
    }
    
    public static function updateGroupteamStatic($colum, $val,$colum2, $val2,$col_update='bouns',$val_update=0) {
       return static::where($colum, $val)->where($colum2, $val2)->update([$col_update=>$val_update]);
    }

    public static function deleteGroupteamStatic($head_group_eldwry_id,$sub_eldwry_id) {
        return static::where('head_group_eldwry_id', $head_group_eldwry_id)->where('sub_eldwry_id',$sub_eldwry_id)->delete();
    }

    public static function deleteGroupteamStaticUser($head_group_eldwry_id,$sub_eldwry_id,$user_id) {
        return static::where('head_group_eldwry_id', $head_group_eldwry_id)->where('sub_eldwry_id',$sub_eldwry_id)->where('user_id',$user_id)->delete();
    }

    public static function leaveTeamMatchStaticUser($head_group_eldwry,$current_subeldwry,$user_id) {
        $team_static = static::foundDataThreeCondition('head_group_eldwry_id', $head_group_eldwry->id,'sub_eldwry_id', $current_subeldwry->id,'user_id', $user_id);
        if(isset($team_static->id)){
            $match_static = HeadGroupEldwryMatch::emptyGroupMatchUser($head_group_eldwry->id,$current_subeldwry->id,$team_static->id);
            $team_static->delete();
        }
        return true;
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
    
    public static function getGroupPrevSubeldwry($head_group_eldwry_id,$sub_eldwry_id,$col_order='id',$val_order='DESC') {
       return static::where('head_group_eldwry_id', $head_group_eldwry_id)->where('sub_eldwry_id','<', $sub_eldwry_id)->orderBy($col_order,$val_order)->first();
    }

    public static function Finaltotal_subeldwry($sub_eldwry_id,$head_group_eldwry_id,$user_id, $sum = 1,$cloum='') {
        $result = static::select(DB::raw('head_group_eldwry_id,sum(points) as sum_points,sum(bouns) as sum_bouns'))->where('sub_eldwry_id','<=',$sub_eldwry_id)->where('head_group_eldwry_id',$head_group_eldwry_id)->where('user_id',$user_id)->groupBy('head_group_eldwry_id')->get();
        if(!empty($cloum)){ //sum_bouns or sum_points
            return (int) $result[0]->$cloum;
        }elseif($sum ==1){
            return (int) $result[0]->sum_points + $result[0]->sum_bouns;
        }else{
            return $result;
        }
    }

    public static function Sort_Finaltotal_group($colum='sub_eldwry_id',$val_colum='', $array = 0,$col_pluck='head_group_eldwry_id',$col_order='head_group_eldwry_id',$val_order='DESC') {
        $data = static::select(DB::raw('head_group_eldwry_id,sum(points) as sum_points,sum(bouns) as sum_bouns'))->where($colum,$val_colum)->groupBy('head_group_eldwry_id')->orderBy($col_order,$val_order);
        if($array ==1){
            $result =$data->pluck($col_pluck,'head_group_eldwry_id')->toArray(); 
        }else{
            $result =$data->get();
        }
        return $result;
    }


}
