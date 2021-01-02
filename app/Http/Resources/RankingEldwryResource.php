<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\RankingEldwry;
use Carbon\Carbon;

class RankingEldwryResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $lang=$request->header('lang');
        if ($lang == 'ar'){
            setlocale(LC_ALL, 'ar_KW.utf8');
            Carbon::setLocale( \App::getLocale());
        }

        $all_sum_data=RankingEldwry::sum_all_before_and_SubldwryTeam($this->eldwry_id,$this->sub_eldwry_id,$this->team_id);

        $all_form_data=RankingEldwry::get_Last_RankingEldwry($this->eldwry_id,$this->team_id,$this->sub_eldwr_id,5,1,'DESC');
        $array_form_match=json_decode($all_form_data[0]->form, true);
        $array_form=[];
        foreach ($all_form_data as $key => $val) {
            $val_match=$all_form_data[0]->match;
            if($key==0){
                $match=$val_match;
            }
            $val_form=[
                'state'=>trans('app.'.$array_form_match[$val->match_id]),
                'first_team_name'=>'',
                'first_team_image'=>'',
                'first_team_goon'=>'',
                'second_team_name'=>'',
                'second_team_image'=>'',
                'second_team_goon'=>'',
            ];
            $array_form[]=$val_form;
        }
        $team=$this->team;       
        return [
            'team_link' =>(string) $team->link,
            'team_name' =>(string) finalValueByLang($team->lang_name,$team->name,$lang),
            'team_image' => (string)$team->image,
            'count_played' => $this->sum_won+$this->sum_draw+$this->sum_loss,
            'won' =>(string)$this->sum_won,
            'draw' => $this->sum_draw,
            'loss' =>(string) $this->sum_loss,
            'goals_own' => (string)$all_sum_data->sum_goals_own,
            'goals_aganist' => (string) $all_sum_data->sum_goals_aganist,
            'goals_diff' => (string) $all_sum_data->sum_goals_diff,
            'points' => (string) $all_sum_data->sum_points,
            'form' =>$array_form,
            'next_match_link' => (string) $match->link,
            'next_match_name' => (string) finalValueByLang($match->lang_name,$match->name,$lang),
        ];
    }
}
