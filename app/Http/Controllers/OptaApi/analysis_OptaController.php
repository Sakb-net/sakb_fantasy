<?php

namespace App\Http\Controllers\OptaApi;

use Illuminate\Http\Request;
// use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Options;
use App\Models\Competition;
use App\Models\Eldwry;
use App\Models\Subeldwry;
use App\Models\Team;
use App\Models\Player;
use App\Models\Match;
use App\Models\DetailMatche;
use App\Models\DetailPlayerMatche;

use App\Http\Controllers\Admin\AdminController;
// use App\Http\Controllers\OptaApi\Class_OptaController;

class analysis_OptaController extends AdminController {

    public function __construct() {
        $data_site = Options::Site_Option();
        $this->max_match =9;
        $this->site_open = $data_site['site_open'];
        $this->lang = $data_site['lang'];
        $this->def_lang = $data_site['def_lang'];
        $this->obta_lang='en';
        $this->site_title = $data_site['site_title'];
        $this->site_url = $data_site['site_url'];
        $this->outletAuthKey='1vmmaetzoxkgg1qf6pkpfmku0k';
        $this->current_id =0;
        if (!empty(Auth::user())) {
            $this->current_id = Auth::user()->id;
            $this->user_key = Auth::user()->name;
        }
    }

    public function analysis_opta_championship($all_data=[]){
        $data=[];
        //[name] => Pro League  //[country] => Saudi Arabia
        $all_comp=$all_data['competition'][1082];
        $competition=$all_comp['@attributes'];
        $competition=RemoveKeyArray($competition,'opta_link','id');
        $competition_id=Competition::Add_Check_Competition($competition,$this->obta_lang);
        $tournamentCalendar=$all_comp['tournamentCalendar']['@attributes'];
       $add_eldwry=Eldwry::Add_Opta_Eldwry($tournamentCalendar,$competition_id,$this->obta_lang);
        return $add_eldwry;
    }

    public function analysis_opta_teams($eldwry_id,$all_data=[]){
        $data=[];
        $all_contestant=$all_data['contestant'];
        foreach ($all_contestant as $key => $value) {
            Team::Add_Opta_Team($value['@attributes'],$eldwry_id,$this->obta_lang);
        }
        return true;
    }
    public function analysis_opta_player($team,$all_data=[]){
        $data=[];
        $all_person=$all_data['person'];
        foreach ($all_person as $key => $value) {
            //shirtNumber
            Player::Add_Opta_Player($value['@attributes'],$team->id,$this->obta_lang);
        }
        return true;
    }

    public function analysis_opta_subeldwrys($eldwry_id,$all_data=[]){
        $data=[];
        //competition  //tournamentCalendar //matchDate
        $all_subeldwry=$all_data['matchDate'];
        $numberOfGames=0;
        foreach ($all_subeldwry as $key => $value) {
            $update_start_date=0;
            if($numberOfGames==0 || $numberOfGames==$this->max_match){
                $numberOfGames=0;
                $update_start_date=1;
                $subeldwry_id=Subeldwry::Add_Opta_Subeldwry($value['@attributes'],$eldwry_id,$this->obta_lang);
            }
            $numberOfGames +=$value['@attributes']['numberOfGames'];
            //match of subeldwry
            $this->analysis_opta_matches($subeldwry_id,$value,$update_start_date);
        }
        return true;
    }

    public function analysis_opta_matches($subeldwry_id,$all_data=[],$update_start_date=1){
        $data=[];
        $all_matchs=$all_data['match'];
        $numberOfGames=$all_data['@attributes']['numberOfGames'];
        foreach ($all_matchs as $key_match => $val_match) {
                if($numberOfGames ==1){
                    $data_matches=$val_match;
                }else{
                    $data_matches=$val_match['@attributes'];
                }
                if($key_match==0){
                    $start_date=get_date($data_matches['date']).' '.get_date($data_matches['time'],2);
                    if($update_start_date==1){
                        Subeldwry::updateOrderColum('id', $subeldwry_id,'start_date', $start_date);
                    }
                }
                Match::Add_Opta_match($data_matches,$subeldwry_id,$this->obta_lang);
        }
        if(count($all_matchs)>0 && $update_start_date > -1){
            $date_time=get_date($data_matches['date']).' '.get_date($data_matches['time'],2);  //+90
            $end_date=addTimeOnDate($date_time,90);
            Subeldwry::updateOrderColum('id', $subeldwry_id,'end_date', $end_date);
        }
        return true;
    }

