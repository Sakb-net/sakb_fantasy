<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Eldwry;
use App\Models\Subeldwry;
use App\Models\Team;
use App\Models\User;
use App\Models\Tag;
use App\Models\Taggable;
use App\Models\Language;
use App\Models\Player;
use DB;

class TeamController extends AdminController {

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */
    public function index(Request $request) {

        if (!$this->user->can(['access-all', 'post-type-all', 'team*'])) {
            return $this->pageUnauthorized();
        }

        $team_delete = $team_edit = $team_active = $team_show = $team_create = 0;

        if ($this->user->can(['access-all', 'post-type-all'])) {
            $team_delete = $team_active = $team_edit = $team_show = $team_create = 1;
        }

        if ($this->user->can('team-all')) {
            $team_delete = $team_active = $team_edit = $team_create = 1;
        }

        if ($this->user->can('team-delete')) {
            $team_delete = 1;
        }

        if ($this->user->can('team-edit')) {
            $team_active = $team_edit = $team_create = 1;
        }

        if ($this->user->can('team-create')) {
            $team_create = 1;
        }
        $type_action = trans('app.team');
        return view('admin.teams.index', compact('type_action',  'team_create','team_edit'));
    }



    public function clubteamsList(){

        if (!$this->user->can(['access-all', 'post-type-all', 'team*'])) {
            return $this->pageUnauthorized();
        }

        $team_delete = $team_edit = $team_active = $team_show = $team_create = 0;

        if ($this->user->can(['access-all', 'post-type-all'])) {
            $team_delete = $team_active = $team_edit = $team_show = $team_create = 1;
        }

        if ($this->user->can('team-all')) {
            $team_delete = $team_active = $team_edit = $team_create = 1;
        }

        if ($this->user->can('team-delete')) {
            $team_delete = 1;
        }

        if ($this->user->can('team-edit')) {
            $team_active = $team_edit = $team_create = 1;
        }

        if ($this->user->can('team-create')) {
            $team_create = 1;
        }

        $data = Team::orderBy('id', 'DESC');

        return datatables()->of($data)
            ->addColumn('action', function ($data)use($team_edit,$team_delete) {

                $button = null;

                if($team_edit == 1){
                    $button .= '&emsp;<a class="btn btn-primary fa fa-edit" data-toggle="tooltip" data-placement="top" data-title="تعديل " href="'.route('admin.clubteams.edit', $data->id).'"></a>';

                    $button .= '&emsp;<a class="btn btn-info fa fa-pencil" data-toggle="tooltip" data-placement="top" data-title="تعديل اللاعبين" href="'.route('admin.clubteams.editAllPlayers', $data->id).'"></a>';
                }
                
                if($team_delete == 1){
                    $button .= '&emsp;<a id="delete" class="btn btn-danger fa fa-trash"  data-toggle="tooltip" data-placement="top" data-title="حذف  الفريق" data-id="'. $data->id.' " data-name="'.$data->name.'"></a>';
                }

                return $button;
            })


            ->editColumn('active', function ($data) {
                if ($data->is_active == 1) {
                    $activeStatus = '<a class="teamstatus fa fa-check btn  btn-success"  data-id="{{ '.$data->id.' }}" data-status="0" ></a>';

                } else {
                    $activeStatus = '<a class="teamstatus fa fa-remove btn  btn-danger"  data-id="{{ '.$data->id.' }}" data-status="1" ></a>';
                }
                return $activeStatus;
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'active'])
            ->make(true);
    }




