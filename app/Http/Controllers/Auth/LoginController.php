<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\Authorizes\Resources;
use Illuminate\Html\Html\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SiteController;
use Illuminate\Http\Request;

use App\Models\User;
use Session;
use Redirect;
// use Auth;

class LoginController extends SiteController
{

    public function __construct() {
        parent::__construct();
        $this->middleware('guest')->except('logout');
    }

    private function checkActive() {
        $state = 1;
        if (!empty(Auth::user())) {
            if (!Auth::user()->is_active == 1) {
//            if (!Auth::user()->isActive()) {
                Auth::logout();
                $state = 0;
            } else {
                //register in current dwary
                User::registerNewdwry(Auth::user()->id,1,0);
                //add session
                $session_user = generateRandomValue();
                $access_token = generateRandomToken();
                session()->put('session_user', $session_user);
                Auth::user()->updateColumTwo(Auth::user()->id, 'access_token', $access_token, 'session', $session_user);
                Auth::user()->sessionLang(Auth::user()->id);
                $state = 1;
            }
        } else {
            $state = -1;
        }
        return $state;
    }

    private function sendLoginResponseNotActive(Request $request, $is_active = 0) {
      if ($is_active == -1) {
          $wrong_form = trans('app.name_or_password_not_correct');
      } else {
          $wrong_form = trans('app.account_disabled_site');
      }
     session()->put('wrong_form_login', $wrong_form);
     return Redirect::to('login');
    }

    private function sendLoginResponseActive(Request $request) {
        $get_session = session()->get('session_url');
        if (empty($get_session) || $get_session == '/' || $get_session == 'home') {
            $get_session = '/';//route('home');
        }
        return Redirect::to($get_session);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(Request $request) {
      // Creating Rules for Email and Password
      $rules = array(
        'email' => 'required', //|email
        'password' => 'required|alphaNum|min:8'
    	);
        // checking all field
        $validator = Validator::make($request->all() , $rules);
        // if the validator fails, redirect back to the form
        if ($validator->fails()){
            return Redirect::to('login')->withErrors($validator) // send back all errors to the login form
            ->withInput($request->except('password')); // send back the input (not the password) so that we can repopulate the form
        }else{
          // create our user data for the authentication
          $ok_login=0;
          if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'is_active' => 1])) {
              $ok_login=1;
          }else{
              if (Auth::attempt(['phone' => $request->email, 'password' => $request->password, 'is_active' => 1])) {
                  $ok_login=1;
              }
          } 
          if ($ok_login==1) {
              $is_active = $this->checkActive();
              if ($is_active == 1) {
                  return $this->sendLoginResponseActive($request);
              } else {
                  return $this->sendLoginResponseNotActive($request, $is_active);
              }
          } else {
              return $this->sendLoginResponseNotActive($request, -1);
          }
        }
    }

  public function logout(Request $request) {
        if (isset(Auth::user()->id)) {
            Auth::user()->updateColumTwo(Auth::user()->id, 'access_token', null, 'session', null);
            Auth::logout();
            session()->flush();
        }
        return Redirect::to('login'); // redirection to login screen
    }

    
}

