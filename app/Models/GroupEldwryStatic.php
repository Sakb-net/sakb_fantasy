<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PointUser;
use App\Models\GroupEldwry;
class GroupEldwryStatic extends Model {

    protected $table = 'group_eldwry_statics';
    protected $fillable = [
        'group_eldwry_id','sub_eldwry_id','sort','points'
    ];

    public function group_eldwry() {
        return $this->belongsTo(\App\Models\GroupEldwry::class,'group_eldwry_id');
    }
    public function subeldwry() {
        return $this->belongsTo(\App\Models\Subeldwry::class,'sub_eldwry_id');
    }

    public static function InsertGroupStatic($group_eldwry_id,$sub_eldwry_id,$points=0,$sort=1){
        $data=static::foundDataTwoCondition('group_eldwry_id', $group_eldwry_id,'sub_eldwry_id', $sub_eldwry_id);
        if(!isset($data->id)){
            $input=[
                'group_eldwry_id'=>$group_eldwry_id,
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

    public static function foundData($colum, $val,$col_order='id',$val_order='asc') {
        return static::where($colum, $val)->orderBy($col_order,$val_order)->first();
    }

    public static function foundDataTwoCondition($colum, $val,$colum2, $val2,$col_order='id',$val_order='DESC') {
        return static::where($colum, $val)->where($colum2, $val2)->orderBy($col_order,$val_order)->first();
    }
    
    public static function All_foundData($colum, $val,$col_order='id',$val_order='DESC') {
       return static::where($colum, $val)->orderBy($col_order,$val_order)->get();
    }
    
    public static function getGroupPrevSubeldwry($group_eldwry_id,$sub_eldwry_id,$col_order='id',$val_order='DESC') {
       return static::where('group_eldwry_id', $group_eldwry_id)->where('sub_eldwry_id','<', $sub_eldwry_id)->orderBy($col_order,$val_order)->first();
    }

    public static function Joingroup_Currenteldwry($user_id,$sub_eldwry_id, $is_active=1,$col_order='sort',$val_order='ASC',$is_block=0) {
       return static::
       leftJoin('group_eldwrys', 'group_eldwrys.id', '=', 'group_eldwry_statics.group_eldwry_id')
       ->leftJoin('group_eldwry_users', 'group_eldwrys.id', '=', 'group_eldwry_users.group_eldwry_id')
        ->where('group_eldwry_statics.sub_eldwry_id','>',$sub_eldwry_id)
        ->where(function ($query) use($user_id,$is_active,$is_block){
                $query->where([['group_eldwrys.user_id',$user_id],['group_eldwrys.is_active',$is_active]])
                ->orwhere([['group_eldwry_users.add_user_id',$user_id],['group_eldwry_users.is_active', $is_active],['group_eldwry_users.is_block', $is_block],['group_eldwrys.is_active',$is_active]]);
            })
        ->distinct()->orderBy($col_order,$val_order)
        ->get(['group_eldwry_statics.group_eldwry_id','group_eldwry_statics.sub_eldwry_id','group_eldwry_statics.sort','group_eldwry_statics.points']);
    }


    public static function Joingroup_Currenteldwry_old($user_id,$sub_eldwry_id, $is_active=1,$col_order='sort',$val_order='ASC',$is_block=0) {
       return static::leftJoin('group_eldwrys', 'group_eldwrys.id', '=', 'group_eldwry_statics.group_eldwry_id')
       ->leftJoin('group_eldwry_users', 'group_eldwrys.id', '=', 'group_eldwry_users.group_eldwry_id')
        ->where('group_eldwry_statics.sub_eldwry_id',$sub_eldwry_id)

        ->where(function ($query) use($user_id,$is_active){
                $query->where([['group_eldwrys.user_id',$user_id],['group_eldwrys.is_active',$is_active]])
                ->orwhere([['group_eldwry_users.add_user_id',$user_id],['group_eldwry_users.is_active', $is_active],['group_eldwry_users.is_block', $is_block],['group_eldwrys.is_active',$is_active]]);
            })

        ->distinct()->orderBy($col_order,$val_order)
        ->get(['group_eldwry_statics.group_eldwry_id','group_eldwry_statics.sub_eldwry_id','group_eldwry_statics.sort','group_eldwry_statics.points']);
    }

}
