<?php
function finalDataInputAdmin($input){
    $array_lang = isset($input['lang']) ? $input['lang'] : array();
    $array_name = isset($input['lang_name']) ? $input['lang_name'] : array();
    $array_image = isset($input['image']) ? $input['image'] : array();
    $array_description = isset($input['description']) ? $input['description'] : array();
    $array_content = isset($input['content']) ? $input['content'] : array();
    $input['lang_name'] = convertArrayToJson($array_name, $array_lang);
    $input['name']= get_ValuName($array_name);
    $input['image'] = convertArrayToJson($array_image, $array_lang);
    $input['description'] = convertArrayToJson($array_description, $array_lang);
    $input['content'] = convertArrayToJson($array_content, $array_lang);
    return $input;
}
function get_ValuName($array_name){
  $final_name='';
  foreach ($array_name as $key => $name) {
    if(!empty($name)){
      $final_name=$name;
      break;
    }
  }
  return $final_name;
}
function convertValueToJson($val,$key=''){
  $result=[];
  if(!empty($key)){
      $result[$key]=$val;
  }else{
      $result[]=$val;
  }
  return json_encode($result,true);
}

function convertArrayToJson($array_val,$array_lang=''){
  $result=[];
  foreach ($array_val as $key => $value) {
    if(isset($array_lang[$key])){
      $result[$array_lang[$key]]=$value;
    }else{
      $def_lang=\App\Models\Language::GetDefaultLang(1, 1);
      $result[$def_lang]=$value;
      break;
    }
  }
  return json_encode($result,true);
}
function convertImageArrayToJson($array_image,$array_lang=''){
  $result=[];
  foreach ($array_image as $key => $image) {
      $result[$array_lang[$key]] = $image;
  }
  return json_encode($result,true);
}

function UpdateconvertImageArrayToJson($current_image,$array_image,$array_lang=''){
  $result=[];
  $array_current_img=json_decode($current_image,true);
  foreach ($array_image as $key => $image) {
    if(isset($array_current_img[$key]) && $array_current_img[$key]==$image){
        $result[$array_lang[$key]] = $image;
     }else{ 
      $result[$array_lang[$key]] = $image;
    }
  }
  return json_encode($result,true);
}

function finalValueByLang($lang_name,$name,$lang='ar'){
  $final_name=$name;
  $array_lang_name = $lang_name;
  if(!is_array($lang_name)){
    $array_lang_name=json_decode($lang_name,true);
  }
  if(is_array($array_lang_name)){
    if(isset($array_lang_name[$lang])){
      $final_name=$array_lang_name[$lang];
    }
    if(empty($final_name)){
      //get value of defualt lang 
      //$def_lang=\App\Models\Language::GetDefaultLang();
        $all_lang=session()->get('all_lang');
        if(empty($all_lang)){
            $all_lang=\App\Models\Language::get_AllLanguagExceptLang($lang,'is_active',1,'lang', 1);
            session()->put('all_lang',$all_lang);
        }
       foreach ($all_lang as $key => $val_lang) {
          if(isset($array_lang_name[$val_lang])){
            $final_name=$array_lang_name[$val_lang];
            break;
          }
       }
    }
  }
  //check for mobile app
  if(empty($final_name) || $final_name=="<null>"|| $final_name=="null"){
      $final_name='';
  }else{
    if(!empty($name)){
      $final_name=str_replace("'", "&#39;", $final_name);
    }
  }
  ////
  return $final_name;
}
function SameKeyByVAl($array_data) {
  $f_data=[];
  foreach ($array_data as $key => $value) {
    $f_data[$value]=$value;
  }
  return $f_data;
}
function RemoveKeyArray($arr,$newkey,$oldkey){
    $arr[$newkey] = $arr[$oldkey];
    unset($arr[$oldkey]);
    return $arr;
}
function checkDataBool($data=''){
    $res=0;
    if(in_array($data,['yes','Yes','YES','active'])){
        $res=1;
    }
    return $res;
}
function DefautValPointsSubstitute($val=0,$point_subeldawry=-1){
    $point=0;
    $type_id=3;
    if($val>=1){
        $type_id=7;
        if($point_subeldawry>-1){
            $point=$point_subeldawry;
        }else{
            $point=-4;
        }
    }
    return array('points'=>$point,'type_id'=>$type_id);
}

