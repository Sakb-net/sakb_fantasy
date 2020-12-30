<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model {

    protected $table = 'permission_role';
    public $timestamps = false;
    protected $fillable = [
        'permission_id', 'role_id'
    ];

    public static function insertRolepermission($permission_id, $role_id) {
        $input=[
            'permission_id' =>$permission_id,
            'role_id' =>$role_id
        ];
        return self::create($input);
    }

    public static function deletepermission($permission_id) {
        return self::where('permission_id', $permission_id)->delete();
    }

    public static function deleteRolepermission($role_id, $permission_id) {
        return self::where('role_id', $role_id)->where('permission_id', $permission_id)->delete();
    }

}
