<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Eldwry;
use App\Models\Subeldwry;
use App\Models\AllType;
use App\Models\Tag;
use App\Models\Taggable;
use App\Models\GameHistory;
use App\Models\GamePlayerHistory;
use App\Models\PointUser;
use App\Models\PointPlayer;
use App\Models\Language;
use DB;
use Datatables;

class SubeldwryController extends AdminController {

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */
    public function index(Request $request) {

        if (!$this->user->can(['access-all', 'post-type-all', 'subeldwry*'])) {
            return $this->pageUnauthorized();
        }

        $subeldwry_delete = $subeldwry_edit = $subeldwry_active = $subeldwry_show = $subeldwry_create = 0;

        if ($this->user->can(['access-all', 'post-type-all'])) {
            $subeldwry_delete = $subeldwry_active = $subeldwry_edit = $subeldwry_show = $subeldwry_create = 1;
        }

        if ($this->user->can('subeldwry-all')) {
            $subeldwry_delete = $subeldwry_active = $subeldwry_edit = $subeldwry_create = 1;
        }

        if ($this->user->can('subeldwry-delete')) {
            $subeldwry_delete = 1;
        }

        if ($this->user->can('subeldwry-edit')) {
            $subeldwry_active = $subeldwry_edit = $subeldwry_create = 1;
        }

        if ($this->user->can('subeldwry-create')) {
            $subeldwry_create = 1;
        }
        
        $type_action = 'جولات الدورى';
        return view('admin.subeldwry.index')->with(compact('subeldwry_edit','subeldwry_create','subeldwry_active','subeldwry_delete','type_action'));
    }

