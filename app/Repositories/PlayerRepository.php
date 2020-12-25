<?php
namespace App\Repositories;

use Auth;
use App\Models\User;
use App\Models\Player;
use App\Models\DetailPlayerMatche;
use App\Models\Match;
use App\Models\DetailMatche;
use App\Models\Team;
use App\Models\PointPlayer;
// use App\Repositories\PlayerDetailsMatchRepository;

class PlayerRepository
{

    public function DetailsPlayer($name_colum, $player_link,$api,$lang='ar', $is_active = 1)
    {
        $statistics_data=[];
        $details_player_match=[];
        $player_data = Player::get_DataOnePlayer($name_colum, $player_link,$is_active, $lang,$api);
        $player_details = DetailPlayerMatche::get_DetailPlayerCloum('player_id', $player_data['id']);
        foreach ($player_details as $player_detail) {
            $match_detail = $player_detail->match;

            $json['link_match'] =$match_detail->link;
            if($player_detail['team_id'] == $match_detail->first_team_id){
                $againest_team_data = $player_detail->match->teams_second;
                $againest_team_result = isset($match_detail->second_goon) ? $match_detail->second_goon : 0;
                $own_team_data = $player_detail->match->teams_first;
                $own_team_result = isset($match_detail->first_goon) ? $match_detail->first_goon : 0;
            }else{
                $againest_team_data = $player_detail->match->teams_first;
                $againest_team_result = isset($match_detail->first_goon) ? $match_detail->first_goon : 0;
                $own_team_data =$player_detail->match->teams_second;
                $own_team_result = isset($match_detail->second_goon) ? $match_detail->second_goon : 0;
            }

            // $detail_player_matche = DetailMatche::get_DetailMatche('match_id', $player_detail['match_id'], 'team_id', $player_detail['team_id'], 'player_id', $player_detail['player_id']);
            $json['againestTeam'] = isset($againest_team_data->name) ? $againest_team_data->name : '';
            $json['againestTeamResult'] = $againest_team_result;
            $json['ownTeam'] = isset($own_team_data->name) ? $own_team_data->name : '';
            $json['ownTeamResult'] = $own_team_result;
            
            // $json['minsPlayed'] = isset($player_detail->minsPlayed) ? $player_detail->minsPlayed : 0;
            // $json['goals'] = isset($player_detail->goals) ? $player_detail->goals : 0;
            // $json['penalitySave'] = isset($player_detail->attPenTarget) ? $player_detail->attPenTarget : 0;
            // $json['penalityLost'] = isset($player_detail->attPenMiss) ? $player_detail->attPenMiss : 0;
            // $json['reverseGoal'] = isset($player_detail->ownGoals) ? $player_detail->ownGoals : 0;
            // $json['goalAssist'] = isset($player_detail->goalAssist) ? $player_detail->goalAssist : 0;
            // $json['saves'] = isset($player_detail->saves) ? $player_detail->saves : 0;
            // $json['cleanSheet'] = isset($player_detail->cleanSheet) ? $player_detail->cleanSheet : 0;
            // $json['redCard'] = isset($player_detail->redCard) ? $player_detail->redCard : 0;
            // $json['yellowCard'] = isset($player_detail->yellowCard) ? $player_detail->yellowCard : 0;

            $player_sum_in_match = PointPlayer::sum_Finaltotal_SinglePlayerAndMatch($player_detail['player_id'], $player_detail['match_id']);

            $json['points'] = isset($player_sum_in_match) ? $player_sum_in_match : 0; 
            $data_statistic=PointPlayer::All_foundDataTwoColum('player_id',$player_detail['player_id'],'match_id',$player_detail['match_id']);
            $json['statistic'] =PointPlayer::get_StatisticPoint($data_statistic, $api);

            if($match_detail != '')
                $json['week'] = isset($match_detail->week) ? $match_detail->week : 0;
            else
                $json['week'] = 0;

            // if($detail_player_matche != ''){
            //     $json['keyPass'] = $detail_player_matche['keyPass'];
            // }else{
            //     $json['keyPass'] = 0;
            // }
            $json['extraPoints'] = 0;
            /////////////
            // $getplayer=new PlayerDetailsMatchRepository();
            // $details_player_match=$getplayer->DetailsPlayerMatch($match_detail);

            // $all_team_data[] = $againest_team_data;
            // $match_details[] = $match_detail;
            $statistics_data[] = $json;
            // $details_player_match[] = $details_player_match;
        }
          //,'details_player_match'=>$details_player_match,  
        return  array('statistics_data'=>$statistics_data,'player_data'=>$player_data);
    }
    
}