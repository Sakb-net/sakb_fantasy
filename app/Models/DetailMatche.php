<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Team;
use App\Models\Player;

class DetailMatche extends Model {

    protected $table = 'detail_matches';
    protected $fillable = [
        'update_by','match_id','type','type_state','team_id','player_id','assist_player_id','off_player_id','location_id','type','goon', 'penalties', 'red_cart', 'yellow_cart','period','outcome','x','y','keyPass','date','start_date','end_date','lengthMin','lengthSec','optaEventId','reason'
    ];


// type ----- period,goal,event,card,substitute,penalties

    public function user() {
        return $this->belongsTo(\App\Models\User::class);
    }
    public function alltypes() {
        return $this->belongsTo(\App\Models\AllType::class);
    }
    public function locatioPlayer() {
        return $this->belongsTo(\App\Models\LocationPlayer::class);
    }
    public function player() {
        return $this->belongsTo(\App\Models\Player::class, 'player_id');
    }

    public function off_player() {
        return $this->belongsTo(\App\Models\Player::class, 'off_player_id');
    }
    public function match() {
        return $this->belongsTo(\App\Models\Match::class);
    }
    public static function AddArray_Opta_DetailMatch($data,$type,$match_id,$player_id=null,$def_lang='en') {
        // $coun_data=count($data);
        foreach ($data as $key => $data_var) {
            // if($coun_data==1){
            //     $data_var=$value;
            // }else{
            //     $data_var=$value['@attributes'];
            // }
            static::Add_Opta_DetailMatch($data_var,$type,$match_id,$player_id,$def_lang);
        }
        return true;
    }

    public static function AddGoal_Opta_DetailMatch($data,$type,$match_id,$player_id=null,$def_lang='en') {
        // $coun_data=count($data);
        foreach ($data as $key => $data_goal) {
            // if($coun_data==1){
            //     $data_goal=$value;
            // }else{
            //     $data_goal=$value['@attributes'];
            // }
            $scorer_player=Player::get_PlayerOneCondtion('opta_link',$data_goal['scorerId']);
            if(isset($scorer_player->id)){
                $assist_player_id=null;
                if(isset($data_goal['assist_player_id'])){
                    $assist_player=Player::get_PlayerOneCondtion('opta_link',$data_goal['assist_player_id']);
                    if(isset($assist_player->id)){
                        $assist_player_id=$assist_player->id;
                    }
                }
                $data_team=Team::get_TeamOneCondition('opta_link',$data_goal['contestantId']);

                $input=[
                    'assist_player_id'=>$assist_player_id,
                    'optaEventId'=>$data_goal['optaEventId'],
                    'lengthMin'=>$data_goal['timeMin'],
                    'lengthSec'=>$data_goal['timeMinSec'],
                    'team_id'=>$data_team->id,
                    'goon'=>1,
                    'period'=>$data_goal['periodId'],
                    'type_state'=>$data_goal['type'],
                ];
                static::Add_Opta_DetailMatch($input,$type,$match_id,$scorer_player->id,$def_lang);
            }
        }
        return true;
    }
    public static function AddCard_Opta_DetailMatch($data,$type,$match_id,$player_id=null,$def_lang='en') {
        // $coun_data=count($data);
        foreach ($data as $key => $data_card) {
            // if($coun_data==1){
            //     $data_card=$value;
            // }else{
            //     $data_card=$value['@attributes'];
            // }
            $data_player=Player::get_PlayerOneCondtion('opta_link',$data_card['playerId']);
            if (isset($data_player->id)) {
                $data_team=Team::get_TeamOneCondition('opta_link',$data_card['contestantId']);
                $red_cart=$yellow_cart=0;
                if($data_card['type']=='YC'){
                    $yellow_cart=1;
                }else{
                    $red_cart=1;
                }
                $input=[
                    'optaEventId'=>$data_card['optaEventId'],
                    'lengthMin'=>$data_card['timeMin'],
                    'lengthSec'=>$data_card['timeMinSec'],
                    'reason'=>$data_card['cardReason'],
                    'team_id'=>$data_team->id,
                    'yellow_cart'=>$yellow_cart,
                    'red_cart'=>$red_cart,
                    'period'=>$data_card['periodId'],
                    'type_state'=>$data_card['type'],
                ];
                static::Add_Opta_DetailMatch($input,$type,$match_id,$data_player->id,$def_lang);
            }
        }
        return true;
    }

