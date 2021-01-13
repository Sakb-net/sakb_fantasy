<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\RankingEldwry;
use Carbon\Carbon;
use Session;

class RankingEldwryHomeResource extends JsonResource
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
        $team=$this->team;
        return [
            'team_link' =>(string) $team->link,
            'team_name' =>(string) finalValueByLang($team->lang_name,$team->name,$lang),
            'team_image' => (string) finalValueByLang($team->image,'',$lang),
            'count_played' => (string) $this->count_played,
            'goals_diff' => (string) abs($this->sum_goals_diff),
            'points' => (string) $this->sum_points,
        ];
    }
}
