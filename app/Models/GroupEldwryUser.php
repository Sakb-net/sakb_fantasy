<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupEldwryUser extends Model {

    protected $table = 'group_eldwry_users';
    protected $fillable = [
        'group_eldwry_id','game_id','add_user_id','is_active','is_block'
    ];


    public function user() {
        return $this->belongsTo(\App\Models\User::class,'add_user_id');
    }
    public function game() {
        return $this->belongsTo(\App\Models\Game::class,'game_id');
    }
    public function group_eldwry() {
        return $this->belongsTo(\App\Models\GroupEldwry::class,'group_eldwry_id');
    }

    public function group_eldwry_join() {
        return $this->belongsTo(\App\Models\GroupEldwry::class, 'user_id', 'add_user_id');
    }

    public static function insertGroup($group_eldwry,$add_user_id,$game_id=0,$subeldwry_id=0) {
        $data=static::foundDataTwoCondition('group_eldwry_id', $group_eldwry->id,'add_user_id', $add_user_id);
        $status=2;
        if(!isset($data->id)){
            $input=[
                'group_eldwry_id'=>$group_eldwry->id,
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
            $cal_stat=new \App\Http\Controllers\OptaApi\Class_PointController();
            $cal_stat->Cal_AllBeforeSubeldwry_GroupEldwry($add_user_id,$group_eldwry->eldwry_id,0,$subeldwry_id,$group_eldwry->id,$game_id);
        }
        return $status;
    }

    public static function updateGroup($group_eldwry_id,$add_user_id,$is_active,$is_block=0) {
        $data=static::foundDataTwoCondition('group_eldwry_id', $group_eldwry_id,'add_user_id', $add_user_id);
        $input=[
            'group_eldwry_id'=>$group_eldwry_id,
            'add_user_id'=>$add_user_id,
            'is_active'=>$is_active,
            'is_block'=>$is_block,
        ];
        return $data->update($input);
    }

    public static function foundDataTwoCondition($colum, $val,$colum2, $val2,$col_order='id',$val_order='DESC') {
        return static::where($colum, $val)->where($colum2, $val2)->orderBy($col_order,$val_order)->first();
    }
    
    public static function All_foundData($colum, $val) {
       return static::where($colum, $val)->get();
    }

    public static function get_data_group_eldwry($group_eldwry_id, $is_active=1,$is_block=0) {
        return static::where('group_eldwry_id',$group_eldwry_id)->where('is_active',$is_active)->where('is_block',$is_block)->get();
    }
}    