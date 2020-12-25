<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Subeldwry;

class PointPlayer extends Model {
    protected $table = 'point_players';
    protected $fillable = [
        'update_by','player_id', 'sub_eldwry_id', 'point_id', 'points', 'match_id','number','detail_playermatches_id'    
    ];

    public function player() {
        return $this->belongsTo(\App\Models\Player::class, 'player_id');
    }

    public function point() {
        return $this->belongsTo(\App\Models\Point::class, 'point_id');
    }

    public function subeldwry() {
        return $this->belongsTo(\App\Models\Subeldwry::class, 'sub_eldwry_id');
    }

    public function match() {
        return $this->belongsTo(\App\Models\Match::class, 'match_id');
    }

    public static function AddUpdatePointPlayer($detail_player,$data_point,$val_point,$number){
        $sub_eldwry_id=$detail_player->match->sub_eldwry_id;
        $data_pointPlayer=static::check_foundPoint($detail_player->player_id,$sub_eldwry_id,$detail_player->match_id,$data_point->id,0);
        if(isset($data_pointPlayer->id)){
            $input['points']=$val_point;
            $input['number']=$number;
            // $input['detail_playermatches_id']=$detail_player->id;
            $data_pointPlayer->update($input);
            $result=$data_pointPlayer->id;
        }else{
            $result=static::insertPoint($detail_player->id,$sub_eldwry_id,$detail_player->match_id,$detail_player->player_id,$data_point->id,$val_point,$number);
        }
        return $result;
    }
        

    public static function AddUpdateBounsPointPlayer($sub_eldwry_id,$player_id,$match_id,$point_id,$val_point,$number){
        $data_pointPlayer=static::check_foundPoint($player_id,$sub_eldwry_id,$match_id,$point_id,0);
        if(isset($data_pointPlayer->id)){
            $input['points']=$val_point;
            $input['number']=$number;
            $data_pointPlayer->update($input);
            $result=$data_pointPlayer->id;
        }else{
            $result=static::insertPoint(null,$sub_eldwry_id,$match_id,$player_id,$point_id,$val_point,$number);
        }
        return $result;
    }
        
