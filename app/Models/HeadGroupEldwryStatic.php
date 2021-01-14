<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PointUser;
use App\Models\HeadGroupEldwry;

class HeadGroupEldwryStatic extends Model {

    protected $table = 'head_group_eldwry_statics';
    protected $fillable = [
        'head_group_eldwry_id','sub_eldwry_id','sort','points'
    ];

    public function group_eldwry() {
        return $this->belongsTo(\App\Models\HeadGroupEldwry::class,'head_group_eldwry_id');
    }
    public function subeldwry() {
        return $this->belongsTo(\App\Models\Subeldwry::class,'sub_eldwry_id');
    }

    public static function InsertGroupStatic($head_group_eldwry_id,$sub_eldwry_id,$points=0,$sort=1,$admin=0){
        $data=static::foundDataTwoCondition('head_group_eldwry_id', $head_group_eldwry_id,'sub_eldwry_id', $sub_eldwry_id);
        $input=[
            'head_group_eldwry_id'=>$head_group_eldwry_id,
            'sub_eldwry_id'=>$sub_eldwry_id,
            'sort'=>$sort,
        ];
        if(!isset($data->id)){
            if($admin !=1){
                $input['sort'] +=static::getLastRow($sub_eldwry_id,'sort');
            }
            $data=static::create($input);
        }else{
            $data->update($input);
        }
        return $data;
    }

    public static function updateGroupStatic($colum, $val,$colum_update, $val_update) {
        return static::where($colum, $val)->update([$colum_update=>$val_update]);
    }

    public static function getLastRow($sub_eldwry_id,$colum='',$col_order='id',$val_order='asc') {
        $data=static::where('sub_eldwry_id',$sub_eldwry_id)->orderBy($col_order,$val_order)->first();
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
    
    public static function All_foundData($colum, $val,$col_order='id',$val_order='DESC') {
       return static::where($colum, $val)->orderBy($col_order,$val_order)->get();
    }
    
    public static function getGroupPrevSubeldwry($head_group_eldwry_id,$sub_eldwry_id,$col_order='id',$val_order='DESC') {
       return static::where('head_group_eldwry_id', $head_group_eldwry_id)->where('sub_eldwry_id','<', $sub_eldwry_id)->orderBy($col_order,$val_order)->first();
    }

    public static function Joingroup_Currenteldwry($user_id,$sub_eldwry_id, $is_active=1,$col_order='sort',$val_order='ASC',$is_block=0) {
       return static::
       leftJoin('head_group_eldwrys', 'head_group_eldwrys.id', '=', 'head_group_eldwry_statics.head_group_eldwry_id')
       ->leftJoin('head_group_eldwry_users', 'head_group_eldwrys.id', '=', 'head_group_eldwry_users.head_group_eldwry_id')
        ->whereIn('head_group_eldwry_statics.sub_eldwry_id',$sub_eldwry_id)
        ->where(function ($query) use($user_id,$is_active,$is_block){
                $query->where([['head_group_eldwrys.user_id',$user_id],['head_group_eldwrys.is_active',$is_active]])
                ->orwhere([['head_group_eldwry_users.add_user_id',$user_id],['head_group_eldwry_users.is_active', $is_active],['head_group_eldwry_users.is_block', $is_block],['head_group_eldwrys.is_active',$is_active]]);
            })
        ->distinct()->orderBy($col_order,$val_order)
        ->get(['head_group_eldwry_statics.head_group_eldwry_id','head_group_eldwry_statics.sub_eldwry_id','head_group_eldwry_statics.sort','head_group_eldwry_statics.points']);
    }

}
