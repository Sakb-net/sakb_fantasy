<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RankingEldwry extends Model {

    protected $table = 'ranking_eldwry';
    protected $fillable = [
        'update_by','eldwry_id','sub_eldwry_id','team_id', 'link','match_id', 'next_match_id','type','won','draw','loss','goals_own','goals_aganist','goals_diff','points','form','is_active'
    ];


    public function user() {
        return $this->belongsToMany(\App\Models\User::class);
    }
    public function eldwry() {
        return $this->belongsTo(\App\Models\Eldwry::class,'eldwry_id');
    }

    public static function add_Ranking_Eldwry($input,$eldwry_id,$def_lang='ar') {
        $input=RemoveKeyArray($input,'match_id','id');
        $input['eldwry_id']=$eldwry_id;
        $input['team_id']=convertValueToJson($input['sub_eldwry_id'],$def_lang);
        $input['is_active']=checkDataBool($input['status']);
        $input['next_match_id']='/uploads/pics/'.$input['draw'].'.png';
        $data_team = static::get_TeamTWoCondition('eldwry_id', $eldwry_id,'match_id', $input['match_id']);
        if(isset($data_team->id)){
            $input['next_match_id']='/uploads/pics/'.$data_team->id.'.png';
            $data_team->update($input);
        }else{
            $input['link']=get_RandLink();
            static::create($input);
        }
        return true;
    }

    public static function updateColum($colum, $valueColum, $columUpdate, $valueUpdate) {
        return static::where($colum, $valueColum)->update([$columUpdate => $valueUpdate]);
    }

    public static function foundData($colum,$val) {
        $link_found = static::where($colum,$val)->first();
        return $link_found;
    }
    public static function All_foundData($colum,$val) {
        $link_found = static::where($colum,$val)->get();
        return $link_found;
    }
    public static function selectRandamTeam($colum,$val,$limit=0) {
        $data = static::where($colum,$val)->inRandomOrder();
        if($limit>0){
            $result=$data->limit($limit);
        }
        $result=$data->get();
        return $result;
    }
    public static function get_OneCondition($name_col,$val_col, $colum='',$order_col='id') {
        $Team = static::where($name_col,$val_col)->where('is_active', $is_active)->orderBy($order_col, 'DESC')->first();
        if (!empty($colum)) {
            return $Team->$colum;
        } else {
            return $Team;
        }
    }

    public static function get_TWoCondition($colum, $val,$colum2, $val2) {
        $Team = static::where($colum, $val)->where($colum2, $val2)->first();
        return $Team;
    }

    public static function getAll_data($is_active = 1,$order='DESC') {
        $Team = static::where('is_active', $is_active)->orderBy('id',$order)->get();
        return $Team;
    }

    public static function SearchRankingEldwry($search, $is_active = '', $limit = 0) {
        $data = static::Where('sub_eldwry_id', 'like', '%' . $search . '%')
                ->orWhere('next_match_id', 'like', '%' . $search . '%')
                ->orWhere('link', 'like', '%' . $search . '%');

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

}