function setcookieValue($cookie_name, $cookie_value, $num_day =365) {
        //Set cooki
    $set_cookie = setcookie($cookie_name, $cookie_value, time() + (86400 * $num_day), "/"); // 86400 = 1 day
    return $set_cookie;
}

function setTwoValue($cookie_name1, $cookie_value1,$cookie_name2, $cookie_value2, $num_day =365) {
    setcookieValue($cookie_name1, $cookie_value1);
    setcookieValue($cookie_name2, $cookie_value2);
    return true;
}

function setThreeValue($cookie_name1, $cookie_value1,$cookie_name2, $cookie_value2,$cookie_name3, $cookie_value3, $num_day =365) {
    setcookieValue($cookie_name1, $cookie_value1);
    setcookieValue($cookie_name2, $cookie_value2);
    setcookieValue($cookie_name3, $cookie_value3);
    return true;
}

function GetcookieValue($cookie_name, $default_val = 0) {
    if (isset($_COOKIE[$cookie_name])) {
        $result_cookie=$_COOKIE[$cookie_name];
    }else{
        $result_cookie=$default_val;
    }
    return $result_cookie;
}

function addSessionDeletPlayer($game_player_id){
    $array_deletplayer=session()->get('array_deletplayer');
    if(empty($array_deletplayer)){
        $array_deletplayer=[];
    }
    $array_deletplayer[$game_player_id]=$game_player_id;
    session()->put('array_deletplayer',$array_deletplayer);
    return $array_deletplayer;
} 

function RemoveSessionDeletPlayer($game_player_id){
    $new_array_deletplayer=[];
    $array_deletplayer=session()->get('array_deletplayer');
    if(empty($array_deletplayer)){
        $array_deletplayer=[];
    }
    if(is_array($array_deletplayer)){
        foreach ($array_deletplayer as $key => $value) {
            if($value!=$game_player_id){
               $new_array_deletplayer[$value]= $value;
            }
        }
    }
    session()->put('array_deletplayer',$new_array_deletplayer);
    return $array_deletplayer;
} 

function emptySessionDeletPlayer(){
    // session()->put('array_deletplayer',[]);
    session()->forget('array_deletplayer');
    session()->forget('array_substitutePlayer');//,[]
    session()->forget('already_usePlayer');//,[]
    session()->forget('active_cardgray'); //0
    session()->forget('active_cardgold'); //0
} 

