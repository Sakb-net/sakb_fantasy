<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Eldwry;
use App\Models\Subeldwry;
use App\Models\Tag;
use App\Models\Taggable;
use App\Models\Language;
use DB;

class EldwryController extends AdminController {

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */
    public function index(Request $request) {

        if (!$this->user->can(['access-all', 'post-type-all', 'eldwry*'])) {
            return $this->pageUnauthorized();
        }

        $eldwry_delete = $eldwry_edit = $eldwry_active = $eldwry_show = $eldwry_create = 0;

        if ($this->user->can(['access-all', 'post-type-all'])) {
            $eldwry_delete = $eldwry_active = $eldwry_edit = $eldwry_show = $eldwry_create = 1;
        }

        if ($this->user->can('eldwry-all')) {
            $eldwry_delete = $eldwry_active = $eldwry_edit = $eldwry_create = 1;
        }

        if ($this->user->can('eldwry-delete')) {
            $eldwry_delete = 1;
        }

        if ($this->user->can('eldwry-edit')) {
            $eldwry_active = $eldwry_edit = $eldwry_create = 1;
        }

        if ($this->user->can('eldwry-create')) {
            $eldwry_create = 1;
        }
        $type_action = 'الدورى';

        return view('admin.eldwry.index', compact('type_action', 'eldwry_create'));
    }



