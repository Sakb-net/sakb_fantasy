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
use App\Models\Team;
use App\Models\GameTransaction;
use App\Models\GameHistory;
use App\Models\Options;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\ClassSiteApi\Class_TransferController;

class Class_MyTeamController extends SiteController {

    public function __construct() {
        parent::__construct();
    }
	
	public function GETMYTeam($user,$num_player=15,$lang='ar',$api=0) {
	    $get_data=new Class_TransferController();
	    $all_data=$get_data->GetDataPlayer_MasterTransfer($user->id,$num_player,$lang,0,1);
	    if(count($all_data['player_master'])==$num_player){
	        $data=Game::MyTeam_MasterTransfer($user->id,$all_data['player_master'],$all_data['current_lineup'],2,$api,1);
	        $array_best_team=User::BestTeam($user,1);
            $image_best_team=$array_best_team['image_best_team'];
	        $current_lineup=Game::Mobile_lineup($all_data['current_lineup']);
	    }else{
	        $data=[];
	        $image_best_team='';
	        $current_lineup=[];
	    }
	    $response['image_best_team'] = $image_best_team;
	    $response['lineup'] =$current_lineup ;
	    $response['data'] = $data;
	    return $response;
    }
    
    public function get_PlayerAddCatptain($current_id,$player_link,$type='captain',$lang='ar',$api=0) {
        $ok_update=0;
        $msg_add = trans('app.add_fail');
        $current_game=[];
        $start_dwry = Eldwry::get_currentDwry();
        if (isset($start_dwry->id)) {
            $current_game = Game::checkregisterDwry($current_id, $start_dwry->id, 1);
            if (isset($current_game->id)) {
                $ok_update=$this->Add_updateCatptain($current_game->id,$player_link,$type,$lang,$api);
                if($ok_update==1){
                    $msg_add = trans('app.add_scuss');
                }
            }
        } 
        if($api==1){
             return array('ok_update'=>$ok_update,'msg_add'=>$msg_add);
        }else{ 
            return array('ok_update'=>$ok_update,'msg_add'=>$msg_add,'current_game'=>$current_game);
        }
    }
    public function Add_updateCatptain($game_id,$player_link,$type='captain',$lang='ar',$api=0) {
        // $type -->'captain','assist'
        $ok_add=1;
        $player_data = Player::get_PlayerCloum('link', $player_link, 1);
        if(isset($player_data->id)){
            $type_coatch=9; //assist
            if($type=='captain'){
                $type_coatch=8;
            }
            //update remove type_coatch 
           $ok_add=GamePlayer::updateOrderColumfour('game_id',$game_id,'type_coatch',$type_coatch,'is_active',1,'type_coatch',null); 
            //update to add type_coatch 
            $ok_add=GamePlayer::updateOrderColumfour('game_id',$game_id,'player_id',$player_data->id,'is_active',1,'type_coatch',$type_coatch);
        }else{
            $ok_add=-1;  //not found player
        }
        return  $ok_add;
    }
    
    public function Inside_changePlayer($current_id,$player_link,$lang='ar',$api=0,$ch_game_player_id_one=0,$ch_game_player_id_two=0,$ch_player_id_one=0,$ch_player_id_two=0) {
        if($api==1){
            $array_data=['ch_game_player_id_one'=>$ch_game_player_id_one,'ch_game_player_id_two'=>$ch_game_player_id_two,'ch_player_id_one'=>$ch_player_id_one,'ch_player_id_two'=>$ch_player_id_two,'type_loc_player_one'=>'','type_loc_player_two'=>null];
        }
        $array_data['ok_add']=$array_data['change']=$array_data['all_hide']=0;
        $array_data['msg_add'] = trans('app.ERROR_MESSAGE');
        $array_return=$array_data;
        $current_game=[];
        $start_dwry = Eldwry::get_currentDwry();
        if (isset($start_dwry->id)) {
            $current_game = Game::checkregisterDwry($current_id, $start_dwry->id, 1);
            if (isset($current_game->id)) {
                $array_data=$this->Add_Inside_changePlayer($current_game,$player_link,$lang,$api,$ch_game_player_id_one,$ch_game_player_id_two,$ch_player_id_one,$ch_player_id_two);

                //return data to api
                if($api==1){
                    $array_return=$array_data;
                }
            }
        } 
        if($api==1){
             return  $array_return;
        }else{ 
            $array_data['current_game']=$current_game;
            return $array_data;
        }
    }