function emptySession(){
    // session()->forget('ch_player_id_one');
    // session()->forget('ch_game_player_id_one');
    // session()->forget('ch_player_id_two');
    // session()->forget('ch_game_player_id_two');

    // session()->put('ch_player_id_one',0);
    // session()->put('ch_game_player_id_one',0);
    // session()->put('ch_player_id_two',0);
    // session()->put('ch_game_player_id_two',0);
    
    setcookieValue('ch_player_id_one',0);
    setcookieValue('ch_game_player_id_one',0);
    setcookieValue('type_loc_player_one',null);
    setcookieValue('ch_player_id_two',0);
    setcookieValue('ch_game_player_id_two',0);
    setcookieValue('type_loc_player_two',null);
   // print_r('expression');die;
    return true;
}
function arrayLocationId(){
    return [[1],[5,6,7],[8,9,10],[2,3,4]];
}
function arrayLocationIdSix(){
    return [[1],[5,6,7],[5,6,7],[8,9,10],[8,9,10],[2,3,4],[2,3,4]];
}
function get_LocationId($loc_name=''){    
//Midfielder  Attacker  Goalkeeper  Defender
    $loc_id=5; //defender //when not found data
    if(in_array($loc_name,['Goalkeeper'])){
        $loc_id=1;
    }elseif(in_array($loc_name,['Defender'])){
        $loc_id=5;
    }elseif(in_array($loc_name,['Midfielder'])){
        $loc_id=8;
    }elseif(in_array($loc_name,['Attacker','Striker','Attacking Midfielder'])){
        $loc_id=2;
    }
    return $loc_id;
}
function getarrayLocation($loca_id,$array=0){
    $array_location_id=[];
    $defender=$line=$attacker=0;
    $key_type_loc='';
    if(in_array($loca_id,[1])){
       $array_location_id=[1];  //goalkeeper
        $key_type_loc='goalkeeper';
    }elseif(in_array($loca_id,[5,6,7])){
       $array_location_id=[5,6,7]; //defender
        $key_type_loc='defender';
       $defender=1;
    }elseif(in_array($loca_id,[8,9,10])){
       $array_location_id=[8,9,10]; //line
        $key_type_loc='line';
       $line=1;
    }elseif(in_array($loca_id,[2,3,4])){
       $array_location_id=[2,3,4]; //attacker
        $key_type_loc='attacker';
       $attacker=1;
    }   
    if($array==1){
        return array('key_type_loc'=>$key_type_loc,'defender'=>$defender,'line'=>$line,'attacker'=>$attacker,'array_location_id'=>$array_location_id,);
    }else{
        return $array_location_id;
    }
}

function count_location_player($lineup,$type_loc_player){
    $count=0;
    if(!empty($lineup)){
        $current_lineup=json_decode($lineup,true);
        if(isset($current_lineup[0])&&$type_loc_player='defender'){
            $count=$current_lineup[0];
        }elseif(isset($current_lineup[1])&&$type_loc_player='line'){
            $count=$current_lineup[1];
        }elseif(isset($current_lineup[2])&&$type_loc_player='attacker'){
            $count=$current_lineup[2];
        }
    }
    return $count;
}
function public_location($type_local){
    $local='';
    if(in_array($type_local, ['goalkeeper'])){
        $local='goalkeeper';
    }elseif(in_array($type_local, ['defender_center','defender_left','defender_right'])){
        $local='defender';
    }elseif(in_array($type_local, ['center_line','left_line','right_line'])){
        $local='line';
    }elseif(in_array($type_local, ['attacker_left','attacker_center','attacker_right'])){
        $local='attacker';
    }
    return $local;
}
function get_Type_myteam_order_id($order_id){
    $myteam_order_id=$order_id;
    $type_id=5;
    $usb_val=1;
    if($myteam_order_id>1){
        if($order_id>=13){
            $usb_val=3;
        }elseif($order_id>=8){
            $usb_val=2;
        }
        $myteam_order_id=$order_id-$usb_val;
        if($order_id==2){
            $type_id=6;
            $myteam_order_id=12;
        }elseif($order_id==7){
            $type_id=6;
            $myteam_order_id=13;
        }elseif($order_id==12){
            $type_id=6;
            $myteam_order_id=14;
        }elseif($order_id==15){
            $type_id=6;
            $myteam_order_id=15;
        }
    }
    return array('myteam_order_id'=>$myteam_order_id,'type_id'=>$type_id);
}
function array_sort_player(){
    return [0 => 'goalkeeper', 1 => 'center_line', 2 => 'left_line', 3 => 'right_line'
            , 4 => 'defender_center', 5 => 'defender_left', 6 => 'defender_right', 7 => 'attacker_center', 8 => 'attacker_left', 9 => 'attacker_right'];
}
function array_count_14(){
    return [0 => 0, 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10, 11 => 11, 12 => 12, 13 => 13, 14 => 14];
}

function array_count_15(){
    return [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10, 11 => 11, 12 => 12, 13 => 13, 14 => 14, 15 => 15];
}

function FormPrice($price,$discount=0) {
    $fin_price=round($price - (($price * $discount) / 100), 2);
    return $fin_price;
}

function ValueKeyArray($array_data) {
    $data = [];
    foreach ($array_data as $key => $value) {
        $data[$value] = $value;
    }
    return $data;
}

