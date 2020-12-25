<?php
namespace App\Repositories;

use Auth;
use App\Models\User;
use App\Models\PointPlayer;
use App\Models\PointBounsSystem;

class PlayerDetailsMatchRepository
{

   public function DetailsPlayerMatch($match)
    {
    	$data_first_team=$match->DetailPlayerMatche->where('team_id', $match->first_team_id);
        $data_second_team=$match->DetailPlayerMatche->where('team_id', $match->second_team_id);

    	$array_first=$this->DetailsTeamInMatch('first_team',$data_first_team);
    	$array_second=$this->DetailsTeamInMatch('second_team',$data_second_team);

    	foreach ($array_first as $key => $value) {
    		$$key['first_team']=array_merge($array_first[$key]['first_team'],$array_second[$key]['first_team']);
    		$$key['second_team']=array_merge($array_first[$key]['second_team'],$array_second[$key]['second_team']);
    	}

        $bouns_system['first_team']=$this->get_pointBounsSystem($match,$match->first_team_id);
        $bouns_system['second_team']=$this->get_pointBounsSystem($match,$match->second_team_id);

        $bouns=$this->get_pointBouns($match,$array_player_id);

        return array('goals'=>$goals,'goalAssist'=>$goalAssist,'goalsConceded'=>$goalsConceded,'saves'=>$saves,'yellowCard'=>$yellowCard,'redCard'=>$redCard,'bouns'=>$bouns,'bouns_system'=>$bouns_system);

    }

    public function DetailsTeamInMatch($type_team,$all_data)
    {
    	$goalsConceded=$saves=$goals=$goalAssist=$yellowCard=$redCard=$array_player_id=['first_team'=>[],'second_team'=>[]];
        $val_bouns=0;
        foreach ($all_data as $key => $val_data) {
            if(isset($val_data->player->id)){
                $data_payer=['player_name'=>$val_data->player->name,'player_link'=>$val_data->player->link];
                
                $array_player_id[$type_team][$val_data->player_id]=$val_data->player_id;
                if($val_data->goals > 0){
                    $data_payer['value']=$val_data->goals;
                    $goals[$type_team][]=$data_payer;
                }
                if($val_data->goalAssist>0){
                    $data_payer['value']=$val_data->goalAssist;
                    $goalAssist[$type_team][]=$data_payer;
                }
                if($val_data->goalsConceded>0){
                    $data_payer['value']=$val_data->goalsConceded;
                    $goalsConceded[$type_team][]=$data_payer;
                }
                if($val_data->saves>0){
                    $data_payer['value']=$val_data->saves;
                    $saves[$type_team][]=$data_payer;
                }
                if($val_data->redCard>0){
                    $data_payer['value']=$val_data->redCard;
                    $redCard[$type_team][]=$data_payer;
                }
                if($val_data->yellowCard>0){
                    $data_payer['value']=$val_data->yellowCard;
                    $yellowCard[$type_team][]=$data_payer;
                }
                // if($val_bouns>0){
                //     $data_payer['value']=0;
                //     $bouns[$type_team][]=$data_payer;
                // }               
            }   
        }

        return array('goals'=>$goals,'goalAssist'=>$goalAssist,'goalsConceded'=>$goalsConceded,'saves'=>$saves,'yellowCard'=>$yellowCard,'redCard'=>$redCard,'array_player_id'=>$array_player_id);
   }

    public function get_pointBounsSystem($match,$team_id,$limit=5)
    {
        $data=PointBounsSystem::get_pointBounsPlayer($match->id,$match->sub_eldwry_id,$team_id,1,$limit);
        $bouns_system=[];
        foreach ($data as $key => $val_data) {
            $bouns_system[]=[
                'player_name'=>$val_data->player->name,
                'player_link'=>$val_data->player->link,
                'value'=>$val_data->points
            ];
        }
        return $bouns_system;
    }

    public function get_pointBouns($match,$array_player_id)
    {
        //$array_player_id['first_team']   $array_player_id['second_team']
        $point_id=55; //point_bouns_system
        
        $all_player_id=array_merge($array_player_id['first_team'],$array_player_id['second_team']);
        
        $data=PointPlayer::getDataPlayerPointMatch($match->sub_eldwry_id,$match->id,$all_player_id,$point_id);

        $bouns=['first_team'=>[],'second_team'=>[]];
        foreach ($data as $key => $val_data) {
            if(in_array($val_data->player_id, $array_player_id['first_team'])){
                $type_team='first_team';
            }elseif(in_array($val_data->player_id, $array_player_id['second_team'])){
                $type_team='second_team';
            }    
            $bouns[$type_team][]=[
                'player_name'=>$val_data->player->name,
                'player_link'=>$val_data->player->link,
                'value'=>$val_data->points
            ];

        }
        return $bouns;
    }

}
