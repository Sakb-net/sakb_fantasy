<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SiteController;
use App\Models\User;
use App\Models\Subeldwry;
use App\Http\Controllers\ClassSiteApi\Class_PointController;

class AjaxPointController extends SiteController {

    public function __construct() {
        parent::__construct();
        if (isset(Auth::user()->id)) {
            $this->current_id = Auth::user()->id;
            $this->user_key = Auth::user()->name;
        }
    }

//*************************************************************
    public function get_points_subeldwry(Request $request) {
        if ($request->ajax()) {
            $response = 1;
            $lang = $this->lang;
            $num_player = $this->num_player;
            $current_id = Auth::user()->id;
            $type_link='prev';
            $sub_eldwry_link=$sub_eldwry_val='';
            $num_week=0;
            foreach ($request->input() as $key => $value) {
                $$key = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            //get sub_eldwry_link
            if($num_week>0){
                $get_sub_eldwry=Subeldwry::get_SubeldwryCloum('num_week', $num_week,1);  
            }elseif(!empty($sub_eldwry_link)){
                $get_sub_eldwry=Subeldwry::NextPrev_DataSubeldwry('link', $sub_eldwry_link,1,$type_link);
            }else{
                $get_sub_eldwry=Subeldwry::get_DataSubeldwry_Current('','',1,'DESC',0,-1,1,null,'');
                //'eldwry_id',$start_dwry->eldwry_id
                // get_startFirstSubDwry();
            }
            $get_data=new Class_PointController();
            $array_data=$get_data->GETHistory_MYTeam(Auth::user(),'row_table',$get_sub_eldwry,$num_player,$lang,0);
             return response()->json(['response' => $response,'subeldwry_points'=>$array_data['subeldwry_points'], 'player_master' => $array_data['player_master'],'order_lineup'=>$array_data['order_lineup'],'current_lineup'=>$array_data['current_lineup']]);
        }    
    }

    public function get_pointsplayer_foruser(Request $request) {
        if ($request->ajax()) {
            $response = 1;
            $lang = $this->lang;
            $num_player = $this->num_player;
            $current_id = Auth::user()->id;
            $player_link=$player_name=$subeldwry_link='';
            foreach ($request->input() as $key => $value) {
                $$key = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            $get_data=new Class_PointController();
            $array_data=$get_data->statisticPlayer_inSubeldwry(Auth::user(),$subeldwry_link,$player_link,$lang,0);
            return response()->json(['response' => $response,'data'=>$array_data]);
        }    
    }

}
