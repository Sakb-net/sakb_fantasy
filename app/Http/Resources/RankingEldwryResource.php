<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\RankingEldwry;
use Carbon\Carbon;
use Session;

class RankingEldwryResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request,$edd=3)
    {
        $lang=$request->header('lang');
        if ($lang == 'ar'){
            setlocale(LC_ALL, 'ar_KW.utf8');
            Carbon::setLocale( \App::getLocale());
        }
        $subeldwry_id=Session::get('subeldwry_ranking');
        $eldwry_id=Session::get('eldwry_ranking');
        $all_form_data=RankingEldwry::get_Last_RankingEldwry($eldwry_id,$this->team_id,$subeldwry_id,5,1,'DESC');
        $team=$this->team;
        $array_form=[];
        $class_state='';
        foreach ($all_form_data as $key => $val) {
            $val_match=$val->match;
            if($key==0 && !empty($val->next_match_id)){
                $next_match=$val->next_match;
            }
            if($val->won > 0){
                $form_status='w';
                $class_state .= ' win';
            }elseif ($val->draw > 0) {
                $form_status='d';
                $class_state .= ' drawn';
            }else{
                $form_status='l';
                $class_state .= ' lose';
            }
            $team_first=$val_match->teams_first;
            $team_second=$val_match->teams_second;
            $location_type='first_type';
            if($val_match->second_team_id == $team->id){
                $location_type='second_type';
            }
            $class_state .= ' '.$val_match->$location_type;
            $val_form=[
                'location_type'=>$val_match->$location_type,
                'state'=>$form_status,
                'state_lang'=>trans('app.form_'.$form_status),
                'match_link' => (string) $val_match->link,
                'first_team_name'=>(string) $team_first->code,
                'first_team_image'=>(string) finalValueByLang($team_first->image,'',$lang),
                'first_team_goon'=>(string) $val_match->first_goon,
                'second_team_name'=>(string) $team_second->code,
                'second_team_image'=>(string)finalValueByLang($team_second->image,'',$lang),
                'second_team_goon'=>(string) $val_match->second_goon,
                'date_day'=> day_lang_game($val_match->date, $lang).' '.date("d-m-Y", strtotime($val_match->date)),
            ];
            $array_form[]=$val_form;
        }
        $next_match_array="";
        if(isset($next_match->id)){    
            $flag_next_match_team='teams_first';
            if($next_match->second_team_id != $team->id){
                $flag_next_match_team='teams_second';
            }
            $next_match_team=$next_match->$flag_next_match_team; 
            $next_match_array=[
                'match_link' => (string) $next_match->link,
                'second_team_link' => (string) $next_match_team->link,
                'second_team_name' => (string) $next_match_team->code,
                'second_team_image' => (string) finalValueByLang($next_match_team->image,'',$lang),
                'date_day'=> day_lang_game($next_match->date, $lang).' '.date("d-m-Y", strtotime($next_match->date)),
                'time' =>time_in_12_hour_format($next_match->time),
            ]; 
        }
        return [
            'team_link' =>(string) $team->link,
            'team_name' =>(string) $team->code,
            'team_image' => (string) finalValueByLang($team->image,'',$lang),
            'site_team'=> (string) $team->site_team,
            'count_played' => (string) $this->count_played,
            'won' =>(string)$this->sum_won,
            'draw' => $this->sum_draw,
            'loss' =>(string) $this->sum_loss,
            'goals_own' => (string) $this->sum_goals_own,
            'goals_aganist' => (string) $this->sum_goals_aganist,
            'goals_diff' => (string) $this->sum_goals_diff,
            'points' => (string) $this->sum_points,
            'form' =>$array_form,
            'current_match' => $array_form[0],
            'next_match' =>$next_match_array,
            'class_state'=>$class_state,
        ];
    }
}
