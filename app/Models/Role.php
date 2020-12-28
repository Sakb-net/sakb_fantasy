<?php

namespace App\Models;
// use Illuminate\Database\Eloquent\Model;
use DB;

class Role extends Model
{

    public function permissions()
   {
        return $this->belongsToMany(\App\Models\Permission::class);
   }
   
   public static function getRoleCount($role_id = 1) {
        return DB::table('users')
        ->join('role_user', 'users.id', '=', 'role_user.user_id')
        ->where('role_user.role_id',$role_id)
        ->groupBy('role_user.role_id')
        ->count();
    }
   public static function CheckRole($role_name = 'admin',$type='member') {
        $allRole= Role::pluck('name', 'id')->toArray();
        $result=0;
        if (in_array($role_name, $allRole) && $role_name !=$type) {
            $result=1;
        }
        return $result;
    }
}