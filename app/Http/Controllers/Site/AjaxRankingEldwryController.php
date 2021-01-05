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

    public function get_ranking_eldwry(Request $request) {
        if ($request->ajax()) {
            $response =1;
            $input = $request->all();
            // $type_page = stripslashes(trim(filter_var($input['type_page'], FILTER_SANITIZE_STRING)));
            $data=$this->RankingEldwryRepository->get_RankingEldwry(); //0,$limit,$offset
                // print_r($data);die;
            return response()->json(['status' =>1, 'response' => $response,'data'=>$data]);
        }
    }

}