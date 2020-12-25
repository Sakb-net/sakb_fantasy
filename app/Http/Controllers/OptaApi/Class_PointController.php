<?php

namespace App\Http\Controllers\OptaApi;

use Illuminate\Http\Request;
// use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
// use App\Models\Options;
// use App\Models\Eldwry;
use App\Models\Subeldwry;
// use App\Models\Team;
// use App\Models\Match;
use App\Models\DetailPlayerMatche;
use App\Models\Point;
use App\Models\PointBounsSystem;
use App\Models\PointPlayer;
use App\Models\PointUser;
use App\Models\GamePlayerHistory;
use App\Models\GroupEldwry;
use App\Models\GroupEldwryStatic;
use App\Models\GroupEldwryTeamStatic;
use App\Models\HeadGroupEldwry;
use App\Models\HeadGroupEldwryTeamStatic;
use App\Models\HeadGroupEldwryMatch;
use App\Models\HeadGroupEldwryStatic;
use App\Models\HeadGroupEldwryUser;

use App\Http\Controllers\Admin\AdminController;
// use App\Http\Controllers\OptaApi\analysis_OptaController;

class Class_PointController extends AdminController {


 //*****************Cal_PointPlayer*************************** 
    public function Cal_PointPlayer($detail_playerId,$flage_id=0){
        $all_points=Point::get_DataAll('type','NP');
        if($flage_id==1){
            $detail_player=$detail_playerId;
        }else{
            $detail_player=DetailPlayerMatche::foundDataCondition('id',$detail_playerId);
        }


//NotUse*********attPenGoal ,  attPenTarget ,goalAssistDeadball ,assistOwnGoal ,assistPenaltyWon 


//use*********minsPlayed,goals,goalAssist,cleanSheet,saves,penaltySave,goalsConceded,redCard,yellowCard,ownGoals,attPenMiss

        $ok_add=$val_point=$number=0;
        foreach ($all_points as $key => $value) {
            $ok_add=$val_point=0;
            if($value->type_key=='playing_up_60_minutes'){
                if($detail_player->minsPlayed >0 && $detail_player->minsPlayed<=60){
                    $ok_add=1;
                    $number=$detail_player->minsPlayed;
                    $val_point=$value->point;
                }
            }elseif($value->type_key=='playing_more_60_minutes'){
                if($detail_player->minsPlayed>60){
                    $ok_add=1;
                    $number=$detail_player->minsPlayed;
                    $val_point=$value->point;
                }
            }elseif($value->type_key=='goal_goalkeeper_or_defender'){
                if(in_array($detail_player->location_id,[1,5,6,7]) && $detail_player->goals > 0){
                    $ok_add=1;
                    $number=$detail_player->goals;
                    $val_point=$value->point * $number;
                }
            }elseif($value->type_key=='goal_midfielder'){
                if(in_array($detail_player->location_id,[8,9,10]) && $detail_player->goals > 0){
                    $ok_add=1;
                    $number=$detail_player->goals;
                    $val_point=$value->point * $number;
                }
            }elseif($value->type_key=='goal_forward'){
                if(in_array($detail_player->location_id,[2,3,4]) && $detail_player->goals > 0){
                    $ok_add=1;
                    $number=$detail_player->goals;
                    $val_point=$value->point * $number;
                }
            }elseif($value->type_key=='goal_assist'){
                if($detail_player->goalAssist > 0){
                    $ok_add=1;
                    $number=$detail_player->goalAssist;
                    $val_point=$value->point * $number;
                }
            }elseif($value->type_key=='cleansheet_goalkeeper_or_defender'){
                if(in_array($detail_player->location_id,[1,5,6,7]) && $detail_player->cleanSheet > 0){
                    $ok_add=1;
                    $number=$detail_player->cleanSheet;
                    $val_point=$value->point * $number;
                }
            }elseif($value->type_key=='cleansheet_midfielder'){
                if(in_array($detail_player->location_id,[8,9,10]) && $detail_player->cleanSheet > 0){
                    $ok_add=1;
                    $number= $detail_player->cleanSheet;
                    $val_point=$value->point *$number;
                }
            }elseif($value->type_key=='every_3_shotsaves_goalkeeper'){
                if(in_array($detail_player->location_id,[1]) && $detail_player->saves > 0){
                    $num_value=floor($detail_player->saves/3);//round
                    if($num_value>0){
                        $number=$detail_player->saves;
                        $ok_add=1;
                        $val_point=$value->point * $num_value;
                    }
                }
            }elseif($value->type_key=='penalty_save'){
                if($detail_player->penaltySave > 0){
                    $ok_add=1;
                    $number= $detail_player->penaltySave;
                    $val_point=$value->point *$number;
                }
            }elseif($value->type_key=='penaltymiss'){
                if($detail_player->attPenMiss > 0){
                    $ok_add=1;
                    $number=$detail_player->attPenMiss;
                    $val_point=$value->point * $number;
                }
            }elseif($value->type_key=='2_goalsconceded_goalkeeper_or_defender'){
                if(in_array($detail_player->location_id,[1,5,6,7]) && $detail_player->goalsConceded > 0){
                    $num_value=floor($detail_player->goalsConceded/2);//round
                    if($num_value>0){
                        $number=$detail_player->goalsConceded;
                        $ok_add=1;
                        $val_point=$value->point * $num_value;
                    }
                }
            }elseif($value->type_key=='yellow_card'){
                if($detail_player->yellowCard > 0){
                    $ok_add=1;
                    $number=$detail_player->yellowCard;
                    $val_point=$value->point * $number;
                }
            }elseif($value->type_key=='red_card'){
                if($detail_player->redCard > 0){
                    $ok_add=1;
                    $number=$detail_player->redCard;
                    $val_point=$value->point * $number;
                }
            }elseif($value->type_key=='own_goal'){
                if($detail_player->ownGoals > 0){
                    $ok_add=1;
                    $number=$detail_player->ownGoals;
                    $val_point=$value->point * $number;
                }
            // }elseif($value->type_key=='Bonus_best_player'){ //1-3
            //     //$number
            //     $value->type=="NP" //normal point

            }
            if($ok_add==1){
                //add/update points in PointPlayer
                PointPlayer::AddUpdatePointPlayer($detail_player,$value,$val_point,$number);
            }

        }
        return true;
    }
//*****************Cal_PointBouns Player
    public function cal_pointBounsSystem($match,$admin=0){
        // $array_bouns['number']=$array_bouns['val_point']=0;
        $first_team_players=$match->DetailPlayerMatche->where('team_id', $match->first_team_id)->toArray();
        $second_team_players=$match->DetailPlayerMatche->where('team_id', $match->second_team_id)->toArray();
        $this->Cal_totalPointBounsSystem($match,$first_team_players);
        $this->Cal_totalPointBounsSystem($match,$second_team_players);
        //final cal point bouns (3,2,1)
        if($admin==1){
            $this->Cal_PlayerBonusPoints($match->id,$match->sub_eldwry_id,$match->first_team_id);
            $this->Cal_PlayerBonusPoints($match->id,$match->sub_eldwry_id,$match->second_team_id);
        }
        return true;
    }

