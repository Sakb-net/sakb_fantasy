<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\RankingEldwry;
use App\Models\Eldwry;
use App\Models\Match;

use App\Repositories\RankingEldwryRepository;

class RankingEldwryController extends AdminController {

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */
    public function index(Request $request) {

        if (!$this->user->can(['access-all', 'ranking_eldwry-type-all', 'ranking_eldwry-all', 'ranking_eldwry-list', 'ranking_eldwry_edit', 'ranking_eldwry-delete', 'ranking_eldwry-show'])) {
            return $this->pageUnauthorized();
        }
        $ranking_eldwry_active = $ranking_eldwry_edit = $ranking_eldwry_create = $ranking_eldwry_delete = $ranking_eldwry_show  = 0;

        if ($this->user->can(['access-all', 'ranking_eldwry-type-all', 'ranking_eldwry-all'])) {
            $ranking_eldwry_active = $ranking_eldwry_edit = $ranking_eldwry_create = $ranking_eldwry_delete = $ranking_eldwry_show  = 1;
        }

        if ($this->user->can('ranking_eldwry_edit')) {
            $ranking_eldwry_active = $ranking_eldwry_edit = $ranking_eldwry_create = $ranking_eldwry_show = 1;
        }

        if ($this->user->can('ranking_eldwry-delete')) {
            $ranking_eldwry_delete = 1;
        }

        if ($this->user->can('ranking_eldwry-show')) {
            $ranking_eldwry_show = 1;
        }

        if ($this->user->can('ranking_eldwry-create')) {
            $ranking_eldwry_create = 1;
        }

        $type_action = trans('app.new_one');
        $name = $type = 'ranking_eldwry';
       	$data=[];
        return view('admin.ranking_eldwry.index', compact('type_action', 'name', 'ranking_eldwry_create','ranking_eldwry_edit','data'));
    }
    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */
    public function create() {
        if (!$this->user->can(['access-all', 'ranking_eldwry-all', 'ranking_eldwry-create', 'ranking_eldwry_edit'])) {
            // return $this->pageUnauthorized();
        }
        if ($this->user->can(['access-all', 'ranking_eldwry-all', 'ranking_eldwry_edit'])) {
            $ranking_eldwry_active = 1;
        } else {
            $ranking_eldwry_active = 0;
        }
        $create='create';
        $type_action='';
        $link_return = route('admin.ranking_eldwry.index');
        $new =1;
        return view('admin.ranking_eldwry.create', compact('link_return', 'new', 'ranking_eldwry_active','type_action','create'));
    }

