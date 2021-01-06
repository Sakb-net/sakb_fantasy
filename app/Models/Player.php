<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Team;
use App\Models\PointPlayer;
use App\Models\GamePlayer;
use DB;

class Player extends Model {
    protected $table = 'players';
    protected $fillable = [
        'update_by', 'team_id', 'type_id','lastName','firstName','name','lang_name', 'link', 'location_id', 'cost',
        'image','image_match', 'is_active','num_t_shirt','nationality','nationalityId','opta_link','weight','height','countryOfBirth','countryOfBirthId','dateOfBirth','placeOfBirth','foot','real_position','real_position_side','join_date'
    ];

    //type_id --->  spare - basic  //احتياطى - اساسى

    public function user() {
        return $this->belongsTo(\App\Models\User::class, 'update_by');
    }

    public function alltypes() {
        return $this->belongsTo(\App\Models\AllType::class, 'type_id');
    }

    public function location_player() {
        return $this->belongsTo(\App\Models\LocationPlayer::class, 'location_id');
    }

    public function teams() {
        return $this->belongsTo(\App\Models\Team::class,'team_id');
    }

    public function player_points()
    {
        return $this->hasMany('App\Models\PointPlayer');
    }
    public static function Add_Opta_Player($data,$team_id,$def_lang='en') {
        $position=isset($data['position']) ? $data['position'] : null;
        $input=[
            'opta_link'=>$data['id'],
            'name'=>$data['matchName'],
            'lang_name'=>convertValueToJson($data['matchName'],$def_lang),
            'is_active'=>checkDataBool($data['status']),
            'firstName'=>$data['firstName'],
            'lastName'=>$data['lastName'],
            'nationality'=>isset($data['nationality']) ? $data['nationality'] : null,
            'nationalityId'=>isset($data['nationalityId']) ? $data['nationalityId'] : null,
            'dateOfBirth'=>isset($data['dateOfBirth']) ? $data['dateOfBirth'] : null,
            'placeOfBirth'=>isset($data['placeOfBirth']) ? $data['placeOfBirth'] : null,
            'countryOfBirthId'=>isset($data['countryOfBirthId']) ? $data['countryOfBirthId'] : null,
            'countryOfBirth'=>isset($data['countryOfBirth']) ? $data['countryOfBirth'] : null,
            'height'=>isset($data['height']) ? $data['height'] : null,
            'weight'=>isset($data['weight']) ? $data['weight'] : null,
            'foot'=>isset($data['foot']) ? $data['foot'] : null,
            'location_id'=>get_LocationId($position),
            'type_id'=>5,
            'team_id'=>$team_id,
            'num_t_shirt'=>null,
            'cost'=>5,
            'image'=>isset($data['image']) ? $data['image'] : '/images/default_player.png',
            'image_match'=>isset($data['image']) ? $data['image'] : null,
        ];
        $data_found=static::get_PlayertwoCondtion('team_id',$team_id,'opta_link',$input[
            'opta_link']);
        if(isset($data_found->id)){
            //for test
                // $num_image=$data_found->id;
                // if($data_found->id >30 && $data_found->id < 46){
                //    $num_image=$data_found->id-30;
                // }elseif($data_found->id >16  && $data_found->id < 31){
                //    $num_image=$data_found->id-16;
                // }else{
                //     $num_image=1;
                // }
                // $input['image']='/uploads/players/'.$num_image.'.png';
            //end for test
            $data_found->update($input);
        }else{
            $input['link']=get_RandLink();
            static::create($input);
        }
        return true;
    }
    public static function updateColum($id, $colum, $columValue) {
        $data = static::findOrFail($id);
        $data->$colum = $columValue;
        return $data->save();
    }

    public static function updateOrderColum($colum, $valueColum, $columUpdate, $valueUpdate) {
        return static::where($colum, $valueColum)->update([$columUpdate => $valueUpdate]);
    }

    public static function foundData($colum, $val) {
        $link_found = static::where($colum, $val)->first();
        return $link_found;
    }

    public static function All_foundData($colum, $val) {
        $link_found = static::where($colum, $val)->get();
        return $link_found;
    }

    public static function get_PlayerID($id, $colum, $all = 0) {
        $Player = static::where('id', $id)->first();
        if ($all == 0) {
            return $Player->$colum;
        } else {
            return $Player;
        }
    }

