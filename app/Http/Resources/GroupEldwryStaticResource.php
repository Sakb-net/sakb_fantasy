<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\GroupEldwryStatic;
use Carbon\Carbon;
class GroupEldwryStaticResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($request->header('lang') == 'ar'){
            setlocale(LC_ALL, 'ar_KW.utf8');
            Carbon::setLocale( \App::getLocale());
        }

        $prev_data=GroupEldwryStatic::getGroupPrevSubeldwry($this->group_eldwry_id,$this->sub_eldwry_id,'sub_eldwry_id');
        $prev_sort=$prev_points=0;
        if(isset($prev_data->id)){
            $prev_sort=$prev_data->sort;
            $prev_points=$prev_data->points;
        }
        return [
            'user_id' =>$this->group_eldwry->user_id,
            'name' =>(string)$this->group_eldwry->name,
            'creator_id' => $this->group_eldwry->creator_id,
            'image' =>(string) $this->group_eldwry->image,
            'link' =>(string) $this->group_eldwry->link,
            'num_allow_users' =>(string)$this->group_eldwry->num_allow_users,
            'eldwry_name' => (string)$this->group_eldwry->eldwry->name,
            'eldwry_link' => (string)$this->group_eldwry->eldwry->link,
            'num_week' => (string)$this->subeldwry->num_week,
            'lang_num_week' => (string)trans('app.gameweek') .' '.$this->subeldwry->num_week,
            'subeldwry_link' => (string)$this->subeldwry->link,
            'current_points' => (string) $this->points,
            'prev_points' => (string) $prev_points,
            'current_sort' => (string) $this->sort,
            'prev_sort' => (string) $prev_sort,
        ];
    }
}