    public function analysis_opta_Resultmatches($data_match,$all_data=[]){
        $all_matchs=$all_data['matchInfo'];
        $match_data=$all_matchs;//['@attributes'];
        $liveData=$all_data['liveData'];
        $match_data_two=$liveData['matchDetails'];//['@attributes'];
        $scores_home=$scores_away=$match_periodId=0;
        $match_winner=null;
        if(isset($liveData['matchDetails']['scores']['total'])){
            $scores_total=$liveData['matchDetails']['scores']['total'];//['@attributes'];
           $scores_home= $scores_total['home'];
           $scores_away= $scores_total['away'];
        }
        if(isset($match_data_two['winner'])){
            $match_winner=$match_data_two['winner'];
        }
        if(isset($match_data_two['periodId'])){
            $match_periodId=$match_data_two['periodId'];
        }
        $match_id=Match::updateOrderMoreData('opta_link', $match_data['id'],$match_data['week'],$all_matchs['description'],$all_matchs['stage']['name'],$all_matchs['venue']['longName'],$scores_home,$scores_away,$match_winner,$match_periodId);
        if($match_id>0){
            //get period from opta in this match and add
            if(isset($liveData['matchDetails']['periods']['period'])){
                $match_periods=$liveData['matchDetails']['periods']['period'];
                DetailMatche::AddArray_Opta_DetailMatch($match_periods,'period',$match_id,null,$this->obta_lang);
            }
            //get goals from opta in this match  and add
            if(isset($liveData['goal'])){
                DetailMatche::AddGoal_Opta_DetailMatch($liveData['goal'],'goal',$match_id,null,$this->obta_lang);
            }
            //get card from opta in this match  and add
            if(isset($liveData['card'])){
                DetailMatche::AddCard_Opta_DetailMatch($liveData['card'],'card',$match_id,null,$this->obta_lang);
            }
            //get substitute from opta in this match  and add
            if(isset($liveData['substitute'])){
                DetailMatche::AddSubstitute_Opta_DetailMatch($liveData['substitute'],'substitute',$match_id,null,$this->obta_lang);
            }
            //get substitute from opta in this match  and add
            if(isset($liveData['missedPen'])){
                DetailMatche::AddMissedPen_Opta_DetailMatch($liveData['missedPen'],'missedPen',$match_id,null,$this->obta_lang);
            }
            //get penalties from opta in this match  and add
            if(isset($liveData['VAR'])){
                DetailMatche::AddVAR_Opta_DetailMatch($liveData['VAR'],'penalties',$match_id,null,$this->obta_lang);
            }
            //get details player from opta in this match and add
            if(isset($liveData['lineUp'])){
                DetailPlayerMatche::AddArray_Opta_DetailPlayerMatch($liveData['lineUp'],$match_id,null,$this->obta_lang);
            }
        }
        return $match_id;
    }
    
//************Not used********************
    public function analysis_opta_Resultmatches_XML($data_match,$all_data=[]){
        $all_matchs=$all_data['matchInfo'];
        //print_r($all_matchs);die;
        $match_data=$all_matchs['@attributes'];
        $liveData=$all_data['liveData'];
        $match_data_two=$liveData['matchDetails']['@attributes'];
        $scores_home=$scores_away=$match_periodId=0;
        $match_winner=null;
        if(isset($liveData['matchDetails']['scores']['total']['@attributes'])){
            $scores_total=$liveData['matchDetails']['scores']['total']['@attributes'];
           $scores_home= $scores_total['home'];
           $scores_away= $scores_total['away'];
        }
        if(isset($match_data_two['winner'])){
            $match_winner=$match_data_two['winner'];
        }
        if(isset($match_data_two['periodId'])){
            $match_periodId=$match_data_two['periodId'];
        }
        $match_id=Match::updateOrderMoreData('opta_link', $match_data['id'],$match_data['week'],$all_matchs['description'],$all_matchs['stage'],$all_matchs['venue'],$scores_home,$scores_away,$match_winner,$match_periodId);
        if($match_id>0){
            //get period from opta in this match and add
            if(isset($liveData['matchDetails']['periods']['period'])){
                $match_periods=$liveData['matchDetails']['periods']['period'];
                DetailMatche::AddArray_Opta_DetailMatch($match_periods,'period',$match_id,null,$this->obta_lang);
            }
            //get goals from opta in this match  and add
            if(isset($liveData['goal'])){
                DetailMatche::AddGoal_Opta_DetailMatch($liveData['goal'],'goal',$match_id,null,$this->obta_lang);
            }
            //get card from opta in this match  and add
            if(isset($liveData['card'])){
                DetailMatche::AddCard_Opta_DetailMatch($liveData['card'],'card',$match_id,null,$this->obta_lang);
            }
            //get substitute from opta in this match  and add
            if(isset($liveData['substitute'])){
                DetailMatche::AddSubstitute_Opta_DetailMatch($liveData['substitute'],'substitute',$match_id,null,$this->obta_lang);
            }
            //get penalties from opta in this match  and add
            if(isset($liveData['VAR'])){
                DetailMatche::AddVAR_Opta_DetailMatch($liveData['VAR'],'penalties',$match_id,null,$this->obta_lang);
            }
            //get details player from opta in this match and add
            if(isset($liveData['lineUp'])){
                DetailPlayerMatche::AddArray_Opta_DetailPlayerMatch($liveData['lineUp'],$match_id,null,$this->obta_lang);
            }
        }
        return $match_id;
    }

}
