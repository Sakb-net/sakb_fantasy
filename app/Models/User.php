<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
// use Zizaco\Entrust\Traits\EntrustUserTrait;

// use Illuminate\Support\Facades\Auth;
// use App\Http\Requests\UserRequest;
// use \Illuminate\Support\Facades\View;

use App\Models\Role;
use App\Models\UserMeta;
use App\Models\Team;
use App\Models\GameTransaction;
use App\Models\Game;
use App\Models\Subeldwry;
use App\Models\Options;
use DB;
use Mail;



class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    // use EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'display_name', 'image', 'access_token', 'address', 'city', 'state',
        'phone', 'is_active', 'device_id', 'fcm_token', 'state_fcm_token', 'gender','best_team','lang', 'reg_site', 'session', 'jop',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];


 public function socialproviders() {
        return $this->hasMany(\App\Models\SocialProvider::class);
    }

    public function userMeta() {
        return $this->hasMany(\App\Models\UserMeta::class);
    }

    public function categories() {
        return $this->hasMany(\App\Models\Category::class);
    }

    public function games() {
        return $this->hasMany(\App\Models\Game::class);
    }

    public function game_transaction() {
        return $this->hasMany(\App\Models\GameTransaction::class);
    }

    public function posts() {
        return $this->hasMany(\App\Models\Post::class);
    }

    public function team() {
        return $this->belongsTo(\App\Models\Team::class,'best_team');
    }

    public function actions() {
        return $this->hasMany(\App\Models\Action::class);
    }

    public function comments() {
        return $this->hasMany(\App\Models\Comment::class);
    }

    public function UserNotif() {
        return $this->hasMany(\App\Models\UserNotif::class);
    }

    public static function addCreate($request, $user_role = '', $display_name, $email, $password, $phone, $fcm_token = NULL, $device_id = NULL, $reg_site = 'site', $address = null, $city = null, $state = null,$best_team=null,$image=null) {
        if (empty($user_role)) {
            $user_role = Options::where('option_key', 'default_role')->value('option_value');
        }
//        $this->validator($request->all())->validate();
        $user = User::insertUser($display_name, $email, $password, $phone, $fcm_token, $device_id, $reg_site, $address, $city, $state,$best_team,$image);
        $user->attachRole($user_role);
//        $this->guard()->login($user);
        return $user;
    }

    public static function insertUser($display_name, $email, $password, $phone, $fcm_token = NULL, $device_id = NULL, $reg_site = 'site', $address = null, $city = null, $state = null,$best_team_link=null,$image=null) {
        $user_active = Options::where('option_key', 'user_active')->value('option_value');
        $is_active = is_numeric($user_active) ? $user_active : 0;
        $user_name = explode('@', $email);
        $best_team=-1;//'general_fan';
        if(!empty($best_team_link)){
            $data_team=Team::foundData('link',$best_team_link);
            if(isset($data_team->id)){
                $best_team=$data_team->id;
            }
        }
        if(empty($image)){
            $image=generateDefaultImage($display_name);
        }
        $user_reg = User::create([
                    'display_name' => $display_name,
                    'email' => $email,
                    'password' => bcrypt($password),
                    'name' => (str_replace(' ', '_', $user_name[0] . time())), //str_random(8)
                    'phone' => $phone,
                    'image' =>$image,
                    'access_token' => generateRandomToken(),
                    'fcm_token' => $fcm_token,
                    'device_id' => $device_id,
                    'reg_site' => $reg_site,
                    'address' => $address,
                    'city' => $city,
                    'state' => $state,
                    'best_team' => $best_team,
                    'is_active' => $is_active,
        ]);
        return $user_reg;
    }

    public static function updateColum($id, $colum, $columValue) {
        $data = static::findOrFail($id);
        $data->$colum = $columValue;
        return $data->save();
    }

    public static function updateColumTwo($id, $colum, $columValue, $colum2, $columValue2) {
        $data = static::findOrFail($id);
        $data->$colum = $columValue;
        $data->$colum2 = $columValue2;
        return $data->save();
    }

    public static function updateColumThree($id, $colum, $columValue, $colum2, $columValue2, $colum3, $columValue3) {
        $data = static::findOrFail($id);
        $data->$colum = $columValue;
        $data->$colum2 = $columValue2;
        $data->$colum3 = $columValue3;
        return $data->save();
    }

    public function isActive() {
        return Auth::user()->is_active == 1;
    }

    public static function userData($id, $column = '') {
        $user = static::where('id', $id)->first();
        if (!empty($column)) {
            if (isset($user->id)) {
                return $user->$column;
            } else {
                return '';
            }
        } else {
            return $user;
        }
    }

    public static function foundUser($name, $column = 'name', $limit = -1) {
        $user = static::where($column, $name)->first();
        if ($limit == -1) {
            if (isset($user)) {
                return $user->id;
            } else {
                return 0;
            }
        } else {
            return $user;
        }
    }

    public function userID($id, $column = '') {
        $user = static::where('id', $id)->first();
        if (!empty($column)) {
            if (isset($user->id)) {
                return $user->$column;
            } else {
                return '';
            }
        } else {
            return $user;
        }
    }

    public static function GetByColumValue($col_name, $col_val, $api = 0) {
        $data = static::where($col_name, $col_val)->first();
        return $data;
    }

    public static function user_access_token($access_token, $is_active = 1, $column = '') {
        $user = static::where('access_token', $access_token)->where('is_active', $is_active)->first();
        if (!empty($column)) {
            if (isset($user->id)) {
                return $user->$column;
            } else {
                return '';
            }
        } else {
            return $user;
        }
    }

    public static function get_searchUser($search, $is_active = '', $limit = 0, $post = '', $bundle = '') {
        $data = static::Where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('display_name', 'like', '%' . $search . '%')
                ->orWhere('image', 'like', '%' . $search . '%')
                ->orWhere('address', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%')
                ->orWhere('gender', 'like', '%' . $search . '%');
        if ($is_active == 1 || $is_active == 0) {
            $result = $data->where('is_active', $is_active);
        }
        if (!empty($is_active)) {
            $result = $data->where('is_active', $is_active);
        }
        if (!empty($post)) {
            $result = $data->with('posts');
        }
        if (!empty($bundle)) {
            $result = $data->with('bundles');
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

    public static function lastMonth($month, $date) {

        $count = static::select(DB::raw('COUNT(*)  count'))->whereBetween(DB::raw('created_at'), [$month, $date])->get();
        return $count[0]->count;
    }

    public static function lastWeek($week, $date) {

        $count = static::select(DB::raw('COUNT(*)  count'))->whereBetween(DB::raw('created_at'), [$week, $date])->get();
        return $count[0]->count;
    }

    public static function lastDay($day, $date) {

        $count = static::select(DB::raw('COUNT(*)  count'))->whereBetween(DB::raw('created_at'), [$day, $date])->get();
        return $count[0]->count;
    }

//**********************************send email*****************************************************************
    public static function DeleteImageAWs($data = null) {
        return true;
    }
     
    public static function BestTeam($data,$api=0,$lang='en') {
        $team_image_fav='';
        $image_best_team=asset('images/logo/logo.png');
        if(!empty($data) && !empty($data->best_team)&& $data->best_team!=-1 && $data->best_team!='-1'){
            if(isset($data->team->id)){
                $image_best_team = finalValueByLang($data->team->image,'',$lang);
                $team_image_fav = $data->team->image_fav;
            }
        }
        return array('image_best_team'=>$image_best_team,'team_image_fav'=>$team_image_fav);
    }

    public static function BestTeamArray($data,$lang='en',$api=0) {
        $team_name=trans('app.general_fan');
        $team_link='general_fan';
        $image_best_team=asset('images/logo/logo.png');
        if(!empty($data->best_team)&& $data->best_team!=-1 && $data->best_team!='-1'){
            if(isset($data->team->id)){
                $image_best_team=finalValueByLang($data->team->image,'',$lang);
                $team_link=$data->team->link;
                $team_name=finalValueByLang($data->team->lang_name,$data->team->name,$lang);
            }
        }
        return array('image_best_team'=>$image_best_team,'team_name'=>$team_name,'team_link'=>$team_link);
    }
    public static function registerNewdwry($user_id = 0, $type_id = 1, $register = 0, $is_active = 1,$api=0) {
        $start_dwry = Subeldwry::get_startFirstSubDwry();
        if (isset($start_dwry->id)) {
            //check if current use register in current dwry
            if ($register == 0) {
                //$user_add_tran = GameTransaction::checkregisterDwry($user_id, $start_dwry->eldwry_id, $type_id);
                $user_add_tran = Game::checkregisterDwry($user_id, $start_dwry->eldwry_id);
                if (!isset($user_add_tran->id)) {
                    $register = 1;
                }
            }
            if ($register == 1) {
                //insert current user in current dwry and add in GameTransaction
                $input['update_by'] = $user_id;
                $input['user_id'] = $user_id;
                $input['eldwry_id'] = $start_dwry->eldwry_id;
                $input['is_active'] = $is_active;
                Game::create($input);
                $input['type_id'] = $type_id;
                $cost = $start_dwry->eldwry->cost;
                if (empty($cost) || !is_numeric($cost)) {
                    $cost = 0.00;
                }
                $input['cost'] = $cost;
                GameTransaction::create($input);
            }
        }
        return true;
    }

    public static function SelectCoulumUser($data_user,$lang='ar',$api=0) {
        $user['id'] = $data_user->id;
        $user['display_name'] = $data_user->display_name;
        $user['email'] = $data_user->email;
        $user['access_token'] = $data_user->access_token;
        $user['image'] = $data_user->image;
        $user['phone'] = $data_user->phone;
        $user['gender'] = $data_user->gender;
        $user['address'] = countryName($data_user->address);
        $user['city'] = cityName($data_user->city);
        $user['state'] = $data_user->state;
        $best_team=User::BestTeamArray($data_user,$lang,$api);
        $user=array_merge($user,$best_team);
        return $user;
    }

    public static function sessionLang($user_id) {
        $get_locale = session()->get('locale');
        if (!empty($get_locale)) {
            User::updateColum($user_id, 'lang', $get_locale);
//            session()->forget('locale');
        }
        return true;
    }

    public static function SendEmailTOUser($user_id, $type, $message_share = '', $array_data = [], $total_price = 0.00, $discount = 0.00, $title_contact = '') {
       return True;
//        $default_server = 'https://' . $_SERVER['SERVER_NAME'];
//        $site_url = $default_server . route('home');
        //$phone = Options::where('option_key', 'phone')->value('option_value');
        $site_url = $phone = $site_title = $site_open = $site_email = $facebook = $twitter = $google = $linkedin = '';
        $array_option_key = ['facebook', 'twitter', 'googleplus', 'linkedin', 'site_email', 'site_title', 'site_url', 'phone', 'site_open', 'logo_image'];
        $All_options = Options::get_Option('setting', $array_option_key);
        foreach ($All_options as $key => $value) {
            $$key = $value;
        }
        if (empty($site_email)) {
            $site_email = 'social@Site.com';
        }
        $inside = 0;
        //**************************************************
        $user_data = User::userData($user_id);
        if (isset($user_data->id)) {
            $user_name = $user_data->display_name;
            $user_email = $user_data->email;
        } else {
            if ($type == 'contact_form' || $type=='invite_group') {
                $user_name = $array_data['name'];
                $user_email = $array_data['email'];
            }
        }
        //**************************************************
        $array_email_data = array(
            'user_name' => $user_name,
            'user_email' => $user_email,
            'site_email' => $site_email,
            'phone' => $phone,
            'type' => $type,
            'site_url' => $site_url,
            'facebook' => $facebook,
            'twitter' => $twitter,
            'google' => $google,
            'linkedin' => $linkedin,
            'message' => $message_share
        );
        //*************************    
        $subject = 'Master';
        if ($type == 'register') {
            $subject = 'Register In Game';
        } elseif ($type == 'invite_group') {
            $subject = 'Invite Group';
            $array_email_data['code_group'] = $array_data['code_group'];
            $array_email_data['link_group'] = $array_data['link_group'];
            if (!isset($user_data->id)) {
                $array_email_data['user_email'] = $user_email = $array_data['email'];
                $array_email_data['user_name'] = $user_name = $array_data['name'];
            }
        } elseif ($type == 'contact_form') {
            $inside = 1;
            $type = 'message';
            $subject = 'Contact Us';
            if (!isset($user_data->id)) {
                $array_email_data['user_email'] = $user_email = $array_data['email'];
                $array_email_data['user_name'] = $user_name = $array_data['name'];
            }
        } elseif ($type == 'replay_contact') {
            $type = 'message';
            $subject = 'Contact Us';
            if (!empty($title_contact)) {
                $subject = $title_contact;
            }
            $array_email_data['user_email'] = $user_email = $array_data->email;
            $array_email_data['user_name'] = $user_name = $array_data->eman;
        }
        //$array_email_data = [];
        if ($inside == 1) {
            Mail::send('emails.' . $type, $array_email_data, function($message) use ($site_email, $user_email, $site_title, $subject) {
                $message->from($user_email);
                $message->to($site_email, $site_title)->subject($subject);
            });
        } else {
            if (!filter_var($user_email, FILTER_VALIDATE_EMAIL) === false) {
                Mail::send('emails.' . $type, $array_email_data, function($message) use ($site_email, $user_email, $site_title, $subject) {
                    $message->from($site_email);
                    $message->to($user_email, $site_title)->subject($subject);
                });
            }
        }
        return True;
    }

//******************************************************************************
    
}

