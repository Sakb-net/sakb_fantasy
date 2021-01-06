<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\PointUser;
use App\Models\Match;
use App\Models\GameSubstitutes;

class Subeldwry extends Model {
    protected $table = 'sub_eldwry';
    protected $fillable = [
        'update_by', 'eldwry_id', 'type_id', 'name','lang_name', 'link','opta_link','num_week', 'start_date', 'end_date', 'is_active','change_point'
    ];
//'start_change_date', 'end_change_date'
    public function user() {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function alltypes() {
        return $this->belongsTo(\App\Models\AllType::class, 'type_id');
    }

    public function eldwry() {
        return $this->belongsTo(\App\Models\Eldwry::class, 'eldwry_id');
    }
    
    public function matches() {
        return $this->hasMany(\App\Models\Match::class, 'sub_eldwry_id', 'id');  //
    }
    public function tags() {
        return $this->morphToMany(\App\Models\Tag::class, 'taggable');
    }

    public static function Add_Opta_Subeldwry($data,$eldwry_id,$def_lang='en') {
        $input=[
            'opta_link'=>$data['date'],
            'eldwry_id'=>$eldwry_id,
            'name'=>$data['date'],
            'lang_name'=>convertValueToJson($data['date'],$def_lang),
            'is_active'=>1,
            'start_date'=>get_date($data['date'],1),
        ];
        $data_found=static::foundDataTwoCondition('opta_link',$input['opta_link'],'eldwry_id',$eldwry_id);
        if(isset($data_found->id)){
            $subeldwry_id=$data_found->id;
            $data_found->update($input);
        }else{
            $data_type_found=static::foundData('eldwry_id',$eldwry_id);
            $type_id=4; //change
            if(!isset($data_type_found->id)){
                $type_id=3; //start
            }
            $num_week=1;
            $last_sub=static::foundData('eldwry_id',$eldwry_id,'id','DESC');
            if(isset($last_sub->id)){
                $num_week=$last_sub->num_week +1;
            }
            $input['type_id']=$type_id;
            $input['num_week']=$num_week;
            $input['link']=get_RandLink();
            $add_data=static::create($input);
            $subeldwry_id=$add_data['id'];
        }
        return $subeldwry_id;
    }

    public static function updateColum($id, $colum, $columValue) {
        $data = static::findOrFail($id);
        $data->$colum = $columValue;
        return $data->save();
    }

    public static function updateOrderColum($colum, $valueColum, $columUpdate, $valueUpdate) {
        return static::where($colum, $valueColum)->update([$columUpdate => $valueUpdate]);
    }

    public static function foundData($colum, $val,$op='=',$col_order='id',$val_order='asc') {
        return static::where($colum,$op,$val)->orderBy($col_order,$val_order)->first();
    }

    public static function foundDataTwoCondition($colum, $val,$colum2, $val2) {
        return static::where($colum, $val)->where($colum2, $val2)->first();
    }

    public static function All_foundData($colum, $val) {
       return static::where($colum, $val)->get();
    }
    
    public static function All_foundDataTwoCondition($colum, $val,$colum2, $val2) {
        return static::where($colum, $val)->where($colum2, $val2)->get();
    }
    public static function getSubeldwryByLargeEqual($eldwry_id,$is_active,$sub_eldwry_id,$op='>=',$date='') {
        if (empty($date)) {
            $date =date('Y-m-d', time()); //Y-m-d h:i:s a
        }
        return static::where('eldwry_id', $eldwry_id)->where('is_active', $is_active)->where('id',$op, $sub_eldwry_id)->where('start_date','<=',$date)->get();
    }
    public static function getSubeldwryByRang($eldwry_id,$is_active,$start_sub_eldwry_id,$end_sub_eldwry_id) {
        $data=static::where('eldwry_id', $eldwry_id)->where('is_active', $is_active);
        if($start_sub_eldwry_id > 0){
            $result=$data->where('id','>=',$start_sub_eldwry_id)->where('id','<',$end_sub_eldwry_id);
        }else{
            $result=$data->where('id','>=',$end_sub_eldwry_id);
        }
        $result=$data->get();
        return $result;
    }
    
    public static function get_SubeldwryID($id, $colum, $all = 0) {
        $Subeldwry = static::where('id', $id)->first();
        if ($all == 0) {
            return $Subeldwry->$colum;
        } else {
            return $Subeldwry;
        }
    }

    public static function get_SubeldwryCloum($colum = 'id', $val = '', $is_active = 1) {
        $Subeldwry = static::where($colum, $val)->where('is_active', $is_active)->orderBy('id', 'DESC')->first();
        return $Subeldwry;
    }

    public static function get_SubeldwryRow($value, $colum = 'id', $is_active = 1) {
        $Subeldwry = static::where($colum, $value)->where('is_active', $is_active)->first();
        return $Subeldwry;
    }

    public static function get_SubeldwryForCurrentWeek($colum1 = 'start_date', $colum2 = 'end_date') {
        $date = Carbon::now();
        $Subeldwry = static::where([
            [$colum1, '<=', $date],
            [$colum2, '>=', $date]])->first();
        return $Subeldwry;
    }

    public static function get_StartSubDwry($type_id = 3, $is_active = 1,$date = '') {
        if (empty($date)) {
            $date = date('Y-m-d h:i:s a', time()); //2019-09-30 00:00:18
        }
        //end_change_date
        $data = static::where('end_date', '>=', $date)->where('type_id', $type_id)->where('is_active', $is_active)->orderBy('id', 'DESC')->first();
        return $data;
    }

    public static function get_startFirstSubDwry($type_id = 3, $is_active = 1,$date = '',$ret_array=0) {
        $data = static::get_StartSubDwry(4,$is_active,$date);
        $inside_dwry = 0;
        if (!isset($data->id)) {
            $inside_dwry = 1;
            $data = static::get_StartSubDwry(3);
        }
        if($ret_array==1){
            return array('data'=>$data,'inside_dwry'=>$inside_dwry);
        }else{
            return $data;
        }
    }

    public static function get_CurrentSubDwry($is_active = 1,$date = '') {
        if (empty($date)) {
            $date = date('Y-m-d h:i:s a', time()); //2019-09-30 00:00:18
        }
        return static::where('end_date', '>=', $date)->where('is_active', $is_active)->orderBy('id', 'DESC')->first();
    }

    public static function get_BeforCurrentSubDwry($is_active = 1,$date = '',$limit = -1) {
        if (empty($date)) {
            $date = date('Y-m-d h:i:s a', time()); //2019-09-30 00:00:18
        }
        $data = static::where('end_date', '<', $date)->where('is_active', $is_active)->orderBy('id', 'DESC');
        if($limit < 0){
            $result=$data->first();
        }elseif($limit == 0){
            $result=$data->get();
        }else{
            $result=$data->limit($limit)->get();
        }
        return $result;
    }

    public static function get_BeforAndCurrentSubDwry($is_active = 1,$date = '',$limit=0) {
        if (empty($date)) {
            $date = date('Y-m-d h:i:s a', time()); //2019-09-30 00:00:18
        }
        $data = static::where('end_date', '<', $date)->orwhere('end_date', '>=', $date)->where('is_active', $is_active)->orderBy('id', 'DESC');
        if ($limit > 0) {
            $result = $data->limit($limit)->pluck('id', 'id')->toArray();
        } else {
            $result = $data->get();
        }
        return $result;
    }

    public static function current_DataSubeldwry($colum = 'id', $val = '', $is_active = 1,$date='',$count=0,$order='DESC') {
        if (empty($date)) {
            $date = date('Y-m-d h:i:s a', time()); //2019-09-30 00:00:18
        }
        $data = static::where($colum, $val)->where('is_active', $is_active)->where('end_date', '>=', $date)->orderBy('id', $order)->get();
        if ($count==1) {
            return count($data);
        }else{
            return $data;
        }
    }
    public static function NextPrev_DataSubeldwry($colum = 'link', $val = '', $is_active = 1,$type_get='prev',$date='') {
        $subeldwry = static::get_SubeldwryCloum($colum, $val, $is_active);
        if(isset($subeldwry->id)){
            if (empty($date)) {
                $date = date('Y-m-d h:i:s a', time());
            }
            $op='<';
            $order='DESC';
            if($type_get=='next'){
                $op='>';
                $order='ASC';
            }
            $result = static::where('id',$op,$subeldwry->id)->where('is_active', $is_active)->where('end_date', '<=', $date)->orderBy('id', $order)->first();
        }else{
            $result=$subeldwry;
        }
        return $result;
    }
    
    public static function count_DataSubeldwry($colum = 'id', $val = '', $is_active = 1,$count=0,$order='DESC') {
        $data = static::where($colum, $val)->where('is_active', $is_active)->orderBy('id', $order)->get();
        if ($count==1) {
            return count($data);
        }else{
            return $data;
        }
    }
    
    public static function get_DataSubeldwry($colum = 'id', $val = '', $is_active = 1,$order='DESC',$limit = 0,$offset=-1,$date='',$page=0,$team) {
        if (empty($date)) {
            $date =date('Y-m-d', time()); //Y-m-d h:i:s a
        }
        if($team != ''){
            $Subeldwry = static::where($colum, $val)->where('is_active', $is_active)->with(['matches' => function ($q) use($team) {
                $q->where('first_team_id',$team)->orWhere('second_team_id',$team);
            }]);
        }
        else{
            $Subeldwry = static::where($colum, $val)->where('is_active', $is_active);
        }
        if($page >0){
            $offset=($page * $limit)-$limit;
            if($offset<0){
                $offset=0;
            }
        }elseif($limit ==1){
            $order='ASC';
            $data = $Subeldwry->where('start_date', '>=', $date); //end_date
        }elseif(empty($page) || $page<=0){
            $order='DESC';
            $data = $Subeldwry->where('end_date', '<=', $date);
        }
        $data = $Subeldwry->orderBy('id', $order);  
        if ($limit > 0 && $offset==-1) {
            $data = $Subeldwry->limit($limit);
        }elseif ($limit > 0 && $offset>-1) {
            $data = $Subeldwry->limit($limit)->offset($offset);
        } 

        $data = $Subeldwry->get();
        return $data;
    }
    
    public static function getSubeldwry_ByDate($val_date='', $is_active = 1,$order='ASC') {
        $data = static::where('start_date', 'like', '%' . $val_date . '%')->where('is_active', $is_active)->orderBy('id', $order)->first();

        return $data;
    }
    public static function get_DataSubeldwry_Current($colum = 'id', $val = '', $is_active = 1,$order='DESC',$limit = 0,$offset=-1,$one_row=0) {
        //$current_date = date('Y-m-d H:i:s');
        $start_end_date=CurrentWeek();
        $start_week=$start_end_date['start_week'];
        $end_week=$start_end_date['end_week'];

        $Subeldwry = static::where('start_date','<=',$end_week)->where('is_active', $is_active)->orderBy('id', $order);
        if(!empty($colum)){
            $data = $Subeldwry->where($colum, $val);
        }    
        if($one_row==1){
            $data = $Subeldwry->first();
        }else{
            if ($limit > 0 && $offset==-1) {
                $data = $Subeldwry->limit($limit);
            }elseif ($limit > 0 && $offset>-1) {
                $data = $Subeldwry->limit($limit)->offset($offset);
            } 
            $data = $Subeldwry->get();
        }
        return $data;
    }

    public static function get_DataSubeldwry_CurrentWeek($colum = 'id', $val = '', $is_active = 1,$order='DESC',$limit = 0,$offset=-1,$one_row=0) {
        $start_end_date=CurrentWeek();
        $start_week=$start_end_date['start_week'];
        $end_week=$start_end_date['end_week'];
        $Subeldwry = static::where('end_date','>=',Carbon::now())->where($colum, $val)->where('is_active', $is_active)->orderBy('id', $order);
        if($one_row==1){
            $data = $Subeldwry->first();
        }else{
            if ($limit > 0 && $offset==-1) {
                $data = $Subeldwry->limit($limit);
            }elseif ($limit > 0 && $offset>-1) {
                $data = $Subeldwry->limit($limit)->offset($offset);
            } 
            $data = $Subeldwry->get();
        }
        return $data;
    }

    public static function SearchSubeldwry($search, $is_active = '', $limit = 0) {
        $data = static::Where('name', 'like', '%' . $search . '%')
                ->orWhere('lang_name', 'like', '%' . $search . '%')
                ->orWhere('opta_link', 'like', '%' . $search . '%')
                ->orWhere('link', 'like', '%' . $search . '%')
                ->orWhere('start_date', 'like', '%' . $search . '%')
                ->orWhere('end_date', 'like', '%' . $search . '%');

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
    public static function get_DataSubeldwry_CurrentPoint($colum = 'id', $val = '', $is_active = 1,$order='DESC',$limit = 0,$offset=-1,$one_row=0, $api = 0,$user_id=0,$lang='ar') {
       $data= static::get_DataSubeldwry_Current($colum, $val, $is_active,$order,$limit,$offset,$one_row);
        $all_data = [];
        foreach ($data as $key => $val_cat) {
            $all_data[] = static::single_DataSubeldwryPoint($val_cat, $api,$user_id,$lang);
        }
        return $all_data;
    }

    public static function get_DataSubeldwryUser($data, $api = 0,$lang='ar') {
        $all_data = [];
        foreach ($data as $key => $val_cat) {
            $all_data[] = static::single_DataSubeldwryUser($val_cat, $api,$lang);
        }
        return $all_data;
    }

    public static function single_DataSubeldwryUser($val_cat, $api = 0,$lang='ar') {
        $array_data['link'] = $val_cat->link;
        $array_data['num_week'] = $val_cat->num_week;
        $array_data['lang_num_week'] = trans('app.gameweek') .' '.$val_cat->num_week;
        $array_data['name'] =finalValueByLang($val_cat->lang_name,$val_cat->name,$lang) ;
        $array_data['change_point'] = $val_cat->change_point;
        $array_data['start_date'] = $val_cat->start_date; //->format('Y-m-d');
        $array_data['end_date'] = $val_cat->end_date; //->format('Y-m-d');
        $array_data['created_at'] = $val_cat->created_at->format('Y-m-d');
        return $array_data;
    }

    public static function single_DataSubeldwryPoint($val_cat, $api = 0,$user_id=0,$lang='ar') {
        if(isset($val_cat->id)){
            $array_data['link'] = $val_cat->link;
            $array_data['name'] =finalValueByLang($val_cat->lang_name,$val_cat->name,$lang) ;
            $array_data['num_week'] = $val_cat->num_week;
            $array_data['lang_num_week'] = trans('app.gameweek') .' '.$val_cat->num_week;
            // $array_data['cost'] = $val_cat->cost;
            $array_data['start_date'] = $val_cat->start_date; //->format('Y-m-d');
            $array_data['end_date'] = $val_cat->end_date; //->format('Y-m-d');
            $array_data['created_at'] = $val_cat->created_at->format('Y-m-d');
            $all_cal=PointUser::CalMath_Finaltotal_User($user_id,'sub_eldwry_id',$val_cat->id);
            $array_data['final_point'] =$all_cal['total_sum'];
            $array_data['avg_point'] = $all_cal['total_avg'];
            $array_data['heigh_point'] = $all_cal['total_max'];
            $array_data['sort_gwla'] =PointUser::Sort_Finaltotal_Subeldwry($user_id,'sub_eldwry_id',$val_cat->id,1,'user_id',1);
            $substitute=GameSubstitutes::sum_count_FinalPoints($user_id,'sub_eldwry_id',$val_cat->id);
            $array_data['transfer'] = (int) $substitute[0]->count_transfer;
            $array_data['transfer_points'] = (int) $substitute[0]->sum_points;
        }else{
            $array_data='';
        }    
        return $array_data;
    }

    public static function single_DataSubEldwry($val_cat, $lang = 'ar', $api = 0) {
        $data_val['start_date'] = $data_val['end_date'] = '';
        $array_date = explode(' ', $val_cat->start_date);
        if (isset($array_date[0])) {
            $data_val['start_date'] = $array_date[0];
        }
        $array_date = explode(' ', $val_cat->end_date);
        if (isset($array_date[0])) {
            $data_val['end_date'] = $array_date[0];
        }
        $data_val['start_date_day'] = day_lang_game($val_cat->start_date, $lang);
        $data_val['end_date_day'] = day_lang_game($val_cat->end_date, $lang);
        $data_val['num_week'] =$val_cat->num_week;
        $data_val['lang_num_week'] =trans('app.gameweek') .' '.$val_cat->num_week;
        return $data_val;
    }
}
