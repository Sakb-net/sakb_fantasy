<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class GameTransaction extends Model {

    protected $table = 'game_transactions';
    protected $fillable = [
        'update_by', 'eldwry_id', 'type_id', 'user_id', 'cost', 'is_active'
    ];

    public function user() {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function alltypes() {
        return $this->belongsTo(\App\Models\AllType::class, 'type_id');
    }

    public function eldwry() {
        return $this->belongsTo(\App\Models\Eldwry::class, 'eldwry_id');
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

    public static function get_GameTransactionID($id, $colum, $all = 0) {
        $GameTransaction = static::where('id', $id)->first();
        if ($all == 0) {
            return $GameTransaction->$colum;
        } else {
            return $GameTransaction;
        }
    }

    public static function get_GameTransactionRow($id, $colum = 'id', $is_active = 1) {
        $GameTransaction = static::where($colum, $id)->where('is_active', $is_active)->first();
        return $GameTransaction;
    }

    public static function get_GameTransactionCloum($colum = 'id', $val = '', $is_active = 1) {
        $GameTransaction = static::where($colum, $val)->where('is_active', $is_active)->orderBy('id', 'DESC')->first();
        return $GameTransaction;
    }

    public static function checkregisterDwry($user_id, $eldwry_id, $type_id = 1) {
        $data = static::where('user_id', $user_id)->where('eldwry_id', $eldwry_id)->where('type_id', $type_id)->orderBy('id', 'DESC')->first();
        return $data;
    }

    public static function sum_Finaltotal($user_id, $eldwry_id, $sum = 0) {
        if ($sum == 1) {
            $sum = static::select(DB::raw('sum(cost) as sum_cost'))->where('user_id', $user_id)->where('eldwry_id', $eldwry_id)->get();
            $data=$sum[0]->sum_cost;
        } else {
            $data = static::where('user_id', $user_id)->where('eldwry_id', $eldwry_id)->orderBy('id', 'DESC')->get();
        }
        return $data;
    }

    public static function SearchGameTransaction($search, $is_active = '', $limit = 0) {
        $data = static::Where('user_id', 'like', '%' . $search . '%')
                ->orWhere('eldwry_id', 'like', '%' . $search . '%')
                ->orWhere('cost', 'like', '%' . $search . '%');

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
    public static function get_DataGameTransactionUser($data, $api = 0) {
        $all_data = [];
        foreach ($data as $key => $val_cat) {
            $all_data[] = static::single_DataGameTransactionUser($val_cat, $api);
        }
        return $all_data;
    }

    public static function single_DataGameTransactionUser($val_cat, $api = 0,$lang='en') {
        $array_data['link'] = $val_cat->link;
        $array_data['user_id'] = $val_cat->user_id;
        $array_data['cost'] = $val_cat->cost;
        $array_data['eldwry'] = finalValueByLang($val_cat->eldwry->lang_name,$val_cat->eldwry->name,$lang);
        $array_data['type'] = $val_cat->alltypes->value_ar;
        $array_data['created_at'] = $val_cat->created_at->format('Y-m-d');
        return $array_data;
    }

}
