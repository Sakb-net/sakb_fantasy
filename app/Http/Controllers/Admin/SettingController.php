<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\AllSetting;
use App\Models\Tag;
use App\Models\Taggable;
use DB;

class SettingController extends AdminController {

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */
    public function index(Request $request) {

        if (!$this->user->can(['access-all', 'setting-type-all', 'setting*'])) {
            return $this->pageUnauthorized();
        }

        $setting_delete = $setting_edit = $setting_active = $setting_show = $setting_create = 0;

        if ($this->user->can(['access-all', 'setting-type-all'])) {
            $setting_delete = $setting_active = $setting_edit = $setting_show = $setting_create = 1;
        }

        if ($this->user->can('setting-all')) {
            $setting_delete = $setting_active = $setting_edit = $setting_create = 1;
        }

        if ($this->user->can('setting-delete')) {
            $setting_delete = 1;
        }

        if ($this->user->can('setting-edit')) {
            $setting_active = $setting_edit = $setting_create = 1;
        }

        if ($this->user->can('setting-create')) {
            $setting_create = 1;
        }
        $type_action = 'تشكيلات الفريق';
        return view('admin.settings.index', compact('type_action', 'setting_create'));
    }

    public function planList(){

       
        if (!$this->user->can(['access-all', 'setting-type-all', 'setting*'])) {
            return $this->pageUnauthorized();
        }

        $setting_delete = $setting_edit = $setting_active = $setting_show = $setting_create = 0;

        if ($this->user->can(['access-all', 'setting-type-all'])) {
            $setting_delete = $setting_active = $setting_edit = $setting_show = $setting_create = 1;
        }

        if ($this->user->can('setting-all')) {
            $setting_delete = $setting_active = $setting_edit = $setting_create = 1;
        }

        if ($this->user->can('setting-delete')) {
            $setting_delete = 1;
        }

        if ($this->user->can('setting-edit')) {
            $setting_active = $setting_edit = $setting_create = 1;
        }

        if ($this->user->can('setting-create')) {
            $setting_create = 1;
        }
        $data = AllSetting::where('setting_key', 'lineup')->orderBy('id', 'DESC');

        return datatables()->of($data)
            ->addColumn('action', function ($data)use($setting_edit,$setting_delete) {

                $button = null;

                if($setting_edit == 1){
                    $button .= '&emsp;<a  class="btn btn-primary fa fa-edit" data-toggle="tooltip" data-placement="top" data-title="تعديل " href="'.route('admin.settings.edit', $data->id).'"></a>';
                }
                
                if($setting_delete == 1 && $data->id != 1){

                    $button .= '&emsp;<a id="delete" class="btn btn-danger fa fa-trash"  data-toggle="tooltip" data-placement="top" data-title="حذف  تشكيل" data-id="'. $data->id.' " data-name="'.$data->name.'"></a>';
                }

                return $button;
            })

            ->editColumn('active', function ($data) {
                if ($data->is_active == 1) {
                    $activeStatus = '<a class="settingstatus fa fa-check btn  btn-success"  data-id="{{ '.$data->id.' }}" data-status="0" ></a>';
                } else {
                    $activeStatus = '<a class="settingstatus fa fa-remove btn  btn-danger"  data-id="{{ '.$data->id.' }}" data-status="1" ></a>';
                }
                return $activeStatus;
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
    public function create() {

        if (!$this->user->can(['access-all', 'setting-type-all', 'setting-all', 'setting-create', 'setting-edit'])) {
            return $this->pageUnauthorized();
        }
        $tags = Tag::pluck('name', 'name');
        if ($this->user->can(['access-all', 'setting-type-all', 'setting-all', 'setting-edit'])) {
            $setting_active = 1;
        } else {
            $setting_active = 0;
        }
        $settingTags = [];
        $new = 1;
        $setting_key = 'lineup';
        $name_one=$name_second=$name_three=null;
        $link_return = route('admin.settings.index');
        return view('admin.settings.create', compact('tags', 'link_return','setting_key', 'new', 'setting_active', 'settingTags','name_one','name_second','name_three'));
    }

    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */
    public function store(Request $request) {

        if (!$this->user->can(['access-all', 'setting-type-all', 'setting-all', 'setting-create', 'setting-edit'])) {
            if ($this->user->can('setting-list')) {
                return redirect()->route('admin.settings.index')->with('error', 'Have No Access');
            } else {
                return $this->pageUnauthorized();
            }
        }

        $this->validate($request, [
            'name_one' => 'required|max:255',
            'name_second' => 'required|max:255',
            'name_one' => 'required|max:255',
            // 'link' => "max:255",
        ]);

        $input = $request->all();
        foreach ($input as $key => $value) {
            if ($key != "tags") {
                $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
        }
        $array_setting_val=[(int)$input['name_one'],(int)$input['name_second'],(int)$input['name_three']];
        $input['setting_value']=json_encode($array_setting_val);
        if ($input['setting_etc'] == Null) {
            $input['setting_etc'] = get_RandLink(time(),1);
        }
        $input['is_active'] = 1;
        $setting = AllSetting::create($input);
        $setting_id = $setting['id'];
      
        return redirect()->route('admin.settings.index')->with('success', 'setting created successfully');
    }

    /**

     * Display the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */
    public function show(Request $request, $id) {
        if (!$this->user->can(['access-all', 'setting-type-all', 'setting*'])) {
            return $this->pageUnauthorized();
        }

        $setting_delete = $setting_edit = $setting_active = $setting_show = $setting_create = 0;

        if ($this->user->can(['access-all', 'setting-type-all'])) {
            $setting_delete = $setting_active = $setting_edit = $setting_show = $setting_create = 1;
        }

        if ($this->user->can('setting-all')) {
            $setting_delete = $setting_active = $setting_edit = $setting_create = 1;
        }

        if ($this->user->can('setting-delete')) {
            $setting_delete = 1;
        }

        if ($this->user->can('setting-edit')) {
            $setting_active = $setting_edit = $setting_create = 1;
        }

        if ($this->user->can('setting-create')) {
            $setting_create = 1;
        }
        $type_action = 'تشكيلات الفريق';
        $data = [];
        return view('admin.subsettings.index', compact('type_action', 'data', 'setting_active', 'setting_create', 'setting_edit', 'setting_show', 'setting_delete'))->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */
    public function edit($id) {

        $setting = AllSetting::find($id);
        if (!empty($setting)) {
            if (!$this->user->can(['access-all', 'setting-type-all', 'setting-all', 'setting-edit'])) {
                if ($this->user->can(['setting-list', 'setting-create'])) {
                    return redirect()->route('admin.settings.index')->with('error', 'Have No Access');
                } else {
                    return $this->pageUnauthorized();
                }
            }
            $tags = [];//Tag::pluck('name', 'name');
            $setting_active = 1;
            $settingTags =[];// $setting->tags->pluck('name', 'name')->toArray();
            $new = 0;
            $link_return = route('admin.settings.index');
            $setting_key = $setting->setting_key;//'lineup';
            $array_setting_val=json_decode($setting->setting_value,true);
            $name_one=$array_setting_val[0];
            $name_second=$array_setting_val[1];
            $name_three=$array_setting_val[2];
            return view('admin.settings.edit', compact('setting_key', 'link_return', 'setting', 'setting_active', 'tags', 'settingTags', 'new','name_one','name_second','name_three'));
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
    public function update(Request $request, $id) {

        $setting = AllSetting::find($id);
        if (!empty($setting)) {
            if (!$this->user->can(['access-all', 'setting-type-all', 'setting-all', 'setting-edit'])) {
                if ($this->user->can(['setting-list', 'setting-create'])) {
                    return redirect()->route('admin.settings.index')->with('error', 'Have No Access');
                } else {
                    return $this->pageUnauthorized();
                }
            }
        $this->validate($request, [
            'name_one' => 'required|max:255',
            'name_second' => 'required|max:255',
            'name_one' => 'required|max:255',
            // 'link' => "max:255",
        ]);

            $input = $request->all();
            foreach ($input as $key => $value) {
                if ($key != "tags") {
                    $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
                }
            }
            $array_setting_val=[(int)$input['name_one'],(int)$input['name_second'],(int)$input['name_three']];
            $input['setting_value']=json_encode($array_setting_val);
            
            // $input['setting_etc'] = $setting->setting_etc;
            $setting->update($input);
            //  return redirect()->route('admin.settings.index')->with('success', 'Updated successfully');
            return redirect()->back()->with('success', 'Updated successfully');
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

        $setting = AllSetting::find($id);
        if (!empty($setting)) {
            if (!$this->user->can(['access-all', 'setting-type-all', 'setting-all', 'setting-delete'])) {
                if ($this->user->can(['setting-list', 'setting-edit'])) {
                    return redirect()->route('admin.settings.index')->with('error', 'Have No Access');
                } else {
                    return $this->pageUnauthorized();
                }
            }
            AllSetting::find($id)->delete();
            $arr = array('msg' =>__('app.delete_success'), 'status' => true);
            return Response()->json($arr);
        } else {
            return $this->pageError();
        }
    }

    public function search($setting_key='lineup') {

        if (!$this->user->can(['access-all', 'setting-type-all', 'setting-all', 'setting-list'])) {
            return $this->pageUnauthorized();
        }

        $setting_delete = $setting_edit = $setting_active = $setting_show = $setting_create = 0;

        if ($this->user->can(['access-all', 'setting-type-all'])) {
            $setting_delete = $setting_active = $setting_edit = $setting_show = $setting_create = 1;
        }

        if ($this->user->can('setting-all')) {
            $setting_delete = $setting_active = $setting_edit = $setting_create = 1;
        }

        if ($this->user->can('setting-delete')) {
            $setting_delete = 1;
        }

        if ($this->user->can('setting-edit')) {
            $setting_active = $setting_edit = $setting_create = 1;
        }

        if ($this->user->can('setting-create')) {
            $setting_create = 1;
        }
        $type_action = 'تشكيلات الفريق';
        $data = AllSetting::where('setting_key', $setting_key)->orderBy('id', 'DESC')->get();
        return view('admin.settings.search', compact('type_action', 'data', 'setting_create', 'setting_edit', 'setting_show', 'setting_active', 'setting_delete'));
    }

}

