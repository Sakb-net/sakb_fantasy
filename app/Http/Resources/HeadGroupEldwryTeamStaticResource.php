<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\HeadGroupEldwryTeamStatic;

class HeadGroupEldwryTeamStaticResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //subeldwry
        $total_bouns = HeadGroupEldwryTeamStatic::Finaltotal_subeldwry($this->sub_eldwry_id,$this->head_group_eldwry_id,$this->user_id,1,'sum_bouns');
        return [
            'user_id' => $this->user_id,
            'user_name' => (string) $this->user->name,
            'display_name' => (string) $this->user->display_name,
            'user_email' => (string) $this->user->email,
            'name_team' => (string) $this->group_eldwry->game->team_name,
            'name_group' => (string) $this->group_eldwry->name,
            'link_group' => (string) $this->group_eldwry->link,
            'total_points' => (string) $total_bouns,
            'gw_points' => (string) $this->bouns,
            'sort' => (string) 1,
            'num_week' => (string) $this->subeldwry->num_week,
            'lang_num_week' => (string) trans('app.gameweek') .' '.$this->subeldwry->num_week,
            'subeldwry_link' => (string)$this->subeldwry->link,
        ];
    }
}
