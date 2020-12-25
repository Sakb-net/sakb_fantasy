<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model {

    protected $table = 'teams';
    protected $fillable = [
        'update_by','name','lang_name', 'link','opta_link', 'image','image_fav', 'eldwry_id','is_active','shortName','officialName','code','addressZip','countryId','country','city','founded','type','teamType'
    ];


    public function user() {
        return $this->belongsToMany(\App\Models\User::class);
    }
    public function eldwry() {
        return $this->belongsTo(\App\Models\Eldwry::class,'eldwry_id');
    }

    public function tags() {
        return $this->morphToMany(\App\Models\Tag::class, 'taggable');
    }
    public static function Add_Opta_Team($input,$eldwry_id,$def_lang='ar') {
        $input=RemoveKeyArray($input,'opta_link','id');
        $input['eldwry_id']=$eldwry_id;
        $input['lang_name']=convertValueToJson($input['name'],$def_lang);
        $input['is_active']=checkDataBool($input['status']);
        $input['image']='/uploads/pics/'.$input['shortName'].'.png';
        $data_team = static::get_TeamTWoCondition('eldwry_id', $eldwry_id,'opta_link', $input['opta_link']);
        if(isset($data_team->id)){
            $input['image']='/uploads/pics/'.$data_team->id.'.png';
            $data_team->update($input);
        }else{
            $input['link']=get_RandLink();
            static::create($input);
        }
        return true;
    }

    public static function updateColum($id, $colum, $columValue) {
        $data = static::findOrFail($id);
        $data->$colum = $columValue;
        return $data->save();
    }

    public static function updateOrderColum($colum, $valueColum, $columUpdate, $valueUpdate) {
        return static::where($colum, $valueColum)->update([$columUpdate => $valueUpdate]);
    }

    public static function foundData($colum,$val) {
        $link_found = static::where($colum,$val)->first();
        return $link_found;
    }
    public static function All_foundData($colum,$val) {
        $link_found = static::where($colum,$val)->get();
        return $link_found;
    }
    public static function selectRandamTeam($colum,$val,$limit=0) {
        $data = static::where($colum,$val)->inRandomOrder();
        if($limit>0){
            $result=$data->limit($limit);
        }
        $result=$data->get();
        return $result;
    }
    public static function get_TeamID($id, $colum, $all = 0) {
        $Team = static::where('id', $id)->first();
        if ($all == 0) {
            return $Team->$colum;
        } else {
            return $Team;
        }
    }

    public static function get_TeamRow($id, $colum = 'id', $is_active = 1) {
        $Team = static::where($colum, $id)->where('is_active', $is_active)->first();
        return $Team;
    }

    public static function get_TeamCloum($colum = 'id', $val = '', $is_active = 1) {
        $Team = static::where($colum, $val)->where('is_active', $is_active)->orderBy('id', 'DESC')->first();
        return $Team;
    }
    public static function get_TeamOneCondition($colum, $val) {
        $Team = static::where($colum, $val)->first();
        return $Team;
    }

    public static function get_TeamTWoCondition($colum, $val,$colum2, $val2) {
        $Team = static::where($colum, $val)->where($colum2, $val2)->first();
        return $Team;
    }

    public static function getAll_Teamdata($is_active = 1,$order='DESC') {
        $Team = static::where('is_active', $is_active)->orderBy('id',$order)->get();
        return $Team;
    }

    public static function SearchTeam($search, $is_active = '', $limit = 0) {
        $data = static::Where('name', 'like', '%' . $search . '%')
                ->orWhere('image', 'like', '%' . $search . '%')
                ->orWhere('link', 'like', '%' . $search . '%');

        if (!empty($is_active)) {
            $result = $data->where('is_active', $is_active);
        }
        if ($limit > 0) {
            $result = $data->paginate($limit);
        } elseif ($limit == -1) {
            $result = $data->pluck('id', 'id')->toArray();
        } else {
            $result = $data->get();
        }
        return $result;
    }

//********************function ************************
    
    public static function getTeam_data($is_active = 1,$lang='ar',$api=0,$order='DESC') {
        $Teams = static::getAll_Teamdata($is_active,$order);
        $data=[];
        foreach ($Teams as $key => $value) {
            $val_data['name']=$value->name;
            $val_data['link']=$value->link;
            $data[]=$val_data;
        }
        $val_data['name']=trans('app.general_fan');
        $val_data['link']='general_fan';
        $data[]=$val_data;
        return $data;
    }
    
    public static function get_DataTeamUser($data, $api = 0) {
        $all_data = [];
        foreach ($data as $key => $val_cat) {
            $all_data[] = static::single_DataTeamUser($val_cat, $api);
        }
        return $all_data;
    }

    public static function single_DataTeamUser($val_cat, $api = 0) {
        $array_data['link'] = $val_cat->link;
        $array_data['name'] = finalValueByLang($val_cat->lang_name,$val_cat->name,$lang);
        $array_data['cost'] = $val_cat->cost;
        $array_data['start_date'] = $val_cat->start_date;//->format('Y-m-d');
        $array_data['end_date'] = $val_cat->end_date;//->format('Y-m-d');
        $array_data['created_at'] = $val_cat->created_at->format('Y-m-d');
        return $array_data;
    }

}
