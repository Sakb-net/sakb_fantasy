<?php

namespace App\Http\Controllers\Site;

use App\Http\Requests\ContactFormRequest;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use App\Models\Options;
use App\Models\Blog;
use App\Models\Post;
use App\Models\Page;
use App\Models\Video;
use App\Models\Match;
use App\Models\Category;
use App\Models\Player;
use App\Models\Team;
use App\Models\Eldwry;
use App\Models\Subeldwry;
use App\Models\DetailMatche;
use App\Models\GamePlayer;
use App\Models\GamePlayerHistory;
use App\Models\PointPlayer;
use App\Models\Contact;
use App\Models\GroupEldwry;
use App\Models\GroupEldwryUser;
use App\Models\HeadGroupEldwry;
use App\Models\HeadGroupEldwryUser;
use App\Models\PriceCard;

use Auth;
use Mail;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\ClassSiteApi\Class_PageController;
use App\Http\Controllers\OptaApi\Class_PointController;

class ScriptController extends SiteController {

    public function __construct() {
        $this_data = Options::Site_Option();
        $this->site_open = $this_data['site_open'];
        $this->def_lang = $this_data['def_lang'];
        $this->site_title = $this_data['site_title'];
        $this->logo_image = $this_data['logo_image'];
        $this->limit = $this_data['limit'];
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function script() {
      PointPlayer::where('detail_playermatches_id',null)->delete();
        $get_point=new Class_PointController();
        $data= Match::where('winner_team_id','<>',null)->get();
        foreach ($data as $key => $data_match) {
            $get_point->cal_pointBounsSystem($data_match,1);       
        }


        // $data= HeadGroupEldwry::get();
        // $group_eldwry=$data[0];
        // foreach ($data as $key => $value) {
        //     HeadGroupEldwryUser::insertGroup($group_eldwry,$value->user_id,[],$value->game_id);
        // }

      print_r('done:'.count($data));die;
    }

    public function script_groupeldwry() {
        $data=GroupEldwry::get();
        $group_eldwry_id=9;
        foreach ($data as $key => $value) {
            GroupEldwryUser::insertGroup($group_eldwry_id,$value->user_id,$value->game_id);
        }

         print_r('done:'.$group_eldwry_id);die;
        $data_test=AllSetting::get_rowSetting('ResultMatchCron');
         print_r($data_test);die;
        $def_lang=$this->def_lang;
        $players=Player::get();
        foreach ($players as $key => $value) {
            GamePlayer::updateOrderColum('player_id',$value->id,'cost', $value->cost);

            GamePlayerHistory::updateOrderColum('player_id',$value->id,'cost', $value->cost);

        }
        print_r(count($players));die;
    }

    public function script_oooooold() {
      $data=Subeldwry::get();
      foreach ($data as $key => $value) {
        PriceCard::InsertData($value->id);
      }

        die;
       $cal_stat=new \App\Http\Controllers\OptaApi\Class_PointController();
        $cal_stat->Cal_AllBeforeSubeldwry_GroupEldwry(1,1,0,13,12,1);

        die;
        $obta_lang ='en';
        $data= GroupEldwry::get();
       foreach ($data as $key => $value) {
            $value->update(['creator_id'=>$value->user_id]);
       }
       $new_array=[];
       $add_json='';
        $data= Blog::get();
       foreach ($data as $key => $value) {
            $array_data=json_decode($value->lang_name,true);
            $new_array[$obta_lang]=$array_data['ar'];
            $add_json=json_encode($new_array);
            ///
           $new_image[$obta_lang]=$value->image;
            $json_image=json_encode($new_image);
            //
            $new_des[$obta_lang]=$value->description;
            $json_description=json_encode($new_des);
            //
            $new_content[$obta_lang]=$value->content;
            $json_content=json_encode($content);
            
            $value->update(['lang_name'=>$add_json,'image'=>$json_image,'description'=>$json_description,'content'=>$json_content]);
       $new_array=[];
       $add_json='';
       die;

        $data= Eldwry::get();
       foreach ($data as $key => $value) {
            $array_data=json_decode($value->lang_name,true);
            $new_array[$obta_lang]=$array_data['ar'];
            $add_json=json_encode($new_array);
            $value->update(['lang_name'=>$add_json]);
       }
       $new_array=[];
       $add_json='';
       $data= Subeldwry::get();
       foreach ($data as $key => $value) {
            $array_data=json_decode($value->lang_name,true);
            $new_array[$obta_lang]=$array_data['ar'];
            $add_json=json_encode($new_array);
            $value->update(['lang_name'=>$add_json]);
       }
       $new_array=[];
       $add_json='';
        $data= Team::get();
       foreach ($data as $key => $value) {
            $array_data=json_decode($value->lang_name,true);
            $new_array[$obta_lang]=$array_data['ar'];
            $add_json=json_encode($new_array);
           $new_image[$obta_lang]=$value->image;
            $json_image=json_encode($new_image);
            $value->update(['lang_name'=>$add_json,'image'=>$json_image]);
       }
       $new_array=[];
       $add_json='';
       $data= Player::get();
       foreach ($data as $key => $value) {
            $array_data=json_decode($value->lang_name,true);
            $new_array[$obta_lang]=$array_data['ar'];
            $add_json=json_encode($new_array);
            $new_image[$obta_lang]=$value->image;
            $json_image=json_encode($new_image);
            $value->update(['lang_name'=>$add_json,'image'=>$json_image]);
       }
       $new_array=[];
       $add_json='';

       $data= Match::get();
       foreach ($data as $key => $value) {
            $array_data=json_decode($value->lang_name,true);
            $new_array[$obta_lang]=$array_data['ar'];
            $new_des[$obta_lang]=$value->description;
            $add_json=json_encode($new_array);
            $json_description=json_encode($new_des);
            $value->update(['lang_name'=>$add_json,'description'=>$json_description]);
       }
       $new_array=[];
       $add_json='';

       print_r('Done');die;
    }
}

     public function script_player() {
        $def_lang=$this->def_lang;//'ar';
        $players=Player::get();
        foreach ($players as $key => $value) {
            $input['image']='/uploads/players/'.$value->team_id.'.png';
            $value->update($input);
        }
        print_r(count($players));die;
    }

    public function script_eldwry() {
        $def_lang=$this->def_lang;//'ar';
        // $offset=0;
        // $limit=9;
        // for ($i=1; $i < 35 ; $i++) { 
        //     $matches=Match::limit($limit)->offset($offset)->get();
        //     $offset=$offset+$limit;
        //     foreach ($matches as $key => $value) {
        //         $input['sub_eldwry_id']=$i;
        //         $value->update($input);
        //     }
        // }  
        // print_r(count($matches));die;

        $subeldwry=Subeldwry::get();
        foreach ($subeldwry as $key => $val_sub) {
           // $count_match=count($val_sub->matches);
           // $subeldwry_match=$val_sub->matches;
           $start_match=$val_sub->matches[0];
           $end_match=$val_sub->matches[8];
           $input=[
                'opta_link'=>$start_match->name,
                'name'=>$start_match->name,
                'lang_name'=>convertValueToJson($start_match->name,$def_lang),
                'is_active'=>1,
                'num_week'=>++$key,
                'start_date'=>$start_match->date,
                'end_date'=>$end_match->date,
            ];
          //print_r($input);die;
            $val_sub->update($input);
        }
        print_r(count($subeldwry));die;
    }

    public function script_old() {
        $data=DetailMatche::where('type','penalties')->get();
        foreach ($data as $key => $value) {
            $state_add=0;

            if(strpos($value->reason,'Goal') !== false){
                $type='var_goal';
                $state_add=1;
                if(strpos($value->reason,'Cancelled') !== false){
                    //upate to zero
                    DetailMatche::updateState_Opta_DetailMatch($value->team_id,'goal',$value->match_id,$value->player_id,'goon',0);
                }
            } elseif(strpos($value->reason,'Card') !== false){
                $type='var_card';
                $state_add=1;
                if(strpos($value->reason,'Cancelled') !== false){
                    DetailMatche::updateState_Opta_DetailMatch($value->team_id,'card',$value->match_id,$value->player_id,'yellow_cart',0,'red_cart',0);
                }
            }
            if($state_add==1){
                $input['type']=$type;
                $input['penalties']=0;
                $value->update($input);
            }

        }
        print_r(count($data));die;
    }
    
}