    public static function AddSubstitute_Opta_DetailMatch($data,$type,$match_id,$player_id=null,$def_lang='en') {
        //$coun_data=count($data);
        foreach ($data as $key => $data_substitute) {
            // if($coun_data==1){
            //     $data_substitute=$value;
            // }else{
            //     $data_substitute=$value['@attributes'];
            // }
            $on_player=Player::get_PlayerOneCondtion('opta_link',$data_substitute['playerOnId']);
            if(isset($on_player->id)){
                $off_player_id=null;
                if(isset($data_substitute['playerOffId'])){
                    $off_player=Player::get_PlayerOneCondtion('opta_link',$data_substitute['playerOffId']);
                    if(isset($off_player->id)){
                        $off_player_id=$off_player->id;
                    }
                }
                $data_team=Team::get_TeamOneCondition('opta_link',$data_substitute['contestantId']);
                $input=[
                    'off_player_id'=>$off_player_id,
                    'lengthMin'=>$data_substitute['timeMin'],
                    'lengthSec'=>$data_substitute['timeMinSec'],
                    'team_id'=>$data_team->id,
                    'reason'=>$data_substitute['subReason'],
                    'period'=>$data_substitute['periodId'],
                ];
                static::Add_Opta_DetailMatch($input,$type,$match_id,$on_player->id,$def_lang);
            }
        }
        return true;
    }

    public static function AddMissedPen_Opta_DetailMatch($data,$type,$match_id,$player_id=null,$def_lang='en') {
        foreach ($data as $key => $data_pen) {
            $data_player=Player::get_PlayerOneCondtion('opta_link',$data_pen['playerId']);
            if (isset($data_player->id)) {
                $data_team=Team::get_TeamOneCondition('opta_link',$data_pen['contestantId']);
                $input=[
                    'optaEventId'=>$data_pen['optaEventId'],
                    'lengthMin'=>$data_pen['timeMin'],
                    'lengthSec'=>$data_pen['timeMinSec'],
                    'team_id'=>$data_team->id,
                    'period'=>$data_pen['periodId'],
                    'type_state'=>$data_card['type'],
                ];
                static::Add_Opta_DetailMatch($input,$type,$match_id,$data_player->id,$def_lang);
            }
        }
        return true;
    }

    public static function AddVAR_Opta_DetailMatch($data,$type,$match_id,$player_id=null,$def_lang='en') {//penalties
        //$coun_data=count($data);
        foreach ($data as $key => $data_var) {
            // if($coun_data==1){
            //     $data_var=$value;
            // }else{
            //     $data_var=$value['@attributes'];
            // }
            $data_player=Player::get_PlayerOneCondtion('opta_link',$data_var['playerId']);
            $state_add=0;
            $penalties=0;
            if(isset($data_player->id)){
                $data_team=Team::get_TeamOneCondition('opta_link',$data_var['contestantId']);

                $data_found=static::foundDataFourCondition('player_id',$data_player->id,'match_id',$match_id,'optaEventId',$data_var['optaEventId'],'lengthMin',$data_var['lengthMin']);

                if(!isset($data_found->id)){
                    if(strpos($data_var['type'],'Goal') !== false){
                        $type='var_goal';
                        $state_add=1;
                        if($data_var['decision'] =='Cancelled'){
                            //upate to zero
                            static::updateState_Opta_DetailMatch($data_team->id,'goal',$match_id,$data_player->id,'goon',0);
                        }
                    } elseif(strpos($data_var['type'],'Card') !== false){
                        $type='var_card';
                        $state_add=1;
                        if($data_var['decision'] =='Cancelled'){
                            //upate to zero
                            static::updateState_Opta_DetailMatch($data_team->id,'card',$match_id,$data_player->id,'yellow_cart',0,'red_cart',0);
                        }
                    }else{  //if($type=='penalties'){
                        //Ex:$data_var[type] = Penalty not awarded
                        $state_add=1;
                        $penalties=1;
                        if($data_var['decision'] =='Cancelled'){
                            $penalties=0;
                        }
                    }    

                    $input=[
                        'optaEventId'=>$data_var['optaEventId'],
                        'lengthMin'=>$data_var['timeMin'],
                        'lengthSec'=>$data_var['timeMinSec'],
                        'team_id'=>$data_team->id,
                        'reason'=>$data_var['decision'].' ( '.$data_var['type'].' ) ',
                        'period'=>$data_var['periodId'],
                        'outcome'=>$data_var['outcome'],
                        'penalties'=>$penalties,
                    ];
                    static::Add_Opta_DetailMatch($input,$type,$match_id,$data_player->id,$def_lang);
                }    
            }
        }
        return true;
    }