    public function eldwryList(){
       
        if (!$this->user->can(['access-all', 'post-type-all', 'eldwry*'])) {
            return $this->pageUnauthorized();
        }

        $eldwry_delete = $eldwry_edit = $eldwry_active = $eldwry_show = $eldwry_create = 0;

        if ($this->user->can(['access-all', 'post-type-all'])) {
            $eldwry_delete = $eldwry_active = $eldwry_edit = $eldwry_show = $eldwry_create = 1;
        }

        if ($this->user->can('eldwry-all')) {
            $eldwry_delete = $eldwry_active = $eldwry_edit = $eldwry_create = 1;
        }

        if ($this->user->can('eldwry-delete')) {
            $eldwry_delete = 1;
        }

        if ($this->user->can('eldwry-edit')) {
            $eldwry_active = $eldwry_edit = $eldwry_create = 1;
        }

        if ($this->user->can('eldwry-create')) {
            $eldwry_create = 1;
        }

        $data = Eldwry::orderBy('id', 'DESC');

        return datatables()->of($data)
            ->addColumn('action', function ($data)use($eldwry_delete,$eldwry_edit) {

                $button = null;

                if($eldwry_edit == 1){
                    $button .= '&emsp;<a  class="btn btn-primary fa fa-edit" data-toggle="tooltip" data-placement="top" data-title="تعديل " href="'.route('admin.eldwry.edit', $data->id).'"></a>';
                }
                
                if($eldwry_delete == 1){
                    $button .= '&emsp;<a id="delete" class="btn btn-danger fa fa-trash"  data-toggle="tooltip" data-placement="top" data-title="حذف  الدورى" data-id="'. $data->id.' " data-name="'.$data->name.'"></a>';
                }
                return $button;
            })
            ->editColumn('active', function ($data) {
                if ($data->is_active == 1) {
                    $activeStatus = '<a class="poststatus fa fa-check btn  btn-success"  data-id="{{ '.$data->id.' }}" data-status="0" ></a>';
                } else {
                    $activeStatus = '<a class="poststatus fa fa-remove btn  btn-danger"  data-id="{{ '.$data->id.' }}" data-status="1" ></a>';
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

        if (!$this->user->can(['access-all', 'post-type-all', 'eldwry-all', 'eldwry-create', 'eldwry-edit'])) {
            return $this->pageUnauthorized();
        }
        $tags = Tag::pluck('name', 'name');
        if ($this->user->can(['access-all', 'post-type-all', 'eldwry-all', 'eldwry-edit'])) {
            $eldwry_active = 1;
        } else {
            $eldwry_active = 0;
        }
        $eldwryTags = [];
        $new = 1;
        $date_booking = '';
        $mainLanguage = Language::get_Languag('is_active', 1, 'lang', 1);
        $link_return = route('admin.eldwry.index');
        return view('admin.eldwry.create', compact('mainLanguage','tags','date_booking', 'link_return', 'new', 'eldwry_active', 'eldwryTags'));
    }

    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */
    public function store(Request $request) {

        if (!$this->user->can(['access-all', 'post-type-all', 'eldwry-all', 'eldwry-create', 'eldwry-edit'])) {
            if ($this->user->can('eldwry-list')) {
                return redirect()->route('admin.eldwry.index')->with('error', 'Have No Access');
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
            $input['link'] = get_RandLink($input['name'],1);
        }
        if (!empty($input['date_booking'])) {
            $date_booking = explode('/', $input['date_booking']);
            $input['start_date'] = $date_booking[0];
            $input['end_date'] = $date_booking[1];
        }
        $input['is_active'] = 1;
        $input['user_id'] = $this->user->id;
        $eldwry = Eldwry::create($input);
        $eldwry_id = $eldwry['id'];
        $taggable_id = $eldwry_id;
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
                    $taggable->insertTaggable($tag_id, $taggable_id, "eldwry");
                }
            }
        }

        return redirect()->route('admin.eldwry.index')->with('success', 'eldwry created successfully');
    }

    /**

     * Display the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */
    public function show(Request $request, $id) {

    }
    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */
    public function edit($id) {

        $eldwry = Eldwry::find($id);
        if (!empty($eldwry)) {
            if (!$this->user->can(['access-all', 'post-type-all', 'eldwry-all', 'eldwry-edit'])) {
                if ($this->user->can(['eldwry-list', 'eldwry-create'])) {
                    return redirect()->route('admin.eldwry.index')->with('error', 'Have No Access');
                } else {
                    return $this->pageUnauthorized();
                }
            }
            $tags = Tag::pluck('name', 'name');
            $eldwry_active = 1;
            $eldwryTags = $eldwry->tags->pluck('name', 'name')->toArray();
            $new = 0;
            $link_return = route('admin.eldwry.index');
            $date_booking ='';
            if(!empty($eldwry->start_date)){
            $start_date=date('Y-m-d', strtotime($eldwry->start_date)) ;
            $end_date=date('Y-m-d', strtotime($eldwry->end_date)) ;
            $date_booking = $start_date.' / '.$end_date; //2020-02-15 / 2020-02-15
            }
            $array_name = json_decode($eldwry->lang_name, true);
            $mainLanguage = Language::get_Languag('is_active', 1, 'lang', 1);
            return view('admin.eldwry.edit', compact('mainLanguage','array_name','date_booking', 'link_return', 'eldwry', 'eldwry_active', 'tags', 'eldwryTags', 'new'));
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

        $eldwry = Eldwry::find($id);
        if (!empty($eldwry)) {
            if (!$this->user->can(['access-all', 'post-type-all', 'eldwry-all', 'eldwry-edit'])) {
                if ($this->user->can(['eldwry-list', 'eldwry-create'])) {
                    return redirect()->route('admin.eldwry.index')->with('error', 'Have No Access');
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
            $eldwry->update($input);

            Taggable::deleteTaggableType($id, "eldwry");
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
                        $taggable->insertTaggable($tag_id, $id, "eldwry");
                    }
                }
            }
            //  return redirect()->route('admin.eldwry.index')->with('success', 'Updated successfully');
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

        $eldwry = Eldwry::find($id);
        if (!empty($eldwry)) {
            if (!$this->user->can(['access-all', 'post-type-all', 'eldwry-all', 'eldwry-delete'])) {
                if ($this->user->can(['eldwry-list', 'eldwry-edit'])) {
                    return redirect()->route('admin.eldwry.index')->with('error', 'Have No Access');
                } else {
                    return $this->pageUnauthorized();
                }
            }
            Eldwry::find($id)->delete();
            Taggable::deleteTaggableType($id, "eldwry");
            $arr = array('msg' =>__('app.delete_success'), 'status' => true);
            return Response()->json($arr);
        } else {
            return $this->pageError();
        }
    }

    public function search() {

        if (!$this->user->can(['access-all', 'post-type-all', 'eldwry-all', 'eldwry-list'])) {
            return $this->pageUnauthorized();
        }

        $eldwry_delete = $eldwry_edit = $eldwry_active = $eldwry_show = $eldwry_create = 0;

        if ($this->user->can(['access-all', 'post-type-all'])) {
            $eldwry_delete = $eldwry_active = $eldwry_edit = $eldwry_show = $eldwry_create = 1;
        }

        if ($this->user->can('eldwry-all')) {
            $eldwry_delete = $eldwry_active = $eldwry_edit = $eldwry_create = 1;
        }

        if ($this->user->can('eldwry-delete')) {
            $eldwry_delete = 1;
        }

        if ($this->user->can('eldwry-edit')) {
            $eldwry_active = $eldwry_edit = $eldwry_create = 1;
        }

        if ($this->user->can('eldwry-create')) {
            $eldwry_create = 1;
        }
        $type_action = 'الدورى';
        $data = Eldwry::orderBy('id', 'DESC')->get();
        return view('admin.eldwry.search', compact('type_action', 'data', 'eldwry_create', 'eldwry_edit', 'eldwry_show', 'eldwry_active', 'eldwry_delete'));
    }

}