    public static function get_PlayerRow($id, $colum = 'id', $is_active = 1) {
       return static::where($colum, $id)->where('is_active', $is_active)->first();
    }
    public static function get_PlayerOneCondtion($colum, $val) {
        return static::where($colum, $val)->first();
    }
    public static function get_PlayerName($colum, $val) {
        return static::select(DB::raw('name'))->where($colum, $val)->first();
    }
    public static function get_AllPlayersName() {
        return static::select(DB::raw('id, name'))->pluck('name','id')->toArray();
    }
    public static function get_PlayertwoCondtion($colum, $val,$colum2, $val2) {
        return static::where($colum, $val)->where($colum2, $val2)->first();
    }

    public static function get_PlayerCloum($colum = 'id', $val = '', $is_active = 1) {
        return static::where($colum, $val)->where('is_active', $is_active)->orderBy('id', 'DESC')->first();
    }

    public static function get_DataColum($colm = 'location_id', $val = '', $is_active = '') {
        $data = static::where($colm, $val);
        if (!empty($is_active)) {
            $result = $data->where('is_active', $is_active);
        }
        $result = $data->get();
        return $result;
    }

    public static function get_DataAll($is_active = '') {
        if (!empty($is_active)) {
            $data = static::where('is_active', $is_active)->get();
        } else {
            $data = static::get();
        }
        return $data;
    }
    public static function get_DataAllPlayer($is_active = '1', $lang='ar',$order_play='', $link_team = '', $type_key='', $offset=1, $api=0, $array=1,$limit = 20) {
        if($order_play == 'lowest_point'){
            $val_order = 'ASC';
        }else{
            $val_order = 'DESC';
        }
        $final_offset=$offset-1;
        if($final_offset < 0){
            $final_offset=0;
        }
        $final_offset=$limit*$final_offset;
        $data = DB::table('players')
            ->leftJoin('point_players', 'players.id', '=', 'point_players.player_id')
            ->join('all_types', 'players.type_id', '=', 'all_types.id')
            ->join('teams', 'players.team_id', '=', 'teams.id')
            ->join('location_player', 'players.location_id', '=', 'location_player.id');

        // if($lang = 'ar'){
        //     $result = $data->select(DB::raw('players.*, location_player.type_key, location_player.value_ar as location_player_lang, all_types.value_ar as all_types_lang, teams.name as team_name, teams.link as team_link, teams.code as team_code, SUM(point_players.points) as points'));
        // }else{
        //     $result = $data->select(DB::raw('players.*, location_player.type_key, location_player.value_en as location_player_lang, all_types.value_en as all_types_lang, teams.name as team_name, teams.link as team_link, teams.code as team_code, SUM(point_players.points) as points'));
        // }

        $result = $data->select(DB::raw('players.*, location_player.type_key, location_player.value_en, location_player.value_ar , all_types.value_en as type_value_en, all_types.value_ar  as type_value_ar, teams.name as team_name, teams.link as team_link, teams.code as team_code, SUM(point_players.points) as points'));

        if(!empty($type_key)){
            $result = $data->Where('location_player.type_key', 'like', '%' . $type_key . '%');
        }
        if(!empty($link_team)){
            $result = $data->where('teams.link', $link_team);
        }
        $result = $data->where('players.is_active', $is_active)//->where('teams.link', $link_team)
        ->groupBy('players.id') 
        ->orderBy('points', $val_order);
        if($limit>0 &&$final_offset>-1){
            $result=$data->limit($limit)->offset($final_offset);
        }
        $result = $data->get();
        // print_r($result);die;

        /////////////////////////////
        $dataCount = DB::table('players')
        ->leftJoin('point_players', 'players.id', '=', 'point_players.player_id')
        // ->join('all_types', 'players.type_id', '=', 'all_types.id')
        ->join('teams', 'players.team_id', '=', 'teams.id')
        ->join('location_player', 'players.location_id', '=', 'location_player.id');
        $count = $dataCount->select(DB::raw('players.*, SUM(point_players.points) as points'));
        if(!empty($type_key)){
            $count = $dataCount->Where('location_player.type_key', 'like', '%' . $type_key . '%');
        }
        if(!empty($link_team)){
            $count = $dataCount->where('teams.link', $link_team);
        }
        $count = $dataCount->where('players.is_active', $is_active)//->where('teams.link', $link_team)
        ->groupBy('players.id') 
        ->orderBy('points', $val_order)
        ->get()
        ->count();
        $reslt_data = [];
        foreach ($result as $key => $val_cat) {
            $reslt_data[] = static::single_DataPlayerUserJoin($val_cat, $lang, $api);
        }
        $count_pag=round($count/$limit,0);
        return array('all_data'=>$reslt_data,'count_pag'=>$count_pag, 'count'=>$count);
    }