function NumItemCount($array_data,$val_item) {
    $sum = 0;
    foreach ($array_data as $key => $value) {
        if($val_item==$value){
            $sum +=1;
        }
    }
    return $sum;
}
function TypePlayer_item($array_type_location,$array_order_location,$array_loc,$start_order_id,$end_order_id,$num_plyer=4) {
    $all_loc_type=$all_order_id=[];
    $type_palyer_id=5;
    $order_id=$start_order_id;
    foreach ($array_loc as $key => $value) {
        if(isset($array_type_location[$value])){
            $all_loc_type=array_merge($all_loc_type,$array_type_location[$value]);

            $all_order_id=array_merge($all_order_id,$array_order_location[$value]);
        }
    }
    $count__loctype = NumItemCount($all_loc_type,5);
    if($count__loctype >= $num_plyer){
        $type_palyer_id=6;
        $order_id=$end_order_id;
    }else{
        $order_id = NumItemOrder($all_order_id,$start_order_id,$end_order_id);
    }
    return array('type_palyer_id'=>$type_palyer_id,'order_id'=>$order_id);
}
function NumItemOrder($all_order_id,$start_order_id,$end_order_id){
    $order_id=$start_order_id;
    for ($i = $start_order_id;$i <= $end_order_id;$i++) {
        if(!in_array($i, $all_order_id)){
            $order_id=$i;
            break;
        }
    }
    return $order_id;
}
function getmyteam_order_id($order_id){
    $array_count=[1=>1,2=>12,3=>2,4=>3,5=>4,6=>5,7=>13,8=>6,9=>7,10=>8,11=>9,12=>14,13=>10,14=>11,15=>15];
    $myteam_order_id=$array_count[$order_id];
    return $myteam_order_id;
}
function TypePlayer_item_old($array_type_location,$array_loc,$num_plyer=4) {
    $all_loc_type=[];
    $type_palyer_id=5;
    foreach ($array_loc as $key => $value) {
        if(isset($array_type_location[$value])){
            $all_loc_type=array_merge($all_loc_type,$array_type_location[$value]);
        }
    }
    $count__loctype = NumItemCount($all_loc_type,5);
    if($count__loctype >= $num_plyer){
        $type_palyer_id=6;
    }
    return $type_palyer_id;
}
function get_CurrentLink($link = '') {
    $link= str_replace("'", "&#39;", $link);
    return $link;
}
function get_RandLink($link = '', $new = 0) {
    if ($new == 0) {
        $link = $link . \Illuminate\Support\Str::random(8) . time(); //str_random //rand(100, 9999)//generateRandomValue()
    }
    $link = str_replace(' ', '_', $link);
    //$link = preg_replace('/[^A-Za-z0-9\-]/', '', $link); // Removes special chars.
    $link = str_replace(array( '\'', '"',"'",',' , ';', '<', '>','&#39;',' ','\xD9'), '_', $link); 
    // $link = preg_replace('/[[:^print:]]/', '',$link);
    $link =remove_emoji($link);
    $link = substr($link, 0,120);
    if(empty($link) || array_count_values(str_split($link)) < 50 ){
        $link = $link . str_random(8) . time();
    }
    return $link;
}

 function generateRandomCode($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function remove_emoji($text)
{
    return preg_replace('/[^\w\s]+/u','' ,$text);
}
function GetResultMsgsession(){
    $data['correct']=session()->get('correct');
    $data['wrong']=session()->get('wrong');
    $data['correct_form']=session()->get('correct_form');
    $data['wrong_form']=session()->get('wrong_form');
     //empty session
    session()->forget('correct');
    session()->forget('wrong');
    session()->forget('correct_form');
    session()->forget('wrong_form');
    return $data;
}
function CurrentWeek($val_time=1){
    $start_week = strtotime('friday this week');
    $start_week = date('w', $start_week)==date('w') ? strtotime(date("Y-m-d",$start_week)." +7 days") : $start_week;
    $end_week = strtotime(date("Y-m-d",$start_week)." +6 days");
    //Y-m-d H:i:s
    $start_week=date("Y-m-d",$start_week);
    $end_week=date("Y-m-d",$end_week);
    if($val_time==1){
       $start_week=$start_week.' 00:00:01'; 
       $end_week=$end_week.' 23:59:59'; 
    }
    return array('start_week'=>$start_week,'end_week'=>$end_week);
}
function get_date($old_date,$time=0) {
    if($time==1){
        $date = date("Y-m-d H:i:s", strtotime($old_date));
    }elseif($time==2){
        $date = date("H:i:s", strtotime($old_date));
    }else{
        $date = date("Y-m-d", strtotime($old_date));
    }
    return $date;
}
function addTimeOnDate($date_time,$time_to_add,$type_add='M'){
    if(empty($time_to_add)){
       return $date_time;
    }
    $time = new \DateTime($date_time);
    $time->add(new DateInterval('PT' . $time_to_add . $type_add));
    return $time->format('Y-m-d H:i:s');
}

function subTimeOnDate($date_time,$time_to_sub,$type_sub='minutes'){
    if(empty($time_to_sub)){
       return $date_time;
    }
    return date("Y-m-d H:i:s",strtotime("-".$time_to_sub." ".$type_sub."",strtotime($date_time)));
}

function time_in_12_hour_format($time = '')
{
//"13:30" // 24-hour time to 12-hour time
    return date("g:i a", strtotime($time));
} 
function ConvertUTC_ToDateCurrentUser($data_server, $timezone = 'GMT')
{
    $timezone=currentUserCountry();
    $data_server = \Carbon::parse($data_server)->tz($timezone);
    return $data_server;
}
function ConvertUTC_ToDateCurrentUser12_hour($data_server)
{
    $date_value = ConvertUTC_ToDateCurrentUser($data_server);
    $array_date=explode(' ', $date_value);
    return $array_date[0].' '.time_in_12_hour_format($array_date[1]);
}

// convert current user date to utc 
function ConvertDateCurrentUserToUtc($data_server, $timezone = 'GMT')
{
    $timezone=currentUserCountry();
    $date = Carbon::createFromFormat('Y-m-d h:i a', $data_server, $timezone);
    $date->setTimezone('UTC');
    return $date;
}

function currentUserCountry($array = 0)
{
    if (isset($_SERVER["HTTP_CF_IPCOUNTRY"])) {
        $country_code = $_SERVER["HTTP_CF_IPCOUNTRY"];
    } else {
        $country_code ='EG';// 'KW';
    }
    $array_timezone = \DateTimeZone::listIdentifiers(\DateTimeZone::PER_COUNTRY, $country_code);
    if ($array==1) {
        return array('country_code'=>$country_code,'timezone'=>$array_timezone[0]) ;
    } else {
        return $array_timezone[0];
    }
}
function cal_age_birthdate($birthDate = '', $lang = 'en') {
    //explode the date to get month, day and year
    $birthDate = explode("-", $birthDate);
    //get age from date or birthdate
    $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[2], $birthDate[1], $birthDate[0]))) > date("md") ? ((date("Y") - $birthDate[0]) - 1) : (date("Y") - $birthDate[0]));
    return $age . ' ' . 'سنة ';
}

