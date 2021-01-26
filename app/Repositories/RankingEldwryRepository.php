<?php
namespace App\Repositories;

use Illuminate\Http\Request;
use App\Http\Resources\RankingEldwryResource;
use App\Http\Resources\RankingEldwryHomeResource;
use App\Http\Resources\SubeldwryResource;
use App\Models\RankingEldwry;
use App\Models\Eldwry;
use App\Models\Subeldwry;
use App\Models\Match;
use Auth;
use Session;

class RankingEldwryRepository
{

    public function add_RankingEldwry($match_data=[]){
        if(count($match_data)<=0){
            $last_rinking=RankingEldwry::get_last_row();
            $last_match_id=0;
            if(isset($last_rinking->id)){
                $last_match_id=$last_rinking->match_id;
            }
            $match_data=Match::get_MatchLargeFromId($last_match_id);
        }
        foreach ($match_data as $key => $value) {
            if(!isset($sub_eldwry->id) || $sub_eldwry->id != $value->sub_eldwry_id){
                $sub_eldwry=$value->sub_eldwry;
            }
            $first_won=$first_draw=$first_loss=$second_won=$second_draw=$second_loss=0;
            if($value->first_goon == $value->second_goon){
                $first_draw=$second_draw=1;
            }elseif($value->first_goon > $value->second_goon){
                $first_won=3;
                $second_loss=1;
            }elseif($value->first_goon < $value->second_goon){
                $second_won=3;
                $first_loss=1;
            }
            $input_first=$this->get_dataInput($sub_eldwry,$value,'first_team_id','first_type','first_goon','second_goon',$first_won,$first_draw,$first_loss);
            $input_second=$this->get_dataInput($sub_eldwry,$value,'second_team_id','second_type','second_goon','first_goon',$second_won,$second_draw,$second_loss);

            RankingEldwry::add_Ranking_Eldwry($input_first);
            RankingEldwry::add_Ranking_Eldwry($input_second);
        }
        return true;
    }

    public function get_dataInput($sub_eldwry,$value,$col_team_id,$col_type,$col_goals_own,$col_goals_aganist,$won,$draw,$loss){
        $next_match=Match::get_MatchNextId($value->id,$value->$col_team_id);
        $next_match_id=null;
        if(isset($next_match->id)){
            $next_match_id=$next_match->id;
        }
        return $input=[
            'update_by'=>Auth::user()->id,
            'eldwry_id'=>$sub_eldwry->eldwry_id,
            'sub_eldwry_id'=>$value->sub_eldwry_id,
            'match_id'=>$value->id,
            'team_id'=>$value->$col_team_id,
            'next_match_id'=>$next_match_id,
            'type'=>$value->$col_type,
            'won'=>$won,
            'draw'=>$draw,
            'loss'=>$loss,
            'goals_own'=> $value->$col_goals_own,
            'goals_aganist'=> $value->$col_goals_aganist,
            'goals_diff'=> $value->$col_goals_own - $value->$col_goals_aganist,
            'points'=> $won+$draw,
            'is_active'=>1,
        ];
    }

    public function get_RankingEldwry($colum_subeldwry='link',$val_subeldwry='',$limit=18,$offset=0,$request=[]){
        $eldwry=Eldwry::get_currentDwry();
        $ranking_eldwry=[];
        $subeldwry='';
        if(isset($eldwry->id)){
            if(empty($val_subeldwry)){
                $subeldwry=Subeldwry::get_BeforCurrentSubDwry();
            }else{
                $subeldwry=Subeldwry::get_SubeldwryRow($val_subeldwry, $colum_subeldwry);
            }
            if(isset($subeldwry->id)){
                $request->headers->set('subeldwry_ranking',$subeldwry->id);
                $request->headers->set('eldwry_ranking', $eldwry->id);
                $data=RankingEldwry::all_statistic_all_before_of_subldwry_team($eldwry->id,$subeldwry->id,-1,$limit,$offset);
                $ranking_eldwry= RankingEldwryResource::collection($data);
            }
        }
        return array('ranking_eldwry'=>$ranking_eldwry,'active_subeldwry'=>$subeldwry);
    } 

    public function get_home_RankingEldwry(){ //get current Subeldwry
        $eldwry=Eldwry::get_currentDwry();
        $ranking_eldwry=[];
        $subeldwry='';
        if(isset($eldwry->id)){
            $subeldwry=Subeldwry::get_BeforCurrentSubDwry();
            if(isset($subeldwry->id)){
                Session::put('subeldwry_ranking',$subeldwry->id);
                Session::put('eldwry_ranking',$eldwry->id);
                $data=RankingEldwry::statistic_all_before_of_subldwry_team($eldwry->id,$subeldwry->id);
                $ranking_eldwry= RankingEldwryHomeResource::collection($data);
            }
        }
        return array('ranking_eldwry'=>$ranking_eldwry,'active_subeldwry'=>$subeldwry);
    } 

    function get_subeldwry_ranking_eldwry(){
        $eldwry =Eldwry::get_currentDwry();
        $subeldwry=[];
        if(isset($eldwry->id)){
            $subeldwry=Subeldwry::get_BeforCurrentSubDwry(1,'',0);
        }
        return SubeldwryResource::collection($subeldwry);
    }
}