    public static function Cal_totalPointBounsSystem($match,$team_players){
        $sum = 0;
        $passComplation=0;
        if(isset($team_players)){
            foreach ($team_players as $player) {
                $sum = 0;
                if($player['minsPlayed'] <= 60){
                    $sum = $sum+3;
                }
                if($player['minsPlayed'] > 60){
                    $sum = $sum+6;
                }
                if($player['location_id'] = 1 | $player['location_id'] = 5){
                    $sum = $sum+($player['goals'] * 12);
                }
                if($player['location_id'] = 8){
                    $sum = $sum+($player['goals'] * 18);
                }
                if($player['goalAssist'] > 0){
                    $sum = $sum+($player['goalAssist'] * 9);
                }
                if($player['cleanSheet'] > 0){
                    $sum = $sum+($player['cleanSheet'] * 12);
                }
                if($player['penaltySave'] > 0){
                    $sum = $sum+($player['penaltySave'] * 15);
                }
                if($player['saves'] > 0){
                    $sum = $sum+($player['saves'] * 2);
                }
                if($player['totalClearance'] > 0){
                    $sum = $sum+($player['totalClearance'] * 1);
                }
                if($player['wonTackle'] > 0){
                    $sum = $sum+($player['wonTackle'] * 2);
                }
                if($player['attPenMiss'] > 0){
                    $sum = $sum+($player['attPenMiss'] * -6);
                }
                if($player['yellowCard'] > 0){
                    $sum = $sum+($player['yellowCard'] * -3);
                }
                if($player['redCard'] > 0){
                    $sum = $sum+($player['redCard'] * -9);
                }
                if($player['ownGoals'] > 0){
                    $sum = $sum+($player['ownGoals'] * -6);
                }
                if($player['fouls'] > 0){
                    $sum = $sum+($player['fouls'] * -1);
                }
                if($player['totalOffside'] > 0){
                    $sum = $sum+($player['totalOffside'] * -1);
                }
                if($player['shotOffTarget'] > 0){
                    $sum = $sum+($player['shotOffTarget'] * -1);
                }
                if ($player['totalPass'] > 29){
                    $passComplation=($player['accuratePass']/$player['totalPass'])*100;
                }
                if($passComplation>70 && $passComplation<80){
                    $sum=$sum+2;
                }else if($passComplation>80 && $passComplation<90){
                    $sum=$sum+4;
                }else if($passComplation>90){
                    $sum=$sum+6;
                }
                $input=[
                    'player_id'=>$player['player_id'],
                    'point_id'=>null,
                    'points'=>$sum,
                    'match_id'=>$match->id,
                    'sub_eldwry_id'=>$match->sub_eldwry_id,
                    'team_id'=>$player['team_id'],
                    'is_active'=>1,

                ];
                PointBounsSystem::AddUpdateBounsPlayer($input);
            }
            
        }
        return true;
    }

