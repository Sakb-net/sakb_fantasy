<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SubeldwryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $lang = $request->header('lang');
        if ($lang == 'ar'){
            setlocale(LC_ALL, 'ar_KW.utf8');
            Carbon::setLocale( \App::getLocale());
        }
        
        $start_date = $end_date = '';
        $array_date = explode(' ', $this->start_date);
        if (isset($array_date[0])) {
            $start_date = $array_date[0];
        }
        $array_date = explode(' ', $this->end_date);
        if (isset($array_date[0])) {
            $end_date = $array_date[0];
        }
        return [
            'link' => (string)$this->link,
            'name' => (string)finalValueByLang($this->lang_name,$this->name,$lang),
            'num_week' => (string)$this->num_week,
            'lang_num_week' => (string)trans('app.gameweek') .' '.$this->num_week,
            'start_date' => (string)$start_date,
            'start_date_day' => (string)day_lang_game($this->start_date,$request->header('lang')),
            'end_date' => (string)$end_date,
            'end_date_day' => (string)day_lang_game($this->end_date,$request->header('lang')),
        ];
    }
}