    public static function get_PlayersLocation($order_play,$lang='ar',$location_id,$is_active=1,$limit=15,$final_offset=0,$type_key='',$link_team=''){

        if($order_play == 'lowest_point'){
            $val_order = 'ASC';
        }else{
            $val_order = 'DESC';
        }
        $data = Player::leftJoin('point_players', 'players.id', '=', 'point_players.player_id')
            ->join('all_types', 'players.type_id', '=', 'all_types.id')
            ->join('teams', 'players.team_id', '=', 'teams.id')
            ->join('location_player', 'players.location_id', '=', 'location_player.id');
        
        // if($lang = 'ar'){
        //     $result = $data->select(DB::raw('players.*, location_player.type_key, location_player.value_ar as location_player_lang, all_types.value_ar as all_types_lang, teams.name as team_name, teams.link as team_link, teams.code as team_code, SUM(point_players.points) as points'));
        // }else{
        //     $result = $data->select(DB::raw('players.*, location_player.type_key, location_player.value_en as location_player_lang, all_types.value_en as all_types_lang, teams.name as team_name, teams.link as team_link, teams.code as team_code, SUM(point_players.points) as points'));
        // }

        $result = $data->select(DB::raw('players.*, location_player.type_key, location_player.value_en, location_player.value_ar , all_types.value_en as type_value_en, all_types.value_ar  as type_value_ar, teams.name as team_name, teams.link as team_link, teams.code as team_code, SUM(point_players.points) as points'));
        if(!empty($type_key)){
            $result = $data->Where('location_player.type_key', 'like', '%' . $type_key . '%');
        }else{
            $result = $data->where('players.location_id', $location_id);
        }
        if(!empty($link_team)){
            $result = $data->where('teams.link', $link_team);
        }
        $result = $data->where('players.is_active', $is_active)
        ->groupBy('players.id') 
        ->orderBy('points', $val_order);
        if($limit>0 &&$final_offset>-1){
            $result=$data->limit($limit)->offset($final_offset);
        }
        $result=$data->get();
        return $result;
    }

    public static function selectRandamTeam($colum,$val,$team_id,$array_location_id,$cost=0,$limit=0) {
        $data = static::where($colum,$val)->where('team_id',$team_id)->whereIn('location_id',$array_location_id);
        if($cost>0){
            $result=$data->where('cost','<=',$cost);
        }
        $result=$data->inRandomOrder();
        if($limit>0){
            $result=$data->limit($limit);
        }
        $result=$data->get();
        return $result;
    }
    public static function SearchPlayer($search, $is_active = '', $limit = 0) {
        $data = static::Where('name', 'like', '%' . $search . '%')
                ->orWhere('image', 'like', '%' . $search . '%')
                ->orWhere('link', 'like', '%' . $search . '%')
                ->orWhere('start_date', 'like', '%' . $search . '%')
                ->orWhere('cost', 'like', '%' . $search . '%');

        if (!empty($is_active)) {
            $result = $data->where('is_active', $is_active);
        }
        if ($limit > 0) {
            $result = $data->paginate($limit);
        } elseif ($limit == -1) {
            $result = $data->pluck('id', 'id')->toArray();
        } else {
            $result = $data->get();
        }
        return $result;
    }
    
    public static function SearchTeamPlayer($search, $is_active = '',$array_player_id = [], $link_team = '',$typeLocation_key='',$limit=0,$final_offset=-1) {
        $data = static::leftJoin('teams', 'teams.id', '=', 'players.team_id')
                ->leftJoin('location_player', 'location_player.id', '=', 'players.location_id')
                ->select('players.*')
                ->where(function($q) use($search) {
                     $q->Where('players.name', 'like', '%' . $search . '%')
                       ->orWhere('players.image', 'like', '%' . $search . '%')
                        ->orWhere('players.link', 'like', '%' . $search . '%')
                        ->orWhere('players.cost', 'like', '%' . $search . '%')
                        ->Where('teams.name', 'like', '%' . $search . '%')
                        ->orWhere('teams.image', 'like', '%' . $search . '%')
                        ->orWhere('teams.link', 'like', '%' . $search . '%')
                        ->orWhere('location_player.value_en', 'like', '%' . $search . '%')
                        ->orWhere('location_player.value_ar', 'like', '%' . $search . '%')
                        ->orWhere('location_player.type_key', 'like', '%' . $search . '%')
                        ->orWhere('location_player.color', 'like', '%' . $search . '%');
                     });
                
        if (!empty($typeLocation_key)) {
            $result = $data->where('location_player.type_key', 'like', '%' . $typeLocation_key . '%');
        }
        if (!empty($array_player_id)) {
            $result = $data->whereIn('players.id', $array_player_id);
        }
        if (!empty($link_team)) {
            $result = $data->where('teams.link', $link_team);
        }
        if (!empty($is_active)) {
            $result = $data->where('players.is_active', $is_active);
        }
        if ($limit > 0 && $final_offset>-1) {
            $result = $data->limit($limit)->offset($final_offset)->get();
        }elseif ($limit > 0) {
            $result = $data->paginate($limit);
        } elseif ($limit == -1) {
            $result = $data->pluck('id', 'id')->toArray();
        } else {
            $result = $data->get();
        }
        return $result;
    }

