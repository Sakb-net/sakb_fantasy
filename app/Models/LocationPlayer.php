<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationPlayer extends Model {

    protected $table = 'location_player';
    protected $fillable = [
        'update_by', 'type_key', 'value_ar', 'value_en', 'color', 'is_active'
    ];
//  type_key--> goalkeeper,defender_left,defender_center,defender_right,left_line,center_line,right_line,attacker_center,attacker_left,attacker_right,defender_center
    public function user() {
        return $this->belongsToMany(\App\Models\User::class);
    }

    public function Players() {
        return $this->hasMany(\App\Models\Player::class, 'location_id', 'id');  //
    }

    public static function updateColum($id, $colum, $columValue) {
        $data = static::findOrFail($id);
        $data->$colum = $columValue;
        return $data->save();
    }

    public static function updateOrderColum($colum, $valueColum, $columUpdate, $valueUpdate) {
        return static::where($colum, $valueColum)->update([$columUpdate => $valueUpdate]);
    }

    public static function foundData($colum, $val) {
        $link_found = static::where($colum, $val)->first();
        return $link_found;
    }

    public static function All_foundData($colum, $val) {
        $link_found = static::where($colum, $val)->get();
        return $link_found;
    }

    public static function get_LocationPlayerID($id, $colum, $all = 0) {
        $LocationPlayer = static::where('id', $id)->first();
        if ($all == 0) {
            return $LocationPlayer->$colum;
        } else {
            return $LocationPlayer;
        }
    }

    public static function get_LocationPlayerRow($id, $colum = 'id', $is_active = 1) {
        $LocationPlayer = static::where($colum, $id)->where('is_active', $is_active)->first();
        return $LocationPlayer;
    }

    public static function get_LocationPlayerCloum($colum = 'id', $val = '', $is_active = 1) {
        $LocationPlayer = static::where($colum, $val)->where('is_active', $is_active)->orderBy('id', 'DESC')->first();
        return $LocationPlayer;
    }

    public static function get_DataAll($is_active = '', $type_key = '',$array_id=[]) {
        $data = static::distinct();
        if (!empty($is_active) && empty($type_key)) {
            $result=$data->where('is_active', $is_active);
        } elseif (empty($is_active) && !empty($type_key)) {
            $result=$data->Where('type_key', 'like', '%' . $type_key . '%');
        } elseif (!empty($is_active) && !empty($type_key)) {
            $result=$data->where('is_active', $is_active)->Where('type_key', 'like', '%' . $type_key . '%');
        }
        if(!empty($array_id)){
            $result=$data->whereIn('id',$array_id);
        }
        $result=$data->get();
        return $result;
    }

    public static function SearchLocationPlayer($search, $is_active = '', $limit = 0) {
        $data = static::Where('name', 'like', '%' . $search . '%')
                ->orWhere('type_key', 'like', '%' . $search . '%')
                ->orWhere('value_ar', 'like', '%' . $search . '%')
                ->orWhere('value_en', 'like', '%' . $search . '%');

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
}
