<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class RankingEldwry extends Model {

    protected $table = 'ranking_eldwry';
    protected $fillable = [
        'update_by','eldwry_id','sub_eldwry_id','team_id', 'link','match_id', 'next_match_id','type','won','draw','loss','goals_own','goals_aganist','goals_diff','points','form','is_active'
    ];


    public function user() {
        return $this->belongsToMany(\App\Models\User::class,'update_by');
    }
    public function eldwry() {
        return $this->belongsTo(\App\Models\Eldwry::class,'eldwry_id');
    }
    public function sub_eldwry() {
        return $this->belongsTo(\App\Models\Subeldwry::class,'sub_eldwry_id');
    }
    public function team() {
        return $this->belongsTo(\App\Models\Team::class,'team_id');
    }
    public function match() {
        return $this->belongsTo(\App\Models\Match::class,'match_id');
    }
    public function next_match() {
        return $this->belongsTo(\App\Models\Match::class,'next_match_id');
    }

    public static function add_Ranking_Eldwry($input) {
        $data_data = static::get_TWoCondition('team_id', $input['team_id'],'match_id', $input['match_id']);
        if(isset($data_data->id)){
            $data_data->update($input);
        }else{
            $input['link']=get_RandLink();
            static::create($input);
        }
        return true;
    }

    public static function updateColum($colum, $valueColum, $columUpdate, $valueUpdate) {
        return static::where($colum, $valueColum)->update([$columUpdate => $valueUpdate]);
    }
    public static function get_last_row($is_active = 1,$order='DESC') {
        $data = static::where('is_active', $is_active)->orderBy('id',$order)->first();
        return $data;
    }

    public static function get_OneCondition($name_col,$val_col,$is_active=1,$colum='',$order_col='id') {
        $data = static::where($name_col,$val_col)->where('is_active', $is_active)->orderBy($order_col, 'DESC')->first();
        if (!empty($colum)) {
            return $data->$colum;
        } else {
            return $data;
        }
    }

    public static function get_TWoCondition($colum, $val,$colum2, $val2) {
        $data = static::where($colum, $val)->where($colum2, $val2)->first();
        return $data;
    }

    public static function getAll_data($colum, $val,$is_active = 1,$get_array=0,$order='DESC') {
        $data = static::where($colum, $val)->where('is_active', $is_active);
        if($get_array==1){
        	$result=$data->orderBy('match_id',$order)->distinct()->get(['match_id','sub_eldwry_id']);
        }else{
        	$result=$data->orderBy('id',$order)->get();
        }
        return $result;
    }

    public static function get_Last_RankingEldwry($eldwry_id,$team_id,$sub_eldwr_id=-1, $limit = 0,$is_active =1,$order='ASC') {
        $data = static::Where('eldwry_id',$eldwry_id)->Where('team_id',$team_id)->Where('is_active',$is_active);
        if($sub_eldwr_id > 0){
            $result=$data->where('sub_eldwr_id','<=',$sub_eldwr_id);
        }
        if($limit>0){
            $result=$data->limit($limit);
        }
        $result=$data->orderBy('id',$order)->get();
        return $result;
    }

    public static function sum_SubldwryID($eldwry_id='',$sub_eldwry_id='',$val_order='DESC') {
        return static::select(DB::raw('eldwry_id,sub_eldwry_id,team_id,sum(won) as sum_won,sum(draw) as sum_draw,sum(loss) as sum_loss,sum(goals_diff) as sum_goals_diff,sum(points) as sum_points'))->where('eldwry_id',$eldwry_id)->where('sub_eldwry_id',$sub_eldwry_id)->groupBy('team_id','eldwry_id','sub_eldwry_id')->orderBy('sum_points',$val_order)->orderBy('sum_goals_diff',$val_order)->get();
    }

    public static function sum_all_before_and_SubldwryTeam($eldwry_id='',$sub_eldwry_id='',$team_id=-1,$val_order='DESC') {
        $data= static::select(DB::raw('team_id,sum(goals_own) as sum_goals_own,sum(goals_aganist) as sum_goals_aganist,sum(goals_diff) as sum_goals_diff,sum(points) as sum_points'))->where('eldwry_id',$eldwry_id)->where('sub_eldwry_id','<=',$sub_eldwry_id);
        if($team_id > 0){
            $result=$data->where('team_id',$team_id);
        }
        $result=$data->groupBy('team_id')->orderBy('sum_points',$val_order)->orderBy('sum_goals_diff',$val_order)->get();
        return $result;
    }
}