    public static function ColumTeamPlayer($is_active = '',$array_player_id = [], $link_team = '',$limit=0,$final_offse=-1) {
        $data = static::leftJoin('teams', 'teams.id', '=', 'players.team_id')
                ->select('players.*');

        if (!empty($array_player_id)) {
            $result = $data->whereIn('players.id', $array_player_id);
        }
        if (!empty($link_team)) {
            $result = $data->where('teams.link', $link_team);
        }
        if (!empty($is_active)) {
            $result = $data->where('players.is_active', $is_active);
        }
        if ($limit > 0 && $final_offset>-1) {
            $result = $data->limit($limit)->offset($final_offset)->get();
        }elseif ($limit > 0) {
            $result = $data->paginate($limit);
        } elseif ($limit == -1) {
            $result = $data->pluck('id', 'id')->toArray();
        } else {
            $result = $data->get();
        }
        return $result;
    }
    public static function get_PlayerByArray($array_player_id,$array=0,$col_pluck='id'){
        $data = static::whereNotIn('id',$array_player_id);
        if($array ==1){
            $result =$data->pluck($col_pluck,'id')->toArray(); 
        }else{
            $result =$data->get();
        }
        return $result;
    }

//**********************function ************************
    public static function get_DataGroupForStatistic($data, $lang = 'ar', $filter = 0, $api = 0, $from_price = '', $to_price = '', $link_team = '', $order_play = '', $word_search = '',$offset=1,$array=0,$count_pag=0,$user_id=0) {
        $all_data = [];
        $final_offset=0;
        $limit=10;
        if($api == 0){
            $final_offset=$offset-1;
            $final_offset=$limit*$final_offset;
        }else{
            //in mobile for api==1
          $limit=-1;
          $offset=-1;  
        }
        //$teams=$players_group=[];
        foreach ($data as $key => $val_cat) {
            if(count($val_cat->Players)>0){
                $data_val['type_key'] = $val_cat->type_key;
                $data_val['all_type_key'] = public_location($val_cat->type_key);
                $data_val['color'] = $val_cat->color;
                $data_val['currency'] = trans('app.SAR');
                $data_val['points'] = trans('app.points');
                $name_group = 'value_' . $lang;
                $data_val['name'] = $val_cat->$name_group;
                if ($filter == 1) {
                    if($order_play != 'heigh_price' && $order_play != 'low_price'){
                        $count_pag=round(count($val_cat->Players)/$limit,0);
                        $val_Players =static::get_PlayersLocation($order_play,$lang, $val_cat->id,1,$limit,$final_offset);
                        $reslt_data = static::get_DataPlayerUserJoin($val_Players, $lang, $api,$filter,$user_id);
                    }else{
                        //eman
                        $array_val_Players = static::get_val_Players($val_cat->Players, $from_price, $to_price, $link_team, $order_play, $word_search, $lang, $api,$val_cat->type_key,$limit,$final_offset,1);


                        $val_Players=$array_val_Players['result'];
                        $all_count_pag=count($array_val_Players['array_player_id']);
                        $count_pag=0;
                        if($all_count_pag >= $limit){ // && $limit>0
                            $count_pag=round($all_count_pag/$limit,0);
                        }
                        $reslt_data = static::get_DataPlayerUser($val_Players, $lang, $api,$filter,$user_id);
                    }
                } else {
                    $count_pag=round(count($val_cat->Players)/$limit,0);
                    // $val_Players = $val_cat->Players;//
                    $val_Players =static::get_PlayersLocation($order_play,$lang, $val_cat->id,1,$limit,$final_offset);
                    $reslt_data = static::get_DataPlayerUserJoin($val_Players, $lang, $api,$filter,$user_id);
                }
               // if(count($val_Players)>0){
                    //$reslt_data = static::get_DataPlayerUser($val_Players, $lang, $api,$filter,$user_id);
                    if($filter==1 &&$api ==1){ 
                        $players_group =$reslt_data['players'];
                        $teams =$reslt_data['teams'];
                    }else{
                        $players_group=$reslt_data;
                    }    
                    if (!empty($players_group)) {
                        $data_val['players_group'] = $players_group;
                        if($filter==1 && $api ==1){
                            $data_val['teams'] =$teams;
                        }
                        $all_data[] = $data_val; 
                    }
                //}
            }
            
        }
        if($array==1){
            return array('all_data'=>$all_data,'count_pag'=>$count_pag);
        }else{
            return $all_data;
        }
    }