    public function create_match() {
        if (!$this->user->can(['access-all', 'ranking_eldwry-all', 'ranking_eldwry-create', 'ranking_eldwry_edit'])) {
            return $this->pageUnauthorized();
        }
        if ($this->user->can(['access-all', 'ranking_eldwry-all', 'ranking_eldwry_edit'])) {
            $ranking_eldwry_active = 1;
        } else {
            $ranking_eldwry_active = 0;
        }
        $create='create_match';
        $type_action='';
        $eldwry=Eldwry::get_currentDwry();
        $matches=[];
        if(isset($eldwry->id)){
        	$matches=RankingEldwry::getAll_data('eldwry_id',$eldwry->id,1,1);
        }
        $link_return = route('admin.ranking_eldwry.index');
        $new =1;
        return view('admin.ranking_eldwry.create', compact('link_return', 'new', 'ranking_eldwry_active','type_action','create','matches'));
    }
    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request) {
        
        if (!$this->user->can(['access-all', 'ranking_eldwry-all', 'ranking_eldwry-create', 'ranking_eldwry_edit'])) {
            if ($this->user->can('ranking_eldwry-list')) {
                session()->put('error', trans('app.no_access'));
                return redirect()->route('admin.ranking_eldwry.index');
            } else {
                return $this->pageUnauthorized();
            }
        }
        $get_data=new RankingEldwryRepository();
        $data=$get_data->add_RankingEldwry();
        session()->put('success', trans('app.save_success'));
        return redirect()->route('admin.ranking_eldwry.create');
    }

    public function store_match(Request $request) {
        
        if (!$this->user->can(['access-all', 'ranking_eldwry-all', 'ranking_eldwry-create', 'ranking_eldwry_edit'])) {
            if ($this->user->can('ranking_eldwry-list')) {
                session()->put('error', trans('app.no_access'));
                return redirect()->route('admin.ranking_eldwry.index');
            } else {
                return $this->pageUnauthorized();
            }
        }
        $input = $request->all();
        foreach ($input as $key => $value) {
            $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
        }
        $match_data=Match::where('id',$input['match_id'])->get();
        $get_data=new RankingEldwryRepository();
        $data=$get_data->add_RankingEldwry($match_data);

        session()->put('success', trans('app.save_success'));
        return redirect()->route('admin.ranking_eldwry.create');
    }

    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */
    public function show(Request $request, $id) {
//        $ranking_eldwry = RankingEldwry::find($id);
        return redirect()->route('admin.ranking_eldwry.edit', $id);
    }

    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */
    public function edit($id) {
        $ranking_eldwry = RankingEldwry::find($id);
        if (!empty($ranking_eldwry)) {
            if (!$this->user->can(['access-all', 'ranking_eldwry-all', 'ranking_eldwry_edit', 'ranking_eldwry_edit-only'])) {
                if ($this->user->can(['ranking_eldwry-list', 'ranking_eldwry-create'])) {
                    session()->put('error', trans('app.no_access'));
                    return redirect()->route('admin.ranking_eldwry.index');
                } else {
                    return $ranking_eldwry->pageUnauthorized();
                }
            }

            if ($this->user->can('ranking_eldwry_edit-only') && !$this->user->can(['access-all', 'ranking_eldwry-all', 'ranking_eldwry_edit'])) {
                if ($this->user->can(['ranking_eldwry-list', 'ranking_eldwry-create'])) {
                    session()->put('error', trans('app.no_access'));
                    return redirect()->route('admin.ranking_eldwry.index');
                } else {
                    return $ranking_eldwry->pageUnauthorized();
                }
            }

            $image = $new = 0;
            if ($this->user->can(['access-all', 'ranking_eldwry-all', 'ranking_eldwry_edit'])) {
                $ranking_eldwry_active = $image = 1;
            } else {
                $ranking_eldwry_active = 0;
            }
            if ($this->user->can(['image-edit'])) {
                $image = 1;
            }
            if ($this->user->can(['access-all', 'ranking_eldwry-all', 'ranking_eldwry_edit'])) {
                $ranking_eldwry_active = 1;
            } else {
                $ranking_eldwry_active = 0;
            }
            $link_return = route('admin.ranking_eldwry.index');

            return view('admin.ranking_eldwry.edit', compact( 'link_return', 'ranking_eldwry','ranking_eldwry_active'));
        } else {
            $error = new AdminController();
            return $error->pageError();
        }
    }

    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */
    public function update(Request $request, $id) {
        $ranking_eldwry = RankingEldwry::find($id);
        $image = 0;
        if (!empty($ranking_eldwry)) {
            if (!$this->user->can(['access-all', 'ranking_eldwry-all', 'ranking_eldwry_edit', 'ranking_eldwry_edit-only'])) {
                if ($this->user->can(['ranking_eldwry-list', 'ranking_eldwry-create'])) {
                    session()->put('error', trans('app.no_access'));
                    return redirect()->route('admin.ranking_eldwry.index');
                } else {
                    return $ranking_eldwry->pageUnauthorized();
                }
            }

            if ($this->user->can('ranking_eldwry_edit-only') && !$this->user->can(['access-all', 'ranking_eldwry-all', 'ranking_eldwry_edit'])) {
                if ($this->user->can(['ranking_eldwry-list', 'ranking_eldwry-create'])) {
                    session()->put('error', trans('app.no_access'));
                    return redirect()->route('admin.ranking_eldwry.index');
                } else {
                    return $ranking_eldwry->pageUnauthorized();
                }
            }
            $input = $request->all();
            $ranking_eldwry->update($input);
            session()->put('success', trans('app.save_success'));
            return redirect()->route('admin.ranking_eldwry.index');
        } else {
            $error = new AdminController();
            return $error->pageError();
        }
    }

    /**

     * Remove the specified resource from storage.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */
    public function destroy($id) {
        $ranking_eldwry = RankingEldwry::find($id);
        if (!empty($ranking_eldwry)) {
            if (!$this->user->can(['access-all', 'ranking_eldwry-all', 'ranking_eldwry-delete'])) {
                if ($this->user->can(['ranking_eldwry-list', 'ranking_eldwry_edit'])) {
                    session()->put('error', trans('app.no_access'));
                    return redirect()->route('admin.ranking_eldwry.index');
                } else {
                    return $this->pageUnauthorized();
                }
            }
            RankingEldwry::find($id)->delete();
            $arr = array('msg' =>__('app.delete_success'), 'status' => true);
            return Response()->json($arr);  
        } else {
            $error = new AdminController();
            return $error->pageError();
        }
    }

    public function search() {

        if (!$this->user->can(['access-all', 'ranking_eldwry-type-all', 'ranking_eldwry-all', 'ranking_eldwry-list', 'ranking_eldwry_edit', 'ranking_eldwry-delete', 'ranking_eldwry-show'])) {
            return $ranking_eldwry->pageUnauthorized();
        }

        $ranking_eldwry_active = $ranking_eldwry_edit = $ranking_eldwry_create = $ranking_eldwry_delete = $ranking_eldwry_show  = 0;

        if ($this->user->can(['access-all', 'ranking_eldwry-type-all', 'ranking_eldwry-all'])) {
            $ranking_eldwry_active = $ranking_eldwry_edit = $ranking_eldwry_create = $ranking_eldwry_delete = $ranking_eldwry_show  = 1;
        }

        if ($this->user->can('ranking_eldwry_edit')) {
            $ranking_eldwry_active = $ranking_eldwry_edit = $ranking_eldwry_create = $ranking_eldwry_show  = 1;
        }

        if ($this->user->can('ranking_eldwry-delete')) {
            $ranking_eldwry_delete = 1;
        }

        if ($this->user->can('ranking_eldwry-show')) {
            $ranking_eldwry_show = 1;
        }

        if ($this->user->can('ranking_eldwry-create')) {
            $ranking_eldwry_create = 1;
        }

        $type_action = trans('app.ranking_eldwry');
        $name = 'ranking_eldwry';
        return view('admin.ranking_eldwry.search', compact('type_action', 'name', 'ranking_eldwry_active', 'ranking_eldwry_create', 'ranking_eldwry_edit', 'ranking_eldwry_delete', 'ranking_eldwry_show'));
    }

}
