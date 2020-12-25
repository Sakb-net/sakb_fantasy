<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PointUser;
use App\Models\HeadGroupEldwry;
use App\Models\HeadGroupEldwryTeamStatic;
use DB;
class HeadGroupEldwryMatch extends Model {

    protected $table = 'head_group_eldwry_matches';
    protected $fillable = [
        'head_group_eldwry_id','sub_eldwry_id','first_team_match_id','second_team_match_id','sort','is_active'
    ];

    public function group_eldwry() {
        return $this->belongsTo(\App\Models\HeadGroupEldwry::class,'head_group_eldwry_id');
    }
    public function subeldwry() {
        return $this->belongsTo(\App\Models\Subeldwry::class,'sub_eldwry_id');
    }

    public function first_team() {
        return $this->belongsTo(\App\Models\HeadGroupEldwryTeamStatic::class,'first_team_match_id');
    }

    public function second_team() {
        return $this->belongsTo(\App\Models\HeadGroupEldwryTeamStatic::class,'second_team_match_id');
    }

    public static function InsertGroupMatch($head_group_eldwry_id,$sub_eldwry_id,$team_match_id,$sort=1){
        $data=static::EmptyTeamMatchIdUser($head_group_eldwry_id, $sub_eldwry_id,$team_match_id);
        if(!isset($data->id)){
            $data=static::EmptyTeamMatchId($head_group_eldwry_id, $sub_eldwry_id);
            $input=[
                    'head_group_eldwry_id'=>$head_group_eldwry_id,
                    'sub_eldwry_id'=>$sub_eldwry_id,
                    'sort'=>$sort,
                    'is_active'=>1
                ];
            if(!isset($data->id)){
                $input['first_team_match_id']=$team_match_id;
                $input['second_team_match_id']= null;
                $input['sort'] +=static::getLastRow('sort');
                $data=static::create($input);
            }else{
               if(empty($data->first_team_match_id)){
                    $input['first_team_match_id']=$team_match_id;
               }else{
                    $input['second_team_match_id']=$team_match_id;
               }
                $data->update($input);
            }
        }
        return $data;
    }

    public static function deleteGroupMatch($head_group_eldwry_id,$sub_eldwry_id) {
        return static::where('head_group_eldwry_id', $head_group_eldwry_id)->where('sub_eldwry_id',$sub_eldwry_id)->delete();
    }

    public static function emptyGroupMatchUser($head_group_eldwry_id,$sub_eldwry_id,$team_id) {
        $match=static::EmptyTeamMatchIdUser($head_group_eldwry_id, $sub_eldwry_id,$team_id);
        //'first_team_match_id','second_team_match_id'
        if($match->first_team_match_id == $team_id  && !empty($match->second_team_match_id)){
            $match->update(['first_team_match_id'=>null]);
        }elseif($match->second_team_match_id == $team_id  && !empty($match->first_team_match_id)){
            $match->update(['second_team_match_id'=>null]);
        }else{
            $match->delete();
        }

    }
    
    public static function getLastRow($colum='',$col_order='id',$val_order='asc') {
        $data=static::orderBy($col_order,$val_order)->first();
        if(!empty($colum)){
            $result=0;
            if(isset($data->$colum)){
                $result=$data->$colum;
            }
        }else{
            $result=$data;
        }
        return $result;
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

    public static function All_foundDataTwoCondition($colum, $val,$colum2, $val2,$col_order='id',$val_order='DESC') {
        return static::where($colum, $val)->where($colum2, $val2)->orderBy($col_order,$val_order)->get();
    }
    
    public static function EmptyTeamMatchId($head_group_eldwry_id, $sub_eldwry_id,$col_order='id',$val_order='DESC') {
        return static::where('head_group_eldwry_id', $head_group_eldwry_id)->where('sub_eldwry_id', $sub_eldwry_id)
                ->Where(function ($query) {
                    $query->where('first_team_match_id',null)
                          ->orwhere('second_team_match_id',null);
                })->orderBy($col_order,$val_order)->first();
    }

    public static function EmptyTeamMatchIdUser($head_group_eldwry_id, $sub_eldwry_id,$team_match_id,$col_order='id',$val_order='DESC') {
        return static::where('head_group_eldwry_id', $head_group_eldwry_id)->where('sub_eldwry_id', $sub_eldwry_id)
                ->Where(function ($query) use($team_match_id) {
                    $query->where('first_team_match_id',$team_match_id)
                          ->orwhere('second_team_match_id',$team_match_id);
                })->orderBy($col_order,$val_order)->first();
    }
    
    public static function All_foundData($colum, $val,$col_order='id',$val_order='DESC') {
       return static::where($colum, $val)->orderBy($col_order,$val_order)->get();
    }
    
    public static function getGroupPrevSubeldwry($head_group_eldwry_id,$sub_eldwry_id,$col_order='id',$val_order='DESC') {
       return static::where('head_group_eldwry_id', $head_group_eldwry_id)->where('sub_eldwry_id','<', $sub_eldwry_id)->orderBy($col_order,$val_order)->first();
    }

}