function date_string_game($old_date, $number = 0) {
    //'Y-m-d H:i:s',
//    $old_date = date('l, F d y h:i:s');  // returns Saturday, January 30 10 02:06:34
    $old_date_timestamp = strtotime($old_date);
    $new_date = date('l, d F Y h:i:s', $old_date_timestamp);
//    $new_date = date('D d F Y h:i:s', $old_date_timestamp);
    if ($number == 1) {
        $new_date = date('D d M Y h:i', $old_date_timestamp);
    }
    return $new_date;
}

function date_lang_game($old_date, $lang = 'en') {
    $new_date = date_string_game($old_date);
    if ($lang == 'ar') {
        $new_date = date_string_game($old_date, 1);
        $exp_date = explode(' ', $new_date);
        $day_date = array_dayWeek_lang($exp_date[0]);
        $month_date = array_month_lang($exp_date[2]);
        $new_date = $day_date . ' ' . $exp_date[1] . ' ' . $month_date . ' ' . $exp_date[3] . ' ' . ' ' . $exp_date[4];
    }
    return $new_date;
}

function day_lang_game($old_date, $lang = 'en') {
    $new_date = date_string_game($old_date, 1);
    $exp_date = explode(' ', $new_date);
    $day_date = $exp_date[0];
    if ($lang == 'ar') {
        $day_date = array_dayWeek_lang($exp_date[0]);
    }
    return $day_date;
}

