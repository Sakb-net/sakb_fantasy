<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;
use App\Models\Team;
use App\Models\Player;

class DetailPlayerMatche extends Model {

    protected $table = 'detail_playermatches';
    protected $fillable = [
        'update_by','link','match_id','team_id','player_id','location_id','cost','captain','shirtNumber','positionSide','formationPlace','accuratePass','wasFouled','lostCorners','goalsConceded','saves','goalKicks','totalPass','gameStarted','minsPlayed','blockedScoringAtt','totalScoringAtt','totalThrows','goalAssist','totalOffside','wonCorners','fouls','totalClearance','cornerTaken','wonTackle','totalTackle','totalSubOff','ontargetScoringAtt','shotOffTarget','goals','totalSubOn','cleanSheet','ownGoals','penaltySave','redCard','yellowCard','attPenGoal','attPenMiss','attPenTarget','goalAssistDeadball','assistOwnGoal','assistPenaltyWo'
    ];
// cleanSheet,ownGoals,penaltySave,redCard,yellowCard,attPenGoal,attPenMiss,attPenTarget,goalAssistDeadball,assistOwnGoal,assistPenaltyWon

//goals,goalAssist,goalsConceded,saves,minsPlayed

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
        return $this->belongsTo(\App\Models\Player::class);
    }
    public function match() {
        return $this->belongsTo(\App\Models\Match::class);
    }
    public function team() {
        return $this->belongsTo(\App\Models\Team::class);
    }

    public static function AddArray_Opta_DetailPlayerMatch($data,$match_id,$player_id=null,$def_lang='en') {
        //$coun_data=count($data);
       foreach ($data as $key => $data_val) {
            // if($coun_data==1){
            //     $data_val=$value;
            // }else{
            //     $data_val=$value['@attributes'];
            // }
            $data_team=Team::get_TeamOneCondition('opta_link',$data_val['contestantId']);
            $team_id=null;
            if(isset($data_team->id)){
                $team_id=$data_team->id;
            }
            foreach ($data_val['player'] as $key => $input_data) {
                //$input_data=$val_player['@attributes'];
                $data_player=Player::get_PlayerOneCondtion('opta_link',$input_data['playerId']);
                if(isset($data_player->id)){
                    static::Add_Opta_DetailPlayerMatch($input_data,$match_id,$team_id,$data_player->id,$def_lang);
                }
            }
       }
       return true;
    }    

    public static function Add_Opta_DetailPlayerMatch($data,$match_id,$team_id,$player_id,$def_lang='en') {
        if(isset($data['subPosition'])){
            $position=$data['subPosition'];
            $positionSide=isset($data['position']) ? $data['position'] : null;
        }else{
            $position=isset($data['position']) ? $data['position'] : null;
            $positionSide=isset($data['positionSide']) ? $data['positionSide'] : null;
        }                                  
        $array_stat=isset($data['stat']) ? $data['stat'] : [];
        foreach ($array_stat as $key_stat => $value_stat) {
            $data[$value_stat['type']]=$value_stat['value'];
        }
        $captain=0;
        if(isset($data['captain']) && $data['captain']=='yes'){
            $captain=1;
        }     

        $input=[
            'player_id'=>$player_id,
            'match_id'=>$match_id,
            'team_id'=>$team_id,
            'location_id'=>get_LocationId($position),
            'captain'=>$captain,
            'shirtNumber'=>isset($data['shirtNumber']) ? $data['shirtNumber'] : 0,
            'positionSide'=>$positionSide,
            'formationPlace'=>isset($data['formationPlace']) ? $data['formationPlace'] : 0,
            'accuratePass'=>isset($data['accuratePass']) ? $data['accuratePass'] : 0,
            'wasFouled'=>isset($data['wasFouled']) ? $data['wasFouled'] : 0,
            'lostCorners'=>isset($data['lostCorners']) ? $data['lostCorners'] : 0,
            'goalsConceded'=>isset($data['goalsConceded']) ? $data['goalsConceded'] : 0,
            'saves'=>isset($data['saves']) ? $data['saves'] : 0,
            'goalKicks'=>isset($data['goalKicks']) ? $data['goalKicks'] : 0,
            'totalPass'=>isset($data['totalPass']) ? $data['totalPass'] : 0,
            'minsPlayed'=>isset($data['minsPlayed']) ? $data['minsPlayed'] : 0,
            'blockedScoringAtt'=>isset($data['blockedScoringAtt']) ? $data['blockedScoringAtt'] : 0,
            'totalScoringAtt'=>isset($data['totalScoringAtt']) ? $data['totalScoringAtt'] : 0,
            'totalThrows'=>isset($data['totalThrows']) ? $data['totalThrows'] : 0,
            'goalAssist'=>isset($data['goalAssist']) ? $data['goalAssist'] : 0,
            'totalOffside'=>isset($data['totalOffside']) ? $data['totalOffside'] : 0,
            'wonCorners'=>isset($data['wonCorners']) ? $data['wonCorners'] : 0,
            'fouls'=>isset($data['fouls']) ? $data['fouls'] : 0,
            'totalClearance'=>isset($data['totalClearance']) ? $data['totalClearance'] : 0,
            'cornerTaken'=>isset($data['cornerTaken']) ? $data['cornerTaken'] : 0,
            'wonTackle'=>isset($data['wonTackle']) ? $data['wonTackle'] : 0,
            'totalTackle'=>isset($data['totalTackle']) ? $data['totalTackle'] : 0,
            'totalSubOff'=>isset($data['totalSubOff']) ? $data['totalSubOff'] : 0,
            'ontargetScoringAtt'=>isset($data['ontargetScoringAtt']) ? $data['ontargetScoringAtt'] : 0,
            'shotOffTarget'=>isset($data['shotOffTarget']) ? $data['shotOffTarget'] : 0,
            'goals'=>isset($data['goals']) ? $data['goals'] : 0,
            'totalSubOn'=>isset($data['totalSubOn']) ? $data['totalSubOn'] : 0,
            'cleanSheet'=>isset($data['cleanSheet']) ? $data['cleanSheet'] : 0,
            'ownGoals'=>isset($data['ownGoals']) ? $data['ownGoals'] : 0,
            'penaltySave'=>isset($data['penaltySave']) ? $data['penaltySave'] : 0,
            'redCard'=>isset($data['redCard']) ? $data['redCard'] : 0,
            'yellowCard'=>isset($data['yellowCard']) ? $data['yellowCard'] : 0,
            'attPenGoal'=>isset($data['attPenGoal']) ? $data['attPenGoal'] : 0,
            'attPenMiss'=>isset($data['attPenMiss']) ? $data['attPenMiss'] : 0,
            'attPenTarget'=>isset($data['attPenTarget']) ? $data['attPenTarget'] : 0,
            'goalAssistDeadball'=>isset($data['goalAssistDeadball']) ? $data['goalAssistDeadball'] : 0,
            'assistOwnGoal'=>isset($data['assistOwnGoal']) ? $data['assistOwnGoal'] : 0,
            'assistPenaltyWo'=>isset($data['assistPenaltyWo']) ? $data['assistPenaltyWo'] : 0,
        ];
        $data_found=static::foundDataThreeCondition('player_id',$player_id,'match_id',$match_id,'team_id',$team_id);
        if(isset($data_found->id)){
            $data_found->update($input);
        }else{
            $input['link']=get_RandLink();
            static::create($input);
        }
        return true;
    }

    public static function foundDataCondition($colum, $val) {
        $link_found = static::where($colum, $val)->first();
        return $link_found;
    }

    public static function foundDataTwoCondition($colum, $val,$colum2, $val2) {
        $link_found = static::where($colum, $val)->where($colum2, $val2)->first();
        return $link_found;
    }

    public static function foundDataThreeCondition($colum, $val,$colum2, $val2,$colum3, $val3) {
        $link_found = static::where($colum, $val)->where($colum2, $val2)->where($colum3, $val3)->first();
        return $link_found;
    }

    public static function get_DetailPlayerCloum($colum = 'id', $val = '') {
        return static::where($colum, $val)->orderBy('id', 'DESC')->get();
    }

    public static function getPlayers_PlayInSubeldwry($sub_eldwry_id,$minsPlayed_op='>',$minsPlayed=0,$array=0,$col_pluck='player_id') {
        $data = static::leftJoin('matches', 'matches.id', '=', 'detail_playermatches.match_id')
        ->where('matches.sub_eldwry_id', $sub_eldwry_id)
        ->where('detail_playermatches.minsPlayed',$minsPlayed_op,$minsPlayed)
        ->where('matches.is_active',1);
        if($array ==1){
            $result =$data->pluck($col_pluck,'player_id')->toArray(); 
        }else{
            $result =$data->get();
        }
        return $result;
    }

    public static function get_AllDetailPlayerUser($data, $lang = 'en', $api = 0) {
        $all_data =[];
        foreach ($data as $key => $value) {
            $all_data []= static::single_DetailPlayerUser($value, $lang, $api);
        }
        return $all_data;
    }

//***********************************************************
    public static function single_DetailPlayerUser($val_cat, $lang = 'en', $api = 0) {
        $player=$val_cat->player;
        $array_data=$val_cat->toArray();
        $array_data['player_name'] =finalValueByLang($player->lang_name,$player->name,$lang);
        $array_data['link'] = $player->link;
        return $array_data;
    }

}
