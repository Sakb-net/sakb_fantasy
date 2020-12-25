<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\HeadGroupEldwry;

class HeadGroupEldwryUser extends Model {

    protected $table = 'head_group_eldwry_users';
    protected $fillable = [
        'head_group_eldwry_id','game_id','add_user_id','is_active','is_block'
    ];


    public function user() {
        return $this->belongsTo(\App\Models\User::class,'add_user_id');
    }
    public function game() {
        return $this->belongsTo(\App\Models\Game::class,'game_id');
    }
    public function group_eldwry() {
        return $this->belongsTo(\App\Models\HeadGroupEldwry::class,'head_group_eldwry_id');
    }

    public function group_eldwry_join() {
        return $this->belongsTo(\App\Models\HeadGroupEldwry::class, 'user_id', 'add_user_id');
    }

    public static function insertGroup($head_group_eldwry,$add_user_id,$current_subeldwry,$game_id=0) {
        $data=static::foundDataTwoCondition('head_group_eldwry_id', $head_group_eldwry->id,'add_user_id', $add_user_id);
        $status=2;
        if(!isset($data->id)){
            $input=[
                'head_group_eldwry_id'=>$head_group_eldwry->id,
                'add_user_id'=>$add_user_id,
                'game_id'=>$game_id,
                'is_active'=>1,
            ];
            $data=static::create($input);
            $status=1;
        }else{
            if($data->is_block != 1){
                if($data->is_active != 1){
                    $data->update(['is_active'=>1]);
                    $status=1;
                }
            }else{
                $status=0;  
            }
        }
        if($status==1){
            //account points and statistic for groups eldwry
            HeadGroupEldwry::addTeamMatchHeadNewSubldwry($current_subeldwry,$head_group_eldwry->subeldwry,$head_group_eldwry->id,$head_group_eldwry->user_id);
        }
        return $status;
    }

    public static function updateGroup($head_group_eldwry_id,$add_user_id,$is_active,$is_block=0) {
        $data=static::foundDataTwoCondition('head_group_eldwry_id', $head_group_eldwry_id,'add_user_id', $add_user_id);
        $input=[
            'head_group_eldwry_id'=>$head_group_eldwry_id,
            'add_user_id'=>$add_user_id,
            'is_active'=>$is_active,
            'is_block'=>$is_block,
        ];
        if($is_block==1){
            //delete this user from team and match for current dwry
            $current_subeldwry=Subeldwry::get_startFirstSubDwry();
            
        }
        return $data->update($input);
    }

    public static function foundDataTwoCondition($colum, $val,$colum2, $val2,$col_order='id',$val_order='DESC') {
        return static::where($colum, $val)->where($colum2, $val2)->orderBy($col_order,$val_order)->first();
    }
    
    public static function All_foundData($colum, $val) {
       return static::where($colum, $val)->get();
    }

    public static function get_data_group_eldwry($head_group_eldwry_id, $is_active=1,$is_block=0,$array=0,$pluck='add_user_id') {
        $data=static::where('head_group_eldwry_id',$head_group_eldwry_id)->where('is_active',$is_active)->where('is_block',$is_block);
        if($array==1){
            $result=$data->inRandomOrder()->pluck($pluck)->toArray();
        }else{
            $result=$data->get();
        }
        return $result;
    }

}    