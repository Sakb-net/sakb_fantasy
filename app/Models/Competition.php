<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class Competition extends Model {
    protected $table = 'competitions';

    protected $fillable = [
        'update_by', 'name','lang_name', 'opta_link', 'competitionCode', 'displayOrder','country','countryId','isFriendly','competitionFormat','type','competitionType', 'is_active'
    ];


    public function user() {
        return $this->belongsToMany(\App\Models\User::class);
    }

    public static function Add_Check_Competition($data,$def_lang='en') {
        $comp_data = static::found_Competition('opta_link',$data['opta_link'],0);
        $data['lang_name']=convertValueToJson($data['name'],$def_lang);
        if(isset($comp_data->id)){
            $comp_id=$comp_data->id;
            $comp_data->update($data);
        }else{
        	//add
        	$add=static::create($data);
        	$comp_id=$add['id'];
        }
        return $comp_id;
    }

    public static function found_Competition($colum='opta_link',$val_col='',$check=0) {
        $data = static::where($colum,$val_col)->first();
        if($check==1){
        	$result=0;
        	if(isset($data->id)){
        		$result=$data->id;
        	}
        }else{
        	$result=$data;
        }
        return $result;
    }



}