function date_string($old_date) {
    //'Y-m-d H:i:s',
//    $old_date = date('l, F d y h:i:s');              // returns Saturday, January 30 10 02:06:34
    $old_date_timestamp = strtotime($old_date);
    $new_date = date('l,d Y', $old_date_timestamp);
    return $new_date;
}

function generateRandomValue($long=0) {
    $length = 1;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    $randomNumber = rand(100000, 9999999);
    if($long==1){
        return md5(time() . $randomString . $randomNumber);
    }else{
        return $randomString . time();
    }
}

function generateRandomPromocode() {
    $length = 1;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    $randomNumber = rand(100000, 9999999);
//    return md5(time() . $randomString . $randomNumber);
    return $randomNumber . $randomString;
}

function generateRandomToken() {
    $length = 3;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    $randomNumber = rand(100000, 9999999);
    $token = md5(time() . $randomString . $randomNumber);
    if (substr($token, 0, 1) === '0') {
        $token = '1' . $token;
    }
    return $token;
}
function get_randNum($num = 6) {
    return substr(number_format(time() * rand(), 0, '', ''), 0, $num);
}

//function to arrange
function conditionPrice($price) {
    if ($price < 0.00) {
        $price = 0.00;
    }
    return $price;
}

function conditionDiscount($discount) {
    if ($discount > 100) {
        $discount = 100;
    }
    if ($discount < 0.00) {
        $discount = 0.00;
    }
    return $discount;
}
function BestType() {
    $status_array = array(
        0 => trans('app.not') . trans('app.best'),
        1 => trans('app.best')
    );
    return $status_array;
}

function CategoryType() {
    $status_array = array(
        'menu' => trans('app.menu'),
        'category' => trans('app.category')
    );
    return $status_array;
}

function type_stateCategory() {
    //type_state
    $status_array = array(
        null => 'اختروضعيه السكشن',
        'normal' => 'عادى',
        'best' => 'مميز',
        'complete' => 'محجوز',
        'not_valid' => 'غير متاح حجزه',
//        'special'=>'خاص',
    );
    return $status_array;
}

function FeesTypePrice() {
    $status_array = array(
        'value' => 'مبلغ',
        'persent' => 'نسبة من المبلغ الاصلى للمنتج'
    );
    return $status_array;
}

function FeesTypePriceData($status = null) {
    $output = '';
    $status_array = array(
        'value' => 'مبلغ',
        'persent' => 'نسبة من المبلغ الاصلى للمنتج'
    );
    foreach ($status_array as $key => $status_name) {
        if ($status == $key) {
            if ($key == "value") {
                $class = "btn-success";
            } else if ($key == "persent") {
                $class = "btn-info";
            }
            $output = "<span class= 'label $class' style='padding:5px;'>$status_name</span>";
        }
    }
    return $output;
}

function get_UserTeamType($status = null) {
    $output = '';
    $status_array = array(
        'player' => 'لاعب',
        'coach' => 'مدرب',
        'help_coach' => 'مساعد مدرب'
    );
    foreach ($status_array as $key => $status_name) {
        if ($status == $key) {
            if ($key == "player") {
                $class = "btn-success";
            } else if ($key == "coach") {
                $class = "btn-info";
            } else if ($key == "help_coach") {
                $class = "btn-primary";
            }
            $output = "<span class= 'label $class' style='padding:5px 10px;'>$status_name</span>";
        }
    }
    return $output;
}

