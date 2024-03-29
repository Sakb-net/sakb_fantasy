<?php

namespace App\Http\Controllers\Site;
use Illuminate\Http\Request;
use App\Http\Controllers\SiteController;
use App\Models\Eldwry;
use App\Models\DraftUsers;
use App\Models\Subeldwry;
use App\Models\Team;
use App\Models\Draft;
use App\Models\Options;
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
                    }else{
                        $lang='en';
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
        $code = generateRandomCode(7);

        $input = $request->all();
        foreach ($input as $key => $value) {
            $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
         }

         if (array_key_exists("followed",$input))
            {
                $notifi = 1;
            }

        $dataUtc = ConvertDateCurrentUserToUtc(date("Y-m-d h:s a", strtotime($input['draftDate'] .' '.$input['drafTime'])));

        $dateAfterDay = date('Y-m-d h:s a', strtotime("+1 day"));
        if(strtotime($dateAfterDay) - strtotime($dataUtc) < 0){
            return response()->json(['msg' =>__('text.checkDraftTime'),'status'=>false]);
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
        $draft->date = date("Y-m-d", strtotime($dataUtc));
        $draft->time = date("H:i:s");
        $draft->time_choose = date("Y-m-d H:i:s", strtotime($dataUtc));
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
        $draftUser->fav_team = $input['fav_team'];
        $draftUser->notifi = $notifi;
        $draftUser->user_count = 1;
        $draftUser->save();
        return response()->json(['url'=>url('draft/draftRoom'),'status'=>true]);

    }
    public function saveDraft(Request $request){

        $notifi = 0;
        $link = get_RandLink();
        $code = generateRandomCode(7);

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
            $draft->link = $link;
            $draft->eldwry_id = $eldwry->id;
            $draft->sub_eldwry_id = $subEldwry->id;
            $draft->type_id = 15;
            $draft->max = $input['dawrySize'];
            $draft->min = 2;
            $draft->date = date("Y-m-d");
            $draft->time = date("H:i:s");
            $draft->time_choose = date('H:i:s', strtotime('+1 hour'));
            $draft->user_id = Auth::user()->id;
            $draft->save();
    
            $draftId = $draft->id;
            $draftUser = new DraftUsers;
    
            $draftUser->draft_id = $draftId;
            $draftUser->user_id = Auth::user()->id;
            $draftUser->team_name = $input['teamName'];
            $draftUser->notifi = $notifi;
            $draftUser->user_count = 1;
            $draftUser->save();
            return response()->json(['url'=>url('draft/draftRoom'),'status'=>true]);
    }

    public function joinLeauge(Request $request){


        $checkCode = Draft::checkLeagueCode($request->dawryCode);
        if($checkCode){

            $input = $request->all();
            foreach ($input as $key => $value) {
                     $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
             }

             $notifi = 0;
             if (array_key_exists("followed",$input))
                {
                    $notifi = 1;
                }

            $draftUser = new DraftUsers;
            $draftUser->draft_id = $checkCode->id;
            $draftUser->user_id = Auth::user()->id;
            $draftUser->fav_team = $input['fav_team'];
            $draftUser->team_name = $input['teamName'];
            $draftUser->notifi = $notifi;
            $draftUser->user_count = 1;
            $draftUser->save();
            return response()->json(['url'=>url('draft/draftRoom'),'status' => true]);
        }else{
            $arr = array('msg' =>__('text.addFailed'), 'status' => true);
            return response()->json(array('msg' =>__('text.wrongCode'), 'status' => false));
        }
    }

    public function draftRoom(){
        if ($this->site_open == 1 || $this->site_open == "1") {
            $register_dwry = 1;
            $msg_finish_dwry = '';
            if (isset(Auth::user()->id)) {
                $draftUser = DraftUsers::selectUserDraft(Auth::user()->id);
                $draft = Draft::selectDraft($draftUser->draft_id);

                $convertTime = ConvertUTC_ToDateCurrentUser(($draft->date .' '.$draft->time_choose));
                $title = 'draft room';
                $time = date("Y-m-d H:i:s", strtotime($convertTime));
                return view('site.draft.draftRoom',compact('title','draft','time'));
            } else {
                return redirect()->route('login');
            }
        } else {
            return redirect()->route('close');
        }
    }


    public function checkFullDraft(Request $request){
        $draftId = $request->id;
        $draftData = Draft::selectDraft($draftId);
        $draftJoinCount = DraftUsers::getJoinCount($draftId);
        if($draftJoinCount >= $draftData->min && $draftJoinCount <= $draftData->max){
            $arr = array('status' => true);
        }else{
            $option = Options::where('option_key', 'draft_cooldown')->first();
            if($option->option_value == 1){
                $hours = '+'.$option->option_value .'hour';
            }else{
                $hours = '+'.$option->option_value .'hours';
            }

            $userTime = ConvertUTC_ToDateCurrentUser(date('Y-m-d H:i:s', strtotime($hours)));
            $updateDraft = Draft::find($draftId);
            $updateDraft->time_choose = date('H:i:s', strtotime($hours));
            $updateDraft->date = date('Y-m-d', strtotime($hours));

            $updateDraft->save();

            $arr = array('status' => false,'time'=>$userTime);
        }
        return response()->json($arr);
    }


}