    public static function insertPoint($detail_playermatches_id,$sub_eldwry_id,$match_id,$player_id,$point_id,$points,$number,$update_by=null) {
        $input=[
            'player_id'=>$player_id,
            'update_by'=>$update_by,
            'sub_eldwry_id'=>$sub_eldwry_id,
            'detail_playermatches_id'=>$detail_playermatches_id,
            'match_id'=>$match_id,
            'point_id'=>$point_id,
            'number'=>$number,
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
        $data = static::where($colum, $val)->first();
        return $data;
    }

    public static function All_foundData($colum, $val) {
        $data = static::where($colum, $val)->get();
        return $link_found;
    }
    public static function All_foundDataTwoColum($colum, $val,$colum2, $val2) {
        $data = static::where($colum, $val)->where($colum2, $val2)->get();
        return $data;
    }
    public static function sum_Finaltotal($colum='sub_eldwry_id',$val_colum, $array = 0) {
        $data = static::select(DB::raw('player_id,sum(points) as sum_points'))->where($colum,$val_colum)->groupBy('player_id');
        if($array ==1){
            $result =$data->pluck('sum_points','player_id')->toArray(); //[player_id]=sum_points;
        }else{
            $result =$data->get();
        }
        return $result;
    }
    public static function sum_Finaltotal_AllPlayers($array = 0) {
        $data = static::select(DB::raw('player_id,sum(points) as sum_points'))->groupBy('player_id');
        if($array ==1){
            $result =$data->pluck('sum_points','player_id')->toArray(); //[player_id]=sum_points;
        }else{
            $result =$data->get();
        }
        return $result;
    }

    public static function sum_Finaltotal_SinglePlayer($player_id) {
            $sum = static::select(DB::raw('sum(points) as sum_points'))->where('player_id', $player_id)->get();
            $data=$sum[0]->sum_points;
        return $data;
    }
    public static function sum_Finaltotal_SinglePlayerAndMatch($player_id, $match_id) {
        $sum = static::select(DB::raw('sum(points) as sum_points'))->where('player_id', $player_id)->where('match_id', $match_id)->get();
        $data=$sum[0]->sum_points;
    return $data;
}
    public static function sum_Finaltotal_SinglePlayerAndSubEldwry($player_id, $sub_eldwry_id) {
        $sum = static::select(DB::raw('sum(points) as sum_points'))->where('player_id', $player_id)->where('sub_eldwry_id',$sub_eldwry_id)->get();
        $data=$sum[0]->sum_points;
    return $data;
    }
    public static function sum_Finaltotal_player($player_id,$colum,$val_colum, $sum = 0) {
        if ($sum == 1) {
            $sum = static::select(DB::raw('sum(points) as sum_points'))->where('player_id', $player_id)->where($colum,$val_colum)->get();
            $data=(int) $sum[0]->sum_points;
        } else {
            $data = static::where('player_id', $player_id)->where($colum,$val_colum)->orderBy('id', 'DESC')->get();
        }
        return $data;
    }
    public static function check_foundPoint($player_id,$sub_eldwry_id,$match_id,$point_id,$check=0){
        $data=static::where('player_id',$player_id)->where('sub_eldwry_id',$sub_eldwry_id)->where('match_id',$match_id)->where('point_id',$point_id)->first();
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

    public static function getDataPlayer_subeldwry($sub_eldwry_id ,$player_id){
        $data=static::where('sub_eldwry_id',$sub_eldwry_id)->where('player_id',$player_id)->get();
        return $data;
    }

    public static function getDataPlayerPointMatch($sub_eldwry_id,$match_id,$array_player_id,$point_id=55){
        $data=static::where('match_id',$match_id)->where('sub_eldwry_id',$sub_eldwry_id)->where('point_id',$point_id)->whereIn('player_id',$array_player_id)->get();
        return $data;
    }
//********************function ************************
    public static function get_DataPointPlayerUser($data, $api = 0) {
        $all_data = [];
        foreach ($data as $key => $val_cat) {
            $all_data[] = static::single_DataPointPlayerUser($val_cat, $api);
        }
        return $all_data;
    }

    public static function single_DataPointPlayerUser($val_cat, $api = 0,$lang='en') {
        $array_data['player_name'] = finalValueByLang($val_cat->player->lang_name,$val_cat->player->name,$lang);
        $array_data['sub_eldwry_link'] = $val_cat->subeldwry->link;
        $array_data['match_name'] =  finalValueByLang($val_cat->match->lang_name,$val_cat->match->name,$lang);
        $array_data['teams_first'] = $val_cat->match->teams_first->code;
        $array_data['first_goon'] = $val_cat->match->first_goon;
        $array_data['teams_second'] = $val_cat->match->teams_second->code;
        $array_data['second_goon'] = $val_cat->match->second_goon;
        $array_data['action_point'] = $val_cat->point->action;
        $array_data['points'] = $val_cat->points;
        $array_data['created_at'] = $val_cat->created_at->format('Y-m-d');
        return $array_data;
    }

    public static function get_StatisticPointPlayerUser($data,$player_data, $api = 0,$lang='en') {
        $all_data = [];
        $all_data['player_name'] = finalValueByLang($player_data->lang_name,$player_data->name,$lang);
        $all_data['player_link'] = $player_data->link;
        $all_data['sub_eldwry_link'] = '';
        $all_data['match_name'] = '';
        $all_data['teams_first'] = '';
        $all_data['first_goon'] = 0;
        $all_data['teams_second'] = '';
        $all_data['second_goon'] = 0;
        if(count($data)>0){
            $all_data['sub_eldwry_link'] = $data[0]->subeldwry->link;
            $all_data['match_name'] = finalValueByLang($data[0]->match->lang_name,$data[0]->match->name,$lang);
            $all_data['teams_first'] = $data[0]->match->teams_first->code;
            $all_data['first_goon'] = $data[0]->match->first_goon;
            $all_data['teams_second'] = $data[0]->match->teams_second->code;
            $all_data['second_goon'] = $data[0]->match->second_goon;
            $all_data['statistic'] =static::get_StatisticPoint($data, $api);
        }else{
            $all_data['statistic'] =static::empty_get_StatisticPoint();
        }
        return $all_data;
    }

    public static function get_StatisticPoint($data, $api = 0,$lang='en')
    {
        $all_data=[];
        foreach ($data as $key => $val_cat) {
            $array_data['type_key_point'] = $val_cat->point->type_key;
            $array_data['lang_point'] = trans('app.'.$val_cat->point->type_key);
            $array_data['action_point'] = $val_cat->point->action;
            $array_data['number'] = $val_cat->number;
            $array_data['points'] = $val_cat->points;
            $all_data[] = $array_data;
            $array_data=[];
        }
        return $all_data;
    }
    public static function empty_get_StatisticPoint()
    {
        $all_data=[];
        $array_data['type_key_point'] ='playing_up_60_minutes';
        $array_data['lang_point'] = trans('app.playing_up_60_minutes');
        $array_data['action_point'] ='For playing up to 60 minutes';
        $array_data['number'] = 0;
        $array_data['points'] = 0;
        $all_data[] = $array_data;
        return $all_data;
    }
}
