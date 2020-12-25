<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupEldwryUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'user_id' => $this->user->id,
            'user_name' => (string) $this->user->name,
            'display_name' => (string) $this->user->display_name,
            'user_email' => (string) $this->user->email,
            'name_team' => (string) $this->group_eldwry->game->team_name,
            'name_group' => (string) $this->group_eldwry->name,
            'link_group' => (string) $this->group_eldwry->link,
            'points' => (string) 22,
            'num_week' => (string) $this->group_eldwry->subeldwry->num_week,
            'lang_num_week' => (string) trans('app.gameweek') .' '.$this->group_eldwry->subeldwry->num_week,
            'subeldwry_link' => (string)$this->group_eldwry->subeldwry->link,
        ];
    }
}
