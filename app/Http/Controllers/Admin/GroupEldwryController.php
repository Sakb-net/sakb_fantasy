<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\GroupEldwry;
use App\Models\GroupEldwryUser;
use App\Models\Language;
use App\Models\User;
use App\Models\Eldwry;
use App\Models\Subeldwry;
use App\Models\Game;
use App\Http\Controllers\ClassSiteApi\Class_GroupEldwryController;
use Session;

class GroupEldwryController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        if (!$this->user->can(['access-all', 'post-type-all', 'groupEldwry*'])) {
            return $this->pageUnauthorized();
        }

        $groupEldwry_delete = $groupEldwry_edit = $groupEldwry_active = $groupEldwry_show = $groupEldwry_create = 0;

        if ($this->user->can(['access-all', 'post-type-all'])) {
            $groupEldwry_delete = $groupEldwry_active = $groupEldwry_edit = $groupEldwry_show = $groupEldwry_create = 1;
        }

        if ($this->user->can('groupEldwry-all')) {
            $groupEldwry_delete = $groupEldwry_active = $groupEldwry_edit = $groupEldwry_create = 1;
        }

        if ($this->user->can('groupEldwry-delete')) {
            $groupEldwry_delete = 1;
        }

        if ($this->user->can('groupEldwry-edit')) {
            $groupEldwry_active = $groupEldwry_edit = $groupEldwry_create = 1;
        }

        if ($this->user->can('groupEldwry-create')) {
            $groupEldwry_create = 1;
        }
        $type_action = 'مجموعات الدورى';

        return view('admin.groupEldwry.index', compact('type_action', 'groupEldwry_create'));
    }


    

    public function groupEldwryList(){

        if (!$this->user->can(['access-all', 'post-type-all', 'groupEldwry*'])) {
            return $this->pageUnauthorized();
        }

        $groupEldwry_delete = $groupEldwry_edit = $groupEldwry_active = $groupEldwry_show = $groupEldwry_create = 0;

        if ($this->user->can(['access-all', 'post-type-all'])) {
            $groupEldwry_delete = $groupEldwry_active = $groupEldwry_edit = $groupEldwry_show = $groupEldwry_create = 1;
        }

        if ($this->user->can('groupEldwry-all')) {
            $groupEldwry_delete = $groupEldwry_active = $groupEldwry_edit = $groupEldwry_create = 1;
        }

        if ($this->user->can('groupEldwry-delete')) {
            $groupEldwry_delete = 1;
        }

        if ($this->user->can('groupEldwry-edit')) {
            $groupEldwry_active = $groupEldwry_edit = $groupEldwry_create = 1;
        }

        if ($this->user->can('groupEldwry-create')) {
            $groupEldwry_create = 1;
        }
        $data = GroupEldwry::orderBy('id', 'desc');

        return datatables()->of($data)
            ->addColumn('action', function ($data)use($groupEldwry_delete,$groupEldwry_edit) {

                $button = null;

                if($groupEldwry_edit == 1){
                    $button .= '&emsp;<a  class="btn btn-primary fa fa-edit" data-toggle="tooltip" data-placement="top" data-title="تعديل " href="'.route('admin.groupEldwry.edit', $data->id).'"></a>';

                    $button .= '&emsp;<a  class="btn btn-success fa fa-user" data-toggle="tooltip" data-placement="top" data-title="users" href="'.route('admin.groupEldwry.users', $data->id).'"></a>';

                    $button .= '&emsp;<a  class="btn btn-info fa fa-eye" data-toggle="tooltip" data-placement="top" data-title="standing " href="'.route('admin.groupEldwry.standing', $data->id).'"></a>';
                }
                
                if($groupEldwry_delete == 1){
                    $button .= '&emsp;<a id="delete" class="btn btn-danger fa fa-trash"  data-toggle="tooltip" data-placement="top" data-title="حذف  مجموعة الدورى" data-id="'. $data->id.' " data-name="'.$data->name.'"></a>';
                }
                return $button;
            })
            ->editColumn('active', function ($data) {
                if ($data->is_active == 1) {
                    $activeStatus = '<a class="fa fa-check btn  btn-success"  data-id="{{ '.$data->id.' }}" data-status="0" ></a>';
                } else {
                    $activeStatus = '<a class="fa fa-remove btn  btn-danger"  data-id="{{ '.$data->id.' }}" data-status="1" ></a>';
                }
                return $activeStatus;
            })

            ->addColumn('user', function ($data) {
                return $data->user->name;
            })

            ->addColumn('subeldwry', function ($data) {
                return $data->subeldwry->num_week;
            })
            ->addColumn('game', function ($data) {
                return $data->game->team_name;
            })
            
            ->addIndexColumn()
            ->rawColumns(['action', 'active'])
            ->make(true);
    }