    public static function Cal_PlayerBonusPoints($match_id,$sub_eldwry_id,$team_id,$limit=5){
        $data=PointBounsSystem::get_pointBounsPlayer($match_id,$sub_eldwry_id,$team_id,1,$limit);
        $total_point=0;
        foreach ($data as $key => $value) {
            $total_point +=$value->points;
        }
        $avg_points=round($total_point/$limit,2);
        $min_point=25;
        $start_point=$val_point=0;
        $point_id=55;  //point_bouns_system BP
        if($avg_points > $min_point){
            foreach ($data as $key => $value) {
                if($key==0 && $value->points >$min_point){
                    $start_point=$value->points;
                    $val_point=3;
                }
                if($key > 0 && $start_point > $value->points && $value->points >$min_point){
                    $start_point=$value->points;
                    $val_point -=1;
                }else{
                    if($key > 0){
                        $val_point=0;
                    } 
                }
                if($val_point>0){
                    PointPlayer::AddUpdateBounsPointPlayer($sub_eldwry_id,$value->player_id,$match_id,$point_id,$val_point,1);
                }
            }
        }    
        return true;
    }

 //*****************Cal_PointUser***************************
    public function Cal_PointUser($sub_eldwry_id,$flage_id=0,$limit=0,$offset=-1){
        $array_playerPoint=PointPlayer::sum_Finaltotal('sub_eldwry_id',$sub_eldwry_id,1);

         $details_players=GamePlayerHistory::GetHistoryPlayUser_Subeldwry($sub_eldwry_id,1,'ASC',$limit,$offset,'<',12); 
         $bench_card_game_history_id=$triple_card_game_history_id=[];
        foreach ($details_players as $key => $value) {
            if($value->gameshistory->bench_card==1){
                $bench_card_game_history_id[]=$value->game_history_id;
            }elseif($value->gameshistory->triple_card==1){
                $triple_card_game_history_id[]=$value->game_history_id;
            }
            if(isset($array_playerPoint[$value->player_id])){
                $val_point=PointUser::User_ConditioCountPoint($sub_eldwry_id,$value,$array_playerPoint[$value->player_id],$triple_card_game_history_id);
                PointUser::AddUpdatePointUser($sub_eldwry_id,$value,$val_point);
            }
        }
        if(count($bench_card_game_history_id)>0){
            //get player >=12 subplayer
            $this->Cal_PointUser_SubPlayer($bench_card_game_history_id,$sub_eldwry_id,$array_playerPoint,$limit,$offset);
        }
        $this->Cal_HeadGroupEldwry($sub_eldwry_id);
        $this->Cal_GroupEldwry($sub_eldwry_id); //Classic
        return true;
    }