    public function type(Request $request, $eldwry_id) {

        $team_delete = $team_edit = $team_active = $team_create = 0;

        if ($this->user->can(['access-all', 'post-type-all', 'team-all'])) {
            $team_delete = $team_active = $team_edit = $team_create = 1;
        }

        if ($this->user->can('team-delete')) {
            $team_delete = 1;
        }

        if ($this->user->can('team-edit')) {
            $team_active = $team_edit = $team_create = 1;
        }

        if ($this->user->can('team-create')) {
            $team_create = 1;
        }
        $type_action = 'الفريق';
        $data = Team::where('eldwry_id',$eldwry_id)->orderBy('id', 'DESC')->paginate($this->limit);
        return view('admin.teams.index', compact('type_action', 'data', 'team_create', 'team_edit', 'team_delete'))
                        ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */
    public function create() {
        if (!$this->user->can(['access-all', 'post-type-all', 'team-all', 'team-create', 'team-edit'])) {
            return $this->pageUnauthorized();
        }
        $tags = Tag::pluck('name', 'name');
        if ($this->user->can(['access-all', 'post-type-all', 'team-all', 'team-edit'])) {
            $team_active = 1;
        } else {
            $team_active = 0;
        }
        $eldwrys=Eldwry::where('is_active',1)->pluck('name', 'id');
        $teamTags = [];
        $new = $image = 1;
        $image_link = $image_fav='';
        $link_return = route('admin.clubteams.index');
        $mainLanguage = Language::get_Languag('is_active', 1, 'lang', 1);
        return view('admin.teams.create', compact('mainLanguage','eldwrys','image_link','image', 'tags', 'link_return', 'new', 'team_active', 'teamTags','image_fav'));
    }

    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */
    public function store(Request $request) {

        if (!$this->user->can(['access-all', 'post-type-all', 'team-all', 'team-create', 'team-edit'])) {
            if ($this->user->can('team-list')) {
                return redirect()->route('admin.clubteams.index')->with('error', 'Have No Access');
            } else {
                return $this->pageUnauthorized();
            }
        }

        $this->validate($request, [
            'lang_name' => 'required|max:255',
//            'link' => "max:255|uniqueteamLinkType:{$request->type}",
        ]);

        $input = $request->all();
        $input=finalDataInputAdmin($input);
        foreach ($input as $key => $value) {
           if ($key != "tags" && $key != "lang" && $key != "lang_name" && $key != "image") {
                $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
        }
        if ($input['link'] == Null) {
            $input['link'] = get_RandLink($input['name'],1);
        }
        $input['is_active'] = 1;
        $input['user_id'] = $this->user->id;
        $team = Team::create($input);
        $team_id = $team['id'];
        $taggable_id = $team_id;
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
                    $taggable->insertTaggable($tag_id, $taggable_id, "team");
                }
            }
        }
        return redirect()->route('admin.clubteams.index')->with('success', 'Created successfully');
    }

    /**

     * Display the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */
    public function show(Request $request, $id) {
        $team = Team::find($id);
        if (!empty($team)) {
            if (!$this->user->can(['access-all', 'post-type-all', 'team*'])) {
                return $this->pageUnauthorized();
            }

            $team_delete = $team_edit = $team_active = $team_show = $team_create = 0;

            if ($this->user->can(['access-all', 'post-type-all'])) {
                $team_delete = $team_active = $team_edit = $team_show = $team_create = 1;
            }

            if ($this->user->can('team-all')) {
                $team_delete = $team_active = $team_edit = $team_create = 1;
            }

            if ($this->user->can('team-delete')) {
                $team_delete = 1;
            }

            if ($this->user->can('team-edit')) {
                $team_active = $team_edit = $team_create = 1;
            }

            if ($this->user->can('team-create')) {
                $team_create = 1;
            }
            $parent_id = $id;
            $type_action = 'القسم الفرعى';
            $data =[];// Team::where('id',$id)->orderBy('id', 'DESC')->paginate($this->limit);
            return view('admin.subteams.index', compact('type_action', 'parent_id', 'data', 'team_active', 'team_create', 'team_edit', 'team_show', 'team_delete'))->with('i', ($request->input('page', 1) - 1) * 5);
        } else {
            return $this->pageError();
        }
    }

    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */
    public function edit($id) {

        $team = Team::find($id);
        if (!empty($team)) {
            if (!$this->user->can(['access-all', 'post-type-all', 'team-all', 'team-edit'])) {
                if ($this->user->can(['team-list', 'team-create'])) {
                    return redirect()->route('admin.clubteams.index')->with('error', 'Have No Access');
                } else {
                    return $this->pageUnauthorized();
                }
            }
            $tags = Tag::pluck('name', 'name');
            $team_active = 1;
            // print_r($team->tags/die;
            $teamTags = $team->tags->pluck('name', 'name')->toArray();
            $new = 0;
            $image = 1;
            $image_link =$image_fav='';
            $eldwrys=Eldwry::where('is_active',1)->pluck('name', 'id');
            $link_return = route('admin.clubteams.index');
            $array_name = json_decode($team->lang_name, true);
            $array_image = json_decode($team->image, true);
            $mainLanguage = Language::get_Languag('is_active', 1, 'lang', 1);
            return view('admin.teams.edit', compact('mainLanguage','eldwrys','image_link', 'image', 'link_return', 'team', 'team_active', 'tags', 'teamTags', 'new','array_name','array_image','image_fav'));
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
        $team = Team::find($id);
        if (!empty($team)) {
            if (!$this->user->can(['access-all', 'post-type-all', 'team-all', 'team-edit'])) {
                if ($this->user->can(['team-list', 'team-create'])) {
                    return redirect()->route('admin.clubteams.index')->with('error', 'Have No Access');
                } else {
                    return $this->pageUnauthorized();
                }
            }

            $this->validate($request, [
                'lang_name' => 'required|max:255',
//            'link' => "required|max:255|uniqueteamUpdateLinkType:$request->type,$id",
            ]);
            $input = $request->all();
            $input=finalDataInputAdmin($input);
            foreach ($input as $key => $value) {
               if ($key != "tags" && $key != "lang" && $key != "lang_name" && $key != "image") {
                    $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
                }
            }
            if (empty($input['link'])) {
                $input['link'] = str_replace(' ', '_', $input['name'] . str_random(8));
            }
            $team->update($input);
            Taggable::deleteTaggableType($id, "team");
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
                        $taggable->insertTaggable($tag_id, $id, "team");
                    }
                }
            }
            //  return redirect()->route('admin.clubteams.index')->with('success', 'Updated successfully');
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

        $team = Team::find($id);
        if (!empty($team)) {
            if (!$this->user->can(['access-all', 'post-type-all', 'team-all', 'team-delete'])) {
                if ($this->user->can(['team-list', 'team-edit'])) {
                    return redirect()->route('admin.clubteams.index')->with('error', 'Have No Access');
                } else {
                    return $this->pageUnauthorized();
                }
            }
            Team::find($id)->delete();
            Taggable::deleteTaggableType($id, "team");
            $arr = array('msg' =>__('app.delete_success'), 'status' => true);
            return Response()->json($arr);
        } else {
            return $this->pageError();
        }
    }

    public function search() {

        if (!$this->user->can(['access-all', 'post-type-all', 'team-all', 'team-list'])) {
            return $this->pageUnauthorized();
        }

        $team_delete = $team_edit = $team_active = $team_show = $team_create = 0;

        if ($this->user->can(['access-all', 'post-type-all'])) {
            $team_delete = $team_active = $team_edit = $team_show = $team_create = 1;
        }

        if ($this->user->can('team-all')) {
            $team_delete = $team_active = $team_edit = $team_create = 1;
        }

        if ($this->user->can('team-delete')) {
            $team_delete = 1;
        }

        if ($this->user->can('team-edit')) {
            $team_active = $team_edit = $team_create = 1;
        }

        if ($this->user->can('team-create')) {
            $team_create = 1;
        }
        $type_action = 'الفريق';
        $data = Team::orderBy('id', 'DESC')->get();
        return view('admin.teams.search', compact('type_action', 'data', 'team_create', 'team_edit', 'team_show', 'team_active', 'team_delete'));
    }

    public function allSearch() {

        if (!$this->user->can(['access-all', 'post-type-all', 'team-all', 'team-list'])) {
            return $this->pageUnauthorized();
        }

        $team_delete = $team_edit = $team_active = $team_show = $team_create = 0;

        if ($this->user->can(['access-all', 'post-type-all'])) {
            $team_delete = $team_active = $team_edit = $team_show = $team_create = 1;
        }

        if ($this->user->can('team-all')) {
            $team_delete = $team_active = $team_edit = $team_create = 1;
        }

        if ($this->user->can('team-delete')) {
            $team_delete = 1;
        }

        if ($this->user->can('team-edit')) {
            $team_active = $team_edit = $team_create = 1;
        }

        if ($this->user->can('team-create')) {
            $team_create = 1;
        }
        $type_action = 'الفريق';
//        $data = Team::whereIn('type', ['team','subteam'])->with('user')->get();
        $data = Team::get();
        return view('admin.teams.search', compact('type_action', 'data', 'team_create', 'team_edit', 'team_show', 'team_active', 'team_delete'));
    }
    public function clubteamsEditAll(){

        if (!$this->user->can(['access-all', 'post-type-all', 'team-all', 'team-edit'])) {
            if ($this->user->can(['team-list', 'team-create'])) {
                return redirect()->route('admin.clubteams.index')->with('error', 'Have No Access');
            }
        }

        $mainLanguage = Language::get_Languag('is_active', 1, 'lang', 1);

        
        $team = Team::orderBy('id', 'DESC')->get();
        foreach ($team as $value){
            $value->newVal = json_decode($value->lang_name, true);
        }
        return view('admin.teams.editAll', compact('mainLanguage','team'));
    }
    public function clubteamsUpdateAll(Request $request){


            if (!$this->user->can(['access-all', 'post-type-all', 'team-all', 'team-edit'])) {
                if ($this->user->can(['team-list', 'team-create'])) {
                    return redirect()->route('admin.clubteams.index')->with('error', 'Have No Access');
                } else {
                    return $this->pageUnauthorized();
                }
            }

        $lang = $request->lang;
        $request->request->remove('_token'); 
        $request->request->remove('lang'); 
        $input =  $request->all();
 
        foreach($input as $key=>$val){
            $allData = array_combine($lang, $val);
            $jsonData = json_encode($allData);
            Team::where('id', $key)
            ->update(['lang_name' => $jsonData]);
        }
         return redirect()->route('admin.clubteams.index')->with('success', 'Updated successfully');
    }

    public function editAllPlayers($id){
    
        
        if (!$this->user->can(['access-all', 'post-type-all', 'team-all', 'team-edit'])) {
            if ($this->user->can(['team-list', 'team-create'])) {
                return redirect()->route('admin.clubteams.index')->with('error', 'Have No Access');
            }
        }

        $mainLanguage = Language::get_Languag('is_active', 1, 'lang', 1);
        
        $players = Player::where('team_id',$id)->orderBy('id', 'DESC')->get();
        foreach ($players as $value){
            $value->newVal = json_decode($value->lang_name, true);
        }
        return view('admin.teams.editAllPlayers', compact('mainLanguage','players'));

    }

    public function updateAllPlayers(Request $request){


        if (!$this->user->can(['access-all', 'post-type-all', 'team-all', 'team-edit'])) {
            if ($this->user->can(['team-list', 'team-create'])) {
                return redirect()->route('admin.clubteams.index')->with('error', 'Have No Access');
            } else {
                return $this->pageUnauthorized();
            }
        }

        $lang = $request->lang;
        $request->request->remove('_token'); 
        $request->request->remove('lang'); 
        $input =  $request->all();

        foreach($input as $key=>$val){
            $allData = array_combine($lang, $val);
            $jsonData = json_encode($allData);
            Player::where('id', $key)
            ->update(['lang_name' => $jsonData]);
        }
     return redirect()->route('admin.clubteams.index')->with('success', 'Updated successfully');
}
}

//   UID' => 'required|unique:{tableName},{secondcolumn}'








