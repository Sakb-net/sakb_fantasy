<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DateTimeZone;
use DB;
use App\Models\Video;
use App\Models\Subeldwry;
use App\Models\Team;
use App\Models\Player;

class Match extends Model {

    protected $table = 'matches';
    protected $fillable = [
        'sub_eldwry_id','name','lang_name', 'update_by', 'first_team_id', 'second_team_id','winner_team_id','first_type','second_type', 'first_goon', 'second_goon','link','opta_link','description', 'date', 'time', 'video_id', 'is_active', 'image', 'coverageLevel','week','stage','venue','periodId'
    ];
//first_goon,second_type --- home,away

    public function user() {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function sub_eldwry() {
        return $this->belongsTo(\App\Models\Subeldwry::class, 'sub_eldwry_id', 'id');
    }

    public function teams_first() {
        return $this->belongsTo(\App\Models\Team::class, 'first_team_id', 'id');
    }

    public function teams_second() {
        return $this->belongsTo(\App\Models\Team::class, 'second_team_id', 'id');
    }
    public function DetailPlayerMatche() {
        return $this->hasMany(\App\Models\DetailPlayerMatche::class);
    }
    public function tags() {
        return $this->morphToMany(\App\Models\Tag::class, 'taggable');
    }
    
    public static function Add_Opta_match($data,$sub_eldwry_id,$def_lang='en',$colum_team='opta_link') {
        //for test only
        //$colum_team='link';
        //end for test
        $first_team=Team::foundData($colum_team,$data['homeContestantId']);
        $second_team=Team::foundData($colum_team,$data['awayContestantId']);
        //checkDataBool($data['active'])
        $input=[
            'opta_link'=>$data['id'],
            'sub_eldwry_id'=>$sub_eldwry_id,
            'name'=>$data['date'],
            'lang_name'=>convertValueToJson($data['date'],$def_lang),
            'is_active'=>1,
            'date'=>get_date($data['date'],1),
            'time'=>get_date($data['time'],2),
            'coverageLevel'=>$data['coverageLevel'],
            'first_team_id'=>isset($first_team->id) ? $first_team->id : null,
            'second_team_id'=>isset($second_team->id) ? $second_team->id : null,
            'first_type'=>'home',
            'second_type'=>'away',
        ];
        $data_found=static::foundDataTwoCondition('sub_eldwry_id',$sub_eldwry_id,'opta_link',$input[
            'opta_link']);
        if(isset($data_found->id)){
            $data_found->update($input);
        }else{
            $input['link']=get_RandLink();
            static::create($input);
        }
        return true;
    }
    public static function Addanotherlang($old_id, $new_id, $sub_eldwry_id, $video_id) {
        $lang_anothers = Match::DataLangAR($old_id);
        foreach ($lang_anothers as $keyLang => $valueLang) {
            $input = [];
            $old_match_lang = $valueLang->toArray();
            foreach ($old_match_lang as $key => $val_Lang) {
                if ($key != "id") {
                    $input[$key] = $val_Lang;
                }
            }
            if ($video_id != -1) {
                $input['video_id'] = $video_id + 1;
            }
            $input['first_goon'] = str_replace(' ', '_', $input['second_team_id'] . str_random(8));
            $input['file_id'] = 1;
            $input['update_by'] = $sub_eldwry_id;
            $new_match = Match::create($input);
        }
    }
    public static function updateOrderMoreData($colum, $valueColum,$week,$description,$stage,$venue,$first_goon,$second_goon,$winner='home',$periodId=0) {
        $match=static::where($colum, $valueColum)->first();
        $match_id=0;
        if(isset($match->id)){
            $match_id=$match->id;
            $winner_team_id=null;
            if(!empty($winner)){
                $winner_team_id=$match->first_team_id;
                if($winner=='draw'){
                    $winner_team_id=$match->second_team_id;
                }
            }
            $input=[
                'week'=>$week,
                'description'=>$description,
                'stage'=>$stage,
                'venue'=>$venue,
                'winner_team_id'=>$winner_team_id,
                'first_goon'=>$first_goon,
                'second_goon'=>$second_goon,
                'periodId'=>$periodId,
            ];
            $match->update($input);
        }
         return $match_id;
    }

    public static function updateColum($id, $colum, $columValue) {
        $data = static::findOrFail($id);
        $data->$colum = $columValue;
        return $data->save();
    }

    public static function updateOrderColum($colum, $valueColum, $columUpdate, $valueUpdate) {
        return static::where($colum, $valueColum)->update([$columUpdate => $valueUpdate]);
    }

    public static function updateOrderTwoColum($colum, $valueColum, $columUpdate, $valueUpdate, $columUpdate2, $valueUpdate2) {
        return static::where($colum, $valueColum)->update([$columUpdate => $valueUpdate,$columUpdate2 => $valueUpdate2]);
    }

    public static function updateOrderThreeColum($colum, $valueColum, $columUpdate, $valueUpdate, $columUpdate2, $valueUpdate2, $columUpdate3, $valueUpdate3) {
        return static::where($colum, $valueColum)->update([$columUpdate => $valueUpdate,$columUpdate2 => $valueUpdate2,$columUpdate3 => $valueUpdate3]);
    }

    public static function updateMatchTime($id, $sub_eldwry_id) {
        $match = static::findOrFail($id);
        $match->updateMatch_at = new Carbon();
        $match->updateMatch_by = $sub_eldwry_id;
        return $match->save();
    }

    public static function updateMatchViewCount($id) {
        return static::where('id', $id)->increment('view_count');
    }

    public static function countMatchUnRead() {
        return static::where('is_read', 0)->count();
    }

    public static function countMatchTypeUnRead($second_goon = 'chair') {
        return static::where('second_goon', $second_goon)->where('is_read', 0)->count();
    }
    public static function AllfoundData($colum, $val) {
        $data = static::where($colum, $val)->get();
        return $data;
    }

    public static function foundData($colum, $val) {
        $data = static::where($colum, $val)->first();
        return $data;
    }

    public static function foundDataTwoCondition($colum, $val,$colum2, $val2) {
        $data = static::where($colum, $val)->where($colum2, $val2)->first();
        return $data;
    }

    public static function deleteMatchParent($first_team_id, $second_goon) {
        if ($second_goon == 'match') {
            $matchs = static::where('first_team_id', $first_team_id)->get();
            foreach ($matchs as $key => $match) {
                if (isset($match->id)) {
                    static::deleteMatchParent($match->id, $match->second_goon);
                    static::find($match->id)->delete();
                }
            }
            Feature::deleteMatchBundle($first_team_id, 0);
            return 1;
        } else {
            return self::where('first_team_id', $first_team_id)->delete();
        }
    }

    public static function get_LastRow($second_goon, $lang='', $first_team_id = NULL, $colum, $data_order = 'video_id') {
        $match = Match::where('second_goon', $second_goon)->where('first_team_id', $first_team_id)->orderBy($data_order, 'DESC')->first();
        if (!empty($match)) {
            return $match->$colum;
        } else {
            return 0;
        }
    }

    public static function DataLangAR($lang_id, $all_lang = '', $limit = 0) {
        $data = static::where('id', $lang_id)->orderBy('id', 'DESC');
        if ($limit > 0) {
            $result = $data->paginate($limit);
        } elseif ($limit == -1) {
            $result = $data->pluck('id', 'id')->toArray();
        } else {
            $result = $data->get();
        }
        return $result;
    }

    public static function get_match($colum, $valColum, $lang = 'ar', $is_active = 1) {
        $data_one = static::where($colum, $valColum)->where('is_active', $is_active)->first();
        if (!isset($data_one->id)) {
            $data = [];
        }
        return $data;
    }

    public static function getMatchType($colum, $columvalue, $second_goon = 'match', $lang = 'ar', $columOrder = 'video_id', $columvalueOrder = 'ASC', $is_active = 1, $limit = 0) {
        $data = static::where($colum, $columvalue)->where('is_active', $is_active);
        $data->where('second_goon', $second_goon)->orderBy($columOrder, $columvalueOrder); //with('user')->  //orderBy('id', 'DESC')->  
        if ($limit > 6) {
            $result = $data->paginate($limit);
        } elseif ($limit <= 0) {
            $result = $data->get();
        } else {
            $result = $data->limit($limit)->get();
        }

        return $result;
    }

    public static function getMatchs($colum, $columvalue, $second_goon, $first_team_id = NULL, $parent_state = '=', $limit = 0) {
        $data = static::where($colum, $columvalue)
                ->where('second_goon', $second_goon);
        if ($first_team_id != -1) {
            $result = $data->where('first_team_id', $parent_state, $first_team_id);
        }
        $result = $data->orderBy('id', 'asc'); //with('user')->  //orderBy('id', 'DESC')->  
        if ($limit > 0) {
            $result = $data->paginate($limit);
        } else {
            $result = $data->get();
        }
        return $result;
    }

    public static function getMatchsNotArray($colum, $columvalue, $second_goon = 'match', $limit = 0, $lang, $is_active = '', $col_val = 'id', $offset = 0) {
        $data = static::whereNotIn($colum, $columvalue)->where('second_goon', $second_goon);
        if (!empty($is_active)) {
            $result = $data->where('is_active', $is_active);
        }
        if ($limit > 15) {
            $result = $data->paginate($limit);
        } elseif ($limit > 0) {
            $result = $data->limit($limit)->offset($offset)->pluck($col_val, $col_val)->toArray();
        } elseif ($limit == -1) {
            $result = $data->pluck($col_val, $col_val)->toArray();
        } else {
            $result = $data->get();
        }
        return $result;
    }

    public static function getMatchsArray($colum, $columvalue, $limit = 0, $lang='', $is_active = '') {
        $data = static::whereIn($colum, $columvalue);
        if ($is_active == 1 || $is_active == 0) {
            $result = $data->where('is_active', $is_active);
        }
        $result = $data->orderBy('id', 'asc');
        if ($limit > 0) {
            $result = $data->paginate($limit);
        } elseif ($limit == -1) {
            $result = $data->pluck($col_val, $col_val)->toArray();
        } else {
            $result = $data->get();
        }
        return $result;
    }

    public static function matchData($id, $column = 'second_team_id') {
        $match = static::where('id', $id)->first();
        if (isset($match)) {
            return $match->$column;
        } else {
            return '';
        }
    }

    public static function matchDataLang($lang_id, $lang='', $column = '') {
        $match = static::where('id', $lang_id)->first();
        if (!empty($column) && isset($match->$column)) {
            return $match->$column;
        } else {
            return$match;
        }
    }

    public static function matchDataUser($id, $column = '') {
        $match = static::with('user')->where('id', $id)->first();
        if (!empty($column)) {
            return $match->$column;
        } else {
            return $match;
        }
    }

    public static function get_MatchTeamSubeldwry($sub_eldwry_id, $team_id, $is_active = 1) {
        $data = Match::Where([['sub_eldwry_id',$sub_eldwry_id], ['first_team_id', $team_id], ['is_active', $is_active]])->orWhere([['sub_eldwry_id',$sub_eldwry_id], ['second_team_id', $team_id], ['is_active', $is_active]])->first();
        return $data;
    }
    public static function get_MatchLargeFromId($match_id,$is_active=1,$current_date='') {
        $current_date = date('Y-m-d H:i:s');
        return static::where('id', '>', $match_id)->where('date', '<=', $current_date)->where('is_active', $is_active)->get();
    }
    public static function get_MatchNextId($match_id,$team_id,$is_active=1, $col_order = 'id', $val_order = 'ASC') {
        return static::where('id', '>', $match_id)->Where('first_team_id', $team_id)->orWhere('second_team_id', $team_id)->where('is_active', $is_active)->orderBy($col_order, $val_order)->first();
    }
    
    public static function get_MatchActiveFirst($is_active, $second_goon_time = 'next', $col_order = 'id', $val_order = 'ASC', $second_goon = 'match', $lang = 'ar') {
        $data = Match::where('second_goon', $second_goon)
                ->where('is_active', $is_active);
        if (!empty($second_goon_time)) {
            $current_date = date('Y-m-d H:i:s');
            if ($second_goon_time == 'next') {
                $result = $data->where('date', '>=', $current_date);
            } elseif ($second_goon_time == 'prev') {
                $result = $data->where('date', '<', $current_date);
            }
        }
        $result = $data->orderBy($col_order, $val_order)->first();
        return $result;
    }

    public static function get_MatchActive($is_active, $column = '', $columnValue = '', $lang = '', $second_goon_time = '', $array = 0, $limit = 0, $offset = -1) {
        $data = static::with('user');
        if (!empty($second_goon_time)) {
            $current_date = date('Y-m-d H:i:s');
            if ($second_goon_time == 'next') {
                $result = $data->where('date', '>=', $current_date);
            } elseif ($second_goon_time == 'prev') {
                $result = $data->where('date', '<', $current_date);
            }
        }
        if ($is_active == 1 || $is_active == 0) {
            $result = $data->where('is_active', $is_active);
        }
        if (!empty($column)) {
            if ($array == 1) {
                $result = $data->whereIn($column, $columnValue);
            } else {
                $result = $data->where($column, $columnValue);
            }
        }
        if ($limit > 0 && $offset > -1) {
            $result = $data->limit($limit)->offset($offset)->get();
        } elseif ($limit > 0 && $offset == -1) {
            $result = $data->paginate($limit);
        } else {
            $result = $data->get();
        }
        return $result;
    }
    
    public static function get_MatchActiveThisWeek($is_active,$limit = 0, $offset = -1,$lang = '') {
        $now = Carbon::now();
        $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i');
        $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i');
        
        $data = static::whereBetween(DB::raw('date'), [$weekStartDate, $weekEndDate]);
        if ($is_active == 1 || $is_active == 0) {
            $result = $data->where('is_active', $is_active);
        }
        if ($limit > 0 && $offset > -1) {
            $result = $data->limit($limit)->offset($offset)->get();
        } elseif ($limit > 0 && $offset == -1) {
            $result = $data->paginate($limit);
        } else {
            $result = $data->get();
        }
        return $result;
    }
    public static function get_NextWeekMatchForPlayer($team_id,$is_active,$limit = 0, $offset = -1,$lang = '') {
        $nowForStart = Carbon::now();
        $nowForEnd = Carbon::now();
        $weekStartDate = $nowForStart->startOfWeek()->addDays(6)->format('Y-m-d H:i');
        $weekEndDate = $nowForEnd->endOfWeek()->addDays(6)->format('Y-m-d H:i');
        
        $data = static::whereBetween(DB::raw('date'), [$weekStartDate, $weekEndDate]);
        $result = $data->where('first_team_id', $team_id)->orWhere('second_team_id', $team_id);
        if ($is_active == 1 || $is_active == 0) {
            $result = $data->where('is_active', $is_active);
        }
        if ($limit > 0 && $offset > -1) {
            $result = $data->limit($limit)->offset($offset)->get();
        } elseif ($limit > 0 && $offset == -1) {
            $result = $data->paginate($limit);
        } else {
            $result = $data->get();
        }
        return $result;
    }
    public static function get_NextSubEldwryMatchForPlayer($sub_eldwry_id,$team_id,$is_active,$lang = 'ar') {
        $result = static::Where('sub_eldwry_id', $sub_eldwry_id)->Where('is_active', $is_active)->Where('first_team_id', $team_id)->orWhere('second_team_id', $team_id)->first();
        return $result;
    }
    public static function SearchMatch($search, $second_goon = 'match', $is_active = '', $limit = 0) {
        $data = static::with('user')->Where('second_team_id', 'like', '%' . $search . '%')
                ->orWhere('first_goon', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%')
                ->orWhere('content', 'like', '%' . $search . '%')
                ->orWhere('image', 'like', '%' . $search . '%')
                ->orWhere('sub_eldwry_id', 'like', '%' . $search . '%');
        if (!empty($second_goon)) {
            $result = $data->where('second_goon', $second_goon);
        }
        if ($is_active == 1 || $is_active == 0) {
            $result = $data->where('is_active', $is_active);
        }
        $result = $data->orderBy('id', 'DESC');
        if ($limit > 0) {
            $result = $data->paginate($limit);
        } elseif ($limit == -1) {
            $result = $data->pluck('id', 'id')->toArray();
        } elseif ($limit == -2) {
            $result = $data->pluck('second_goon', 'id')->toArray();
        } else {
            $result = $data->get();
        }
        return $result;
    }

    public static function lastMonth($month, $date, $second_goon = 'match') {

        $count = static::select(DB::raw('COUNT(*)  count'))->where('second_goon', $second_goon)->whereBetween(DB::raw('created_at'), [$month, $date])->get();
        return $count[0]->count;
    }

    public static function lastWeek($week, $date, $second_goon = 'match') {

        $count = static::select(DB::raw('COUNT(*)  count'))->where('second_goon', $second_goon)->whereBetween(DB::raw('created_at'), [$week, $date])->get();
        return $count[0]->count;
    }

    public static function lastDay($day, $date, $second_goon = 'match') {
        $count = static::select(DB::raw('COUNT(*)  count'))->where('second_goon', $second_goon)->whereBetween(DB::raw('created_at'), [$day, $date])->get();
        return $count[0]->count;
    }

    public static function MatchOrderUserView($lang, $sub_eldwry_id, $second_goon = 'match', $is_active = 1) {
        $data = Match::select(DB::raw('sum(matchs.view_count) AS view_count'))
                ->where('second_goon', $second_goon)->where('sub_eldwry_id', $sub_eldwry_id)
                ->where('is_active', $is_active)
                ->get();
        if (empty($data[0]->view_count)) {
            $data_view_count = 0;
        } else {
            $data_view_count = $data[0]->view_count;
        }
        return $data_view_count;
    }

    public static function MatchUser($lang, $sub_eldwry_id, $second_goon = 'match', $is_active = 1, $count = 1) {
        $data = Match::where('second_goon', $second_goon)->where('sub_eldwry_id', $sub_eldwry_id);
        if ($is_active == 1 || $is_active == 0) {
            $result = $data->where('is_active', $is_active);
        }
        $result = $data->get();
        if ($count == -1) {
            return $result->pluck('id', 'id')->toArray();
        } elseif ($count == 1) {
            return count($result);
        } else {
            return $result;
        }
    }
    public static function getCurrentMatch_BYDate($date, $time='') {
        //date time
        $data = static::where('date', 'like', '%' . $date . '%')->get();
        return $data;
    }

//**************************************************************

    public static function get_DataGroup($data, $lang = 'ar', $api = 0,$limit=0,$final_offset=-1) {
        $all_data = [];
        foreach ($data as $key => $val_cat) {
            $data_val = Subeldwry::single_DataSubEldwry($val_cat, $lang , $api,$limit,$final_offset);
            $match_group = static::get_DataMatch($val_cat->matches, $lang, $api);
            if (!empty($match_group)) {
                $data_val['match_group'] = $match_group;
                $all_data[] = $data_val;
            }
        }
        return $all_data;
    }
    
    public static function get_DataGroup_week($all_subdwry,$data, $lang = 'ar', $api = 0) {
        $all_data = $match_group=[];
       if(isset($all_subdwry->id)){
            $data_val = Subeldwry::single_DataSubEldwry($all_subdwry, $lang , $api);
            $match_group = static::get_DataMatch($data, $lang, $api);
            if (!empty($match_group)) {
                $data_val['match_group'] = $match_group;
                $all_data[] = $data_val;
            }
        }
        return $all_data;
    }
   
    public static function get_DataMatch($data, $lang = 'ar', $api = 0) {
        $all_data = [];
        foreach ($data as $key => $val_cat) {
            $all_data[] = static::single_DataMatch($val_cat, $lang, $api);
        }
        return $all_data;
    }

    public static function single_DataMatch($val_cat, $lang = 'en', $api = 0) {
        $array_data['link_match'] = $val_cat->link;
        $array_data['description'] =finalValueByLang($val_cat->description,'',$lang);
        $array_date = explode(' ', $val_cat->date);
        $array_data['date'] = '';
        if (isset($array_date[0])) {
            $array_data['date'] = $array_date[0];
        }
        $array_data['date_day'] = day_lang_game($val_cat->date, $lang);
        $array_data['time'] = $val_cat->time;
        $array_data['first_goon'] = $val_cat->first_goon;
        $array_data['second_goon'] = $val_cat->second_goon;
        $array_data['name_first'] = $array_data['name_second'] =$array_data['link_first'] = $array_data['link_second'] = '';
        $array_data['short_first'] = $val_cat->teams_first->code;
        $array_data['short_second'] = $val_cat->teams_second->code;
        $array_data['id_first'] = $val_cat->teams_first->id;
        $array_data['id_second'] = $val_cat->teams_second->id;        
        $array_data['id'] = $val_cat->id;
        $array_data['image_first'] = $array_data['image_second'] = '/images/logo/logo.png';
        if(isset($val_cat->teams_first->id)){
            $array_data['name_first'] =finalValueByLang($val_cat->teams_first->lang_name,$val_cat->teams_first->name,$lang);
            $array_data['link_first'] = $val_cat->teams_first->link;
            if (!empty($val_cat->teams_first->image)) {
                $array_data['image_first'] = finalValueByLang($val_cat->teams_first->image,'',$lang);
            }
        }
        if(isset($val_cat->teams_second->id)){
            $array_data['name_second'] =finalValueByLang($val_cat->teams_second->lang_name,$val_cat->teams_second->name,$lang);
            $array_data['link_second'] = $val_cat->teams_second->link;
            if (!empty($val_cat->teams_second->image)) {
                $array_data['image_second'] =finalValueByLang($val_cat->teams_second->image,'',$lang);
            }
        }
        //details and bouns
        $get_details=new \App\Repositories\PlayerDetailsMatchRepository();
        $array_data['details']=$get_details->DetailsPlayerMatch($val_cat);
        return $array_data;
    }

    public static function get_MatchData($colum = 'id', $val = '', $lang = 'ar', $api = 0) {
        $all_data = '';
        $data = static::get_MatchDataCloum($colum, $val);
        if (isset($data->id)) {
            $all_data = static::single_MatchDataUser($data, $lang, $api);
        }
        return $all_data;
    }

    public static function get_MatchDataCloum($colum = 'id', $val = '') {
        $Player = static::where($colum, $val)->orderBy('id', 'DESC')->first();
        return $Player;
    }

    public static function single_MatchDataUser($val_cat, $lang = 'ar', $api = 0) {
        $array_data['first_team_id'] = $val_cat->first_team_id;
        $array_data['second_team_id'] = $val_cat->second_team_id;
        $array_data['week'] = $val_cat->week;
        $array_data['first_goon'] = $val_cat->first_goon;
        $array_data['second_goon'] = $val_cat->second_goon;
        return $array_data;
    }
//*********************Not use*****************************************
    public static function get_StateBooking($match) {
        $ok_booking = 0;
        $msg_booking = trans('app.finish_ticket_booking');
        $start_booking = get_date($match['start_booking']);
        $end_booking = get_date($match['end_booking']);
        $current_date = get_date(date("Y-m-d"));
        if ($start_booking <= $current_date && $current_date <= $end_booking) {
            $ok_booking = 1;
            $msg_booking = '';
        } elseif ($start_booking > $current_date) {
            $ok_booking = 2;
            $msg_booking = trans('app.notstart_ticket_booking');
        }
        return array('ok_booking' => $ok_booking, 'msg_booking' => $msg_booking);
    }

    public static function get_DateTime($date, $time) {
        $second_goon_time = 'prev';
        if (!empty($date) && !empty($time)) {
            $current_date = date('Y-m-d H:i:s');
            if ($date >= $current_date) {
                $second_goon_time = 'next';
            }
            $date_time = explode(' ', $date);
            $date = $date_time[0];
            $time = substr($date_time[1], 0, -3) . $time;
        }
        return array('date' => $date, 'time' => $time, 'second_goon_time' => $second_goon_time);
    }


    public static function getFollowingMatches($followingTeams = [], $lang = '', $limit = 0, $offset = -1) {
        $data = static::whereIn('first_team_id', $followingTeams)->orWhereIn('second_team_id',$followingTeams);
        $result = $data->orderBy('id', 'DESC');
        if ($limit > 0 && $offset > -1) {
            $result = $data->limit($limit)->offset($offset)->get();
        } elseif ($limit > 0 && $offset == -1) {
            $result = $data->paginate($limit);
        } else {
            $result = $data->get();
        }
        return $result;
    }

}