public function groupEldwryUsers($id){

    if (!$this->user->can(['access-all', 'post-type-all', 'groupEldwry-all', 'groupEldwry-create', 'groupEldwry-edit'])) {
        if ($this->user->can('groupEldwry-list')) {
            return redirect()->route('admin.groupEldwry.index')->with('error', 'Have No Access');
        } else {
            return $this->pageUnauthorized();
        }
    }
    $groupEldwry = GroupEldwry::find($id);
    $groupEldwryName =  $groupEldwry->name;
    $type_action = 'users';
    return view('admin.groupEldwry.users',compact('type_action','id','groupEldwryName'));
}


public function groupEldwryUsersList(Request $request){


    $groupEldwry = GroupEldwry::find($request->id);
    $groupEldwryId = $groupEldwry->id;
    $groupEldwry_edit = 0;
    if (!empty($groupEldwry)) {
        $adminId = $groupEldwry->user_id;
        if (!$this->user->can(['access-all', 'post-type-all', 'groupEldwry-all', 'groupEldwry-edit'])) {
            if ($this->user->can(['groupEldwry-list', 'groupEldwry-create'])) {
                return redirect()->route('admin.groupEldwry.index')->with('error', 'Have No Access');
            } else {
                return $this->pageUnauthorized();
            }
        }


        // if ($this->user->can('groupEldwry-edit')) {
        //      $groupEldwry_edit = 1;
        // }

        $groupEldwry_edit = 1;


        $data = GroupEldwryUser::orderBy('id', 'desc')->where('group_eldwry_id',$request->id);

        return datatables()->of($data)
            ->addColumn('action', function ($data)use($groupEldwry_edit,$adminId,$groupEldwryId) {

                $button = null;

                if($groupEldwry_edit == 1){

                    if($data->add_user_id != $adminId ){

                    $button .= '&emsp;<a id="setAdmin" class="btn btn-info fa fa-graduation-cap"  data-toggle="tooltip" data-placement="top" data-title="تعين ادمن" data-id="'. $groupEldwryId.' " data-adminId="'. $data->add_user_id .'"></a>';

                    if($data->is_active == 1){
                        $button .= '&emsp;<a id="deactivate" class="fa fa-check btn btn-success"  data-toggle="tooltip" data-placement="top" data-title="ايقاف التفعيل" data-id="'. $data->id.' "></a>';
                    }else{
                        $button .= '&emsp;<a id="active" class="fa fa-remove btn btn-danger"  data-toggle="tooltip" data-placement="top" data-title="تفعيل" data-id="'. $data->id.' "></a>';
                    }
    
                    if($data->is_block == 1){
                        $button .= '&emsp;<a id="removeBlock" class="fa fa-user-times btn btn-danger"  data-toggle="tooltip" data-placement="top" data-title="الغاء الحظر" data-id="'. $data->id.' "></a>';
                    }else{
                        $button .= '&emsp;<a id="block" class="fa fa-user btn btn-primary"  data-toggle="tooltip" data-placement="top" data-title="حظر" data-id="'. $data->id.' "></a>';
                    }
                    }



            }
            return $button;
            })    
            ->addColumn('user', function ($data) {
                return $data->user->name;
            })
            ->addColumn('game', function ($data) {
                return $data->game->team_name;
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);




    } else {
        return $this->pageError();
    }

}



public function groupEldwryStanding($id){
    if (!$this->user->can(['access-all', 'post-type-all', 'groupEldwry-all', 'groupEldwry-create', 'groupEldwry-edit'])) {
        if ($this->user->can('groupEldwry-list')) {
            return redirect()->route('admin.groupEldwry.index')->with('error', 'Have No Access');
        } else {
            return $this->pageUnauthorized();
        }
    }

    $eldawry = GroupEldwry::where('id',$id)->first();
    $eldwryLink = $eldawry->link;
    $eldwyData = new Class_GroupEldwryController;
    $return_data = $eldwyData->get_current_sub_eldwry('',$eldawry->link,'classic');

    return view('admin.groupEldwry.standing',compact('return_data','eldwryLink'));

}


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


        if (!$this->user->can(['access-all', 'post-type-all', 'groupEldwry-all', 'groupEldwry-create', 'groupEldwry-edit'])) {
            return $this->pageUnauthorized();
        }
        if ($this->user->can(['access-all', 'groupEldwry-type-all', 'groupEldwry-all', 'groupEldwry-edit'])) {
            $groupEldwry_active = 1;
        } else {
            $groupEldwry_active = 0;
        }
        $new = 1;

        $start_dwry = Eldwry::get_currentDwry();
        $currentDwryId = $start_dwry->id;

        $users=User::where('is_active',1)->pluck('name', 'id');

        $subEldwry=Subeldwry::where(['is_active'=>1,'eldwry_id'=>$currentDwryId])->pluck('num_week', 'id');

        foreach($subEldwry as $key => $value){
            $subEldwry[$key]  =  trans('app.week').' '.$value ;
        }

        $mainLanguage = Language::get_Languag('is_active', 1, 'lang', 1);
        $groupEldwry = [];
        $link_return = route('admin.groupEldwry.index');
        return view('admin.groupEldwry.create', compact('mainLanguage','users','link_return', 'new','groupEldwry_active','subEldwry','groupEldwry'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (!$this->user->can(['access-all', 'post-type-all', 'groupEldwry-all', 'groupEldwry-create', 'groupEldwry-edit'])) {
            if ($this->user->can('groupEldwry-list')) {
                return redirect()->route('admin.groupEldwry.index')->with('error', 'Have No Access');
            } else {
                return $this->pageUnauthorized();
            }
        }

        $this->validate($request, [
            'name' => 'required|max:30',
        ]);
        $input_data = [];
        // dd($request->all());
        if ($request->link == Null) {
            $input['link'] = get_RandLink($request->name,1);
        }

        $game = Game::select('id')->where('user_id', $request->user_id)->first();
        $subEldwy = Subeldwry::where(['is_active'=>1,'num_week'=>$request->start_sub_eldwry_id])->first();

        $input_data=[
            'name'=>$request->name,
            'eldwry_id'=>$request->eldwry_id,
            'start_sub_eldwry_id'=>$request->start_sub_eldwry_id,
            'game_id'=>$game->id,
            'user_id'=>$request->user_id,
            'update_by'=>$this->user->id,
            'creator_id'=>$this->user->id
        ];

        $groupEldwry = GroupEldwry::insertGroup($input_data,$subEldwy,Session::get('locale'),'0');

        return redirect()->route('admin.groupEldwry.index')->with('success',  trans('app.groupEldwryCreatedSuccessfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $groupEldwry = GroupEldwry::find($id);
        if (!empty($groupEldwry)) {
            if (!$this->user->can(['access-all', 'post-type-all', 'groupEldwry-all', 'groupEldwry-edit'])) {
                if ($this->user->can(['groupEldwry-list', 'groupEldwry-create'])) {
                    return redirect()->route('admin.groupEldwry.index')->with('error', 'Have No Access');
                } else {
                    return $this->pageUnauthorized();
                }
            }
            $groupEldwry_active = 1;
            $new = 0;
            $link_return = route('admin.groupEldwry.index');

            $users=User::where('is_active',1)->pluck('name', 'id');
            $subEldwry=Subeldwry::where('is_active',1)->pluck('num_week', 'id');

            foreach($subEldwry as $key => $value){
                $subEldwry[$key]  =  trans('app.week').' '.$value ;
            }

            $array_name = json_decode($groupEldwry->lang_name, true);
            $mainLanguage = Language::get_Languag('is_active', 1, 'lang', 1);
            return view('admin.groupEldwry.edit', compact('mainLanguage','array_name','groupEldwry', 'link_return', 'groupEldwry_active', 'new','users','subEldwry'));
        } else {
            return $this->pageError();
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


        
        $groupEldwry = GroupEldwry::find($id);
        if (!empty($groupEldwry)) {
            if (!$this->user->can(['access-all', 'post-type-all', 'groupEldwry-all', 'groupEldwry-edit'])) {
                if ($this->user->can(['groupEldwry-list', 'groupEldwry-create'])) {
                    return redirect()->route('admin.groupEldwry.index')->with('error', 'Have No Access');
                } else {
                    return $this->pageUnauthorized();
                }
            }


            $this->validate($request, [
                'name' => 'required|max:30|min:2',
                'code' => 'required|max:30|min:5|unique:group_eldwrys,code,'.$id,
                'link' => 'required|max:90|min:5|unique:group_eldwrys,link,'.$id,
            ]);

            $game = Game::select('id')->where('user_id', $request->user_id)->first();

            $input = [];
            $input['is_active'] = $request->is_active;
            $input['update_by'] = $this->user->id;
            $input['name'] =  $request->name;
            $input['code'] =  $request->code;
            $input['link'] =  $request->link;
            $input['user_id'] =  $request->user_id;
            $input['start_sub_eldwry_id'] = $request->start_sub_eldwry_id;
            $input['game_id'] = $game->id;
            $input['lang_name']=json_encode([Session::get('locale')=>$request->name]);

            $groupEldwry->update($input);

             return redirect()->route('admin.groupEldwry.index')->with('success', trans('app.update_success'));
        } else {
            return $this->pageError();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {

        $groupEldwry = GroupEldwry::find($id);
        if (!empty($groupEldwry)) {
            if (!$this->user->can(['access-all', 'post-type-all', 'groupEldwry-all', 'groupEldwry-delete'])) {
                if ($this->user->can(['groupEldwry-list', 'groupEldwry-edit'])) {
                    return redirect()->route('admin.groupEldwry.index')->with('error', 'Have No Access');
                } else {
                    return $this->pageUnauthorized();
                }
            }
            GroupEldwry::find($id)->delete();
            $arr = array('msg' =>__('app.delete_success'), 'status' => true);
            return Response()->json($arr);
        } else {
            return $this->pageError();
        }
    }

    

    public function setAdmin(Request $request) {
        $groupEldwry = GroupEldwry::find($request->id);
        if (!empty($groupEldwry)) {
            if (!$this->user->can(['access-all', 'post-type-all', 'groupEldwry-all', 'groupEldwry-delete'])) {
                if ($this->user->can(['groupEldwry-list', 'groupEldwry-edit'])) {
                    return redirect()->route('admin.groupEldwry.index')->with('error', 'Have No Access');
                } else {
                    return $this->pageUnauthorized();
                }
            }
            $input['user_id'] = $request->adminId;
            $groupEldwry->update($input);
            $arr = array('msg' =>__('app.update_success'), 'status' => true);
            return Response()->json($arr);
        } else {
            return $this->pageError();
        }
    }


    public function activeUser(Request $request) {
        $groupEldwryUser = GroupEldwryUser::find($request->id);
        if (!empty($groupEldwryUser)) {
            if (!$this->user->can(['access-all', 'post-type-all', 'groupEldwry-all', 'groupEldwry-delete'])) {
                if ($this->user->can(['groupEldwry-list', 'groupEldwry-edit'])) {
                    return redirect()->route('admin.groupEldwry.index')->with('error', 'Have No Access');
                } else {
                    return $this->pageUnauthorized();
                }
            }
            $input['is_active'] = 1;
            $groupEldwryUser->update($input);
            $arr = array('msg' =>__('app.update_success'), 'status' => true);
            return Response()->json($arr);
        } else {
            return $this->pageError();
        }
    }

    public function disActiveUser(Request $request) {
            $groupEldwryUser = GroupEldwryUser::find($request->id);
            if (!empty($groupEldwryUser)) {
                if (!$this->user->can(['access-all', 'post-type-all', 'groupEldwry-all', 'groupEldwry-delete'])) {
                    if ($this->user->can(['groupEldwry-list', 'groupEldwry-edit'])) {
                        return redirect()->route('admin.groupEldwry.index')->with('error', 'Have No Access');
                    } else {
                        return $this->pageUnauthorized();
                    }
                }
                $input['is_active'] = 0;
                $groupEldwryUser->update($input);
                $arr = array('msg' =>__('app.update_success'), 'status' => true);
                return Response()->json($arr);
            } else {
                return $this->pageError();
            }
        }

        public function block(Request $request) {
            $groupEldwryUser = GroupEldwryUser::find($request->id);
            if (!empty($groupEldwryUser)) {
                if (!$this->user->can(['access-all', 'post-type-all', 'groupEldwry-all', 'groupEldwry-delete'])) {
                    if ($this->user->can(['groupEldwry-list', 'groupEldwry-edit'])) {
                        return redirect()->route('admin.groupEldwry.index')->with('error', 'Have No Access');
                    } else {
                        return $this->pageUnauthorized();
                    }
                }
                $input['is_block'] = 1;
                $groupEldwryUser->update($input);
                $arr = array('msg' =>__('app.update_success'), 'status' => true);
                return Response()->json($arr);
            } else {
                return $this->pageError();
            }
        }

        public function removeBlock(Request $request) {
            $groupEldwryUser = GroupEldwryUser::find($request->id);
            if (!empty($groupEldwryUser)) {
                if (!$this->user->can(['access-all', 'post-type-all', 'groupEldwry-all', 'groupEldwry-delete'])) {
                    if ($this->user->can(['groupEldwry-list', 'groupEldwry-edit'])) {
                        return redirect()->route('admin.groupEldwry.index')->with('error', 'Have No Access');
                    } else {
                        return $this->pageUnauthorized();
                    }
                }
                $input['is_block'] = 0;
                $groupEldwryUser->update($input);
                $arr = array('msg' =>__('app.update_success'), 'status' => true);
                return Response()->json($arr);
            } else {
                return $this->pageError();
            }
        }
}
