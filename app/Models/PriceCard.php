<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Game;
use DB;
class PriceCard extends Model {

    protected $table = 'price_cards';
    protected $fillable = [
        'add_by','sub_eldwry_id','type_id', 'link', 'cost', 'cost_condtion', 'op_condtion'
    ];

//type_id=12 -->card_gold
//type_id=13 --->card_silver (card_gray)    
    public function user() {
        return $this->belongsToMany(\App\Models\User::class,'add_by');
    }
    public function subeldwry() {
        return $this->belongsTo(\App\Models\Subeldwry::class,'sub_eldwry_id');
    }
        
    public function alltypes() {
        return $this->belongsTo(\App\Models\AllType::class, 'type_id');
    }

    public static function InsertData($sub_eldwry_id, $type_id=12, $cost=50,$cost_condtion=0,$op_condtion=null) {
        $input=[
            'sub_eldwry_id'=>$sub_eldwry_id,
            'type_id'=>$type_id,
            'link'=>generateRandomValue(1),
            'cost'=>$cost,
            'cost_condtion'=>$cost_condtion,
            'op_condtion'=>$op_condtion,
        ];
        return static::create($input);
    }

    public static function updateColum($id, $colum, $columValue) {
        $data = static::findOrFail($id);
        $data->$colum = $columValue;
        return $data->save();
    }

    public static function updateOrderColum($colum, $valueColum, $columUpdate, $valueUpdate) {
        return static::where($colum, $valueColum)->update([$columUpdate => $valueUpdate]);
    }


    public static function get_priceCardSubeldwry($user, $sub_eldwry_id,$colum='') {
        $data=static::where('sub_eldwry_id', $sub_eldwry_id)->first();
        if(!empty($colum)){
            if(isset($data->$colum)){
                return $data->$colum;
            }else{
                return 0;
            }    
        }else{
            return $data;
        }
    }


}