    public function Cal_PointUser_SubPlayer($bench_card_game_history_id,$sub_eldwry_id,$array_playerPoint,$limit,$offset){
        //get player >=12 subplayer
        $sub_details_players=GamePlayerHistory::GetHistorySubPlayUser_Subeldwry($sub_eldwry_id,1,'ASC',$limit,$offset,'>=',12,$bench_card_game_history_id);
        foreach ($sub_details_players as $key => $value) {
            if(isset($array_playerPoint[$value->player_id])){
                PointUser::AddUpdatePointUser($sub_eldwry_id,$value,$array_playerPoint[$value->player_id]);
            }
        }
        return true;
    }
///*****************GroupEldwry *************************
    public function Cal_GroupEldwry($sub_eldwry_id){ //Classic
        //account points and statistic for groups eldwry
        $groups=GroupEldwry::Get_allow_sub_eldwry($sub_eldwry_id);
        $arraypoint_user=[];
        foreach ($groups as $key_group => $val_group) {
            $arraypoint_user[$val_group->user_id]=GroupEldwryTeamStatic::InsertGroupStatic($val_group->id,$val_group->game_id,$val_group->user_id,$sub_eldwry_id,$arraypoint_user);
            $this->GroupEldwryuser_Static($val_group,$sub_eldwry_id,$arraypoint_user);
            $this->SortGroupEldwry_Static($sub_eldwry_id,$val_group->id);
        }
        $this->FinalGroupEldwryStatic($sub_eldwry_id);
        return true;
    }

    public function Cal_HeadGroupEldwry($sub_eldwry_id){
        //account points and statistic for groups eldwry
        $groups=HeadGroupEldwry::Get_allow_sub_eldwry($sub_eldwry_id);
        foreach ($groups as $key_group => $val_group) {
            HeadGroupEldwryTeamStatic::InsertPointGroupTeamStatic($val_group->id,$sub_eldwry_id);
            $this->SortHeadGroupEldwry_Static($sub_eldwry_id,$val_group->id);
        }
        $this->FinalHeadGroupEldwryStatic($sub_eldwry_id);
        $this->NextHeadGroupEldwryStatic($sub_eldwry_id);
        return true;
    }
//******************* Classic Group********************************
     
    public function GroupEldwryuser_Static($val_group,$sub_eldwry_id,$arraypoint_user){
        $groups_user=$val_group->group_eldwry_user;
        foreach ($groups_user as $key_user => $val_user) {
            $arraypoint_user[$val_user->add_user_id]=GroupEldwryTeamStatic::InsertGroupStatic($val_group->id,$val_user->game_id,$val_user->add_user_id,$sub_eldwry_id,$arraypoint_user);
        }
        return true;
    }

    public function SortGroupEldwry_Static($sub_eldwry_id,$group_eldwry_id){
        $groups_user=GroupEldwryTeamStatic::All_foundDataTwoCondition('sub_eldwry_id',$sub_eldwry_id,'group_eldwry_id',$group_eldwry_id,'points','DESC');
        foreach ($groups_user as $key_user => $val_user) {
            $val_user->update(['sort'=>++$key_user]);
        }
        return true;
    }

    public function FinalGroupEldwryStatic($sub_eldwry_id){
        $groups_user=GroupEldwryTeamStatic::Sort_Finaltotal_group('sub_eldwry_id',$sub_eldwry_id);
        foreach ($groups_user as $key_user => $val_group) {
            GroupEldwryStatic::InsertGroupStatic($val_group->group_eldwry_id,$sub_eldwry_id,$val_group->sum_points,++$key_user);
        }
        return true;
    }
    public function Cal_AllBeforeSubeldwry_GroupEldwry($user_id,$eldwry_id,$start_sub_eldwry_id,$end_sub_eldwry_id,$group_eldwry_id,$game_id){
        //account points and statistic for groups eldwry
        $sub_eldwrys=Subeldwry::getSubeldwryByRang($eldwry_id,1,$start_sub_eldwry_id,$end_sub_eldwry_id);
        $arraypoint_user=[];
        foreach ($sub_eldwrys as $key_sub => $val_sub_eldwry) {
            GroupEldwryTeamStatic::InsertGroupStatic($group_eldwry_id,$game_id,$user_id,$val_sub_eldwry->id,$arraypoint_user);
            $this->SortGroupEldwry_Static($val_sub_eldwry->id,$group_eldwry_id);
            $this->FinalGroupEldwryStatic($val_sub_eldwry->id);
        }
        return true;
    }

