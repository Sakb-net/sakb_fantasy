<?php
namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\Authorizes\Resources;
use Illuminate\Html\Html\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Options;
use Redirect;



class LoginAdminController extends BaseController {

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
     
//    protected $redirectTo = '/admin';
    protected $redirectTo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $admin_panel = Options::where('option_key', 'admin_url')->value('option_value');
        if($admin_panel == '' || $admin_panel == NULL){
            $admin_panel = 'admin';   
        }
        $this->redirectTo = '/'.$admin_panel;
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm() {
        session()->put('url.intended', url()->previous());
        return view('admin.pages.login');
    }

    public function authenticated($request, $user) {
        return redirect(session()->pull('url.intended', $this->redirectTo));
        
    }

    private function checkActive() {
        if (!Auth::user()->is_active==1) {
            Auth::logout();
        }
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
        'email' => 'required|email',
        'password' => 'required|alphaNum|min:8'
        );
        // checking all field
        $validator = Validator::make($request->all() , $rules);
        // if the validator fails, redirect back to the form
        if ($validator->fails()){
            return Redirect::to('admin.login')->withErrors($validator) // send back all errors to the login form
            ->withInput($request->except('password')); // send back the input (not the password) so that we can repopulate the form
        }else{
          // create our user data for the authentication
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'is_active' => 1])) {
                $this->checkActive();
                return Redirect::to('admin');
            } else {
                return Redirect::to('admin.login');
            }
        }
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect($this->redirectTo);
    }

}
