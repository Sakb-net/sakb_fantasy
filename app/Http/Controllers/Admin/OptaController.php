<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Eldwry;
use App\Models\Subeldwry;
use App\Models\Match;
use App\Models\Player;
use DB;
use App\Http\Controllers\OptaApi\Class_OptaController;
use App\Http\Controllers\OptaApi\Class_TransferNotPlayController;

class OptaController extends AdminController {

    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */
    public function create($type_page='') {

        if (!$this->user->can(['access-all', 'post-type-all', 'opta-all', 'opta-create', 'opta-edit'])) {
            return $this->pageUnauthorized();
        }
        if ($this->user->can(['access-all', 'post-type-all', 'opta-all', 'opta-edit'])) {
            $opta_active = 1;
        } else {
            $opta_active = 0;
        }
        $new = 1;
        $matches=Match::where('is_active',1)->get();
        $subeldwrys=Subeldwry::where('is_active',1)->get();

        //for upload data only for test
        if($type_page=='upload'){
            $date='2019-05-30 00:00:18';
            //***************
            $get_data=new Class_OptaController();
            // $data=$get_data->GetAllMatch_PointPlayer(); //GetAllPointPlayer
            $data=$get_data->GetAllEldwry_PointUser($date);

           //****************
           // $get_transfer=new Class_TransferNotPlayController();
           // $data=$get_transfer->TransferAllSubEldwry();
           //****************
            print_r($data);die;
        }
        //end
        $type_action='';
        $link_return = route('admin.opta.index');
        return view('admin.opta.create_opta', compact('link_return','type_page','type_action', 'new', 'opta_active','matches','subeldwrys'));
    }

    /**

     * Remove the specified resource from storage.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */
    public function championship(Request $request) {
        if (!$this->user->can(['access-all', 'post-type-all', 'opta-all', 'opta-add'])) {
            if ($this->user->can(['opta-list', 'opta-add'])) {
                return redirect()->route('admin.opta.create')->with('error', 'Have No Access');
            } else {
                return $this->pageUnauthorized();
            }
        }
        $get_data=new Class_OptaController();
        $data=$get_data->opta_championship();
        return redirect()->route('admin.opta.create')
                            ->with('success', 'opta Championship add successfully');
    }

    public function eldwry(Request $request) {
        if (!$this->user->can(['access-all', 'post-type-all', 'opta-all', 'opta-add'])) {
            if ($this->user->can(['opta-list', 'opta-add'])) {
                return redirect()->route('admin.opta.create')->with('error', 'Have No Access');
            } else {
                return $this->pageUnauthorized();
            }
        }
        $get_data=new Class_OptaController();
        $data=$get_data->opta_eldwry();
        return redirect()->route('admin.opta.create')
                            ->with('success', 'opta Eldwry add successfully');
    }

    public function teams(Request $request) {
        if (!$this->user->can(['access-all', 'post-type-all', 'opta-all', 'opta-add'])) {
            if ($this->user->can(['opta-list', 'opta-add'])) {
                return redirect()->route('admin.opta.create')->with('error', 'Have No Access');
            } else {
                return $this->pageUnauthorized();
            }
        }
        $get_data=new Class_OptaController();
        $data=$get_data->opta_teams();
        return redirect()->route('admin.opta.create')
                            ->with('success', 'opta Teams add successfully');
    }

    public function players(Request $request) {
        if (!$this->user->can(['access-all', 'post-type-all', 'opta-all', 'opta-add'])) {
            if ($this->user->can(['opta-list', 'opta-add'])) {
                return redirect()->route('admin.opta.create')->with('error', 'Have No Access');
            } else {
                return $this->pageUnauthorized();
            }
        }
        $get_data=new Class_OptaController();
        $data=$get_data->opta_players();
        return redirect()->route('admin.opta.create')
                            ->with('success', 'opta Players add successfully');
    }

    public function subeldwrys(Request $request) {
        if (!$this->user->can(['access-all', 'post-type-all', 'opta-all', 'opta-add'])) {
            if ($this->user->can(['opta-list', 'opta-add'])) {
                return redirect()->route('admin.opta.create')->with('error', 'Have No Access');
            } else {
                return $this->pageUnauthorized();
            }
        }
        $get_data=new Class_OptaController();
        $data=$get_data->opta_subeldwrys();
        return redirect()->route('admin.opta.create')
                            ->with('success', 'opta Subeldwrys add successfully');
    }