    public function Delete_AllBeforeSubeldwry_GroupEldwry($user_id,$eldwry_id,$start_sub_eldwry_id,$end_sub_eldwry_id,$group_eldwry_id,$game_id){
        //account points and statistic for groups eldwry
        $sub_eldwrys=Subeldwry::getSubeldwryByRang($eldwry_id,1,$start_sub_eldwry_id,$end_sub_eldwry_id);
        foreach ($sub_eldwrys as $key_sub => $val_sub_eldwry) {
            GroupEldwryTeamStatic::DeleteGroupStatic($group_eldwry_id,$game_id,$user_id,$val_sub_eldwry->id);
            $this->SortGroupEldwry_Static($val_sub_eldwry->id,$group_eldwry_id);
            $this->FinalGroupEldwryStatic($val_sub_eldwry->id);
        }
        return true;
    }
    //************************Head To Head Group**************************

    public function SortHeadGroupEldwry_Static($sub_eldwry_id,$head_group_eldwry_id){
        HeadGroupEldwryTeamStatic::updateGroupteamStatic('sub_eldwry_id',$sub_eldwry_id,'head_group_eldwry_id',$head_group_eldwry_id,'bouns',0);
        $groups_teams=HeadGroupEldwryTeamStatic::All_foundDataTwoCondition('sub_eldwry_id',$sub_eldwry_id,'head_group_eldwry_id',$head_group_eldwry_id,'points','DESC');

        $array_points=array_values($groups_teams->pluck('points')->toArray());
        foreach ($groups_teams as $key_user => $val_team) {
            $val_bouns=0;
            if(isset($array_points[0]) && $array_points[0] == $val_team->points && $val_team->points >0 ){
                $val_bouns=3;
            }elseif(isset($array_points[1]) && $array_points[1] == $val_team->points  && $val_team->points >0  ){
                $val_bouns=2;
            }elseif(isset($array_points[2]) && $array_points[2] == $val_team->points  && $val_team->points >0 ){
                $val_bouns=1;
            }else{
                break;
            }
            $val_team->update(['bouns'=>$val_bouns]);
        }
        return true;
    }

    public function FinalHeadGroupEldwryStatic($sub_eldwry_id){
        $groups_teams=HeadGroupEldwryTeamStatic::Sort_Finaltotal_group('sub_eldwry_id',$sub_eldwry_id);
        foreach ($groups_teams as $key_team => $val_group) {
            HeadGroupEldwryStatic::InsertGroupStatic($val_group->head_group_eldwry_id,$sub_eldwry_id,$val_group->sum_points,++$key_team,1);
        }
        return true;
    }

    public function NextHeadGroupEldwryStatic($sub_eldwry_id){
        $next_subeldwry=Subeldwry::foundData('id',$sub_eldwry_id,'>');
        if(isset($next_subeldwry->id)){
            $groups=HeadGroupEldwry::Get_allow_sub_eldwry($next_subeldwry->id);
            foreach ($groups as $key_group => $val_group) {
                $this->addUserNextHeadGroupEldwry($val_group,$next_subeldwry->id);
            }
        }
        return true;
    }

    public function addUserNextHeadGroupEldwry($head_group_eldwry,$next_subeldwry_id){
        $group_users = HeadGroupEldwryUser::get_data_group_eldwry($head_group_eldwry->id,1,0,1,'add_user_id');
        $group_users=array_unique(array_merge($group_users,[$head_group_eldwry->user_id])); 
        foreach ($group_users as $key => $user_id) {
            $team_static = HeadGroupEldwryTeamStatic::InsertGroupTeamStatic($head_group_eldwry->id,$next_subeldwry_id,$user_id,0,0);
            $match_static = HeadGroupEldwryMatch::InsertGroupMatch($head_group_eldwry->id,$next_subeldwry_id,$team_static['id']);
        }
        return true;
    }

}
