<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\GroupEldwryTeamStatic;

class GroupEldwryTeamStaticResource extends JsonResource
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
        $total_points = GroupEldwryTeamStatic::Finaltotal_subeldwry($this->sub_eldwry_id,$this->group_eldwry_id,$this->game_id,1);
        return [
            'user_id' => $this->game->user->id,
            'user_name' => (string) $this->game->user->name,
            'display_name' => (string) $this->game->user->display_name,
            'user_email' => (string) $this->game->user->email,
            'name_team' => (string) $this->game->team_name,
            'name_group' => (string) $this->group_eldwry->name,
            'link_group' => (string) $this->group_eldwry->link,
            'total_points' => (string) $total_points,
            'gw_points' => (string) $this->points,
            'sort' => (string) $this->sort,
            'num_week' => (string) $this->subeldwry->num_week,
            'lang_num_week' => (string) trans('app.gameweek') .' '.$this->subeldwry->num_week,
            'subeldwry_link' => (string)$this->subeldwry->link,
        ];
    }
}
