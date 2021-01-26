<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SiteController;
use App\Repositories\RankingEldwryRepository;  
use App\Models\User;

class AjaxRankingEldwryController extends SiteController {

    public function __construct() {
        parent::__construct();
        $this->RankingEldwryRepository =new RankingEldwryRepository();
        if (isset(Auth::user()->id)) {
            $this->current_id = Auth::user()->id;
            $this->user_key = Auth::user()->name;
        }
    }

    public function get_subeldwry_ranking_eldwry(Request $request) {
        if ($request->ajax()) {
            $response =1;
            $data=$this->RankingEldwryRepository->get_subeldwry_ranking_eldwry();
            return response()->json(['status' =>1, 'response' => $response,'data'=>$data]);
        }
    }

    public function get_all_ranking_eldwry(Request $request) {
        if ($request->ajax()) {
            $response =1;
            $subeldwry_link='';
            $input = $request->all();
            foreach ($input as $key => $value) {
                $$key = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            $data=$this->RankingEldwryRepository->get_RankingEldwry('link',$subeldwry_link,18,0,$request); //$limit,$offset
            return response()->json(['status' =>1, 'response' => $response,'data'=>$data]);
        }
    }

    public function get_home_ranking_eldwry(Request $request) {
        if ($request->ajax()) {
            $response =1;
            $subeldwry_link='';
            $data=$this->RankingEldwryRepository->get_home_RankingEldwry(); //$limit,$offset
            return response()->json(['status' =>1, 'response' => $response,'data'=>$data]);
        }
    }

}