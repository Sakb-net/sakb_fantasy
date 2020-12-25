<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;
use Config;
//use App\Models\User;
// use App\Models\Game;
use Auth;

class AllSetting extends Model {

    protected $table = 'settings';
    protected $fillable = [
        'setting_key', 'setting_value', 'setting_etc', 'is_active',
    ];

//lineup_id

    public static function insertSetting($setting_key, $setting_value, $setting_etc = null, $is_active = 1) {
        $input['setting_etc'] = $setting_etc;
        $input['setting_key'] = $setting_key;
        $input['setting_value'] = $setting_value;
        $input['is_active'] = $is_active;
        return static::create($input);
    }

    public static function updateSetting($id, $setting_key, $setting_value, $setting_etc = null, $is_active = 1) {
        $data = static::findOrFail($id);
        $data->setting_key = $setting_key;
        $data->setting_value = $setting_value;
        $data->setting_etc = $setting_etc;
        $data->is_active = $is_active;
        return $data->save();
    }

    public static function insertUpdateSetting($setting_key, $setting_value, $setting_etc, $is_active = 1) {
        $setting_value_found = static::where('setting_key', $setting_key)->first();
        if (isset($setting_value_found->id)) {
            $data = static::updateSetting($setting_value_found->id, $setting_key, $setting_value, $setting_etc, $is_active);
        } else {
            $data = static::insertSetting($setting_key, $setting_value, $setting_etc, $is_active);
        }
        return $data;
    }
    
    public static function updateOrderColum($colum, $valueColum, $columUpdate, $valueUpdate) {
        return static::where($colum, $valueColum)->update([$columUpdate => $valueUpdate]);
    }
    
    public static function updateSettingLang($id, $setting_value) {
        $data = static::findOrFail($id);
        $data->setting_value = $setting_value;
        return $data->save();
    }

    public static function updateSettingActive($id, $is_active = 1) {
        $data = static::findOrFail($id);
        $data->is_active = $is_active;
        return $data->save();
    }

    public static function foundLink($setting_key) {
        $setting_value_found = static::where('setting_key', $setting_key)->first();
        if (isset($setting_value_found->id)) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function get_rowSetting($setting_key) {
        $data = static::where('setting_key', $setting_key)->first();
        return $data;
    }
     
    public static function get_rowSettingLink($setting_key,$setting_etc,$is_active=1) {
        $data = static::where('setting_key', $setting_key)->where('setting_etc', $setting_etc)->where('is_active', $is_active)->first();
        return $data;
    }
    public static function get_ValSettingLike($setting_key,$setting_value,$is_active=1) {
        $data = static::where('setting_key', $setting_key)->where('is_active', $is_active)->where('setting_value', 'like', '%' . $setting_value . '%')->orderBy('id', 'DESC')->first();
        return $data;
    }

    public static function get_Setting($setting_key = 'lineup', $is_active = 1, $colum = '',$order='DESC') {
        $data = static::where('setting_key', $setting_key)->where('is_active', $is_active)
                ->orderBy('id',$order);
        if (!empty($colum)) {
            $result = $data->pluck($colum, 'id')->toArray();
        } else {
            $result = $data->get();
        }
        return $result;
    }

    public static function get_SettingSearch($setting_key = 'lineup', $setting_value = '', $is_active = 1, $colum = '') {
        $data = static::where('setting_key', $setting_key)->where('is_active', $is_active)
                        ->where('setting_value', 'like', '%' . $setting_value . '%')->orderBy('id', 'DESC');
        if (!empty($colum)) {
            $result = $data->pluck($colum, 'id')->toArray();
        } else {
            $result = $data->get();
        }
        return $result;
    }

    public static function SearchSetting($search, $is_active = '', $limit = 0) {
        $data = static::Where('setting_key', 'like', '%' . $search . '%')
                ->orWhere('setting_value', 'like', '%' . $search . '%')
                ->orWhere('setting_etc', 'like', '%' . $search . '%');
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
//****************************
    public static function DataAllSetting($key, $is_active = '',$order='ASC',$colum='',$limit = 0) {
        $result=[];
        $data = static::get_Setting($key, $is_active,$colum,$order);
        foreach ($data as $key => $value) {
            $data_val['link']=$value->setting_etc;
            $setting_value=json_decode($value->setting_value,true);
            $data_val['linup_one']=$setting_value[0];
            $data_val['linup_second']=$setting_value[1];
            $data_val['linup_three']=$setting_value[2];
            $result[]=$data_val;
        }
        return $result;
    }

}
