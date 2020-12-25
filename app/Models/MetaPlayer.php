<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetaPlayer extends Model {

    protected $table = 'meta_players';
    protected $fillable = [
        'player_id', 'meta_key', 'meta_value','is_active'
    ];


    public function user() {
        return $this->belongsToMany(\App\Models\User::class);
    }
    public function player() {
        return $this->belongsTo(\App\Models\Player::class,'player_id');
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

    public static function get_MetaPlayerID($id, $colum, $all = 0) {
        $MetaPlayer = static::where('id', $id)->first();
        if ($all == 0) {
            return $MetaPlayer->$colum;
        } else {
            return $MetaPlayer;
        }
    }

    public static function get_MetaPlayerRow($id, $colum = 'id', $is_active = 1) {
        $MetaPlayer = static::where($colum, $id)->where('is_active', $is_active)->first();
        return $MetaPlayer;
    }

    public static function get_MetaPlayerCloum($colum = 'id', $val = '', $is_active = 1) {
        $MetaPlayer = static::where($colum, $val)->where('is_active', $is_active)->orderBy('id', 'DESC')->first();
        return $MetaPlayer;
    }

    public static function SearchMetaPlayer($search, $is_active = '', $limit = 0) {
        $data = static::Where('name', 'like', '%' . $search . '%')
                ->orWhere('type_key', 'like', '%' . $search . '%')
                ->orWhere('meta_key', 'like', '%' . $search . '%')
                ->orWhere('meta_value', 'like', '%' . $search . '%');

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
