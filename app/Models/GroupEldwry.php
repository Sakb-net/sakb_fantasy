<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupEldwry extends Model {

    protected $table = 'group_eldwrys';
    protected $fillable = [
        'update_by','user_id','eldwry_id','game_id','name','lang_name','image','link',  'start_sub_eldwry_id','creator_id','num_allow_users','code','is_active'
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
        return $this->hasMany(\App\Models\GroupEldwryUser::class, 'group_eldwry_id', 'id');
    }

    public function group_eldwry_owner() {
        return $this->hasMany(\App\Models\GroupEldwryUser::class, 'add_user_id', 'user_id');
    }

    public function group_eldwry_statics()
    {
        return $this->hasMany(\App\Models\GroupEldwryStatic::class, 'group_eldwry_id', 'id');
    }

    public static function insertGroup($input_data,$subeldwry,$lang='en',$num_allow_users=0) {
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
            //account points and statistic for groups eldwry
            $cal_stat=new \App\Http\Controllers\OptaApi\Class_PointController();
            $cal_stat->Cal_AllBeforeSubeldwry_GroupEldwry($input_data['user_id'],$subeldwry->eldwry_id,0,$subeldwry->id,$data['id'],$input_data['game_id']);
        }else{
            $data=$data->toArray();
        }
        return $data;
    }

    public static function updateGroup($input_data,$subeldwry,$lang='ar',$num_allow_users=0) {
        $data=static::foundData('link',$input_data['link_group']);
        $status=0;
        if(isset($data->id)){
            $lang_name=json_encode([$lang=>$input_data['name']]);
            $input=[
                'name'=>$input_data['name'],
                'lang_name'=>$lang_name,
                'start_sub_eldwry_id'=>$subeldwry->id,
                'update_by'=>$input_data['user_id'],
            ];
            $data->update($input);
            $status=2;
            //change account points and statistic for groups eldwry
            $cal_stat=new \App\Http\Controllers\OptaApi\Class_PointController();
            if($data->start_sub_eldwry_id < $subeldwry->id){
                //insert new cal
                $cal_stat->Cal_AllBeforeSubeldwry_GroupEldwry($data->user_id,$data->eldwry_id,$data->start_sub_eldwry_id,$subeldwry->id,$data->id,$data->game_id);
            }elseif($data->start_sub_eldwry_id > $subeldwry->id){
                //delete 
                $cal_stat->Delete_AllBeforeSubeldwry_GroupEldwry($data->user_id,$data->eldwry_id,$subeldwry->id,$data->start_sub_eldwry_id,$data->id,$data->game_id);
            }
        }
        return array('status'=>$status,'data'=>$data);
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

    public static function Joingroup_eldwry_users($user_id,$eldwry_id, $is_active=1) {
        return static::leftJoin('group_eldwry_users', 'group_eldwrys.id', '=', 'group_eldwry_users.group_eldwry_id')
        ->where('group_eldwrys.eldwry_id',$eldwry_id)
        ->where([['group_eldwrys.user_id',$user_id],['group_eldwrys.is_active',$is_active]])->orwhere([['group_eldwry_users.add_user_id',$user_id],['group_eldwry_users.is_active', $is_active],['group_eldwrys.is_active',$is_active]])
        ->get();
    }

    public static function Get_allow_sub_eldwry($start_sub_eldwry_id,$is_active=1) {
        return static::where('start_sub_eldwry_id','<=', $start_sub_eldwry_id)->where('is_active',1)->get();
    }
// **********************************Not use******************
    public static function GroupEldwry_CurrentEldwry($user_id,$eldwry_id,$is_active=1,$val_order='ASC') {
        return static::leftJoin('group_eldwry_statics', 'group_eldwrys.id', '=', 'group_eldwry_statics.group_eldwry_id')
        ->where('group_eldwrys.eldwry_id',$eldwry_id)
        ->where('group_eldwrys.user_id',$user_id)
        ->where('group_eldwrys.is_active',$is_active)
        ->whereDoesntHave('group_eldwry_statics', function ($q) {

        })
        ->orwhereHas('group_eldwry_user', function ($q) use ($user_id) {
            $q->where('group_eldwry_users.add_user_id',$user_id);
        })

        ->orderBy('group_eldwry_statics.sort',$val_order)
        ->get();
    }

}
