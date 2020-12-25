<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SiteController;
use App\Models\User;
use App\Models\AllSetting;
use App\Models\Eldwry;
use App\Models\Subeldwry;
use App\Models\Match;
use App\Models\GameTransaction;
use App\Models\GameSubstitutes;
use App\Models\AllType;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Player;
use App\Models\LocationPlayer;
use App\Http\Controllers\ClassSiteApi\Class_MyTeamController;

class AjaxMyTeamController extends SiteController {

    public function __construct() {
        parent::__construct();
        if (isset(Auth::user()->id)) {
            $this->current_id = Auth::user()->id;
            $this->user_key = Auth::user()->name;
        }
    }

//*************************************************************
    public function get_datalineup(Request $request) {
        if ($request->ajax()) {
            $response = 1;
            $lang = $this->lang;
            $current_id = Auth::user()->id;
            $start_dwry = Eldwry::get_currentDwry();
            $current_lineup='default_lineup_l';//[4,4,2];
            if (isset($start_dwry->id)) {
                $current_game = Game::checkregisterDwry($current_id, $start_dwry->id, 1);
                if (isset($current_game->id)) {
                    if(isset($current_game->lineup->setting_value)){
                        $current_lineup=$current_game->lineup->setting_etc;//json_decode($current_game->lineup->setting_value,true);
                    }
                }
            }
            $lineup=AllSetting::DataAllSetting('lineup',1,'ASC');
            if ($request->val_view == 1 || $request->val_view == '1') {
                $array_data = array('lineup' => $lineup,'current_lineup'=>$current_lineup);
                $response = view('site.games.side_table', $array_data)->render();
                return response()->json(['response' => $response]);
            } else {
                return response()->json(['response' => $response, 'lineup' => $lineup,'current_lineup'=>$current_lineup]);
            }
        }
    }

