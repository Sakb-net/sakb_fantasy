<?php
namespace App\Repositories;

use App\Http\Resources\RankingEldwryResource;
use App\Models\RankingEldwry;
use App\Models\Eldwry;
use App\Models\Subeldwry;
use App\Models\Match;
use Auth;

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
            'goals_own'=>$value->$col_goals_own,
            'goals_aganist'=>$value->$col_goals_aganist,
            'goals_diff'=>abs($value->$col_goals_own - $value->$col_goals_aganist),
            'points'=>$won+$draw,
            'form'=>$this->get_form($sub_eldwry,$value->$col_team_id,$value->id,$won,$draw,$loss),
            'is_active'=>1,
        ];
    }
    public function get_form($sub_eldwry,$team_id,$match_id,$won,$draw,$loss){
        $data_form=RankingEldwry::get_Last_RankingEldwry($sub_eldwry->eldwry_id,$team_id,-1,4);
        $array_form=[];
        foreach ($data_form as $key => $value) {
            if($value->won > 0){
                $array_form[$match_id]='form_w';
            }elseif($value->draw > 0){
                $array_form[$match_id]='form_d';
            }elseif($value->loss > 0){
                $array_form[$match_id]='form_l';
            }       
        }
        if($won > 0){
            $array_form[$match_id]='form_w';
        }elseif($draw > 0){
            $array_form[$match_id]='form_d';
        }elseif($loss > 0){
            $array_form[$match_id]='form_l';
        }
        return json_encode($array_form);
    }

    public function get_RankingEldwry($sub_eldwry_id=0,$limit=12,$offset=0){
        $eldwry=Eldwry::get_currentDwry();
        $return_data=[];
        if(isset($eldwry->id)){
            if($sub_eldwry_id <= 0){
                $current_subeldwry=Subeldwry::get_BeforCurrentSubDwry();
                if(isset($current_subeldwry->id)){
                    $sub_eldwry_id=$current_subeldwry->id;
                }
            }
            if($sub_eldwry_id >0){
                $data=RankingEldwry::sum_SubldwryID($eldwry->id,$sub_eldwry_id,$limit,$offset);
                $return_data= RankingEldwryResource::collection($data);
            }
        }
        return $return_data;
    } 
}