    public static function get_DataGroup($data, $lang = 'ar', $filter = 0, $api = 0, $from_price = '', $to_price = '', $link_team = '', $order_play = '', $type_key='', $word_search = '',$offset=1,$array=0,$count_pag=0,$user_id=0,$limit=5) {
        $all_data = [];
        $final_offset=0;
        if($api == 0){
            $final_offset=$offset-1;
            $final_offset=$limit*$final_offset;
        }else{
            //in mobile for api==1
          $limit=-1;
          $offset=-1;  
        }
        //$teams=$players_group=[];
        foreach ($data as $key => $val_cat) {
            if(count($val_cat->Players)>0){
                $data_val['type_key'] = $val_cat->type_key;
                $data_val['all_type_key'] = public_location($val_cat->type_key);
                $data_val['color'] = $val_cat->color;
                $data_val['currency'] = trans('app.SAR');
                $data_val['points'] = trans('app.points');
                $name_group = 'value_' . $lang;
                $data_val['name'] = $val_cat->$name_group;
                if ($filter == 1) {
                    if($order_play != 'heigh_price' && $order_play != 'low_price'){
                        $count_pag=round(count($val_cat->Players)/$limit,0);
                        $val_Players =static::get_PlayersLocation($order_play,$lang, $val_cat->id,1,$limit,$final_offset,$type_key,$link_team);
                        $reslt_data = static::get_DataPlayerUserJoin($val_Players, $lang, $api,$filter,$user_id);
                    }else{
                        //eman
                        $array_val_Players = static::get_val_Players($val_cat->Players, $from_price, $to_price, $link_team, $order_play, $word_search, $lang, $api,$val_cat->type_key,$limit,$final_offset,1);


                        $val_Players=$array_val_Players['result'];
                        $all_count_pag=count($array_val_Players['array_player_id']);
                        $count_pag=0;
                        if($all_count_pag >= $limit){ // && $limit>0
                            $count_pag=round($all_count_pag/$limit,0);
                        }
                        $reslt_data = static::get_DataPlayerUser($val_Players, $lang, $api,$filter,$user_id);
                    }
                } else {
                    $count_pag=round(count($val_cat->Players)/$limit,0);
                    // $val_Players = $val_cat->Players;//
                    $val_Players =static::get_PlayersLocation($order_play,$lang, $val_cat->id,1,$limit,$final_offset,$type_key,$link_team);
                    $reslt_data = static::get_DataPlayerUserJoin($val_Players, $lang, $api,$filter,$user_id);
                }
               // if(count($val_Players)>0){
                    //$reslt_data = static::get_DataPlayerUser($val_Players, $lang, $api,$filter,$user_id);
                    if($filter==1 &&$api ==1){ 
                        $players_group =$reslt_data['players'];
                        $teams =$reslt_data['teams'];
                    }else{
                        $players_group=$reslt_data;
                    }    
                    if (!empty($players_group)) {
                        $data_val['players_group'] = $players_group;
                        if($filter==1 && $api ==1){
                            $data_val['teams'] =$teams;
                        }
                        $all_data[] = $data_val; 
                    }
                //}
            }
            
        }
        if($array==1){
            //$count_pag=round(count($all_data)/$limit,0);
            return array('all_data'=>$all_data,'count_pag'=>$count_pag);
        }else{
            return $all_data;
        }
    }