    public function Add_Inside_changePlayer($game,$player_link,$lang='ar',$api=0,$ch_game_player_id_one=0,$ch_game_player_id_two=0,$ch_player_id_one=0,$ch_player_id_two=0,$type_loc_player_one=null,$type_loc_player_two=null) {
        $ok_add= $change=$first_choose=$second_choose=$remove_class=$change_sub=0;
        $msg_add =$type_loc_player= $type_loc_hidden='';
        $game_id=$game->id;
        $player_data = Player::get_PlayerCloum('link', $player_link, 1);
        if(isset($player_data->id)){
            //check found player in game of current user
           $game_player=GamePlayer::checkFoundData($player_data->id, $game_id,0,1); 
            if($api==0){
                $ch_player_id_two=GetcookieValue('ch_player_id_two');
                $ch_player_id_one=GetcookieValue('ch_player_id_one');
                $ch_game_player_id_one=GetcookieValue('ch_game_player_id_one');
                $type_loc_player_one=GetcookieValue('type_loc_player_one');
                $type_loc_player_two=GetcookieValue('type_loc_player_two');
                
            }
           if(isset($game_player->id)){ 
                $ok_add=1;//yes found in game
                $array_data=GamePlayer::checkNUMTypePlayer($game,$player_data);
                $type_loc_hidden=$array_data['type_loc_hidden'];
                if($game_player->myteam_order_id < 12){//$ch_player_id_one <=0 && 
                    $ch_player_id_one=$player_data->id;
                    $ch_game_player_id_one=$game_player->id;
                    $type_loc_player=$type_loc_player_one=$array_data['type_loc_player'];
                    $first_choose=1;
                    if($api==0){
                       $old_ch_player_id_one=GetcookieValue('ch_player_id_one');
                        if($old_ch_player_id_one==$ch_player_id_one){
                            $remove_class=1;
                            $ch_player_id_one=0;
                            setThreeValue('ch_player_id_one',0,'ch_game_player_id_one',0,'type_loc_player_one',null);
                        }else{
                            setThreeValue('ch_player_id_one',$ch_player_id_one,'ch_game_player_id_one',$ch_game_player_id_one,'type_loc_player_one',$type_loc_player_one);
                        }
                    }
                }elseif($game_player->myteam_order_id >=12){//$ch_player_id_two<=0
                    $ch_player_id_two=$player_data->id;
                    $ch_game_player_id_two=$game_player->id;
                    $type_loc_player=$type_loc_player_two=$array_data['type_loc_player'];
                    $second_choose=1;
                    if($api==0){
                        $old_ch_player_id_two=GetcookieValue('ch_player_id_two');
                        if($old_ch_player_id_two==$ch_player_id_two){
                            $remove_class=2;
                            $ch_player_id_two=0;
                            setThreeValue('ch_player_id_two',0,'ch_game_player_id_two',0,'type_loc_player_two',null);
                        }elseif ($old_ch_player_id_two>0&&$game_player->myteam_order_id >=13) {
                            //three last sub player
                            $change_sub=1;
                            setThreeValue('ch_player_id_one',$ch_player_id_two,'ch_game_player_id_one',$ch_game_player_id_two,'type_loc_player_one',$type_loc_player_two);
                        }else{
                            setThreeValue('ch_player_id_two',$ch_player_id_two,'ch_game_player_id_two',$ch_game_player_id_two,'type_loc_player_two',$type_loc_player_two);
                        }
                    }
                }

                if($type_loc_player!='goalkeeper'&&$ch_player_id_one<=0&&$ch_player_id_two>0){
                   $array_data['all_hide']=0; 
                }
                if($ch_player_id_one>0&&$ch_player_id_two>0) {
                     $change=1;
                }elseif($ch_player_id_one<=0&&$ch_player_id_two<=0) {
                     $remove_class=3;
                } 
                if($api==0&&$array_data['all_hide']==1&&$first_choose==1&&($type_loc_player_one!=$type_loc_player_two)){
                    setThreeValue('ch_player_id_one',0,'ch_game_player_id_one',0,'type_loc_player_one',null);
                }
           }else{
                $ok_add=0;//not found in game
                $msg_add = trans('app.not_allow');//.'not found in game';   
           }
        }else{
            $ok_add=-1;  //not found player
            $msg_add =trans('app.not_allow');//.'not found player'; //not_found_player
        }
        $array_data['msg_add']=$msg_add;
        $array_data['ok_add']=$ok_add;
        $array_data['change']=$change;
        $array_data['change_sub']=$change_sub;
        $array_data['first_choose']=$first_choose;
        $array_data['second_choose']=$second_choose;
        $array_data['type_loc_player_one']=$type_loc_player_one;
        $array_data['type_loc_player_two']=$type_loc_player_two;


        if($api==1){
            $array_data['ch_game_player_id_one']=$ch_game_player_id_one;
            $array_data['ch_player_id_one']=$ch_player_id_one;
            $array_data['ch_game_player_id_two']=$ch_game_player_id_two;
            $array_data['ch_player_id_two']=$ch_player_id_two;
        }else{
            $array_data['type_loc_hidden']=$type_loc_hidden;
            $array_data['type_loc_player']=$type_loc_player;
            $array_data['remove_class']=$remove_class;
        }
        return  $array_data;
    }

