<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Game;
use DB;
class Eldwry extends Model {
    protected $table = 'eldwry';
    protected $fillable = [
        'update_by','name','lang_name','competition_id', 'link', 'cost', 'start_date', 'end_date','opta_link','is_active','includesVenues','includesStandings'
    ];


    public function user() {
        return $this->belongsToMany(\App\Models\User::class);
    }
    public function tags() {
        return $this->morphToMany(\App\Models\Tag::class, 'taggable');
    }
    
    public function team() {
        return $this->hasMany(\App\Models\Team::class);
    }
    public static function Add_Opta_Eldwry($tournamentCalendar,$competition_id,$def_lang='en') {
        $data_found=static::foundData('opta_link',$tournamentCalendar['id']);
        if(isset($data_found->id)){
            $id=$data_found->id;
        }else{
            $id=static::Insert_Opta_Eldwry($tournamentCalendar,$competition_id,$def_lang);
        }
        return $id;
    }
    public static function Insert_Opta_Eldwry($tournamentCalendar,$competition_id,$def_lang='en') {
        $includesStandings=isset($tournamentCalendar['includesStandings']) ? $tournamentCalendar['includesStandings'] : 0;
        $includesVenues=isset($tournamentCalendar['includesVenues']) ? $tournamentCalendar['includesVenues'] : 0;
        $tournment_data=[
            'competition_id'=>$competition_id,
            'name'=>$tournamentCalendar['name'],
            'lang_name'=>convertValueToJson($tournamentCalendar['name'],$def_lang),
            'link'=>get_RandLink(),
            'cost'=>100,
            'start_date'=>get_date($tournamentCalendar['startDate'],1),
            'end_date'=>get_date($tournamentCalendar['endDate'],1),
            'opta_link'=>$tournamentCalendar['id'],
            'is_active'=>checkDataBool($tournamentCalendar['active']),
            'includesStandings'=>checkDataBool($includesStandings),
            'includesVenues'=>checkDataBool($includesVenues),
        ];
        $add=static::create($tournment_data);
        return $add['id'];
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

    public static function get_EldwryID($id, $colum, $all = 0) {
        $Eldwry = static::where('id', $id)->first();
        if ($all == 0) {
            return $Eldwry->$colum;
        } else {
            return $Eldwry;
        }
    }

    public static function get_EldwryRow($id, $colum = 'id', $is_active = 1) {
        $Eldwry = static::where($colum, $id)->where('is_active', $is_active)->first();
        return $Eldwry;
    }

    public static function get_EldwryCloum($colum = 'id', $val = '', $is_active = 1) {
        $Eldwry = static::where($colum, $val)->where('is_active', $is_active)->orderBy('id', 'DESC')->first();
        return $Eldwry;
    }
    public static function get_currentDwry($start_date='',$end_date='',$is_active = 1,$order='ASC',$competition_id=1) {
        if(empty($start_date)){
            $start_date=date("Y",strtotime("-1 year")).'-01-01';
        }
        if(empty($end_date)){
            $end_date=date('Y').'-12-31';
        }
        $Eldwry = static::where('competition_id', $competition_id)->where('is_active', $is_active)->whereBetween(DB::raw('end_date'), [$start_date, $end_date])->orderBy('id',$order)->first();
        return $Eldwry;
    }

    public static function SearchEldwry($search, $is_active = '', $limit = 0) {
        $data = static::Where('name', 'like', '%' . $search . '%')
                ->orWhere('lang_name', 'like', '%' . $search . '%')
                ->orWhere('cost', 'like', '%' . $search . '%')
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

    public static function get_CurrentEldwryGame($user_id, $api = 0) {
        $game=[];
        $eldwry = Eldwry::get_currentDwry();
        if (isset($eldwry->id)) {
            $game = Game::checkregisterDwry($user_id, $eldwry->id, 1);
        }
        return array('eldwry'=>$eldwry,'game'=>$game);
    }

//********************function ************************
    public static function get_DataEldwryUser($data, $api = 0) {
        $all_data = [];
        foreach ($data as $key => $val_cat) {
            $all_data[] = static::single_DataEldwryUser($val_cat, $api);
        }
        return $all_data;
    }

    public static function single_DataEldwryUser($val_cat, $api = 0,$lang='en') {
        $array_data['link'] = $val_cat->link;
        $array_data['name'] =finalValueByLang($val_cat->lang_name,$val_cat->name,$lang);
        $array_data['cost'] = $val_cat->cost;
        $array_data['start_date'] = $val_cat->start_date;//->format('Y-m-d');
        $array_data['end_date'] = $val_cat->end_date;//->format('Y-m-d');
        $array_data['created_at'] = $val_cat->created_at->format('Y-m-d');
        return $array_data;
    }

}