    public function matches(Request $request) {
        if (!$this->user->can(['access-all', 'post-type-all', 'opta-all', 'opta-add'])) {
            if ($this->user->can(['opta-list', 'opta-add'])) {
                return redirect()->route('admin.opta.create')->with('error', 'Have No Access');
            } else {
                return $this->pageUnauthorized();
            }
        }
        $get_data=new Class_OptaController();
        $data=$get_data->opta_matches();
        return redirect()->route('admin.opta.create')
                            ->with('success', 'opta Matches add successfully');
    }

    public function result_subeldwry(Request $request) {
        if (!$this->user->can(['access-all', 'post-type-all', 'opta-all', 'opta-add'])) {
            if ($this->user->can(['opta-list', 'opta-add'])) {
                return redirect()->route('admin.opta.create')->with('error', 'Have No Access');
            } else {
                return $this->pageUnauthorized();
            }
        }
        // $request->validate([
        //     'subeldwry_id' => 'required',
        // ]);
        // $subeldwry_id>0
        $input = $request->all();
        foreach ($input as $key => $value) {
            $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
        }

        $get_data=new Class_OptaController();
        $data=$get_data->opta_result_subeldwry($input['subeldwry_id'],1);
        return redirect()->route('admin.opta.creat','result_subeldwry')
                            ->with('success', 'opta Result Subeldwry add successfully');
    }


    public function result_matche(Request $request) {
        if (!$this->user->can(['access-all', 'post-type-all', 'opta-all', 'opta-add'])) {
            if ($this->user->can(['opta-list', 'opta-add'])) {
                return redirect()->route('admin.opta.create')->with('error', 'Have No Access');
            } else {
                return $this->pageUnauthorized();
            }
        }
        // $request->validate([
        //     'matche_id' => 'required',
        // ]);
        // $matche_id>0
        $input = $request->all();
        foreach ($input as $key => $value) {
            $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
        }

        $get_data=new Class_OptaController();
        $data=$get_data->opta_result_matche($input['matche_id'],0,1);
        return redirect()->route('admin.opta.creat','result_matche')
                            ->with('success', 'opta Result Matche add successfully');
    }

    public function transfer_player(Request $request) {
        if (!$this->user->can(['access-all', 'post-type-all', 'opta-all', 'opta-add'])) {
            if ($this->user->can(['opta-list', 'opta-add'])) {
                return redirect()->route('admin.opta.create')->with('error', 'Have No Access');
            } else {
                return $this->pageUnauthorized();
            }
        }
        // $request->validate([
        //     'subeldwry_id' => 'required',
        // ]);
        // $subeldwry_id>0
        $input = $request->all();
        foreach ($input as $key => $value) {
            $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
        }

        $get_data=new Class_TransferNotPlayController();
        $data=$get_data->TransferNotPlay($input['subeldwry_id']);
        return redirect()->route('admin.opta.creat','transfer_player')
                            ->with('success', 'opta Transfer Player successfully');
    }
    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */
    public function store(Request $request) {
        //for add all data
        if (!$this->user->can(['access-all', 'post-type-all', 'opta-all', 'opta-create', 'opta-edit'])) {
            if ($this->user->can('opta-list')) {
                return redirect()->route('admin.opta.create')->with('error', 'Have No Access');
            } else {
                return $this->pageUnauthorized();
            }
        }

        $get_data=new Class_OptaController();
        $data=$get_data->opta_championship();
        $data=$get_data->opta_eldwry();
        $data=$get_data->opta_teams();
        $data=$get_data->opta_players();
        $data=$get_data->opta_subeldwrys();
        $data=$get_data->opta_matches();

        return redirect()->route('admin.opta.creat','all')->with('success', 'opta created successfully');
    }

    public function search() {

        if (!$this->user->can(['access-all', 'post-type-all', 'opta-all', 'opta-list'])) {
            return $this->pageUnauthorized();
        }

        $opta_delete = $opta_edit = $opta_active = $opta_show = $opta_create = 0;

        if ($this->user->can(['access-all', 'post-type-all'])) {
            $opta_delete = $opta_active = $opta_edit = $opta_show = $opta_create = 1;
        }

        if ($this->user->can('opta-all')) {
            $opta_delete = $opta_active = $opta_edit = $opta_create = 1;
        }

        if ($this->user->can('opta-delete')) {
            $opta_delete = 1;
        }

        if ($this->user->can('opta-edit')) {
            $opta_active = $opta_edit = $opta_create = 1;
        }

        if ($this->user->can('opta-create')) {
            $opta_create = 1;
        }
        $type_action = 'opta';
        $data = Eldwry::orderBy('id', 'DESC')->get();
        return view('admin.opta.search', compact('type_action', 'data', 'opta_create', 'opta_edit', 'opta_show', 'opta_active', 'opta_delete'));
    }

}