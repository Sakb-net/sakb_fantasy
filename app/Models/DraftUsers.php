<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Draft;

class DraftUsers extends Model
{
    use HasFactory;
    protected $table = 'draft_users';

    public function user() {
        return $this->belongsTo(\App\Models\User::class,'user_id');
    }

    public function draft() {
        return $this->belongsTo(\App\Models\draft::class,'draft_id');
    }
    public static function checkUserDraft($userId){
        $data = static::where(['user_id'=>$userId,'is_active'=>1])->first();
        return $data;
    }
    static function selectUserDraft($id){
        $data = static::where('user_id',$id)->where('is_active',1)->first();
        return $data;
    }
    static function getJoinCount($id){
        $data = static::where('draft_id',$id)->where('is_active',1)->count();
        return $data;
    }

    
}
