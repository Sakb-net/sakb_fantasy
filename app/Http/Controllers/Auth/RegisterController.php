<?php
namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\Authorizes\Resources;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\SiteController;
use App\Models\Options;
use App\Models\User;
use App\Models\SocialProvider;
use App\Models\Role;
use App\Models\Team;
use App\Models\TeamUser;
use Hash;
use Socialite;

//use Auth;
class RegisterController extends SiteController { 

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';//home

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->middleware('guest');
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        return Validator::make($data, [
                    'name' => 'required|string|max:255',
                    // 'phone' => 'required|string|max:100',
                    // 'email' => 'required|string|email|max:255|unique:users',
                    'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function MakeConfirmValidat(array $input) {
        $wrong_form = $correct_form = NULL;
        if(empty($input['email'])&&empty($input['phone'])){
            $wrong_form = trans('app.enter_email_or_phone');
        }else{
            $cont_check=1;
            if(!empty($input['email'])){
                if (filter_var($input['email'], FILTER_VALIDATE_EMAIL) === false) {
                    $cont_check=0;
                    $wrong_form = trans('app.email_not_correct');
                }else{
                    //confirm not use email
                    $user_email_id = User::foundUser($input['email'], 'email');
                    if ($user_email_id > 0) {
                        $cont_check=0;
                        $wrong_form = trans('app.email_already_use');
                    }   

                }    
            }
            if(!empty($input['phone'])){
                if (preg_match("/^([+]?)[0-9]{8,16}$/", $input['phone'])) {
                    //confirm not use phone
                    $user_phone_id = User::foundUser($input['phone'], 'phone');
                    if ($user_phone_id > 0) {
                        $cont_check=0;
                        $wrong_form = trans('app.phone_number_already_used');
                    }
                } else {
                    $cont_check=0;
                    $wrong_form = trans('app.please_phone_correct');
                }  
            }

            if($cont_check==1){
                if (strlen($input['password']) >= 8 && strlen($input['password']) <= 100) {
                    if ($input['password'] == $input['password_confirmation']) {

                        if (strlen($input['name']) >= 3 && strlen($input['name']) <= 100) {
                            if ($input['name'] == 'master' || $input['name'] == 'fantasy') {
                                $input['name'] = $input['name'] . time();
                            }
                            if (!empty($input['city'])) {
                                //if (!empty($input['state'])) {
                                    //ok register
                                    $wrong_form = NULL;
                               //} else {
                                 //  $wrong_form = trans('app.please_enter_state');
                               //}
                            } else {
                                $wrong_form = trans('app.please_enter_city');
                            }
                        } else {
                            $wrong_form = trans('app.user_name_3_100');
                        }                       

                    } else {
                        $wrong_form = trans('app.enter_password_match');
                    }
                } else {
                    $wrong_form = trans('app.password_8_100');
                }
            }
        }
        return $wrong_form;
    }

    protected function create(array $data) {
        $is_active = 1; // is_numeric($user_active) ? $user_active : 0;
        $display_name = $data['name'];
        $email = $data['email'];
        $address = $data['address'];
        $user_name = explode('@', $email);
        $type = 'member';
        $best_team=-1;//'general_fan';
        if(!empty($data['best_team'])){
            $data_team=Team::foundData('link',$data['best_team']);
            if(isset($data_team->id)){
                $best_team=$data_team->id;
            }
        }
        //add session
        $session_user = generateRandomValue();
        session()->put('session_user', $session_user);
        return User::create([
                    'display_name' => $display_name,
                    'email' => $email,
                    'password' =>Hash::make($data['password']),
                    'name' => (str_replace(' ', '_', $user_name[0] . time())),
                    'phone' => $data['phone'],
                    'image' => generateDefaultImage($display_name),
                    'access_token' => generateRandomToken(),
                    'session' => $session_user,
                    'is_active' => $is_active,
                    'type' => $type,
                    'best_team' => $best_team,
                    'address' => $address,
                    'city' => $data['city'],
//                    'state' => $data['state'],
                    'reg_site' => 'site',
        ]);
    }

    protected function addCreate($request, array $input, $user_role = '') {
        if (empty($user_role)) {
            $user_role = Options::where('option_key', 'default_role')->value('option_value');
        }
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create($input)));
        $user->attachRole($user_role);
        Auth::login($user);
        return $user;
    }

    public function register(Request $request) {
        $input = $request->all();
        $teamArray = explode('-', $request->best_team);
        $input['best_team'] = $teamArray[1];
        foreach ($input as $key => $value) {
            $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
        }
            $user = $this->addCreate($request, $input);
            User::sessionLang($user['id']);
            User::registerNewdwry($user['id'],1,1);

        if($request->has('followed')){
            foreach($request->followed as $value){
                $myArray = explode('-', $value);
                $teamId = Team::where('link',$myArray[1])->select('id')->first();
                $checkEmail = 0;
                $checknoti = 0;
                if($request->has('emailMessages')){
                    if (in_array($myArray[1], $request->emailMessages)) {
                        $checkEmail = 1;
                    }
                }

                if($request->has('sms')){
                    if (in_array($myArray[1], $request->sms)) {
                        $checknoti = 1;
                    }
                }
                 
                $teamUser = new TeamUser;
                $teamUser->user_id = $user->id;
                $teamUser->team_id = $teamId->id;
                $teamUser->is_notif = $checknoti;
                $teamUser->is_email = $checkEmail;
                $teamUser->save();
            }

        }
        return redirect(route('wellcome'));
        //return redirect(RouteServiceProvider::HOME);
    }
    //************** Login By social (facebook ,twitter , google )**********
    //https://laravel.com/docs/socialite 
    //https://console.developers.google.com
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($provider) {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback($provider) {
        $wrong_form = $correct_form = null;
        if (isset($_REQUEST['error'])&& !empty($_REQUEST['error'])) {
            return redirect('/');
        }else{
            try {
                $socialuser = Socialite::driver($provider)->user();
            } catch (Exception $e) {
                return redirect('/');
            }
        }
        $user_id = 0;
        //check we have logged provider
        $socialprovider = SocialProvider::where('provider_id', '=', $socialuser->getId())->where('provider', '=', $provider)->first();
        // $socialprovider = SocialProvider::where('provider_id', '=', $socialuser->getId())->first();
        if (!$socialprovider) {
            //check email found or not
            $user_email_data = User::foundUser($socialuser->getEmail(), 'email', 1);
            if (isset($user_email_data->id)) {
                // $wrong_form = trans('app.email_already_use');
                $user = $user_email_data;
                $user_id = $user->id;
                $user_provider = SocialProvider::insertProviderUser($user_id, $socialuser->getId(), $provider, 1);
            } else {
                //create user and provider
                $display_name = $socialuser->getNickname();
                if (empty($display_name)) {
                    $display_name = $socialuser->getName();
                }
                $name_user = (str_replace(' ', '_', $socialuser->getName() . time())); //str_random(8)
                $image = $socialuser->getAvatar();
                if (empty($image)) {
                    $image = generateDefaultImage($display_name);
                }
                $email = $socialuser->getEmail();
                if (empty($email)) {
                    $email = $name_user . '@gamefantasy.com';
                }
                $session_user = generateRandomValue();
                session()->put('session_user', $session_user);
                $user = User::create([
                            'display_name' => $display_name,
                            'email' => $email,
                            'password' => Hash::make('sakb'),
                            'name' => $name_user,
                            'phone' => time(),
                            'mobile' => null,
                            'image' => $image,
                            'session' => $session_user,
                            'access_token' => generateRandomToken(),
                            'is_active' => 1,
                            'reg_site' => 'site',
                ]);
//            ['phone' => $socialuser->getPhone()],['gender' => $socialuser->getGender()]
                $user->socialproviders()->create(
                        ['provider_id' => $socialuser->getId(), 'provider' => $provider]
                );
                $user_id = $user['id'];
                //add role
                $user_role = Options::where('option_key', 'default_role')->value('option_value');
                $user->attachRole($user_role);
                //send email
                $sen_email = User::SendEmailTOUser($user_id, 'register');
            }
        } else {
            //get user
            $user = $socialprovider->user;
            $user_id = $user->id;
        }
        if (empty($wrong_form)) {
            Auth::login($user);
            $session_user = generateRandomValue();
            $access_token = null; //generateRandomToken();
            session()->put('session_user', $session_user);
            User::updateColumTwo($user_id, 'access_token', $access_token, 'session', $session_user);
            User::sessionLang($user_id);
            //last url
            $get_session = session()->get('session_url');
            if (empty($get_session) || $get_session == '/') {
                $get_session = route('home');
            }
            //register in current dwary
            User::registerNewdwry($user_id,1,0);
            return redirect($get_session); //return redirect('/');
        } else {
            return view('auth.register');            
        }
//        return $user->getEmail();
    }

//**************************** End Login By Social Media****************************






}