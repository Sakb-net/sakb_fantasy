<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllType extends Model {

    protected $table = 'all_types';
    protected $fillable = [
        'update_by', 'type_key', 'value_ar', 'value_en', 'is_active'
    ];

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

    public static function get_AllTypeID($id, $colum, $all = 0) {
        $AllType = static::where('id', $id)->first();
        if ($all == 0) {
            return $AllType->$colum;
        } else {
            return $AllType;
        }
    }

    public static function get_AllTypeRow($id, $colum = 'id', $is_active = 1) {
        $AllType = static::where($colum, $id)->where('is_active', $is_active)->first();
        return $AllType;
    }

    public static function get_AllTypeCloum($colum = 'id', $val = '', $is_active = 1) {
        $AllType = static::where($colum, $val)->where('is_active', $is_active)->orderBy('id', 'DESC')->first();
        return $AllType;
    }

    public static function get_DataAll($is_active = -1) {
        if ($is_active != -1) {
            $data = static::where('is_active', $is_active)->get();
        } else {
            $data = static::get();
        }
        return $data;
    }

    public static function SearchAllType($search, $is_active = '', $limit = 0) {
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
    public static function get_DataAllTypeUser($data, $api = 0) {
        $all_data = [];
        foreach ($data as $key => $val_cat) {
            $all_data[] = static::single_DataAllTypeUser($val_cat, $api);
        }
        return $all_data;
    }

    public static function single_DataAllTypeUser($val_cat, $api = 0) {
        $array_data['link'] = $val_cat->link;
        $array_data['name'] = $val_cat->name;
        $array_data['cost'] = $val_cat->cost;
        $array_data['start_date'] = $val_cat->start_date; //->format('Y-m-d');
        $array_data['end_date'] = $val_cat->end_date; //->format('Y-m-d');
        $array_data['created_at'] = $val_cat->created_at->format('Y-m-d');
        return $array_data;
    }

}
