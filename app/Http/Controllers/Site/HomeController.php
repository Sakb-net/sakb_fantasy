<?php

namespace App\Http\Controllers\Site;

use App\Http\Requests\ContactFormRequest;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use App\Models\User;
use App\Models\Page;
use App\Models\Match;
use App\Models\Contact;
use App\Models\TeamUser;
use App\Models\Eldwry;
use App\Models\Subeldwry;
use Auth;
use Mail;
use App\functions;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\ClassSiteApi\Class_PageController;

class HomeController extends SiteController {

    public function __construct() {
        parent::__construct();       
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
    public function home() {
      if ($this->site_open == 1 || $this->site_open == "1") {
            $lang = $this->lang;
            $current_id=0;
            $attrs = [];
            $favTeamMatches = [];
            if (isset(Auth::user()->id)) {
                $current_id=Auth::user()->id;
                session()->put('teamFav', Auth::user()->best_team);

                $favTeamMatches = Match::where('first_team_id',Auth::user()->best_team)->orWhere('second_team_id',Auth::user()->best_team)->limit(10)->orderBy('id','desc')->get();
                
                foreach($favTeamMatches as $key => $value){
                    $time=strtotime($value->date);
                    $date=date("d/m/Y",$time);
                    $value->date = date("d/m/Y",$time);
                }

                $followTeams = TeamUser::select('team_id')->where('user_id',Auth::user()->id)->pluck('team_id')->toArray();
            
                if(count($followTeams) > 0){
                    $followTeamMatches = Match::whereIn('first_team_id',$followTeams)->orWhereIn('second_team_id',$followTeams)->limit(10)->orderBy('id','desc')->get();

                    if(count($followTeamMatches) > 0){
                        session()->put('followTeams', $followTeams);
                        foreach ($followTeamMatches as $key => $value) {

                            $time=strtotime($value->date);
                            $date=date("d/m/Y",$time);

                            $attrs[$date][] = $value;
                        }
                    }
                }else{
                    $followTeamMatches = [];
                }

            }

            $start_dwry = Eldwry::get_currentDwry();
            $lang = $this->lang;
            $offset = 1;
            $subdwry_limit =1;
            $limit=15;
            $final_offset=0;
            $gwla_final_offset=0;
    
            $all_subdwry = Subeldwry::get_DataSubeldwry('eldwry_id', $start_dwry->id, 1, 'ASC', $subdwry_limit,$gwla_final_offset,'',null,'');

            $match_public = Match::get_DataGroup($all_subdwry, $lang, 0,$limit,$final_offset);
            if(isset($match_public[0])){
                foreach($match_public[0]['match_group'] as $key=>$value){
                    $match_public[0]['match_group'][$key]['userDate'] = ConvertUTC_ToDateCurrentUser12_hour($value['date'].' '.$value['time']);
                }
            }
            $get_data=new Class_PageController();
            $return_data=$get_data->Page_Home($lang ,6,0,2,-1,0,$current_id);
           // $page = Page::get_typeColum('home',$lang);
            $return_data['logo_image'] = $this->logo_image;
            $return_data['title'] = trans('app.Home'). " - " . $this->site_title; // $page->title 
            $return_data['user_key'] = $this->user_key;
            View::share('title', $return_data['title']);
            View::share('activ_menu', 1);
            return view('site.home',$return_data)->with(compact('attrs','favTeamMatches','match_public'));

        } else {
            return redirect()->route('close');
        }
    }

    public function wellcome(Request $request) {
        $title = '';
        $message = '';
        return view('auth.after_register', compact('title', 'message'));
    }
    public function about() {
        if ($this->site_open == 1 || $this->site_open == "1") {
            $data_page = new Class_PageController();
            $return_data = $data_page->Page_about();
            $title = $return_data['name'] . " - " . $this->site_title;
            View::share('title', $title);
            View::share('activ_menu', 2);
            $return_data['page_title'] = $return_data['name']; //'عن النادي';
            $array_best_team=User::BestTeam(Auth::user());
            $return_data['team_image_fav']=$array_best_team['team_image_fav'];
            return view('site.pages.about', $return_data);
        } else {
            return redirect()->route('close');
        }
    }

    public function terms() {
        if ($this->site_open == 1 || $this->site_open == "1") {
            $data_page = new Class_PageController();
            $return_data = $data_page->PageContent('terms');
            $title = $return_data['title'] . " - " . $this->site_title;
            View::share('title', $title);
            View::share('activ_menu', 13);
            $return_data['page_title'] = trans('app.terms_condition');
            $array_best_team=User::BestTeam(Auth::user());
            $return_data['team_image_fav']=$array_best_team['team_image_fav'];
            return view('site.pages.terms', $return_data);
        } else {
            return redirect()->route('close');
        }
    }

    public function contact() {
        if ($this->site_open == 1 || $this->site_open == "1") {
            $data_page = new Class_PageController();
            $return_data = $data_page->Page_contactUs();
            $title = $return_data['title'] . " - " . $this->site_title;
            View::share('title', $title);
            View::share('activ_menu', 12);
            $return_data['correct_form'] = session()->get('correct_form');
            session()->forget('correct_form');
            $return_data['page_title'] =$return_data['title'];// trans('app.contact_us');
            $array_best_team=User::BestTeam(Auth::user());
            $return_data['team_image_fav']=$array_best_team['team_image_fav'];
            return view('site.pages.contact', $return_data); //, 'phone', 'email', 'address'
        } else {
            return redirect()->route('close');
        }
    }

    public function contactStore(ContactFormRequest $request) {
        if ($this->site_open == 1 || $this->site_open == "1") {
            $email = DB::table('options')->where('option_key', 'email')->value('option_value');
            $user_email = $request->get('email');

            $name = stripslashes(trim(filter_var($request->get('name'), FILTER_SANITIZE_STRING)));
            $type = stripslashes(trim(filter_var($request->get('type'), FILTER_SANITIZE_STRING)));
            $useremail = stripslashes(trim(filter_var($request->get('email'), FILTER_SANITIZE_STRING)));
            $user_message = stripslashes(trim(filter_var($request->get('message'), FILTER_SANITIZE_STRING)));
            $visitor = $request->ip();

            $user_id = NULL;
            $user_account = Auth::user();
            if (!empty($user_account)) {
                $user_id = $user_account->id;
            }
            $site_title = $this->site_title;
            $contact = new Contact();
            $contact->insertContact($user_id, $visitor, $name, $useremail, $user_message, $type);
//          $emailClient = 'social@baims.com';
//          $link = URL::to('/') . "/resetpassword/$token";//URL::to('/') . "/client/$linkCllient";
//          $link = route('home');
            Mail::send('emails.contact', array(
                'name' => $name,
                'email' => $useremail,
                'type' => $type, //title
                'user_message' => $user_message
                    ), function($message) use ($email, $user_email, $site_title) {
                $message->from($user_email);
                $message->to($email, $site_title)->subject('Contact US');
            });
            session()->put('correct_form', trans('app.send_success'));
            return redirect()->route('site.contact'); //->with('success', 'Message sent successfully');
        } else {
            return redirect()->route('close');
        }
    }

//**************************goto payment for mobile App***********************************
    public function mobile(Request $request, $checkoutId = '') {
        if (!empty($checkoutId)) {
            $array_data['checkoutId'] = $checkoutId; //$_REQUEST['checkoutId'];
            $array_data['shopperResultUrl'] = 'http://alahliclub.sakb.net/api/v1/confirmPayment'; // 'https://hyperpay.docs.oppwa.com/tutorials/integration-guide';      
            View::share('title', 'ادفع الان');
            return view('site.payment.mobile', $array_data);
        } else {
            return redirect()->route('home');
        }
    }

}