    public static function get_DataGroupForOneLocation($data, $lang = 'ar', $api = 0, $link_team = '', $order_play = '',$type_key='', $offset=1,$array=0,$count_pag=0,$user_id=0) {
        $all_data = [];
        $final_offset=0;
        $limit=10;
        if($api == 0){
            $final_offset=$offset-1;
            $final_offset=$limit*$final_offset;
        }else{
          $limit=-1;
          $offset=-1;  
        }
        foreach ($data as $key => $val_cat) {
            if(count($val_cat->Players)>0){
                $data_val['type_key'] = $val_cat->type_key;
                $data_val['all_type_key'] = public_location($val_cat->type_key);
                $data_val['color'] = $val_cat->color;
                $data_val['currency'] = trans('app.SAR');
                $data_val['points'] = trans('app.points');
                $name_group = 'value_' . $lang;
                $data_val['name'] = $val_cat->$name_group;
                
                    $count_pag=round(count($val_cat->Players)/$limit,0);
                    $val_Players =static::get_PlayersLocation($order_play,$lang, $val_cat->id,1,$limit,$final_offset,$type_key,$link_team);
                    $reslt_data = static::get_DataPlayerUserJoin($val_Players, $lang, $api,$user_id);
                    
                    if($api ==1){ 
                        $players_group =$reslt_data['players'];
                        $teams =$reslt_data['teams'];
                    }else{
                        $players_group=$reslt_data;
                    }    
                    if (!empty($players_group)) {
                        $data_val['players_group'] = $players_group;
                        if($api ==1){
                            $data_val['teams'] =$teams;
                        }
                        $all_data[] = $data_val; 
                    }
            }
            
        }
        if($array==1){
            return array('all_data'=>$all_data,'count_pag'=>$count_pag);
        }else{
            return $all_data;
        }
    }

    public static function get_val_Players($val_Players, $from_price = '', $to_price = '', $link_team = '', $order_play = '', $word_search = '', $lang = 'ar', $api = 0,$typeLocation_key='',$limit=15,$final_offset=0,$array=0) {
        $array_player_id = $val_Players->pluck('id', 'id')->toArray();
        if (!empty($link_team) && !empty($word_search)) {
            $array_player_id = static::SearchTeamPlayer($word_search, 1,$array_player_id, $link_team,$typeLocation_key,-1,-1);
        } elseif (!empty($link_team) && empty($word_search)) {
            $array_player_id = static::ColumTeamPlayer(1,$array_player_id, $link_team,-1,-1);
        } elseif (empty($link_team) && !empty($word_search)) {
            $array_player_id = static::SearchTeamPlayer($word_search, 1,$array_player_id,'',$typeLocation_key,-1,-1);
        }
        if(empty($array_player_id)){
            $array_player_id=[-1];
        }else{
            $array_player_id=array_unique($array_player_id);
        }    
        $data = static::distinct()->where('is_active', 1)->whereIn('id', $array_player_id);
        if (!empty($from_price) && !empty($to_price)) {
            $result = $data->where('cost', '>=', $from_price)->where('cost', '<=', $to_price);
        } elseif (!empty($from_price) && empty($to_price)) {
            $result = $data->where('cost', '>=', $from_price);
        } elseif (empty($from_price) && !empty($to_price)) {
            $result = $data->where('cost', '<=', $to_price);
        }
        if (!empty($order_play)) {
            $colum_order = 'id';
            $val_order = 'DESC';
            if ($order_play == 'heigh_price') {
                $colum_order = 'cost';
                $val_order = 'DESC';
            } elseif ($order_play == 'low_price') {
                $colum_order = 'cost';
                $val_order = 'ASC';
            } elseif ($order_play == 'goon') {
                $colum_order = 'cost';
                $val_order = 'DESC';
            }
            $result = $data->orderBy($colum_order, $val_order);
        }
        if ($limit > 0 && $final_offset>-1) {
            $result = $data->limit($limit)->offset($final_offset)->get();
        }else{
            $result = $data->get();
        }
        if($array==1){
            return array('array_player_id'=>$array_player_id,'result'=>$result);
        }else{
            return $result;
        }
    }

    public static function get_DataOnePlayer($colum = 'id', $val = '', $is_active = 1, $lang = 'ar', $api = 0) {
        $all_data = '';
        $data = static::get_PlayerCloum($colum, $val, $is_active);
        if (isset($data->id)) {
            $all_data = static::single_DataPlayerUser($data, $lang, $api);
        }
        return $all_data;
    }

    public static function get_DataPlayerUser($data, $lang = 'ar', $api = 0,$filter=0,$user_id=0) {
        $all_data = [];
        if($filter==1 && $api ==1){
            $all_data['players']=[];
            $all_data['teams']=[];
        } 
        $data_eldwry_game = Eldwry::get_CurrentEldwryGame($user_id);
        $arraygame_player_id=[];
        if (isset($data_eldwry_game['game']->id)) {
            $arraygame_player_id = GamePlayer::allPlayerGameUser('game_id', $data_eldwry_game['game']->id, 'is_active', 1, 'id', 'ASC',0,1,'player_id');
        } 
        foreach ($data as $key => $val_cat) {
            $reslt_data = static::single_DataPlayerUser($val_cat, $lang, $api,$filter,$arraygame_player_id);
            if($filter==1 && $api ==1){
                $all_data['players'][$val_cat->link] =$reslt_data['array_data'];
                $all_data['teams'][$val_cat->teams->link] =$reslt_data['array_team'];
            }else{
                $all_data[$val_cat->link] =$reslt_data;
            }
        }
        if($filter==1 && $api ==1){
            $all_data['players']=array_values($all_data['players']);
           $all_data['teams']=array_values($all_data['teams']);
        }else{
            $all_data=array_values($all_data);
        }    
        return $all_data;
    }