function UserTeamType() {
    $status_array = array(
        'player' => 'لاعب',
        'coach' => 'مدرب',
        'help_coach' => 'مساعد مدرب'
    );
    return $status_array;
}
function NostatusType() {
    $status_array = array(
        0 => 'Not Default',
        1 => 'Default',
    );
    return $status_array;
}

function statusType() {
    $status_array = array(
        0 => trans('app.not') . trans('app.active'),
        1 => trans('app.active')
    );
    return $status_array;
}
function genderType() {

    $gender_array = array(
        'male' => trans('app.male'),
        'female' => trans('app.female')
    );
    return $gender_array;
}

function readStatus($status = null) {

    $output = '';
    $status_array = [0 => 'Not Read', 1 => 'Read'];
    foreach ($status_array as $key => $status_name) {
        if ($status == $key) {
            if ($key == 1) {
                $class = "btn-success";
            } else {
                $class = "btn-danger";
            }
            $output = "<span class= 'label $class' >$status_name</span>";
        }
    }
    return $output;
}

function activeStatus($status = null) {

    $output = '';
    $status_array = [0 => 'Not Active', 1 => 'Active'];
    foreach ($status_array as $key => $status_name) {
        if ($status == $key) {
            if ($key == 1) {
                $class = "btn-success";
            } else {
                $class = "btn-danger";
            }
            $output = "<span class= 'label $class' >$status_name</span>";
        }
    }
    return $output;
}
function commentType() {

    $status_array = array(
        0 => trans('app.all'),
        1 => trans('app.only_members')
    );
    return $status_array;
}

function showType() {

    $show_array = array(
        0 => trans('app.no'),
        1 => trans('app.yes')
    );
    return $show_array;
}

function continentName($type = null) {
    $type_array = array(
        'africa' => trans('app.africa'),
        'asia' => trans('app.asia'),
        'europe' => trans('app.europe'),
        'north_amarica' => trans('app.north_america'),
        'south_america' => trans('app.south_america'),
        'australia' => trans('app.australia')
    );

    if (isset($type_array[$type])) {
        return $type_array[$type];
    } else {
        return "";
    }
}

function statusName($status = null) {
    $status_array = array(
        0 => trans('app.not') . trans('app.active'),
        1 => trans('app.active')
    );

    if (isset($status_array[$status])) {
        return $status_array[$status];
    } else {
        return "";
    }
}

function array_dayWeek() {
    return $week = array(
        "Saturday" => "السبت",
        "Sunday" => "الاحد",
        "Monday" => "الاثنين",
        "Tuesday" => "الثلاثاء",
        "Wednesday" => "الاربعاء",
        "Thursday" => "الخميس",
        "Friday" => "الجمعة"
    );
}

function array_dayWeek_lang($ar_day_format) {
    $find = array("Sat", "Sun", "Mon", "Tue", "Wed", "Thu", "Fri");
    $replace = array("السبت", "الأحد", "الإثنين", "الثلاثاء", "الأربعاء", "الخميس", "الجمعة");
    return $ar_day = str_replace($find, $replace, $ar_day_format);
}

function array_month_lang($en_month) {
    $ar_month = '';
    $months = array("Jan" => "يناير", "Feb" => "فبراير", "Mar" => "مارس", "Apr" => "أبريل", "May" => "مايو", "Jun" => "يونيو", "Jul" => "يوليو", "Aug" => "أغسطس", "Sep" => "سبتمبر", "Oct" => "أكتوبر", "Nov" => "نوفمبر", "Dec" => "ديسمبر");
    foreach ($months as $en => $ar) {
        if ($en == $en_month) {
            $ar_month = $ar;
        }
    }
    return $ar_month;
}

