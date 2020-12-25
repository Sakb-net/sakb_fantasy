<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Point extends Model {

    protected $table = 'points';
    protected $fillable = [
        'update_by', 'type_key','action', 'type_id', 'point','type', 'note'
    ];
// type_id ---> 1 add, 7 sub
//type : NP(Normal Points),BPS (Bonus Points System) 

    public function user() {
        return $this->belongsToMany(\App\Models\User::class);
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

    public static function get_PointID($id, $colum, $all = 0) {
        $Point = static::where('id', $id)->first();
        if ($all == 0) {
            return $Point->$colum;
        } else {
            return $Point;
        }
    }

    public static function get_PointRow($id, $colum = 'id') {
        $Point = static::where($colum, $id)->orderBy('id', 'DESC')->first();
        return $Point;
    }

    public static function get_PointCloum($colum = 'id', $val = '') {
        $Point = static::where($colum, $val)->first();
        return $Point;
    }

    public static function get_DataAll($colum='', $val='') {
        if (!empty($colum)) {
            $data = static::where($colum, $val)->get();
        } else {
            $data = static::get();
        }
        return $data;
    }

    public static function SearchPoint($search, $note = '', $limit = 0) {
        $data = static::Where('name', 'like', '%' . $search . '%')
                ->orWhere('type_key', 'like', '%' . $search . '%')
                ->orWhere('action', 'like', '%' . $search . '%')
                ->orWhere('point', 'like', '%' . $search . '%');

        if (!empty($note)) {
            $result = $data->where('note', $note);
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
    public static function get_DataPoint($data, $api = 0) {
        $all_data = [];
        foreach ($data as $key => $val_cat) {
            $all_data[] = static::single_DataPoint($val_cat, $api);
        }
        return $all_data;
    }

    public static function single_DataPoint($val_cat, $api = 0) {
        $array_data['type_key'] = $val_cat->type_key;
        $array_data['action'] = $val_cat->action;
        $array_data['point'] = $val_cat->point;
        $array_data['created_at'] = $val_cat->created_at->format('Y-m-d');
        return $array_data;
    }

}
