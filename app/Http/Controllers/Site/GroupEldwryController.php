<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\ClassSiteApi\Class_GroupEldwryPageController;
use App\Http\Controllers\SiteController;

class GroupEldwryController extends SiteController {

    public function __construct() {
        parent::__construct();
        $this->Class_GroupEldwryPage = new Class_GroupEldwryPageController();
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
    public function group_eldwry(Request $request) {
        if ($this->site_open == 1 || $this->site_open == "1") {
            if (isset(Auth::user()->id)) {
                $page_data= $this->Class_GroupEldwryPage->Page_group_eldwry_Site(Auth::user());
                if(!empty($page_data['redirect_route'])){
                    return redirect()->route($page_data['redirect_route']);
                }
                return view($page_data['url_name'],$page_data);
            } else {
                return redirect()->route('home');
            }
        } else {
            return redirect()->route('close');
        }
    }

    public function create(Request $request) {
        if ($this->site_open == 1 || $this->site_open == "1") {
            if (isset(Auth::user()->id)) {
                $page_data= $this->Class_GroupEldwryPage->Page_create_groupEldwry(Auth::user(),'create');
                if(!empty($page_data['redirect_route'])){
                    return redirect()->route($page_data['redirect_route']);
                }
                return view($page_data['url_name'],$page_data);
            } else {
                return redirect()->route('home');
            }
        } else {
            return redirect()->route('close');
        }
    }

    public function create_classic(Request $request) {
        if ($this->site_open == 1 || $this->site_open == "1") {
            if (isset(Auth::user()->id)) {
                $page_data= $this->Class_GroupEldwryPage->Page_create_groupEldwry(Auth::user(),'create_classic');
                if(!empty($page_data['redirect_route'])){
                    return redirect()->route($page_data['redirect_route']);
                }
                return view($page_data['url_name'],$page_data);
            } else {
                return redirect()->route('home');
            }
        } else {
            return redirect()->route('close');
        }
    }

    public function create_head(Request $request) {
        if ($this->site_open == 1 || $this->site_open == "1") {
            if (isset(Auth::user()->id)) {
                $page_data= $this->Class_GroupEldwryPage->Page_create_groupEldwry(Auth::user(),'create_head');
                if(!empty($page_data['redirect_route'])){
                    return redirect()->route($page_data['redirect_route']);
                }
                return view($page_data['url_name'],$page_data);
            } else {
                return redirect()->route('home');
            }
        } else {
            return redirect()->route('close');
        }
    }

    public function create_done(Request $request,$type_group) {
        if ($this->site_open == 1 || $this->site_open == "1") {
            if (isset(Auth::user()->id) && in_array($type_group,['classic','head'])) {
                $page_data= $this->Class_GroupEldwryPage->Page_create_done_groupEldwry(Auth::user(),$type_group);
                if(!empty($page_data['redirect_route'])){
                    return redirect()->route($page_data['redirect_route']);
                }
                return view($page_data['url_name'],$page_data);
            } else {
                return redirect()->route('home');
            }
        } else {
            return redirect()->route('close');
        }
    }
    
    public function invite(Request $request) {
        if ($this->site_open == 1 || $this->site_open == "1") {
            if (isset(Auth::user()->id)) {
                $page_data= $this->Class_GroupEldwryPage->Page_invite_groupEldwry(Auth::user(),'invite');
                if(!empty($page_data['redirect_route'])){
                    return redirect()->route($page_data['redirect_route']);
                }
                return view($page_data['url_name'],$page_data);
            } else {
                return redirect()->route('home');
            }
        } else {
            return redirect()->route('close');
        }
    }
    
    public function accept_invite(Request $request,$link) {
        if ($this->site_open == 1 || $this->site_open == "1") {
            if (isset(Auth::user()->id)) {
                $page_data= $this->Class_GroupEldwryPage->Page_invite_groupEldwry(Auth::user(),'accept_invite',0,$link);
                if(!empty($page_data['redirect_route'])){
                    return redirect()->route($page_data['redirect_route']);
                }
                return view($page_data['url_name'],$page_data);
            } else {
                return redirect()->route('home');
            }
        } else {
            return redirect()->route('close');
        }
    }

    public function join_group(Request $request) {
        if ($this->site_open == 1 || $this->site_open == "1") {
            if (isset(Auth::user()->id)) {
                $page_data= $this->Class_GroupEldwryPage->Page_join_groupEldwry(Auth::user(),0,'join','');
                if(!empty($page_data['redirect_route'])){
                    return redirect()->route($page_data['redirect_route']);
                }
                return view($page_data['url_name'],$page_data);
            } else {
                return redirect()->route('home');
            }
        } else {
            return redirect()->route('close');
        }
    }

    public function join_group_classic(Request $request) {
        if ($this->site_open == 1 || $this->site_open == "1") {
            if (isset(Auth::user()->id)) {
                $page_data= $this->Class_GroupEldwryPage->Page_join_groupEldwry(Auth::user(),0,'join_classic','classic');
                if(!empty($page_data['redirect_route'])){
                    return redirect()->route($page_data['redirect_route']);
                }
                return view($page_data['url_name'],$page_data);
            } else {
                return redirect()->route('home');
            }
        } else {
            return redirect()->route('close');
        }
    }

    public function join_group_head(Request $request) {
        if ($this->site_open == 1 || $this->site_open == "1") {
            if (isset(Auth::user()->id)) {
                $page_data= $this->Class_GroupEldwryPage->Page_join_groupEldwry(Auth::user(),0,'join_head','head');
                if(!empty($page_data['redirect_route'])){
                    return redirect()->route($page_data['redirect_route']);
                }
                return view($page_data['url_name'],$page_data);
            } else {
                return redirect()->route('home');
            }
        } else {
            return redirect()->route('close');
        }
    }

    public function single_group(Request $request,$link='') {
        if ($this->site_open == 1 || $this->site_open == "1") {
            if (isset(Auth::user()->id)) {
                $page_data= $this->Class_GroupEldwryPage->Page_group_groupEldwry(Auth::user(),0,$link);
                if(!empty($page_data['redirect_route'])){
                    return redirect()->route($page_data['redirect_route']);
                }
                return view($page_data['url_name'],$page_data);
            } else {
                return redirect()->route('home');
            }
        } else {
            return redirect()->route('close');
        }
    }

    public function admin_group(Request $request,$link='',$type_group='classic') {
        if ($this->site_open == 1 || $this->site_open == "1") {
            if (isset(Auth::user()->id)) {
                $page_data= $this->Class_GroupEldwryPage->Page_admin_groupEldwry(Auth::user(),0,$link,$type_group);
                if(!empty($page_data['redirect_route'])){
                    return redirect()->route($page_data['redirect_route']);
                }
                return view($page_data['url_name'],$page_data);
            } else {
                return redirect()->route('home');
            }
        } else {
            return redirect()->route('close');
        }
    }

}
