<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\UserMeta;
use App\Models\User;
use App\Models\Category;
use App\Models\Options;
use Hash;
//use App\Models\Page;
//use App\Models\Message;
//use App\Models\MessageContent;
//use App\Models\MessageUser;
//use DB;
//use Carbon\Carbon;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\ClassSiteApi\Class_UserController;

class ProfileController extends SiteController {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    //profile
    public function index(Request $request) {
        if ($this->site_open == 1 || $this->site_open == "1") {
            if (isset(Auth::user()->id)) {
                View::share('title',trans('app.profile'));
                View::share('activ_menu', 7);
                $user = Auth::user();
                $birth_day = NULL;
                $data_msg=GetResultMsgsession();
                $array_best_team=User::BestTeam(Auth::user());
                $array_data = array('user' => $user, 'birth_day' => $birth_day,'team_image_fav'=>$array_best_team['team_image_fav']);
                $array_data=array_merge($array_data,$data_msg);
                return view('site.profile.index', $array_data)->with('i', ($request->input('page', 1) - 1) * 5);
            } else {
                return redirect()->route('login');
            }
        } else {
            return redirect()->route('close');
        }
    }

    //profile store
    public function store(Request $request) {
        $user = Auth::user();
        if (isset($input['submit'])) {
            $this->validate($request, [
                'name' => 'required|max:255|unique:users,name,' . $user->id,
                'email' => 'required|max:255|email|unique:users,email,' . $user->id,
                'phone' => 'max:50',
//            'display_name' => 'required',
            ]);
        } elseif (isset($input['email_pass'])) {
            $this->validate($request, [
                'password' => 'same:confirm-password',
            ]);
        }
        $input = $request->all();
        foreach ($input as $key => $value) {
            $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
        }
        if (isset($input['submit'])) {
            $input['password'] = $user->password;
            $input['display_name'] = $input['name'];
            $input['name'] = $user->name;
            if (empty($input['image'])) {
                $input['image'] = $user->image;
            }
            $update_user = $user->update($input);
//            $data_meta = UserMeta::DataUserMetaValue(Auth::user()->id, 'data', 'meta_value');
//            $input['stateSend'] = $data_meta['stateSend'];
//            $array_meta = array('birth_day' => $input['birth_day'], 'stateSend' => $input['stateSend'],
//                'social' => ['facebook' => $input['facebook'], 'twitter' => $input['twitter'],
//                    'instagram' => $input['instagram'], 'youtube' => $input['youtube']]);
//            $meta_value = json_encode($array_meta);
//            $meta = new UserMeta();
//            $meta->updateMeta($user->id, $meta_value);
            session()->put('correct_form', trans('app.save_success'));
        }
        if (isset($input['email_pass'])) {
            if ($input['password'] == $input['password_confirmation']) {
                $password_hash = Hash::make($input['user_pass']);  //bcrypt($input['user_pass']);
                if (Hash::check($input['user_pass'], $password_hash) && Hash::check($input['user_pass'], $user->password)) {
                    $new_password = Hash::make($input['password']);  //bcrypt($input['password']);
                    User::where('id', $user->id)->update(['password' => $new_password]);
                    session()->put('correct', trans('app.Data_change_success')); //save_success
                } else {
                    session()->put('wrong', trans('app.please_verify_email_password'));
                }
            } else {
                session()->put('wrong', trans('app.enter_password_match'));
            }
            // session()->put('correct', trans('app.save_success'));
        }
        return redirect()->route('profile.index'); //->with('success', 'Successfully Saved');
    }

}
