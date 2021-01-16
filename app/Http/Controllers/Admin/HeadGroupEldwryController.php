<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Models\HeadGroupEldwry;
use App\Models\Subeldwry;
use App\Models\Eldwry;
use App\Models\Game;
use App\Models\User;
use App\Models\Language;
use App\Models\HeadGroupEldwryUser;
use Session;
use App\Http\Resources\SubeldwryResource;
use App\Http\Controllers\ClassSiteApi\Class_GroupEldwryController;

class HeadGroupEldwryController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        if (!$this->user->can(['access-all', 'post-type-all', 'headGroupEldwry*'])) {
            return $this->pageUnauthorized();
        }

        $headGroupEldwry_delete = $headGroupEldwry_edit = $headGroupEldwry_active = $headGroupEldwry_show = $headGroupEldwry_create = 0;

        if ($this->user->can(['access-all', 'post-type-all'])) {
            $headGroupEldwry_delete = $headGroupEldwry_active = $headGroupEldwry_edit = $headGroupEldwry_show = $headGroupEldwry_create = 1;
        }

        if ($this->user->can('headGroupEldwry-all')) {
            $headGroupEldwry_delete = $headGroupEldwry_active = $headGroupEldwry_edit = $headGroupEldwry_create = 1;
        }

        if ($this->user->can('headGroupEldwry-delete')) {
            $headGroupEldwry_delete = 1;
        }

        if ($this->user->can('headGroupEldwry-edit')) {
            $headGroupEldwry_active = $headGroupEldwry_edit = $headGroupEldwry_create = 1;
        }

        if ($this->user->can('headGroupEldwry-create')) {
            $headGroupEldwry_create = 1;
        }
        $type_action = 'مجموعات الدورى';

        return view('admin.headGroupEldwry.index', compact('type_action', 'headGroupEldwry_create'));
    }


    

    public function headGroupEldwryList (){

        if (!$this->user->can(['access-all', 'post-type-all', 'headGroupEldwryroupEldwry*'])) {
            return $this->pageUnauthorized();
        }

        $headGroupEldwry_delete = $headGroupEldwry_edit = $headGroupEldwry_active = $headGroupEldwry_show = $headGroupEldwry_create = 0;

        if ($this->user->can(['access-all', 'post-type-all'])) {
            $headGroupEldwry_delete = $headGroupEldwry_active = $headGroupEldwry_edit = $headGroupEldwry_show = $groupEldwry_create = 1;
        }

        if ($this->user->can('headGroupEldwry-all')) {
            $headGroupEldwry_delete = $headGroupEldwry_active = $headGroupEldwry_edit = $headGroupEldwry_create = 1;
        }

        if ($this->user->can('headGroupEldwry-delete')) {
            $headGroupEldwry_delete = 1;
        }

        if ($this->user->can('headGroupEldwry-edit')) {
            $headGroupEldwryroupEldwry_active = $headGroupEldwry_edit = $headGroupEldwry_create = 1;
        }

        if ($this->user->can('headGroupEldwry-create')) {
            $headGroupEldwry_create = 1;
        }
        $data = HeadGroupEldwry::orderBy('id', 'desc');

        return datatables()->of($data)
            ->addColumn('action', function ($data)use($headGroupEldwry_delete,$headGroupEldwry_edit) {

                $button = null;

                if($headGroupEldwry_edit == 1){
                    $button .= '&emsp;<a  class="btn btn-primary fa fa-edit" data-toggle="tooltip" data-placement="top" data-title="تعديل " href="'.route('admin.headGroupEldwry.edit', $data->id).'"></a>';

                    $button .= '&emsp;<a  class="btn btn-info fa fa-eye" data-toggle="tooltip" data-placement="top" data-title="standing " href="'.route('admin.headGroupEldwry.standing', $data->id).'"></a>';

                    $button .= '&emsp;<a  class="btn btn-success fa fa-user" data-toggle="tooltip" data-placement="top" data-title="users" href="'.route('admin.headGroupEldwry.users', $data->id).'"></a>';
                }
                
                // if($headGroupEldwry_delete == 1){
                //     $button .= '&emsp;<a id="delete" class="btn btn-danger fa fa-trash"  data-toggle="tooltip" data-placement="top" data-title="حذف  مجموعة الدورى" data-id="'. $data->id.' " data-name="'.$data->name.'"></a>';
                // }
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


        if (!$this->user->can(['access-all', 'post-type-all', 'headGroupEldwry-all', 'headGroupEldwry-create', 'headGroupEldwry-edit'])) {
            return $this->pageUnauthorized();
        }
        if ($this->user->can(['access-all', 'headGroupEldwry-type-all', 'headGroupEldwry-all', 'headGroupEldwry-edit'])) {
            $headGroupEldwry_active = 1;
        } else {
            $headGroupEldwry_active = 0;
        }
        $new = 1;


        $users=User::where('is_active',1)->pluck('name', 'id');

        $subEldwry=Subeldwry::where('is_active',1)->pluck('num_week', 'id');

        foreach($subEldwry as $key => $value){
            $subEldwry[$key]  =  trans('app.week').' '.$value ;
        }

        $mainLanguage = Language::get_Languag('is_active', 1, 'lang', 1);
        $headGroupEldwry = [];
        $link_return = route('admin.headGroupEldwry.index');
        return view('admin.headGroupEldwry.create', compact('mainLanguage','users','link_return', 'new','headGroupEldwry_active','subEldwry','headGroupEldwry'));
    }


    public function headGroupEldwryMatches($id){
        if (!$this->user->can(['access-all', 'post-type-all', 'headGroupEldwry-all', 'headGroupEldwry-create', 'headGroupEldwry-edit'])) {
            if ($this->user->can('headGroupEldwry-list')) {
                return redirect()->route('admin.headGroupEldwry.index')->with('error', 'Have No Access');
            } else {
                return $this->pageUnauthorized();
            }
        }
        $eldawryHead = HeadGroupEldwry::where('id',$id)->first();
        $eldwryLink = $eldawryHead->link;
        $eldwyData = new Class_GroupEldwryController;
        $return_data = $eldwyData->get_current_subeldwry_group('',$eldawryHead->link,'head');
        $userData = User::where('id',$eldawryHead->user_id)->first();
        $tableData= $eldwyData->get_data_group_eldwry($userData,$eldawryHead->link,'','ar',0,'head');
        // dd($tableData);
        return view('admin.headGroupEldwry.matches',compact('return_data','tableData','eldwryLink'));

    }


    public function headGroupEldwryUsers($id){

        if (!$this->user->can(['access-all', 'post-type-all', 'headGroupEldwry-all', 'headGroupEldwry-create', 'headGroupEldwry-edit'])) {
            if ($this->user->can('headGroupEldwry-list')) {
                return redirect()->route('admin.headGroupEldwry.index')->with('error', 'Have No Access');
            } else {
                return $this->pageUnauthorized();
            }
        }
        $headGroupEldwry = HeadGroupEldwry::find($id);
        $headGroupEldwryName =  $headGroupEldwry->name;
        $type_action = 'users';
        return view('admin.headGroupEldwry.users',compact('type_action','id','headGroupEldwryName'));
    }


    public function headGroupEldwryUsersList(Request $request){


        $headGroupEldwry = HeadGroupEldwry::find($request->id);
        $headGroupEldwryId = $headGroupEldwry->id;
        $headGroupEldwry_edit = 0;
        if (!empty($headGroupEldwry)) {
            $adminId = $headGroupEldwry->user_id;
            if (!$this->user->can(['access-all', 'post-type-all', 'headGroupEldwry-all', 'groupEldwry-edit'])) {
                if ($this->user->can(['headGroupEldwry-list', 'headGroupEldwry-create'])) {
                    return redirect()->route('admin.headGroupEldwry.index')->with('error', 'Have No Access');
                } else {
                    return $this->pageUnauthorized();
                }
            }


            // if ($this->user->can('headGroupEldwry-edit')) {
            //      $headGroupEldwry_edit = 1;
            // }

            $headGroupEldwry_edit = 1;


            $data = HeadGroupEldwryUser::orderBy('id', 'desc')->where('head_group_eldwry_id',$request->id);

            return datatables()->of($data)
                ->addColumn('action', function ($data)use($headGroupEldwry_edit,$adminId,$headGroupEldwryId) {
    
                    $button = null;
    
                    if($headGroupEldwry_edit == 1){

                        if($data->add_user_id != $adminId ){

                        $button .= '&emsp;<a id="setAdmin" class="btn btn-info fa fa-graduation-cap"  data-toggle="tooltip" data-placement="top" data-title="تعين ادمن" data-id="'. $headGroupEldwryId.' " data-adminId="'. $data->add_user_id .'"></a>';

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

    public function headGroupEldwryStanding($id){
        if (!$this->user->can(['access-all', 'post-type-all', 'headGroupEldwry-all', 'headGroupEldwry-create', 'headGroupEldwry-edit'])) {
            if ($this->user->can('headGroupEldwry-list')) {
                return redirect()->route('admin.headGroupEldwry.index')->with('error', 'Have No Access');
            } else {
                return $this->pageUnauthorized();
            }
        }

        $eldawryHead = HeadGroupEldwry::where('id',$id)->first();
        $eldwryLink = $eldawryHead->link;
        $eldwyData = new Class_GroupEldwryController;
        $return_data = $eldwyData->get_current_subeldwry_group('',$eldawryHead->link,'head');

        return view('admin.headGroupEldwry.standing',compact('return_data','eldwryLink'));

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (!$this->user->can(['access-all', 'post-type-all', 'headGroupEldwry-all', 'headGroupEldwry-create', 'headGroupEldwry-edit'])) {
            if ($this->user->can('headGroupEldwry-list')) {
                return redirect()->route('admin.headGroupEldwry.index')->with('error', 'Have No Access');
            } else {
                return $this->pageUnauthorized();
            }

            $request->validate([
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
            $headGroupEldwry = HeadGroupEldwry::insertGroup($input_data,$subEldwy,Session::get('locale'),'0');
    
            return redirect()->route('admin.headGroupEldwry.index')->with('success',  trans('app.groupEldwryCreatedSuccessfully'));
        }

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

        $headGroupEldwry = HeadGroupEldwry::find($id);
        if (!empty($headGroupEldwry)) {
            if (!$this->user->can(['access-all', 'post-type-all', 'headGroupEldwry-all', 'headGroupEldwry-edit'])) {
                if ($this->user->can(['headGroupEldwry-list', 'headGroupEldwry-create'])) {
                    return redirect()->route('admin.headGroupEldwry.index')->with('error', 'Have No Access');
                } else {
                    return $this->pageUnauthorized();
                }
            }
            $headGroupEldwry_active = 1;
            $new = 0;
            $link_return = route('admin.headGroupEldwry.index');

            $users=User::where('is_active',1)->pluck('name', 'id');
            $subEldwry=Subeldwry::where('is_active',1)->pluck('num_week', 'id');

            foreach($subEldwry as $key => $value){
                $subEldwry[$key]  =  trans('app.week').' '.$value ;
            }

            $array_name = json_decode($headGroupEldwry->lang_name, true);
            $mainLanguage = Language::get_Languag('is_active', 1, 'lang', 1);
            return view('admin.headGroupEldwry.edit', compact('mainLanguage','array_name','headGroupEldwry', 'link_return', 'headGroupEldwry_active', 'new','users','subEldwry'));
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


        
        $headGroupEldwry = HeadGroupEldwry::find($id);
        if (!empty($headGroupEldwry)) {
            if (!$this->user->can(['access-all', 'post-type-all', 'headGroupEldwry-all', 'headGroupEldwry-edit'])) {
                if ($this->user->can(['headGroupEldwry-list', 'headGroupEldwry-create'])) {
                    return redirect()->route('admin.headGroupEldwry.index')->with('error', 'Have No Access');
                } else {
                    return $this->pageUnauthorized();
                }
            }


            $request->validate([
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

            $headGroupEldwry->update($input);

             return redirect()->route('admin.headGroupEldwry.index')->with('success', trans('app.update_success'));
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

        $headGroupEldwry = HeadGroupEldwry::find($id);
        if (!empty($headGroupEldwry)) {
            if (!$this->user->can(['access-all', 'post-type-all', 'headGroupEldwry-all', 'headGroupEldwry-delete'])) {
                if ($this->user->can(['headGroupEldwry-list', 'headGroupEldwry-edit'])) {
                    return redirect()->route('admin.headGroupEldwry.index')->with('error', 'Have No Access');
                } else {
                    return $this->pageUnauthorized();
                }
            }
            HeadGroupEldwry::find($id)->delete();
            $arr = array('msg' =>__('app.delete_success'), 'status' => true);
            return Response()->json($arr);
        } else {
            return $this->pageError();
        }
    }


    public function setAdmin(Request $request) {
        $headGroupEldwry = HeadGroupEldwry::find($request->id);
        if (!empty($headGroupEldwry)) {
            if (!$this->user->can(['access-all', 'post-type-all', 'headGroupEldwry-all', 'headGroupEldwry-delete'])) {
                if ($this->user->can(['headGroupEldwry-list', 'headGroupEldwry-edit'])) {
                    return redirect()->route('admin.headGroupEldwry.index')->with('error', 'Have No Access');
                } else {
                    return $this->pageUnauthorized();
                }
            }
            $input['user_id'] = $request->adminId;
            $headGroupEldwry->update($input);
            $arr = array('msg' =>__('app.update_success'), 'status' => true);
            return Response()->json($arr);
        } else {
            return $this->pageError();
        }
    }


    public function activeUser(Request $request) {
        $headGroupEldwryUser = HeadGroupEldwryUser::find($request->id);
        if (!empty($headGroupEldwryUser)) {
            if (!$this->user->can(['access-all', 'post-type-all', 'headGroupEldwry-all', 'headGroupEldwry-delete'])) {
                if ($this->user->can(['headGroupEldwry-list', 'headGroupEldwry-edit'])) {
                    return redirect()->route('admin.headGroupEldwry.index')->with('error', 'Have No Access');
                } else {
                    return $this->pageUnauthorized();
                }
            }
            $input['is_active'] = 1;
            $headGroupEldwryUser->update($input);
            $arr = array('msg' =>__('app.update_success'), 'status' => true);
            return Response()->json($arr);
        } else {
            return $this->pageError();
        }
    }

    public function disActiveUser(Request $request) {
            $headGroupEldwryUser = HeadGroupEldwryUser::find($request->id);
            if (!empty($headGroupEldwryUser)) {
                if (!$this->user->can(['access-all', 'post-type-all', 'headGroupEldwry-all', 'headGroupEldwry-delete'])) {
                    if ($this->user->can(['headGroupEldwry-list', 'headGroupEldwry-edit'])) {
                        return redirect()->route('admin.headGroupEldwry.index')->with('error', 'Have No Access');
                    } else {
                        return $this->pageUnauthorized();
                    }
                }
                $input['is_active'] = 0;
                $headGroupEldwryUser->update($input);
                $arr = array('msg' =>__('app.update_success'), 'status' => true);
                return Response()->json($arr);
            } else {
                return $this->pageError();
            }
        }

        public function block(Request $request) {
            $headGroupEldwryUser = HeadGroupEldwryUser::find($request->id);
            if (!empty($headGroupEldwryUser)) {
                if (!$this->user->can(['access-all', 'post-type-all', 'headGroupEldwry-all', 'headGroupEldwry-delete'])) {
                    if ($this->user->can(['headGroupEldwry-list', 'headGroupEldwry-edit'])) {
                        return redirect()->route('admin.headGroupEldwry.index')->with('error', 'Have No Access');
                    } else {
                        return $this->pageUnauthorized();
                    }
                }
                $input['is_block'] = 1;
                $headGroupEldwryUser->update($input);
                $arr = array('msg' =>__('app.update_success'), 'status' => true);
                return Response()->json($arr);
            } else {
                return $this->pageError();
            }
        }

        public function removeBlock(Request $request) {
            $headGroupEldwryUser = HeadGroupEldwryUser::find($request->id);
            if (!empty($headGroupEldwryUser)) {
                if (!$this->user->can(['access-all', 'post-type-all', 'headGroupEldwry-all', 'headGroupEldwry-delete'])) {
                    if ($this->user->can(['headGroupEldwry-list', 'headGroupEldwry-edit'])) {
                        return redirect()->route('admin.headGroupEldwry.index')->with('error', 'Have No Access');
                    } else {
                        return $this->pageUnauthorized();
                    }
                }
                $input['is_block'] = 0;
                $headGroupEldwryUser->update($input);
                $arr = array('msg' =>__('app.update_success'), 'status' => true);
                return Response()->json($arr);
            } else {
                return $this->pageError();
            }
        }
}
