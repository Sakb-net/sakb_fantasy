<?php

namespace App\Http\Controllers\Site;
use Illuminate\Http\Request;
use App\Http\Controllers\SiteController;
use App\Models\Eldwry;
use App\Models\DraftUsers;
use App\Models\Subeldwry;
use App\Models\Team;
use App\Models\Draft;
use App\Models\AllType;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class DraftController extends SiteController {

    public function index() {
        if ($this->site_open == 1 || $this->site_open == "1") {
            $register_dwry = 1;
            $msg_finish_dwry = '';
            if (isset(Auth::user()->id)) {
                $locale = App::currentLocale();
                if ($locale == 'ar') {
                    $lang='';
                }else{
                    $lang = 'en';
                }
                $eldwry  = Eldwry::get_currentDwry();
                $checkDraft = DraftUsers::checkUserDraft(Auth::user()->id);
                if($checkDraft){
                    return redirect()->route('draft.draftRoom');
                }else{
                    $team = Team::getTeamId(1,$lang);
                    $title = 'create draft';
                     return view('site.draft.home',compact('title','team'));
                }
            } else {
                return redirect()->route('login');
            }
        } else {
            return redirect()->route('close');
        }
    }


    public function joinLeaugeDraft()
    {
            if ($this->site_open == 1 || $this->site_open == "1") {
            $register_dwry = 1;
            $msg_finish_dwry = '';
            if (isset(Auth::user()->id)) {
                $locale = App::currentLocale();
                if ($locale == 'ar') {
                    $lang='';
                }else{
                    $lang = 'en';
                }
                $checkDraft = DraftUsers::checkUserDraft(Auth::user()->id);
                if($checkDraft){
                    return redirect()->route('draft.draftRoom');
                }else{
                    $team = Team::getTeamId(1,$lang);
                    $title = 'join leauge draft';
                     return view('site.draft.joinLeauge',compact('title','team'));
                }
            } else {
                return redirect()->route('login');
            }
        } else {
            return redirect()->route('close');
        }
    }
    public function createLeaugeDraft()
    {
                if ($this->site_open == 1 || $this->site_open == "1") {
                $register_dwry = 1;
                $msg_finish_dwry = '';
                if (isset(Auth::user()->id)) {
                    $locale = App::currentLocale();
                    if ($locale == 'ar') {
                        $lang='';
                    }else{
                        $lang = 'en';
                    }
                    $eldwry  = Eldwry::get_currentDwry();
                    $checkDraft = DraftUsers::checkUserDraft(Auth::user()->id);
                    if($checkDraft){
                        return redirect()->route('draft.draftRoom');
                    }else{
                        $type = AllType::foundAllKeyType('type_key','eldwry_type',$lang);
                        $team = Team::getTeamId(1,$lang);
                        $title = 'create Leauge draft';
                        return view('site.draft.createLeague',compact('title','team','type'));
                    }
                } else {
                    return redirect()->route('login');
                }
            } else {
                return redirect()->route('close');
            }
    }

    public function createDraft()
    {
                if ($this->site_open == 1 || $this->site_open == "1") {
                $register_dwry = 1;
                $msg_finish_dwry = '';
                if (isset(Auth::user()->id)) {
                    $locale = App::currentLocale();
                    if ($locale == 'ar') {
                        $lang='';
                    }
                    $eldwry  = Eldwry::get_currentDwry();
                    $checkDraft = DraftUsers::where('user_id',Auth::user()->id)->first();
                    if($checkDraft){
                        dd('already joined');
                    }else{
                        $team = Team::getTeamId(1,$lang);
                        $title = 'create draft';
                        return view('site.draft.create',compact('title','team'));
                    }
                } else {
                    return redirect()->route('login');
                }
            } else {
                return redirect()->route('close');
            }
    }

    public function saveLeauge(Request $request){

        $notifi = 0;
        $link = get_RandLink();
        $code = generateRandomValue();

        $input = $request->all();
        foreach ($input as $key => $value) {
                 $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
         }

         if (array_key_exists("followed",$input))
            {
                $notifi = 1;
            }

        $eldwry  = Eldwry::get_currentDwry();
        $subEldwry = Subeldwry::get_CurrentSubDwry();
        $draft = new Draft;
        $draft->name = $input['dawryName'];
        $draft->link = $link;
        $draft->fav_team = $input['fav_team'];
        $draft->eldwry_id = $eldwry->id;
        $draft->sub_eldwry_id = $subEldwry->id;
        $draft->type_id = $input['dawryType'];
        $draft->max = $input['maxTeam'];
        $draft->min = $input['minTeam'];
        $draft->date = date("Y-m-d", strtotime($input['draftDate']));
        $draft->time = date("h:s:i");
        $draft->time_choose = date("h:s:i", strtotime($input['drafTime']));
        $draft->player_trade = '1';  //??
        $draft->code = $code;
        $draft->user_id = Auth::user()->id;
        $draft->admin_id = Auth::user()->id;
        $draft->save();

        $draftId = $draft->id;
        $draftUser = new DraftUsers;

        $draftUser->draft_id = $draftId;
        $draftUser->user_id = Auth::user()->id;
        $draftUser->team_name = $input['teamName'];
        $draftUser->notifi = $notifi;
        $draftUser->user_count = 1;
        $draftUser->save();
        return response()->json(['url'=>url('draft/draftRoom')]);

    }
    public function saveDraft(Request $request){
        dd($request->all());
    }

    public function draftRoom(){
        if ($this->site_open == 1 || $this->site_open == "1") {
            emptySessionDeletPlayer();
            $register_dwry = 1;
            $msg_finish_dwry = '';
            if (isset(Auth::user()->id)) {
                $title = 'draft room';
                return view('site.draft.draftRoom',compact('title'));
            } else {
                return redirect()->route('login');
            }
        } else {
            return redirect()->route('close');
        }
    }


}
