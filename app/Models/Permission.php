<?php

namespace App\Models;
// use Illuminate\Database\Eloquent\Model;
// use Zizaco\Entrust\EntrustPermission;
//use Carbon\Carbon;
//use DB;

// class Permission extends EntrustPermission 
class Permission extends Model 
{
	
     protected $fillable = ['parent_id','name','display_name','description'];
     
     
    public function childrens() {
        return $this->hasMany(\App\Models\Permission::class, 'parent_id');
    }
     
}
