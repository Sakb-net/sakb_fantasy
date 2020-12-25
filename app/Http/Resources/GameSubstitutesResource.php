<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GameSubstitutesResource extends JsonResource
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
        return [
            'id' => $this->id,
            'user_name' => $this->user->display_name,
            'player_link' => $this->player->link,
            'player_name' => finalValueByLang($this->player->lang_name,$this->player->name,$lang),
            'player_image' => finalValueByLang($this->player->image,'',$lang),
            'player_substitute_link' => $this->player_substitute->link,
            'player_substitute_name' => finalValueByLang($this->player_substitute->lang_name,$this->player_substitute->name,$lang),
            'player_substitute_image' => finalValueByLang($this->player_substitute->image,'',$lang),
            'points' => $this->points,
            'cost' => $this->cost,
        ];
    }
}
