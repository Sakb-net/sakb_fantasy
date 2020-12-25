<?php
namespace App\Http\Controllers\ClassSiteApi;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Game;
use App\Models\Eldwry;
use App\Models\Subeldwry;
use App\Models\Match;
use App\Models\Player;
use App\Models\LocationPlayer;
use App\Models\GamePlayer;
use App\Models\PointPlayer;
use App\Models\PointUser;
use App\Models\GameHistory;
use App\Models\GamePlayerHistory;
use App\Models\GameSubstitutes;
use App\Models\Options;
use App\Http\Controllers\SiteController;

class Class_PointController extends SiteController {

    public function __construct() {
        parent::__construct();
    }

function GetDataPlayer_MasterHistory($current_id,$sub_eldwry,$num_player=15,$lang='ar',$val_view=0,$api=0) {
    $player_master = $order_lineup=[];
    $subeldwry_points='';
    $current_lineup=[4,4,2];
    $sub_eldwry_id=0;
    if (isset($sub_eldwry->id)) {
        $status_card=['bench_card'=>0,'triple_card'=>0,'gold_card'=>0,'gray_card'=>0];
        $subeldwry_points=Subeldwry::single_DataSubeldwryPoint($sub_eldwry, $api,$current_id,$lang);
        $game_history = GameHistory::register_GameHistory($current_id, $sub_eldwry->id, 1);
        if (isset($game_history->id)) {
            $sub_eldwry_id=$sub_eldwry->id;//$game_history->sub_eldwry_id;
            if(isset($game_history->lineup->setting_value)){
                $current_lineup=json_decode($game_history->lineup->setting_value,true);
            }
            $all_play_game = GamePlayerHistory::allPlayerGameUser('game_history_id', $game_history->id, 'is_active', 1, 'id', 'ASC');

            $array_player_master = GamePlayer::get_DataGroup_lineup($all_play_game, $num_player, $lang,$api,$current_lineup,$sub_eldwry_id,$current_id);
            $player_master =$array_player_master['all_data'];
            $order_lineup=$array_player_master['order_lineup'];

            $status_card=$this->statisticCardSubeldwry($current_id,$sub_eldwry_id,$game_history,2);
        }
        $subeldwry_points=array_merge($subeldwry_points,$status_card);
    }

    $array_data = array('player_master' => $player_master,'current_lineup'=>$current_lineup,'subeldwry_points'=>$subeldwry_points);
    if($api==0){
        $array_data['order_lineup']=$order_lineup;
    }
    return $array_data;
}

    function GETHistory_MYTeam($user,$col_sub_eldwry='link',$sub_eldwry_val='',$num_player=15,$lang='ar',$api=0) {
        if($col_sub_eldwry=='row_table'){
            $sub_eldwry =$sub_eldwry_val;
        }else{
            $sub_eldwry = Subeldwry::foundData($col_sub_eldwry,$sub_eldwry_val);
        }
        $get_data=new Class_PointController();
        $all_data=$get_data->GetDataPlayer_MasterHistory($user->id,$sub_eldwry,$num_player,$lang,0,$api);
        if($api==0){
            $response=$all_data;
        }else{
            $response['data']=$all_data['subeldwry_points'];
            $response['player_master']=Game::MyTeam_MasterTransfer($user->id,$all_data['player_master'],$all_data['current_lineup'],2,$api,1);
            $response['lineup']=Game::Mobile_lineup($all_data['current_lineup']);
        }
        return $response;
    }

    function statisticPlayer_inSubeldwry($user,$subeldwry_link,$player_link='',$lang='ar',$api=0){
        $array_data=[];
        $sub_eldwry = Subeldwry::foundData('link',$subeldwry_link);
        if(isset($sub_eldwry->id)){
            $player_data = Player::foundData('link',$player_link);
            if(isset($player_data->id)){
                //get details player in Subeldwry
                $data=PointPlayer::getDataPlayer_subeldwry($sub_eldwry->id,$player_data->id);
                $array_data=PointPlayer::get_StatisticPointPlayerUser($data,$player_data,$api);
            }
        }

        return $array_data;
    }

    function statisticCardSubeldwry($current_id,$sub_eldwry_id,$game,$gold_gray=0){
        //check status card gold and gray(silver) this sub_eldwry
        $status_card['bench_card']=$game->bench_card;
        $status_card['triple_card']=$game->triple_card;
        if($gold_gray > 0){
            if($gold_gray ==1){
               $status_card=[]; 
            }
            $array_gold_card=GameSubstitutes::DataSubstitutesSubeldwry($current_id,$sub_eldwry_id,'card_type_id',12,1,'card_type_id','code_substitute',-1);
            $status_card['gold_card']=count($array_gold_card);
            $status_card['gray_card']=$game->num_cardgray;
        }
        return $status_card;
    }
}