    public static function Add_Opta_DetailMatch($data,$type,$match_id,$player_id=null,$def_lang='en') {
        $location_id=null;
        //update_by
        $start_date=isset($data['start']) ? $data['start'] : null;
        if(!empty($start_date)){
            $start_date=get_date($start_date,1);//2019-08-16T18:35:22Z
        }
        $end_date=isset($data['end']) ? $data['end'] : null;
        if(!empty($end_date)){
            $end_date=get_date($end_date,1);//2019-08-16T18:35:22Z
        }
        $date=isset($data['date']) ? $data['date'] : null;
        if(!empty($date)){
            $date=get_date($date,1);//2019-08-16T18:35:22Z
        }
        $input=[
            'match_id'=>$match_id,
            'location_id'=>$location_id,
            'type'=>$type,
            'player_id'=>$player_id,
            'assist_player_id'=>isset($data['assist_player_id']) ? $data['assist_player_id'] : null,
            'off_player_id'=>isset($data['off_player_id']) ? $data['off_player_id'] : null,
            'team_id'=>isset($data['team_id']) ? $data['team_id'] : null,
            'type_state'=>isset($data['type_state']) ? $data['type_state'] : null,
            'goon'=>isset($data['goon']) ? $data['goon'] : 0,
            'penalties'=>isset($data['penalties']) ? $data['penalties'] : 0,
            'red_cart'=>isset($data['red_cart']) ? $data['red_cart'] : 0,
            'yellow_cart'=>isset($data['yellow_cart']) ? $data['yellow_cart'] : 0,
            'period'=>isset($data['period']) ? $data['period'] : 0,
            'outcome'=>isset($data['outcome']) ? $data['outcome'] : 0,
            'x'=>isset($data['x']) ? $data['x'] : 0,
            'y'=>isset($data['y']) ? $data['y'] : 0,
            'keyPass'=>isset($data['keyPass']) ? $data['keyPass'] : 0,
            'date'=>$date,
            'start_date'=>$start_date,
            'end_date'=>$end_date,
            'lengthMin'=>isset($data['lengthMin']) ? $data['lengthMin'] : 0,
            'lengthSec'=>isset($data['lengthSec']) ? $data['lengthSec'] : 0,
            'reason'=>isset($data['reason']) ? $data['reason'] : null,
            'optaEventId'=>isset($data['optaEventId']) ? $data['optaEventId'] : null,
        ];
        $data_found=static::foundDataFourCondition('player_id',$player_id,'match_id',$match_id,'type',$type,'lengthMin',$input['lengthMin']);
        if(isset($data_found->id)){
            $data_found->update($input);
        }else{
            // $input['link']=get_RandLink();
            static::create($input);
        }
        return true;
    }

    public static function updateState_Opta_DetailMatch($team_id,$type,$match_id,$player_id,$colum, $columValue,$colum2='', $columValue2=''){
        $data = static::where('team_id',$team_id)->where('type',$type)->where('match_id',$match_id)->where('player_id',$player_id)->orderBy('id', 'DESC')->first();
        if(isset($data->id)){
            $data->$colum = $columValue;
            if(!empty($colum2)){
                $data->$colum2 = $columValue2;
            }
            return $data->save();
        }else{
            return false;
        }
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
        $penalties_found = static::where($colum,$val)->first();
        return $penalties_found;
    }

    public static function foundDataTwoCondition($colum, $val,$colum2, $val2) {
        $link_found = static::where($colum, $val)->where($colum2, $val2)->first();
        return $link_found;
    }

