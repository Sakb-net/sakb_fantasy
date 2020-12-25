<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
class GroupEldwryResource extends JsonResource
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

        return [
            'user_id' =>$this->user_id,
            'name' =>(string) $this->name,
            'code' =>(string) $this->code,
            'creator_id' =>$this->creator_id,
            'image' =>(string) $this->image,
            'link' =>(string) $this->link,
            'num_allow_users' =>(string) $this->num_allow_users,
            'eldwry_name' => (string)$this->eldwry->name,
            'eldwry_link' => (string)$this->eldwry->link,
            'num_week' => (string)$this->subeldwry->num_week,
            'lang_num_week' => (string)trans('app.gameweek') .' '.$this->subeldwry->num_week,
            'subeldwry_link' => (string)$this->subeldwry->link

        ];
    }
}
