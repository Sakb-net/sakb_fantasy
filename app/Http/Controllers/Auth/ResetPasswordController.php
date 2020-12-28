<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\SiteController;
// use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Models\User;
use App\Models\PasswordReset;

class ResetPasswordController extends SiteController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    // use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest');
    }

    
    public function reset(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|max:255',
            'password' => 'required|max:255',
            'password_confirmation' => 'required|max:255',
        ]);
        $input = $request->all();
        foreach ($input as $key => $value) {
//            if ($key != "_token" ) {
            $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
//            }
        }
        $wrong_form = $correct_form = null;
        if ($input['password'] != "" && $input['password_confirmation'] != "" && $input['password'] = $input['password_confirmation']) {
            $check_rest = PasswordReset::get_DataEmailTime('email',$input['email'], 1);
            if ($check_rest == 1) {
                $user = User::foundUser($input['email'], 'email', 0);
                if (!empty($user) && isset($user->password) && isset($user->id)) {
                    //update new_password
                    $new_password = bcrypt($input['password']);
                    User::where('id', $user->id)->update(['password' => $new_password]);
                    PasswordReset::where('email', $user->email)->update(['created_at' =>null]);
                    $this->guard()->login($user);
                } else {
                    $wrong_form =trans('app.email_not_found');
                }
            } else {
                $wrong_form =trans('app.line_email_expired');
            }
        } else {
            $wrong_form = trans('app.name_or_password_not_correct');
        }
        if (empty($wrong_form)) {
            return  redirect(route('home'));
//            return $this->registered($request, $user) ?: redirect($this->redirectPath());
        } else {
            return view('auth.passwords.reset', compact('wrong_form', 'correct_form'));
        }
    }

}
