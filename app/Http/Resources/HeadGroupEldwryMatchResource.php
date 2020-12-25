<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\HeadGroupEldwryTeamStatic;

class HeadGroupEldwryMatchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // $first_total_points=$this->first_team->points+$this->first_team->bouns;
    // $second_total_points=$this->second_team->points+$this->second_team->bouns;
        return [
            'first_team_name' => (string) $this->first_team->group_eldwry->game->team_name,
            'first_user_name' => (string) $this->first_team->user->display_name,
            'first_team_points' => (string) $this->first_team->points,
            'first_team_bouns' => (string) $this->first_team->bouns,
            // 'first_total_points' => (string) $first_total_points,

            'second_team_name' => (string) $this->second_team->group_eldwry->game->team_name,
            'second_user_name' => (string) $this->second_team->user->display_name,
            'second_team_points' => (string) $this->second_team->points,
            'second_team_bouns' => (string) $this->second_team->bouns,
            // 'second_total_points' => (string) $second_total_points,

            'sort' => (string) $this->sort,
            'name_group' => (string) $this->group_eldwry->name,
            'link_group' => (string) $this->group_eldwry->link,
            'num_week' => (string) $this->subeldwry->num_week,
            'lang_num_week' => (string) trans('app.gameweek') .' '.$this->subeldwry->num_week,
            'subeldwry_link' => (string)$this->subeldwry->link,
        ];
    } 
}
