<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
// use App\Models\Player;
// use App\Models\DetailPlayerMatche;
use App\Repositories\PlayerDetailsMatchRepository;
class FixtureResource extends JsonResource
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
        $name_first=$link_first=$image_first=$name_second=$link_second=$image_second='';
        if(isset($this->teams_first->id)){
            $name_first=finalValueByLang($this->teams_first->lang_name,$this->teams_first->name,$lang);
            $link_first=$this->teams_first->link;
            if (!empty($this->teams_first->image)) {
                $image_first= (string) finalValueByLang($this->teams_first->image,'',$lang);
            }
        }
        if(isset($this->teams_second->id)){
            $name_second=finalValueByLang($this->teams_second->lang_name,$this->teams_second->name,$lang);
            $link_second= $this->teams_second->link;
            if (!empty($this->teams_second->image)) {
                $image_second= finalValueByLang($this->teams_second->image,'',$lang);
            }
        }
        $get_details=new PlayerDetailsMatchRepository();
        $details=$get_details->DetailsPlayerMatch($this);

        $first_team_players=[];// $this->DetailPlayerMatche->where('team_id', $val_cat->first_team_id);
        $second_team_players=[];//$this->DetailPlayerMatche->where('team_id', $val_cat->second_team_id);
        //$request->header('lang')
        return [
	        'description'=> (string) finalValueByLang($this->description,'',$lang),
	        'date'=> (string) date("d-m-Y", strtotime($this->date)),
	        'date_day'=> day_lang_game($this->date, $lang),
	        'time'=> (string) time_in_12_hour_format($this->time),
	        'first_goon'=> (string) $this->first_goon,
	        'second_goon'=> (string) $this->second_goon,
	        'name_first' =>(string)$name_first,
	        'name_second'=>(string)$name_second,
	        'link_first'=>(string)$link_first,
	        'link_second'=> (string)$link_second,
	        'id_first'=> (string) $this->teams_first->id,
	        'id_second'=> (string) $this->teams_second->id,        
	        'id'=> (string) $this->id,
	        'image_first'=> (string)$image_first,
	        'image_second'=> (string)$image_second,
	        'first_team_players'=>$first_team_players,
	        'second_team_players'=>$second_team_players ,
	        'details'=>$details
            // 'details' => DetailPlayerMatcheResource::collection($this->whenLoaded('DetailPlayerMatche')),
        ];
    }
}
