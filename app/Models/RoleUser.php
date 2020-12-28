<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model {

    protected $table = 'role_user';
    public $timestamps = false;
    protected $fillable = [
        'user_id', 'role_id'
    ];

    public static function insertRoleUser($user_id, $role_id) {
        $input=[
            'user_id' =>$user_id,
            'role_id' =>$role_id
        ];
        return self::create($input);
    }

    public static function deleteUser($user_id) {
        return self::where('user_id', $user_id)->delete();
    }

    public static function deleteRoleUser($role_id, $user_id) {
        return self::where('role_id', $role_id)->where('user_id', $user_id)->delete();
    }

}