    public function get_add_linupMyteam(Request $request) {
        if ($request->ajax()) {
            $response = 1;
            $lang = $this->lang;
            $num_player = $this->num_player;
            $current_id = Auth::user()->id;
            $current_lineup='';//[4,4,2];
            $linup_link='';//'default_lineup_l';
            $msg_add = trans('app.add_fail');
            $ok_update=0;
            foreach ($request->input() as $key => $value) {
                $$key = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            $lineup=AllSetting::get_rowSettingLink('lineup',$linup_link,1);
            if (isset($lineup->id)) {
                $start_dwry = Eldwry::get_currentDwry();
                if (isset($start_dwry->id)) {
                    $current_game = Game::checkregisterDwry($current_id, $start_dwry->id, 1);
                    if (isset($current_game->id)) {
                        $ok_update=1;
                        $current_lineup=json_decode($lineup->setting_value,true);
                        $update=Game::updateOrderColum('id',$current_game->id,'lineup_id',$lineup->id);
                        $update_order=GamePlayer::add_lineup($current_game->id,$current_lineup);
                        $msg_add = trans('app.add_scuss');
                    }
                }  
            }          
            if($ok_update==1){
                $all_play_game = GamePlayer::allPlayerGameUser('game_id', $current_game->id, 'is_active', 1, 'id', 'ASC');
                $array_player_master = GamePlayer::get_DataGroup_lineup($all_play_game, $num_player, $lang, 0,$current_lineup);
                $player_master =$array_player_master['all_data'];
                $order_lineup=$array_player_master['order_lineup'];
                return response()->json(['response' => $response, 'player_master' => $player_master,'order_lineup'=>$order_lineup,'current_lineup'=>$current_lineup,'msg_add'=>$msg_add,'ok_update'=>$ok_update]);
            } else {
                return response()->json(['response' => $response, 'msg_add'=>$msg_add,'ok_update'=>$ok_update]);
            }
        }
    }

    public function get_add_Captain(Request $request) {
        if ($request->ajax()) {
            $response = 1;
            $lang = $this->lang;
            $num_player = $this->num_player;
            $current_id = Auth::user()->id;
            $current_lineup=[4,4,2];
            $eldwry_link=$player_link='';
            $type='captain';
            $msg_add = trans('app.add_fail');
            $ok_update=0;
            foreach ($request->input() as $key => $value) {
                $$key = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            $get_data=new Class_MyTeamController();
            $array_data=$get_data->get_PlayerAddCatptain($current_id,$player_link,$type,$lang,0);
            $ok_update=$array_data['ok_update'];
            $msg_add =$array_data['msg_add'];
            $current_game=$array_data['current_game'];
            if($ok_update==1){
                $current_lineup=json_decode($current_game->lineup->setting_value,true);
                $all_play_game = GamePlayer::allPlayerGameUser('game_id', $current_game->id, 'is_active', 1, 'id', 'ASC');
                $array_player_master = GamePlayer::get_DataGroup_lineup($all_play_game, $num_player, $lang, 0,$current_lineup);
                $player_master =$array_player_master['all_data'];
                $order_lineup=$array_player_master['order_lineup'];
                return response()->json(['response' => $response, 'player_master' => $player_master,'order_lineup'=>$order_lineup,'current_lineup'=>$current_lineup,'msg_add'=>$msg_add,'ok_update'=>$ok_update]);
            } else {
                return response()->json(['response' => $response, 'msg_add'=>$msg_add,'ok_update'=>$ok_update]);
            }
        }
    }

    public function inside_changePlayer(Request $request) {
        if ($request->ajax()) {
            $response = 1;
            $lang = $this->lang;
            $num_player = $this->num_player;
            $current_id = Auth::user()->id;
            $current_lineup=[4,4,2];
            $eldwry_link=$player_link='';
            $type_loc_player=$type_loc_player_one=$type_loc_player_two=$type_loc_hidden='';
            $type='captain';
            $msg_add = trans('app.add_fail');
            $ok_update=$all_hide=0;
            foreach ($request->input() as $key => $value) {
                $$key = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            $get_data=new Class_MyTeamController();
            $array_data=$get_data->Inside_changePlayer($current_id,$player_link,$lang,0);
           // print_r($array_data);die;
            $ok_update=$array_data['ok_add'];
            $msg_add =$array_data['msg_add'];
            $change=$array_data['change'];
            $change_sub=$array_data['change_sub'];
            $first_choose=$array_data['first_choose'];
            $second_choose=$array_data['second_choose'];
            $remove_class=$array_data['remove_class'];
            if(isset($array_data['all_hide'])){
                $all_hide=$array_data['all_hide'];
            } 
            if(isset($array_data['type_loc_player'])){
                $type_loc_player=$array_data['type_loc_player'];
            }
            if(isset($array_data['type_loc_hidden'])){
                $type_loc_hidden=$array_data['type_loc_hidden'];
            }
            if(isset($array_data['type_loc_player_one'])){
                $type_loc_player_one=$array_data['type_loc_player_one'];
            }
            if(isset($array_data['type_loc_player_two'])){
                $type_loc_player_two=$array_data['type_loc_player_two'];
            }
            if($change==1){
                return response()->json(['response' => $response, 'msg_add'=>$msg_add,'ok_update'=>$ok_update,'change'=>$change,'change_sub'=>$change_sub,'all_hide'=>$all_hide,'type_loc_player'=>$type_loc_player,'type_loc_hidden'=>$type_loc_hidden,'first_choose'=>$first_choose,'second_choose'=>$second_choose,'remove_class'=>$remove_class,'type_loc_player_one'=>$type_loc_player_one,'type_loc_player_two'=>$type_loc_player_two]);
            }elseif($ok_update==1){
                //all_hide
                return response()->json(['response' => $response, 'msg_add'=>$msg_add,'ok_update'=>$ok_update,'change'=>$change,'change_sub'=>$change_sub,'all_hide'=>$all_hide,'type_loc_player'=>$type_loc_player,'type_loc_hidden'=>$type_loc_hidden,'first_choose'=>$first_choose,'second_choose'=>$second_choose,'remove_class'=>$remove_class,'type_loc_player_one'=>$type_loc_player_one,'type_loc_player_two'=>$type_loc_player_two]);
            } else {
                //
                return response()->json(['response' => $response, 'msg_add'=>$msg_add,'ok_update'=>$ok_update,'change_sub'=>$change_sub,'change'=>$change]);
            }
        }
    }
    public function delete_allowchange(Request $request) {
        if ($request->ajax()) {
            $response =$ok_update=1;
            emptySession();
            return response()->json(['response' => $response,'ok_update'=>$ok_update]);
        }
    }
    public function okInsid_ChangPlayer(Request $request) {
        if ($request->ajax()) {
            $response = 1;
            $lang = $this->lang;
            $num_player = $this->num_player;
            $current_id = Auth::user()->id;
            $current_lineup=[4,4,2];
            $player_link='';
            $msg_add = trans('app.add_fail');
            $ok_update=0;
            foreach ($request->input() as $key => $value) {
                $$key = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            $get_data=new Class_MyTeamController();
            $array_data=$get_data->ok_Inside_changePlayer($current_id,$lang,0); //$player_link,
            $ok_update=$array_data['ok_add'];
            $msg_add =$array_data['msg_add'];
            if($ok_update==1){
                $current_game=$array_data['current_game'];
                if(empty($array_data['new_lineup'])){
                    $current_lineup=json_decode($current_game->lineup->setting_value,true);
                }else{
                    $current_lineup=$array_data['new_lineup'];
                }
                $all_play_game = GamePlayer::allPlayerGameUser('game_id', $current_game->id, 'is_active', 1, 'id', 'ASC');
                $array_player_master = GamePlayer::get_DataGroup_lineup($all_play_game, $num_player, $lang, 0,$current_lineup);
                $player_master =$array_player_master['all_data'];
                $order_lineup=$array_player_master['order_lineup'];
                return response()->json(['response' => $response, 'player_master' => $player_master,'order_lineup'=>$order_lineup,'current_lineup'=>$current_lineup,'msg_add'=>$msg_add,'ok_update'=>$ok_update]);
            }else{
                //
                return response()->json(['response' => $response, 'msg_add'=>$msg_add,'ok_update'=>$ok_update]);
            }
        }
    }
    //*************** Triple Bench Card**********************

    public function check_btns_status(Request $request) {
        if ($request->ajax()) {
            $response = 1;
            $get_data=new Class_MyTeamController();
            $cards_status=$get_data->check_UserCardsStatusInSubeldwry(Auth::user()->id);
            return response()->json(['response' => $response,'cards_status'=>$cards_status]);
        }    
    }
    
    public function get_dataTripleCaptainPoints(Request $request) {
        if ($request->ajax()) {
            $response = 1;
            $get_data=new Class_MyTeamController();
            $data=$get_data->Cal_PointUserTripleCapitain(Auth::user()->id);
            return response()->json(['response' => $response,'data'=>$data]);
        }    
    }

    public function get_dataBenchPlayersPoints(Request $request) {
        if ($request->ajax()) {
            $response = 1;
            $get_data=new Class_MyTeamController();
            $data=$get_data->Cal_PointUserBenchPlayers(Auth::user()->id);
            return response()->json(['response' => $response,'data'=>$data]);
        }    
    }

    public function cancelBenchTripleCard(Request $request) {
        if ($request->ajax()) {
            $response = 1;
            $colum='bench_card';
            $return_colum='triple_card';
            foreach ($request->input() as $key => $value) {
                $$key = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            $get_data=new Class_MyTeamController();
            $return_val=$get_data->cancelBenchTripleCard(Auth::user()->id,$colum,$return_colum);
            $cards_status=[$colum=>0,$return_colum=>$return_val];
            return response()->json(['response' => $response,'cards_status'=>$cards_status]);
        }    
    }


}