    public function subEldwryList(){

        $subeldwry_delete = $subeldwry_edit = $subeldwry_active = $subeldwry_show = $subeldwry_create = 0;

        if (!$this->user->can(['access-all', 'post-type-all', 'subeldwry*'])) {
            return $this->pageUnauthorized();
        }


        if ($this->user->can(['access-all', 'post-type-all'])) {
            $subeldwry_delete = $subeldwry_active = $subeldwry_edit = $subeldwry_show = $subeldwry_create = 1;
        }

        if ($this->user->can('subeldwry-all')) {
            $subeldwry_delete = $subeldwry_active = $subeldwry_edit = $subeldwry_create = 1;
        }

        if ($this->user->can('subeldwry-delete')) {
            $subeldwry_delete = 1;
        }

        if ($this->user->can('subeldwry-edit')) {
            $subeldwry_active = $subeldwry_edit = $subeldwry_create = 1;
        }

        if ($this->user->can('subeldwry-create')) {
            $subeldwry_create = 1;
        }

        $data = subeldwry::orderBy('id', 'DESC');

        return datatables()->of($data)
            ->addColumn('action', function ($data)use($subeldwry_edit,$subeldwry_delete,$subeldwry_show) {

                $button = null;

                if($subeldwry_edit == 1){
                    $button .= '&emsp;<a  class="btn btn-primary fa fa-edit" data-toggle="tooltip" data-placement="top" data-title="تعديل " href="'.route('admin.subeldwry.edit', $data->id).'"></a>';
                }
                
                if($subeldwry_delete == 1){
                    $button .= '&emsp;<a id="delete" class="btn btn-danger fa fa-trash"  data-toggle="tooltip" data-placement="top" data-title="حذف  الجولة" data-id="'. $data->id.' " data-name="'.$data->name.'"></a>';
                }
    
                if($subeldwry_show == 1){
                $button .= '&emsp;<a class="btn btn-primary fa fa-eye" data-toggle="tooltip" data-placement="top" data-title="تفاصيل" href="'.route('admin.subeldwry.details', $data->id).'"></a>';
                }
                return $button;
            })
            ->addColumn('eldwryName', function ($data) {
                return $data->eldwry->name;
            })

            ->editColumn('active', function ($data) {
                if ($data->is_active == 1) {
                    $activeStatus = '<a class="subeldwrystatus fa fa-check btn  btn-success"  data-id="{{ '.$data->id.' }}" data-status="0" ></a>';
                } else {
                    $activeStatus = '<a class="subeldwrystatus fa fa-remove btn  btn-danger"  data-id="{{ '.$data->id.' }}" data-status="1" ></a>';
                }
                return $activeStatus;
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'active','eldwryName'])
            ->make(true);
    }

    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */
    public function create() {

        if (!$this->user->can(['access-all', 'post-type-all', 'subeldwry-all', 'subeldwry-create', 'subeldwry-edit'])) {
            return $this->pageUnauthorized();
        }
        $tags = Tag::pluck('name', 'name');
        if ($this->user->can(['access-all', 'post-type-all', 'subeldwry-all', 'subeldwry-edit'])) {
            $subeldwry_active = 1;
        } else {
            $subeldwry_active = 0;
        }
        $subeldwryTags = [];
        $new = 1;
        $type_ids=AllType::where('is_active',1)->whereIn('type_key',['start','change'])->pluck('value_ar', 'id');
        $eldwry=Eldwry::where('is_active',1)->pluck('name', 'id');
        $date_booking=$date_change_booking='';
        $change_point=-4;
        $link_return = route('admin.subeldwry.index');
        $mainLanguage = Language::get_Languag('is_active', 1, 'lang', 1);
        return view('admin.subeldwry.create', compact('mainLanguage','change_point','eldwry','date_booking','date_change_booking', 'link_return', 'type_ids', 'tags', 'new', 'subeldwry_active', 'subeldwryTags'));
    }

    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */
    public function store(Request $request) {

        if (!$this->user->can(['access-all', 'post-type-all', 'subeldwry-all', 'subeldwry-create', 'subeldwry-edit'])) {
            if ($this->user->can('subeldwry-list')) {
                return redirect()->route('admin.subeldwry.index')->with('error', 'Have No Access');
            } else {
                return $this->pageUnauthorized();
            }
        }

        $request->validate([
            'lang_name' => 'required|max:255',
        ]);
        $input = $request->all();
        $input=finalDataInputAdmin($input);
            foreach ($input as $key => $value) {
               if ($key != "tags" && $key != "lang" && $key != "lang_name") {
                $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
        }
        if ($input['link'] == Null) {
            $input['link'] = get_RandLink(time(),1);
        }
        if (!empty($input['date_booking'])) {
            $date_booking = explode('/', $input['date_booking']);
            $input['start_date'] = $date_booking[0];
            $input['end_date'] = $date_booking[1];
        }
        if (!empty($input['date_change_booking'])) {
            $date_change_booking = explode('/', $input['date_change_booking']);
            $input['start_change_date'] = $date_change_booking[0];
            $input['end_change_date'] = $date_change_booking[1];
        }
        $input['is_active']=1;
        $input['user_id'] = $this->user->id;
        $subeldwry = subeldwry::create($input);
        $subeldwry_id = $subeldwry['id'];
//        if ($input['lang'] == 'ar') {
//            subeldwry::updateColum($subeldwry_id, 'lang_id', $subeldwry_id);
//        }
        $taggable_id = $subeldwry_id;
        $tags = isset($input['tags']) ? $input['tags'] : array();
        if (!empty($tags)) {
            foreach ($tags as $tags_value) {
                $taggable = new Taggable();
                if ($tags_value != NULL || $tags_value != '') {
                    $tag_found = new Tag();
                    $tag_id_found = $tag_found->foundTag($tags_value);
                    if ($tag_id_found > 0) {
                        $tag_id = $tag_id_found;
                    } else {
                        $tag_new = new Tag();
                        $tag_new->insertTag($tags_value);
                        $tag_id = $tag_new->id;
                    }
                    $taggable->insertTaggable($tag_id, $taggable_id, "subeldwry");
                }
            }
        }

        return redirect()->route('admin.subeldwry.index')->with('success', 'Subsubeldwry created successfully');
    }

    /**

     * Display the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */
    public function show(Request $request, $id) {
//        $subeldwry = subeldwry::find($id);
        return redirect()->route('admin.subeldwry.edit', $id);
    }

    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */
    public function edit($id) {

        $subeldwry = subeldwry::find($id);
        if (!empty($subeldwry)) {
            if (!$this->user->can(['access-all', 'post-type-all', 'subeldwry-all', 'subeldwry-edit'])) {
                if ($this->user->can(['subeldwry-list', 'subeldwry-create'])) {
                    return redirect()->route('admin.subeldwry.index')->with('error', 'Have No Access');
                } else {
                    return $this->pageUnauthorized();
                }
            }
            $tags = Tag::pluck('name', 'name');
            $subeldwry_active = 1;
            $subeldwryTags = $subeldwry->tags->pluck('name', 'name')->toArray();
            $new = 0;
            $link_return = route('admin.subeldwry.index');
               
            $type_ids=AllType::where('is_active',1)->whereIn('type_key',['start','change'])->pluck('value_ar', 'id');
            $eldwry=Eldwry::where('is_active',1)->pluck('name', 'id');
            $date_booking=$date_change_booking='';
            if(!empty($subeldwry->start_date)){
                $start_date=date('Y-m-d', strtotime($subeldwry->start_date)) ;
                $end_date=date('Y-m-d', strtotime($subeldwry->end_date)) ;
                $date_booking = $start_date.' / '.$end_date; //2020-02-15 / 2020-02-15
            }
            if(!empty($subeldwry->start_change_date)){
                $start_change_date=date('Y-m-d', strtotime($subeldwry->start_change_date)) ;
                $end_change_date=date('Y-m-d', strtotime($subeldwry->end_change_date)) ;
                $date_change_booking = $start_change_date.' / '.$end_change_date; //2020-02-15 / 2020-02-15
            }
            $change_point=$subeldwry->change_point;
            $array_name = json_decode($subeldwry->lang_name, true);
            $mainLanguage = Language::get_Languag('is_active', 1, 'lang', 1);
            return view('admin.subeldwry.edit', compact('mainLanguage','change_point','array_name','type_ids', 'eldwry','date_booking','date_change_booking', 'link_return', 'subeldwry', 'subeldwry', 'subeldwry_active', 'tags', 'subeldwryTags', 'new'));
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

        $subeldwry = subeldwry::find($id);
        if (!empty($subeldwry)) {
            if (!$this->user->can(['access-all', 'post-type-all', 'subeldwry-all', 'subeldwry-edit'])) {
                if ($this->user->can(['subeldwry-list', 'subeldwry-create'])) {
                    return redirect()->route('admin.subeldwry.index')->with('error', 'Have No Access');
                } else {
                    return $this->pageUnauthorized();
                }
            }

            $request->validate([
                'lang_name' => 'required|max:255',
                'link' => "required|max:255",
            ]);
            $input = $request->all();
            $input=finalDataInputAdmin($input);
            foreach ($input as $key => $value) {
               if ($key != "tags" && $key != "lang" && $key != "lang_name") {
                    $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
                }
            }
            if (!empty($input['date_booking'])) {
                $date_booking = explode('/', $input['date_booking']);
                $input['start_date'] = $date_booking[0];
                $input['end_date'] = $date_booking[1];
            }
            if (!empty($input['date_change_booking'])) {
                $date_change_booking = explode('/', $input['date_change_booking']);
                $input['start_change_date'] = $date_change_booking[0];
                $input['end_change_date'] = $date_change_booking[1];
            }
            $subeldwry->update($input);
            Taggable::deleteTaggableType($id, "subeldwry");
            $tags = isset($input['tags']) ? $input['tags'] : array();
            if (!empty($tags)) {
                foreach ($tags as $tags_value) {
                    $taggable = new Taggable();
                    if ($tags_value != NULL || $tags_value != '') {
                        $tag_found = new Tag();
                        $tag_id_found = $tag_found->foundTag($tags_value);
                        if ($tag_id_found > 0) {
                            $tag_id = $tag_id_found;
                        } else {
                            $tag_new = new Tag();
                            $tag_new->insertTag($tags_value);
                            $tag_id = $tag_new->id;
                        }
                        $taggable->insertTaggable($tag_id, $id, "subeldwry");
                    }
                }
            }

            return redirect()->route('admin.subeldwry.index')
                            ->with('success', 'Subsubeldwry updated successfully');
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

        $subeldwry = subeldwry::find($id);
        if (!empty($subeldwry)) {
            if (!$this->user->can(['access-all', 'post-type-all', 'subeldwry-all', 'subeldwry-delete'])) {
                if ($this->user->can(['subeldwry-list', 'subeldwry-edit'])) {
                    return redirect()->route('admin.subeldwry.index')->with('error', 'Have No Access');
                } else {
                    return $this->pageUnauthorized();
                }
            }
            subeldwry::find($id)->delete();
            Taggable::deleteTaggableType($id, "subeldwry");
            $arr = array('msg' =>__('app.delete_success'), 'status' => true);
            return Response()->json($arr);
        } else {
            return $this->pageError();
        }
    }

    public function search() {

        if (!$this->user->can(['access-all', 'post-type-all', 'subeldwry-all', 'subeldwry-list'])) {
            return $this->pageUnauthorized();
        }

        $subeldwry_delete = $subeldwry_edit = $subeldwry_active = $subeldwry_show = $subeldwry_create = 0;

        if ($this->user->can(['access-all', 'post-type-all'])) {
            $subeldwry_delete = $subeldwry_active = $subeldwry_edit = $subeldwry_show = $subeldwry_create = 1;
        }

        if ($this->user->can('subeldwry-all')) {
            $subeldwry_delete = $subeldwry_active = $subeldwry_edit = $subeldwry_create = 1;
        }

        if ($this->user->can('subeldwry-delete')) {
            $subeldwry_delete = 1;
        }

        if ($this->user->can('subeldwry-edit')) {
            $subeldwry_active = $subeldwry_edit = $subeldwry_create = 1;
        }

        if ($this->user->can('subeldwry-create')) {
            $subeldwry_create = 1;
        }
        $type_action = 'جولات الدورى';
       $data = subeldwry::get();  //with('user')->
        return view('admin.subeldwry.search', compact('type_action', 'data', 'subeldwry_create', 'subeldwry_edit', 'subeldwry_show', 'subeldwry_active', 'subeldwry_delete',));
    }

    public function details($id){

            if (!$this->user->can(['access-all', 'post-type-all', 'subeldwry-all'])) {

                if ($this->user->can(['subeldwry-list'])) {

                    return redirect()->route('admin.subeldwry.index')->with('error', 'Have No Access');
                } else {

                    return $this->pageUnauthorized();
                }
            }

            $data =PointUser::groupBy('user_id','sub_eldwry_id')
            ->selectRaw('sum(points) as sum, user_id')
            ->having('sub_eldwry_id',$id)
            ->get();

             return view('admin.subeldwry.details', compact('data' ,'id'));

     }

     public function pointsDetails($subeldwryId,$userId){


        
        if (!$this->user->can(['access-all', 'post-type-all', 'subeldwry-all'])) {

            if ($this->user->can(['subeldwry-list'])) {

                return redirect()->route('admin.subeldwry.index')->with('error', 'Have No Access');
            } else {

                return $this->pageUnauthorized();
            }
        }

        $id = GameHistory::where(['user_id' => $userId ,'sub_eldwry_id' =>$subeldwryId])
        ->first();

        $playersData = GamePlayerHistory::where(['game_history_id' => $id->id ,'is_active' =>1])->orderBy('order_id', 'Asc')->pluck('player_id')->toArray();

        $points = PointPlayer :: whereIn('player_id',$playersData)->get()->where('sub_eldwry_id',$subeldwryId);

         return view('admin.subeldwry.pointsDetails', compact('points'));
     }

}


