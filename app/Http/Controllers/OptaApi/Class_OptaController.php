<?php

namespace App\Http\Controllers\OptaApi;

use Illuminate\Http\Request;
// use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Options;
use App\Models\Eldwry;
use App\Models\Subeldwry;
use App\Models\Team;
use App\Models\Match;
use App\Models\DetailPlayerMatche;
use App\Models\GameHistory;
use App\Models\GamePlayerHistory;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\OptaApi\analysis_OptaController;
use App\Http\Controllers\OptaApi\Class_PointController;

class Class_OptaController extends AdminController {

    public function __construct() {
        $data_site = Options::Site_Option();
        $this->site_open = $data_site['site_open'];
        $this->lang = $data_site['lang'];
        $this->site_title = $data_site['site_title'];
        $this->site_url = $data_site['site_url'];
        $this->outletAuthKey='19hoqnyq53mjw1h5sizhgl1rk2';
       $this->outletAuthKey_old='19hoqnyq53mjw1h5sizhgl1rk2';//'1vmmaetzoxkgg1qf6pkpfmku0k';
        $this->current_id =0;
        if (!empty(Auth::user())) {
            $this->current_id = Auth::user()->id;
            $this->user_key = Auth::user()->name;
        }
    }
    public function GetDataJson_urlOpta($url,$post_string='') {
        $json_data = file_get_contents($url);
        // Decode JSON data into PHP array
       $array_response = json_decode($json_data, true);
        return $array_response;
    } 


