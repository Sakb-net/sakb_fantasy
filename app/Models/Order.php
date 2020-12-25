<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class Order extends Model {

    protected $fillable = [
        'user_id', 'add_by', 'sub_eldwry_id', 'name', 'link', 'type_id', 'source_pay', 'method_pay', 'type_request', 'discount', 'cost',
        'code_substitute','checkoutId', 'transactionId',
        'is_bill', 'is_read', 'is_active', 'code', 'description'
    ];

    //type_request-->accept,request,....
    //method_pay-->hyperpay , free
    //source_pay-->site /app  (site - ios- android -admin)

    public function user() {
        return $this->belongsToMany(\App\Models\User::class,'user_id');
    }
    
    public function subeldwry() {
        return $this->belongsTo(\App\Models\Subeldwry::class,'sub_eldwry_id');
    }
        
    public function alltypes() {
        return $this->belongsTo(\App\Models\AllType::class, 'type_id');
    }

    public static function Make_order_link($data_name = '') {
        $data_name = \Illuminate\Support\Str::limit($data_name, 20);
        $order_time = str_replace(' ', '_', $data_name . time() . str_random(8));
        $order_link = rand(5, 90) . substr(md5($order_time), 3, 20);
        return $order_link;
    }

    public static function insertOrder($user_id,$sub_eldwry_id,$add_by = NULL, $type_id = '',$data_name = null, $method_pay = 'hyperpay', $source_pay = 'site', $cost = '',$discount = 0,$code_substitute=null, $checkoutId = NULL, $transactionId = NULL, $type_request = 'request',  $is_active = 0) {
       $data=static::get_DataByFourCondition('user_id', $user_id,'code_substitute', $code_substitute,'is_active',0,'checkoutId',null);
        if(!isset($data->id)){
            $input['user_id'] = $user_id;
            $input['add_by'] = $add_by;
            $input['sub_eldwry_id'] = $sub_eldwry_id;
            $input['name'] = $data_name;
            $input['link'] = static::Make_order_link($data_name);
            $input['type_id'] = $type_id;
            $input['type_request'] = $type_request;
            $input['source_pay'] = $source_pay;
            $input['method_pay'] = $method_pay;
            $input['cost'] = conditionPrice($cost);
            $input['discount'] = conditionDiscount($discount);
            $input['code_substitute'] = $code_substitute;
            $input['is_active'] = $is_active;
            $input['transactionId'] = $transactionId;
            $input['checkoutId'] = $checkoutId;
            $order = static::create($input);
        }else{
           $order=$data->toArray();
        }
        return $order;
    }
    public static function updateOrderColumnID($id, $column, $column_value) {
        $order = static::findOrFail($id);
        $order->$column = $column_value;
        return $order->save();
    }

    public static function updateOrderColum($colum, $valueColum, $columUpdate, $valueUpdate) {
        return static::where($colum, $valueColum)->update([$columUpdate => $valueUpdate]);
    }

    public static function updateOrderColumTwoUpdate($colum, $valueColum, $columUpdate, $valueUpdate, $columUpdate2, $valueUpdate2) {
        return static::where($colum, $valueColum)->update([$columUpdate => $valueUpdate, $columUpdate2 => $valueUpdate2]);
    }

    public static function updateOrderTwoColum($colum, $valueColum, $columTwo, $valueColumTwo, $columUpdate, $valueUpdate) {
        return static::where($colum, $valueColum)->where($columTwo, $valueColumTwo)->update([$columUpdate => $valueUpdate]);
    }

    public static function updateOrder($colum, $arrayOrder_id, $columUpdate, $valueUpdate) {
        $result = Order::where($colum, $all_array_id)->update([$columUpdate => $valueUpdate]);
        return $result;
    }

    public static function updateArrayOrder($colum, $arrayOrder_id, $columUpdate, $valueUpdate) {
        $all_array_id = array_values($arrayOrder_id);
        $result = Order::whereIn($colum, $all_array_id)->update([$columUpdate => $valueUpdate]);
        return $result;
    }

    public static function deleteOrderUser($user_id, $sub_eldwry_id = 0) {
        if ($sub_eldwry_id == 0) {
            return self::where('user_id', $user_id)->delete();
        } else {
            return self::where('sub_eldwry_id', $sub_eldwry_id)->where('user_id', $user_id)->delete();
        }
    }

    public static function deleteArrayOrder($colum, $arrayOrder_id) {
        $all_array_id = array_values($arrayOrder_id);
        $result = Order::whereIn($colum, $all_array_id)->delete();
        return $result;
    }
    
    public static function All_checkout_UpdatePayment($user_id, $checkoutId,$is_active, $method_pay, $type_request,$description = null, $code = null) {
        return static::where('checkoutId', $checkoutId)->where('user_id', $user_id)->update(['type_request' => $type_request, 'method_pay' => $method_pay, 'is_active' => $is_active,'description' => $description, 'code' => $code]);
    }

    public static function All_checkoutFail_UpdatePayment($user_id, $checkoutId, $is_active = 0,$description = null, $code = null) {
        return static::where('type_request', '<>', 'accept')->where('checkoutId', $checkoutId)->where('user_id', $user_id)->update(['is_active' => $is_active,'description' => $description, 'code' => $code]);
    }

    public static function get_DataByThreeCondition($colum1, $value1,$colum2, $value2,$colum3, $value3) {
        return static::where($colum1, $value1)->where($colum2, $value2)->where($colum3, $value3)->orderBy('id', 'DESC')->first();
    }
    public static function get_DataByFourCondition($colum1, $value1,$colum2, $value2,$colum3, $value3,$colum4, $value4) {
        return static::where($colum1, $value1)->where($colum2, $value2)->where($colum3, $value3)->where($colum4, $value4)->orderBy('id', 'DESC')->first();
    }
    public static function SearchOrder($search, $is_share = '', $is_active = '', $limit = 0) {
        $data = static::Where('name', 'like', '%' . $search . '%')
                ->orWhere('link', 'like', '%' . $search . '%')
                ->orWhere('type_id', 'like', '%' . $search . '%')
                ->orWhere('sub_eldwry_id', 'like', '%' . $search . '%')
                ->orWhere('add_by', 'like', '%' . $search . '%')
                ->orWhere('user_id', 'like', '%' . $search . '%')
                ->orWhere('cost', 'like', '%' . $search . '%')
                ->orWhere('discount', 'like', '%' . $search . '%')
                ->orWhere('type_request', 'like', '%' . $search . '%')
                ->orWhere('source_pay', 'like', '%' . $search . '%')
                ->orWhere('order_id', 'like', '%' . $search . '%')
                ->orWhere('method_pay', 'like', '%' . $search . '%');

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

    public static function get_LastRow($colum_value, $colum, $data_order = 'id') {
        $order = Order::where($colum_value, $colum_value)->orderBy($data_order, 'DESC')->first();
        if (!empty($order)) {
            return $order->$colum;
        } else {
            return 0;
        }
    }

    public static function CountOrderMore() {
        $data = Order::select(DB::raw('orders.sub_eldwry_id, count(orders.sub_eldwry_id) AS count_post'))
                ->groupBy('orders.sub_eldwry_id')
                ->get();
        return $data;
    }

    public static function countOrderUserShare($user_id, $is_share = 1, $is_active = 1, $limit = 0, $is_bill = -1) {
        $data = static::where('user_id', $user_id)->where('is_active', $is_active);
        if ($is_bill != -1) {
            $result = $data->where('is_bill', $is_bill);
        }
        if ($limit == -1) {
            $result = $data->pluck('sub_eldwry_id', 'sub_eldwry_id')->toArray();
        } elseif ($limit == -2) {
            $result = $data->count();
        } else {
            $result = $data->get();
        }
        return $result;
    }

    public static function countOrderUnRead() {
        return static::where('is_read', 0)->count();
    }

    public static function countOrderTypeUnRead($type_request = 'accept') {
        return static::where('type_request', $type_request)->where('is_read', 0)->count();
    }

    public static function CountOrder($cost, $stateOrder, $type_request, $is_active, $is_share) {
        $count = Order::where('cost', $stateOrder, $cost)->where('type_request', $type_request)->where('is_active', $is_active)->count();
        return $count;
    }

    public static function lastMonth($month, $date, $cost, $stateOrder, $type_request, $is_active, $is_share) {
        $count = static::select(DB::raw('COUNT(*)  count'))->whereBetween(DB::raw('created_at'), [$month, $date])->where('cost', $stateOrder, $cost)->where('type_request', $type_request)->where('is_active', $is_active)->get();
        return $count[0]->count;
    }

    public static function lastWeek($week, $date, $cost, $stateOrder, $type_request, $is_active, $is_share) {

        $count = static::select(DB::raw('COUNT(*)  count'))->whereBetween(DB::raw('created_at'), [$week, $date])->where('cost', $stateOrder, $cost)->where('type_request', $type_request)->where('is_active', $is_active)->get();
        return $count[0]->count;
    }

    public static function lastDay($day, $date, $cost, $stateOrder, $type_request, $is_active, $is_share) {
        $count = static::select(DB::raw('COUNT(*)  count'))->whereBetween(DB::raw('created_at'), [$day, $date])->where('cost', $stateOrder, $cost)->where('type_request', $type_request)->where('is_active', $is_active)->get();
        return $count[0]->count;
    }


}
