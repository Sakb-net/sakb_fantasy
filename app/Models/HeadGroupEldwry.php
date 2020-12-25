<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Subeldwry;
use App\Models\HeadGroupEldwryTeamStatic;
use App\Models\HeadGroupEldwryMatch;
use App\Models\HeadGroupEldwryStatic;

class HeadGroupEldwry extends Model {

    protected $table = 'head_group_eldwrys';
    protected $fillable = [
        'update_by','user_id','creator_id','eldwry_id','game_id','name','lang_name','image','link',  'start_sub_eldwry_id','num_allow_users','code','is_active'
    ];


    public function user() {
        return $this->belongsTo(\App\Models\User::class);
    }
    public function eldwry() {
        return $this->belongsTo(\App\Models\Eldwry::class,'eldwry_id');
    }

    public function subeldwry() {
        return $this->belongsTo(\App\Models\Subeldwry::class,'start_sub_eldwry_id');
    }

    public function game() {
        return $this->belongsTo(\App\Models\Game::class,'game_id');
    }

    public function group_eldwry_user() {
        return $this->hasMany(\App\Models\HeadGroupEldwryUser::class, 'head_group_eldwry_id', 'id');
    }

    public function group_eldwry_statics()
    {
        return $this->hasMany(\App\Models\HeadGroupEldwryStatic::class, 'head_group_eldwry_id', 'id');
    }

    public static function insertGroup($input_data,$subeldwry,$lang='en',$num_allow_users=0) {
        $current_subeldwry=Subeldwry::get_startFirstSubDwry();
        $data=[];
        $status=0;
        if(isset($current_subeldwry->id)){
            $status=1;
            $data=static::foundDataTwoCondition('name', $input_data['name'],'user_id',$input_data['user_id']);
            if(!isset($data->id)){
                $lang_name=json_encode([$lang=>$input_data['name']]);
                $code=generateRandomValue();
                $input=[
                    'name'=>$input_data['name'],
                    'lang_name'=>$lang_name,
                    'user_id'=>$input_data['user_id'],
                    'creator_id'=>$input_data['user_id'],
                    'eldwry_id'=>$subeldwry->eldwry_id,
                    'start_sub_eldwry_id'=>$subeldwry->id,
                    'game_id'=>$input_data['game_id'],
                    'update_by'=>$input_data['user_id'],
                    'link'=>generateRandomValue(1),
                    'code'=>$code,
                    'is_active'=>1,
                    'num_allow_users'=>$num_allow_users,
                ];
                $data=static::create($input);
                $befor_currentsubeldwry = Subeldwry::get_BeforCurrentSubDwry();
                if(isset($befor_currentsubeldwry->id) && $current_subeldwry->id != $befor_currentsubeldwry->id){
                    HeadGroupEldwryStatic::InsertGroupStatic($data['id'],$befor_currentsubeldwry->id);
                }
                //add new account to team,match and statistic for head groups eldwry
                static::addTeamMatchHeadNewSubldwry($current_subeldwry,$subeldwry,$data['id'],$data['user_id']);
                HeadGroupEldwryStatic::InsertGroupStatic($data['id'],$current_subeldwry->id);
            }else{
                $data=$data->toArray();
            }
        }    
        return array('status'=>$status,'data'=>$data);
    }

    public static function updateGroup($input_data,$subeldwry,$lang='ar',$num_allow_users=0) {
        $data=static::foundData('link',$input_data['link_group']);
        $status=0;
        $update_sub_eldwry=0;
        if(isset($data->id)){
            $current_subeldwry=Subeldwry::get_startFirstSubDwry();
            if($current_subeldwry->id < $subeldwry->id){
                //check if group this first game ok update more than one no update
                $get_groups=HeadGroupEldwryStatic::All_foundData('head_group_eldwry_id',$data->id);
                if(count($get_groups)==1){
                    $update_sub_eldwry=1;
                    //update HeadGroupEldwryStatic,team,match
                    static::updateTeamMatchStaticHeadNewSubldwry($data,$subeldwry,$current_subeldwry);
                }
            }   
            $lang_name=json_encode([$lang=>$input_data['name']]);
            $input=[
                'name'=>$input_data['name'],
                'lang_name'=>$lang_name,
                'update_by'=>$input_data['user_id'],
            ];
            if($update_sub_eldwry==1){
                $input['start_sub_eldwry_id']=$subeldwry->id;
            }
            $data->update($input);
            $status=2;
        }
        return array('update_sub_eldwry'=>$update_sub_eldwry,'status'=>$status,'data'=>$data);
    }

    public static function addTeamMatchHeadNewSubldwry($current_subeldwry,$group_subeldwry,$group_eldwry_id,$group_eldwry_user_id){
        if($current_subeldwry->id >= $group_subeldwry->id){
            $team_static = HeadGroupEldwryTeamStatic::InsertGroupTeamStatic($group_eldwry_id,$current_subeldwry->id,$group_eldwry_user_id,0,0);
            $match_static = HeadGroupEldwryMatch::InsertGroupMatch($group_eldwry_id,$current_subeldwry->id,$team_static['id']);
        }
        return true;
    }

    public static function updateTeamMatchStaticHeadNewSubldwry($group_eldwry,$new_subeldwry,$current_subeldwry){
        HeadGroupEldwryStatic::updateGroupStatic('head_group_eldwry_id',$group_eldwry->id,$new_subeldwry->id,0);
        if($current_subeldwry->id < $group_eldwry->start_sub_eldwry_id){    
            static::addTeamMatchHeadNewSubldwry($current_subeldwry,$new_subeldwry,$group_eldwry->id,$group_eldwry->user_id);
        }else{
           if($current_subeldwry->id < $new_subeldwry->id){ 
                //delete from team and match 
                $match_static = HeadGroupEldwryMatch::deleteGroupMatch($group_eldwry->id,$current_subeldwry->id);
                $team_static = HeadGroupEldwryTeamStatic::deleteGroupteamStatic($group_eldwry->id,$current_subeldwry->id);
            } 
        }    
        return true;
    }

    public static function foundData($colum, $val,$col_order='id',$val_order='asc') {
        return static::where($colum, $val)->orderBy($col_order,$val_order)->first();
    }

    public static function foundDataTwoCondition($colum, $val,$colum2, $val2,$col_order='id',$val_order='DESC') {
        return static::where($colum, $val)->where($colum2, $val2)->orderBy($col_order,$val_order)->first();
    }

    public static function All_foundData($colum, $val) {
       return static::where($colum, $val)->get();
    }
    
    public static function All_foundDataTwoCondition($colum, $val,$colum2, $val2) {
        return static::where($colum, $val)->where($colum2, $val2)->get();
    }

    public static function Joinhead_group_eldwry_users($user_id,$eldwry_id, $is_active=1) {
        return static::leftJoin('head_group_eldwry_users', 'head_group_eldwrys.id', '=', 'head_group_eldwry_users.head_group_eldwry_id')
        ->where('head_group_eldwrys.eldwry_id',$eldwry_id)
        ->where([['head_group_eldwrys.user_id',$user_id],['head_group_eldwrys.is_active',$is_active]])->orwhere([['head_group_eldwry_users.add_user_id',$user_id],['head_group_eldwry_users.is_active', $is_active],['head_group_eldwrys.is_active',$is_active]])
        ->get();
    }

    public static function Get_allow_sub_eldwry($start_sub_eldwry_id,$is_active=1) {
        return static::where('start_sub_eldwry_id','<=', $start_sub_eldwry_id)->where('is_active',1)->get();
    }

}