    public function GetDataXml_urlOpta($url,$post_string='') {
        // header('Content-type: application/xml');
        header('Content-type: text/plain');
        $xml_string= file_get_contents($url);
        $xml_data = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $xml_string);
        $all_xml = simplexml_load_string($xml_data);
        $json = json_encode($all_xml);
        $array_response = json_decode($json,TRUE);
        return $array_response;
    }

    public function opta_championship() {
        //Pro League
       $url='http://api.performfeeds.com/soccerdata/tournamentcalendar/'.$this->outletAuthKey.'/active?_fmt=xml&_rt=b';
        $array_response=$this->GetDataXml_urlOpta($url,'');
        $get_data=new analysis_OptaController();
        $data=$get_data->analysis_opta_championship($array_response);
        return $data;
    }

    public function opta_eldwry() {
        //Pro League
       $url='http://api.performfeeds.com/soccerdata/tournamentcalendar/'.$this->outletAuthKey.'/active?_fmt=xml&_rt=b';

        //$url='http://api.performfeeds.com/soccerdata/tournamentcalendar/'.$this->outletAuthKey.'/active/authorized?_rt=b';
        //?_fmt=xml&_rt=b';
        $array_response=$this->GetDataXml_urlOpta($url,'');
        $get_data=new analysis_OptaController();
        $data=$get_data->analysis_opta_championship($array_response);
        return $data;
    }
    
    public function opta_teams() {
        $current_eldwry=Eldwry::get_currentDwry();
        $tournamentCalendarUuid=$current_eldwry->opta_link;
        $url='http://api.performfeeds.com/soccerdata/team/'.$this->outletAuthKey_old.'?tmcl='.$tournamentCalendarUuid.'&_rt=b&_fmt=xml&detailed=yes';
        $array_response=$this->GetDataXml_urlOpta($url,'');
        $get_data=new analysis_OptaController();
        $data=$get_data->analysis_opta_teams($current_eldwry->id,$array_response);
        return $data;
    }
    public function opta_players() {
        $current_eldwry=Eldwry::get_currentDwry();
        $eldwry_teams=Team::All_foundData('eldwry_id',$current_eldwry->id);
        foreach ($eldwry_teams as $key => $team) {
            $url='http://api.performfeeds.com/soccerdata/playercareer/'.$this->outletAuthKey_old.'?ctst='.$team->opta_link.'&active=yes&_fmt=xml&_rt=b';
            $array_response=$this->GetDataXml_urlOpta($url,'');
            $get_data=new analysis_OptaController();
            $data=$get_data->analysis_opta_player($team,$array_response);
        }
        return true;
    }
    public function opta_subeldwrys() {
        $current_eldwry=Eldwry::get_currentDwry();
        $tournamentCalendarUuid=$current_eldwry->opta_link;
       //$tournamentCalendarUuid='bj0v24a2u4ib71lg20mhfyxre';
       $url='http://api.performfeeds.com/soccerdata/tournamentschedule/'.$this->outletAuthKey.'/'.$tournamentCalendarUuid.'?_fmt=xml&_rt=b';
        $array_response=$this->GetDataXml_urlOpta($url,'');
        $get_data=new analysis_OptaController();
        $data=$get_data->analysis_opta_subeldwrys($current_eldwry->id,$array_response);
        return $data;
    }
    public function opta_matches() {
        $current_eldwry=Eldwry::get_currentDwry();
        $tournamentCalendarUuid=$current_eldwry->opta_link;
       //$tournamentCalendarUuid='bj0v24a2u4ib71lg20mhfyxre';
       $url='http://api.performfeeds.com/soccerdata/tournamentschedule/'.$this->outletAuthKey.'/'.$tournamentCalendarUuid.'?_fmt=xml&_rt=b';
        $array_response=$this->GetDataXml_urlOpta($url,'');
        $get_data=new analysis_OptaController();
        $data=$get_data->analysis_opta_subeldwrys($current_eldwry->id,$array_response);
        return $data;
    }

    public function opta_result_subeldwry($subeldwry_id,$admin=0) {
        $subeldwry=Subeldwry::foundData('id',$subeldwry_id);
        if(isset($subeldwry->opta_link)){
            $current_eldwry=Eldwry::get_currentDwry();
            $tournamentCalendarUuid=$current_eldwry->opta_link;
           $url='http://api.performfeeds.com/soccerdata/tournamentschedule/'.$this->outletAuthKey.'/'.$tournamentCalendarUuid.'?_fmt=xml&_rt=b';
            $array_response=$this->GetDataXml_urlOpta($url,'');
            $all_subeldwrys=$array_response['matchDate'];
            foreach ($all_subeldwrys as $key => $val_sub) {
                if($val_sub['@attributes']['date']==$subeldwry->opta_link){
                    $get_data=new analysis_OptaController();
                    $data=$get_data->analysis_opta_matches($subeldwry_id,$val_sub,-1);
                }
            }
            //get details match form opta and add
            $data_matches=Match::AllfoundData('sub_eldwry_id',$subeldwry->id);
            foreach ($data_matches as $key => $val_match) {
                $this->opta_result_matche($val_match,1,$admin);
            }
            ///end
        }
        return true;
    }

    public function opta_result_matche($match_id,$all=0,$admin=0) {
        if($all==1){
            $data_match=$match_id;
        }else{
            $data_match=Match::foundData('id',$match_id);
        }
        if(isset($data_match->id)){
            // $url='http://api.performfeeds.com/soccerdata/match/'.$this->outletAuthKey.'/'.$data_match->opta_link.'?live=yes&_fmt=xml&_rt=b';

            //$url='http://api.performfeeds.com/soccerdata/matchstats/'.$this->outletAuthKey.'/'.$data_match->opta_link.'?_fmt=xml&_rt=b';

            $url='http://api.performfeeds.com/soccerdata/matchstats/'.$this->outletAuthKey.'/'.$data_match->opta_link.'?_fmt=json&_rt=b';

           // $array_response=$this->GetDataXml_urlOpta($url,'');
            $array_response=$this->GetDataJson_urlOpta($url,'');
            $get_data=new analysis_OptaController();
            $match_id=$get_data->analysis_opta_Resultmatches($data_match,$array_response);   
            //pull of pointPlayer
            $point_player=$this->Match_PointPlayer($data_match->id);
            //pull of pointUser
            $get_point=new Class_PointController();
            $point_user=$get_point->Cal_PointUser($data_match->sub_eldwry_id); 
            $get_point->cal_pointBounsSystem($data_match,$admin);       
        }
        return true;
    }

    public function GetCurrentMatches(){ //this run by cronJop
        return true;
        if($_SERVER['SERVER_NAME']!='127.0.0.1'){
            $current_date=date("Y-m-d");//date("Y-m-d H:i:s");
            $time='';
            $matches=Match::getCurrentMatch_BYDate($current_date,$time);
            foreach ($matches as $key => $value) {
                $this->opta_result_matche($value,1);
            }
        }
        return true;
    }

    public function GetAllDetailsMatches($limit=30,$offset=0){
        $matches=Match::limit($limit)->offset($offset)->orderBy('id', 'DESC')->get();
        // $matches=Match::where('id','>=',251)->get();
        foreach ($matches as $key => $value) {
            $this->opta_result_matche($value,1);
        }
        return true;
    }
 // *********************PointPlayer************************   
    public function Match_PointPlayer($match_id=0){
        $get_point=new Class_PointController();
        $details_players=DetailPlayerMatche::where('match_id',$match_id)->orderBy('id', 'DESC')->get();
        foreach ($details_players as $key => $value) {
            $data_point=$get_point->Cal_PointPlayer($value,1);
        }
        return true;
    }

    public function GetAllMatch_PointPlayer($limit=100,$offset=0){
        $get_point=new Class_PointController();
       // $details_players=DetailPlayerMatche::limit($limit)->offset($offset)->orderBy('id', 'DESC')->get();
        $details_players=DetailPlayerMatche::where('id','>=',1)->get();
        foreach ($details_players as $key => $value) {
            $data_point=$get_point->Cal_PointPlayer($value,1);
        }
        return true;
    }
//********************** PointUser ***************************

    public function GetAllEldwry_PointUser($date=''){
        $eldwry=Eldwry::get_currentDwry();
        if(isset($eldwry->id)){
            $subeldwrys=Subeldwry::All_foundData('eldwry_id', $eldwry->id);//current_DataSubeldwry('eldwry_id', $eldwry->id,1,$date,0,'ASC');
            $get_point=new Class_PointController();
            foreach ($subeldwrys as $key => $value) {
            //take copy and move data to history table
                // GameHistory::Copy_Game_GameHistory($value->id);
            //end
                $data_point=$get_point->Cal_PointUser($value->id);
                
                // $data_point=$get_point->Cal_HeadGroupEldwry($value->id);
                // $data_point=$get_point->Cal_GroupEldwry($value->id); //Classic
            }
        }
        return true;
    }
}
