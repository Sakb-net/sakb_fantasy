<?php

namespace App\Models;
use Shanmuga\LaravelEntrust\Models\EntrustPermission;

class Permission extends EntrustPermission 
{
	
     protected $fillable = ['parent_id','name','display_name','description'];
     
     
    public function childrens() {
        return $this->hasMany(\App\Models\Permission::class, 'parent_id');
    }
     
}