    public static function foundDataFourCondition($colum, $val,$colum2, $val2,$colum3, $val3,$colum4, $val4) {
        $link_found = static::where($colum, $val)->where($colum2, $val2)->where($colum3, $val3)->where($colum4, $val4)->first();
        return $link_found;
    }
    public static function All_foundData($colum,$val) {
        $penalties_found = static::where($colum,$val)->get();
        return $penalties_found;
    }

    public static function get_DetailMatcheID($id, $colum, $all = 0) {
        $DetailMatche = static::where('id', $id)->first();
        if ($all == 0) {
            return $DetailMatche->$colum;
        } else {
            return $DetailMatche;
        }
    }

    public static function get_DetailMatcheRow($id, $colum = 'id') {
        $DetailMatche = static::where($colum, $id)->first();
        return $DetailMatche;
    }

    public static function get_DetailMatcheCloum($colum = 'id', $val = '') {
        $DetailMatche = static::where($colum, $val)->orderBy('id', 'DESC')->first();
        return $DetailMatche;
    }

    public static function SearchDetailMatche($search, $limit = 0) {
        $data = static::Where('goon', 'like', '%' . $search . '%')
                ->orWhere('time', 'like', '%' . $search . '%')
                ->orWhere('date', 'like', '%' . $search . '%')
                ->orWhere('penalties', 'like', '%' . $search . '%')
                ->orWhere('red_cart', 'like', '%' . $search . '%')
                ->orWhere('yellow_cart', 'like', '%' . $search . '%');
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
    public static function get_DataDetailMatcheUser($data, $api = 0) {
        $all_data = [];
        foreach ($data as $key => $val_cat) {
            $all_data[] = static::single_DataDetailMatcheUser($val_cat, $api);
        }
        return $all_data;
    }

    public static function single_DataDetailMatcheUser($val_cat, $api = 0) {
        $array_data['penalties'] = $val_cat->penalties;
        $array_data['goon'] = $val_cat->goon;
        $array_data['cost'] = $val_cat->cost;
        $array_data['red_cart'] = $val_cat->red_cart;//->format('Y-m-d');
        $array_data['yellow_cart'] = $val_cat->yellow_cart;//->format('Y-m-d');
        $array_data['created_at'] = $val_cat->created_at->format('Y-m-d');
        return $array_data;
    }

    public static function get_DetailMatche($colum1 = 'match_id', $val1 = '', $colum2 = 'team_id', $val2 = '', $colum3 = 'player_id', $val3 = '', $lang = 'ar', $api = 0) {
        $all_data = '';
        $data = static::get_DetailMatcheByPlayer($colum1, $val1, $colum2, $val2, $colum3, $val3);
        if (isset($data->id)) {
            $all_data = static::single_DetailMatcheUser($data, $lang, $api);
        }
        return $all_data;
    }

    public static function get_DetailMatcheByPlayer($colum1 = 'match_id', $val1 = '', $colum2 = 'team_id', $val2 = '', $colum3 = 'player_id', $val3 = '') {
        $Player = static::where($colum1, $val1)->where($colum2, $val2)->where($colum3, $val3)->orderBy('id', 'DESC')->first();
        return $Player;
    }

    public static function single_DetailMatcheUser($val_cat, $lang = 'en', $api = 0,$filter=0,$arraygame_player_id=[]) {
        $array_data['red_cart'] = isset($val_cat->red_cart) ? $val_cat->red_cart : 0;
        $array_data['yellow_cart'] = isset($val_cat->yellow_cart) ? $val_cat->yellow_cart : 0;
        $array_data['keyPass'] = isset($val_cat->keyPass) ? $val_cat->keyPass : 0;
        $array_data['type'] = isset($val_cat->type) ? $val_cat->type : '';
        $array_data['penalties'] = isset($val_cat->penalties) ? $val_cat->penalties : 0;
        
        if($filter==1 && $api ==1){
            //$array_team['team'] = $val_cat->teams->name;
            //$array_team['link'] = $val_cat->teams->link;
            //return array('array_data'=>$array_data,'array_team'=>$array_team);
            return $array_data;
        }else{
            return $array_data;
        }
    }

}