    public static function get_DataPlayerUserJoin($data, $lang = 'ar', $api = 0,$filter=0,$user_id=0) {
        $all_data = [];
        if($filter==1 && $api ==1){
            $all_data['players']=[];
            $all_data['teams']=[];
        } 
        $data_eldwry_game = Eldwry::get_CurrentEldwryGame($user_id);
        $arraygame_player_id=[];
        if (isset($data_eldwry_game['game']->id)) {
            $arraygame_player_id = GamePlayer::allPlayerGameUser('game_id', $data_eldwry_game['game']->id, 'is_active', 1, 'id', 'ASC',0,1,'player_id');
        } 
        foreach ($data as $key => $val_cat) {
            $reslt_data = static::single_DataPlayerUserJoin($val_cat, $lang, $api,$filter,$arraygame_player_id);
            if($filter==1 && $api ==1){
                $all_data['players'][$val_cat->link] =$reslt_data['array_data'];
                $all_data['teams'][$val_cat->teams->link] =$reslt_data['array_team'];
            }else{
                $all_data[$val_cat->link] =$reslt_data;
            }
        }
        if($filter==1 && $api ==1){
            $all_data['players']=array_values($all_data['players']);
           $all_data['teams']=array_values($all_data['teams']);
        }else{
            $all_data=array_values($all_data);
        }    
        return $all_data;
    }

    public static function single_DataPlayerUserJoin($val_cat, $lang = 'en', $api = 0,$filter=0,$arraygame_player_id=[]) {
        $array_data['link'] = $val_cat->link;
        $array_data['image'] = '/images/member.png';
        if (!empty($val_cat->image)) {
            $array_data['image'] =finalValueByLang($val_cat->image,'',$lang);
        }
        $array_data['player_id'] = $val_cat->id;
        $array_data['name'] = finalValueByLang($val_cat->lang_name,$val_cat->name,$lang);
        // $player_team = Team::get_TeamRow($val_cat->team_id, 'id',1);
        $array_data['team'] = $val_cat->team_name;
        $array_data['teamCode'] = $val_cat->team_code;
        $value_lang = 'value_' . $lang;
        $type_val_lang = 'type_value_' . $lang;
        $array_data['type_player'] = $val_cat->$type_val_lang;
        
        $array_data['location_player'] = $val_cat->$value_lang;
        $array_data['cost'] = $val_cat->cost;
        $array_data['sell_cost'] = $val_cat->cost;
        $array_data['buy_cost'] = $val_cat->cost;
        $array_data['is_choose'] = 0;
        if(in_array($val_cat->id, $arraygame_player_id)){
            $array_data['is_choose'] = 1;
        }
        $array_data['point'] = isset($val_cat->points) ? $val_cat->points : 0;
        $array_data['fix'] = '-';
        $array_data['form'] = 10.0;
        $array_data['week'] =0;
        $array_data['eldwry_name'] ='';
        $array_data['eldwry_link'] ='';
        $current_subeldwry=Subeldwry::get_CurrentSubDwry();
        if(isset($current_subeldwry->id)){
            $array_data['eldwry_name'] =finalValueByLang($current_subeldwry->eldwry->lang_name,$current_subeldwry->eldwry->name,$lang);
            $array_data['eldwry_link'] =$current_subeldwry->eldwry->link;
            $player_match_next_week = Match::get_MatchTeamSubeldwry($current_subeldwry->id,$val_cat->team_id,1);
            if(isset($player_match_next_week->id)){
                if($player_match_next_week->first_team_id == $val_cat->team_id)
                $player_againest_team = $player_match_next_week->teams_second;
                else
                $player_againest_team = $player_match_next_week->teams_first;

                $array_data['fix'] = $player_againest_team->code;
            }
            $array_data['week'] = PointPlayer::sum_Finaltotal_SinglePlayerAndSubEldwry($val_cat->id, $current_subeldwry->id);
        }   
        $count_users = GamePlayer::count_Users_Selected_Player($val_cat->id);
        $total_users = Game::count_Total_Games();
        $percentage = ((isset($count_users) ? $count_users : 0) / (isset($total_users) ? $total_users : 0))*100;
        $array_data['sel_percentage'] = isset($percentage) ? round($percentage, 2) : 0; // = (chosenUsers/totalUsers)*100
        $array_data['influence'] = 10.0;
        $array_data['creativity'] = 10.0;
        $array_data['threats'] = 10.0;
        $array_data['ICT_index'] = 10.0;
        $array_data['state_player'] = 'normal';
        $array_data['id'] = $val_cat->id;
        $array_data['location_id'] = $val_cat->location_id;
        //$check_data = GamePlayer::checkFoundData($player_id, $game->id, 1);
//        $array_data['created_at'] = $val_cat->created_at->format('Y-m-d');
        if($filter==1 && $api ==1){
            $array_team['team'] = finalValueByLang([],$val_cat->team_name,$lang);
            $array_team['link'] = $val_cat->team_link;
            return array('array_data'=>$array_data,'array_team'=>$array_team);
        }else{
            return $array_data;
        }
    }

