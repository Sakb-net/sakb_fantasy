<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class GroupEldwryInvite extends Model {

    protected $table = 'group_eldwry_invites';
    protected $fillable = [
        'user_id','group_eldwry_id','code','link','email_reciver', 'phone_reciver','is_accept', 'is_active'
    ];


    public function user() {
        return $this->belongsToMany(\App\Models\User::class);
    }
    public function group_eldwry() {
        return $this->belongsTo(\App\Models\GroupEldwry::class,'group_eldwry_id');
    }

    public static function InsertInvite($input_data,$group_eldwry) {
        if(!empty($input_data['phone_reciver'])){
            //send whatsapp
            $colum='phone_reciver';
            $value=$input_data['phone_reciver'];
        }else{
            //send email
            $colum='email_reciver';
            $value=$input_data['email_reciver'];
            $array_data['name']='The invitee';
            $array_data['email']=$input_data['email_reciver'];
            $array_data['code']=$group_eldwry->code;
            User::SendEmailTOUser(0,'invite_group','', $array_data);
        }
        $data=static::foundDataThreeCondition('user_id',$input_data['user_id'],'group_eldwry_id',$group_eldwry->id,$colum,$value);
        if(isset($data->id)){
            $data->update(['code'=>$group_eldwry->code]); 
        }else{
            $input=[
                'user_id'=>$input_data['user_id'],
                'phone_reciver'=>$input_data['phone_reciver'],
                'email_reciver'=>$input_data['email_reciver'],
                'group_eldwry_id'=>$group_eldwry->id,
                'code'=>$group_eldwry->code,
                'link'=>generateRandomValue(1),
                'is_active'=>1,
                'is_accept'=>0,
            ];
            $data=static::create($input);  
        }

        return $data;
    }

    public static function foundDataTwoCondition($colum, $val,$colum2, $val2,$col_order='id',$val_order='DESC') {
        return static::where($colum, $val)->where($colum2, $val2)->orderBy($col_order,$val_order)->first();
    }

    public static function foundDataThreeCondition($colum, $val,$colum2, $val2,$colum3, $val3,$col_order='id',$val_order='DESC') {
        return static::where($colum, $val)->where($colum2, $val2)->where($colum3, $val3)->orderBy($col_order,$val_order)->first();
    }

}    