function array_month() {
    return $months = array(
        "Jan" => "يناير",
        "Feb" => "فبراير",
        "Mar" => "مارس",
        "Apr" => "أبريل",
        "May" => "مايو",
        "Jun" => "يونيو",
        "Jul" => "يوليو",
        "Aug" => "أغسطس",
        "Sep" => "سبتمبر",
        "Oct" => "أكتوبر",
        "Nov" => "نوفمبر",
        "Dec" => "ديسمبر"
    );
}

function arabic_date($date) {
    $months = array_month();
    $en_month = date("M", strtotime($date));

    $ar_month = $months[$en_month];

    $last_date = str_replace($en_month, $ar_month, $date);

    echo $last_date;
}

function arabic_date_number($date, $sing = '/') {
    $date_slash = $date . $sing;
    $months = array(
        "01" => "يناير",
        "02" => "فبراير",
        "03" => "مارس",
        "04" => "أبريل",
        "05" => "مايو",
        "06" => "يونيو",
        "07" => "يوليو",
        "08" => "أغسطس",
        "09" => "سبتمبر",
        "10" => "أكتوبر",
        "11" => "نوفمبر",
        "12" => "ديسمبر"
    );
    $en_month = date("m", strtotime($date));

    $ar_month = $months[$en_month];
    $en_month_slash = $en_month . $sing;
    $last_date = str_replace($en_month_slash, $ar_month, $date_slash);
//    $last_date = str_replace($en_month,$ar_month, $date);
    $last_date = str_replace($sing, ' ', $last_date);
    echo $last_date;
//    echo $ar_month;
}

function arabic_Value_month($num_month) {
    $months = array(
        "01" => "يناير",
        "02" => "فبراير",
        "03" => "مارس",
        "04" => "أبريل",
        "05" => "مايو",
        "06" => "يونيو",
        "07" => "يوليو",
        "08" => "أغسطس",
        "09" => "سبتمبر",
        "10" => "أكتوبر",
        "11" => "نوفمبر",
        "12" => "ديسمبر"
    );
    $ar_month = $months[$num_month];
    echo $ar_month;
}

function get_Time_restPasssword($date, $time = 3600)
{
    $current_date = date("Y-m-d H:i:s");
    $current_time = strtotime($current_date);
//    $current_time = $current_time + (2 * 60 * 60);

    $old_time = strtotime($date);
    $sub_time = floor($current_time - $old_time);
    $sub_time = floor((($current_time - $old_time) / 60) / 60);   //by Hours

    $reult = 0;
    if ($time >= $sub_time) {
        $reult = 1;
    }
    return $reult;
}

function get_beforTime_date($val_sec = 0) {
    //get current date time  befor  $val_sec sec
    $date = date("Y-m-d H:i:s");
    $time = strtotime($date);
    $time = ($time - $val_sec) + (2 * 60 * 60);
    $cur_date = date("Y-m-d H:i:s", $time); //$cur_date = date("Y-m-d h:i:s a", strtotime("-360 seconds"));
    return $cur_date;
}



function getChekTime($current_date,$time_stop_subeldwry) {
    $date1 = strtotime($current_date);
    $date2 = strtotime($time_stop_subeldwry);
    $diff = abs($date2 - $date1);

    $years = floor($diff / (365*60*60*24));
    $months = floor(($diff - $years * 365*60*60*24)/ (30*60*60*24)); 


    $differneceDate['days'] = floor(($diff - $years * 365*60*60*24 -  $months*30*60*60*24)/ (60*60*24));

    $differneceDate['hours'] = floor(($diff - $years * 365*60*60*24  
   - $months*30*60*60*24 - $differneceDate['days']*60*60*24) / (60*60));

    $differneceDate['minutes'] = floor(($diff - $years * 365*60*60*24  
     - $months*30*60*60*24 - $differneceDate['days']*60*60*24 - $differneceDate['hours']*60*60)/ 60);

    $differneceDate['seconds'] = floor(($diff - $years * 365*60*60*24  
     - $months*30*60*60*24 - $differneceDate['days']*60*60*24 - $differneceDate['hours']*60*60 - $differneceDate['minutes']*60));

    return $differneceDate;
}