    public static function single_DataPlayerUser($val_cat, $lang = 'en', $api = 0,$filter=0,$arraygame_player_id=[]) {
        $array_data['link'] = $val_cat->link;
        $array_data['image'] = '/images/member.png';
        if (!empty($val_cat->image)) {
            $array_data['image'] = finalValueByLang($val_cat->image,'',$lang);
        }
        $array_data['player_id'] = $val_cat->id;
        $array_data['name'] =finalValueByLang($val_cat->lang_name,$val_cat->name,$lang);

        // $player_team = Team::get_TeamRow($val_cat->team_id, 'id',1);
        $array_data['team'] =finalValueByLang($val_cat->teams->lang_name,$val_cat->teams->name,$lang);
        $array_data['teamCode'] = $val_cat->teams->code;
        $value_lang = 'value_' . $lang;
        $array_data['type_player'] = $val_cat->alltypes->$value_lang;
        
        $array_data['location_player'] = $val_cat->location_player->$value_lang;
        $array_data['cost'] = $val_cat->cost;
        $array_data['sell_cost'] = $val_cat->cost;
        $array_data['buy_cost'] = $val_cat->cost;
        $array_data['is_choose'] = 0;
        if(in_array($val_cat->id, $arraygame_player_id)){
            $array_data['is_choose'] = 1;
        }
        $array_data['point'] = PointPlayer::sum_Finaltotal_SinglePlayer($val_cat->id);
        $current_subeldwry=Subeldwry::get_CurrentSubDwry();
        $array_data['fix'] = '-';
        $array_data['form'] = 10.0;
        $array_data['week'] = 0;
        $array_data['eldwry_name'] ='';
        $array_data['eldwry_link'] ='';
        $current_subeldwry=Subeldwry::get_CurrentSubDwry();
        if(isset($current_subeldwry->id)){
            $array_data['eldwry_name'] =$current_subeldwry->eldwry->name;
            $array_data['eldwry_link'] =$current_subeldwry->eldwry->link;
            $player_match_next_week = Match::get_MatchTeamSubeldwry($current_subeldwry->id,$val_cat->team_id,1);
            if(isset($player_match_next_week->id)){
                if($player_match_next_week->first_team_id == $val_cat->team_id)
                $player_againest_team = $player_match_next_week->teams_second;
                else
                $player_againest_team = $player_match_next_week->teams_first;

                $array_data['fix'] = $player_againest_team->code;
            }
            $array_data['week'] = PointPlayer::sum_Finaltotal_SinglePlayerAndSubEldwry($val_cat->id, $current_subeldwry->id);
        }   
        $count_users = GamePlayer::count_Users_Selected_Player($val_cat->id);
        $total_users = Game::count_Total_Games();
        $percentage = ((isset($count_users) ? $count_users : 0) / (isset($total_users) ? $total_users : 0))*100;
        $array_data['sel_percentage'] = isset($percentage) ? round($percentage, 2) : 0; // = (chosenUsers/totalUsers)*100
        $array_data['influence'] = 10.0;
        $array_data['creativity'] = 10.0;
        $array_data['threats'] = 10.0;
        $array_data['ICT_index'] = 10.0;
        $array_data['state_player'] = 'normal';
        $array_data['id'] = $val_cat->id;
        $array_data['location_id'] = $val_cat->location_id;
        //$check_data = GamePlayer::checkFoundData($player_id, $game->id, 1);
//        $array_data['created_at'] = $val_cat->created_at->format('Y-m-d');
        if($filter==1 && $api ==1){
            $array_team['team'] = finalValueByLang($val_cat->teams->lang_name,$val_cat->teams->name,$lang);
            $array_team['link'] = $val_cat->teams->link;
            return array('array_data'=>$array_data,'array_team'=>$array_team);
        }else{
            return $array_data;
        }
    }

}