    public function ok_Inside_changePlayer($current_id,$lang='ar',$api=0,$ch_game_player_id_one=0,$ch_player_id_one=0,$ch_game_player_id_two=0,$ch_player_id_two=0) {
        $array_data['ok_add']=0;
        $array_data['msg_add'] = trans('app.ERROR_MESSAGE');
        $current_game=[];
        $start_dwry = Eldwry::get_currentDwry();
        if (isset($start_dwry->id)) {
            $current_game = Game::checkregisterDwry($current_id, $start_dwry->id, 1);
            if (isset($current_game->id)) {
                $array_data=$this->Add_changePlayer($current_game,$lang,$api,$ch_game_player_id_one,$ch_player_id_one,$ch_game_player_id_two,$ch_player_id_two);
            }
        } 
        if($api==1){
             return array('ok_add'=>$array_data['ok_add'],'msg_add'=>$array_data['msg_add']);
        }else{ 
            $array_data['current_game']=$current_game;
            return $array_data;
        }    
    }

    public function Add_changePlayer($game,$lang='ar',$api=0,$ch_game_player_id_one=0,$ch_player_id_one=0,$ch_game_player_id_two=0,$ch_player_id_two=0) {
        $game_id=$game->id;
        if($api==0){
            $ch_player_id_one=GetcookieValue('ch_player_id_one');
            $ch_game_player_id_one=GetcookieValue('ch_game_player_id_one');
            $ch_player_id_two=GetcookieValue('ch_player_id_two');
            $ch_game_player_id_two=GetcookieValue('ch_game_player_id_two');
        }
        // print_r('api:'.$api);
        // print_r('ch_player_id_one:'.$ch_player_id_one);
        // print_r('ch_player_id_two:'.$ch_player_id_two);die;
        $array_add=GamePlayer::changeInsidePlayer($game,$ch_game_player_id_one,$ch_player_id_one,$ch_game_player_id_two,$ch_player_id_two);
        $ok_add=$array_add['result'];
        $new_lineup=$array_add['new_lineup'];
        if($ok_add!=1){
            $msg_add =trans('app.not_allow');//.'not found anoth palyer';   
        }else{
            $msg_add = trans('app.add_scuss');
        }
        if($api==0){
            //empty session
            emptySession();
        }
        return array('msg_add'=>$msg_add,'ok_add'=>$ok_add,'new_lineup'=>$new_lineup);
    }

    public function add_direct_insideChange($current_id,$lang='ar',$api=0,$ch_player_data_one=[],$ch_player_data_two=[]){
        $array_data['ok_add']=0;
        $array_data['msg_add'] = trans('app.ERROR_MESSAGE');
        $current_game=[];
        $start_dwry = Eldwry::get_currentDwry();
        if (isset($start_dwry->id)) {
            $current_game = Game::checkregisterDwry($current_id, $start_dwry->id, 1);
            if (isset($current_game->id)) {
                $array_add=GamePlayer::DirectchangeInsidePlayer($current_game,$ch_player_data_one,$ch_player_data_two,$api);
                $array_data['msg_add'] = trans('app.add_scuss');
                $array_data['ok_add']=$array_add['result'];

            }
        } 
        if($api==1){
            if($array_data['ok_add']==-2){
                $array_data['ok_add']=0;
                $array_data['msg_add'] = trans('app.ERROR_MESSAGE');
            }
            return array('ok_add'=>$array_data['ok_add'],'msg_add'=>$array_data['msg_add']);
        }else{ 
            $array_data['current_game']=$current_game;
            return $array_data;
        }    
    }

    public function get_datalineup($current_id,$lang='ar',$api=0) {
        if($api==0){
            $lang = $this->lang;
            $current_id = Auth::user()->id;
        }    
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
       return  array('lineup' => $lineup,'current_lineup'=>$current_lineup);
    }

    public function get_add_linupMyteam($current_id,$linup_link='default_lineup_l',$lang='ar',$api=0) {
        if($api==0){
            $lang = $this->lang;
            $current_id = Auth::user()->id;
        }  
        $num_player = $this->num_player;
        $current_lineup='';//[4,4,2];
        $msg_add = trans('app.add_fail');
        $ok_update=0;
        
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
            return array('player_master' => $player_master,'order_lineup'=>$order_lineup,'current_lineup'=>$current_lineup,'msg_add'=>$msg_add,'ok_update'=>$ok_update);
        } else {
            return array('msg_add'=>$msg_add,'ok_update'=>$ok_update);
        }
    }
    //*************** Triple Bench Card**********************    
    public function check_UserCardsStatusInSubeldwry($user_id){
        $game = Game::get_GameCloum('user_id', $user_id,1);
        $cards_status['bench_card'] = isset($game->bench_card) ? $game->bench_card : 0;
        $cards_status['triple_card'] = isset($game->triple_card) ? $game->triple_card : 0;
        return $cards_status;
    }
    public function Cal_PointUserTripleCapitain($user_id){
        $game = Game::get_GameCloum('user_id', $user_id,1);
        $game->update(['triple_card'=>1]); 
        return true;
    } 

    public function Cal_PointUserBenchPlayers($user_id){
        $game = Game::get_GameCloum('user_id', $user_id,1);
        $game->update(['bench_card'=>1]); 
        return true;
    }

    public function cancelBenchTripleCard($user_id,$colum='bench_card',$return_colum='triple_card'){
        $game = Game::get_GameCloum('user_id', $user_id,1);
        $game->update([$colum=>0]); 
        return $game->$return_colum ;
    } 


}