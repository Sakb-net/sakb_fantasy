<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\User;
use App\Models\Tag;
use App\Models\Taggable;
use DB;

class SubteamController extends AdminController {

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
        $type_action = 'الفريق الفرعى';
        $data = Team::where('parent_id', '<>', 0)->where('type', 'subteam')->orderBy('id', 'DESC')->paginate($this->limit);
        return view('admin.subteams.index', compact('type_action', 'data', 'team_active', 'team_create', 'team_edit', 'team_show', 'team_delete'))->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */
    public function create($id = null) {
        $parent_id = $id;
        if (!$this->user->can(['access-all', 'post-type-all', 'team-all', 'team-create', 'team-edit'])) {
            return $this->pageUnauthorized();
        }
        $tags = Tag::pluck('name', 'name');
        if ($this->user->can(['access-all', 'post-type-all', 'team-all', 'team-edit'])) {
            $team_active = 1;
        } else {
            $team_active = 0;
        }
        $teamTags = [];
        $categories_all = Team::where('type', 'team')->where('parent_id', 0)->where('is_active', 1)->pluck('id', 'name')->toArray();
        $first_title = ['اختر الفريق الرئيسى' => 0];
        $categories = array_flip(array_merge($first_title, $categories_all));
        $new = 1;
        $icon = $icon_image = '';
        if (!empty($parent_id)) {
            $link_return = route('admin.clubteams.show', $parent_id);
        } else {
            $link_return = route('admin.subclubteams.index');
        }
        $lang='ar';
        return view('admin.subteams.create', compact('parent_id','lang', 'icon', 'link_return', 'icon_image', 'tags', 'new', 'categories', 'team_active', 'teamTags'));
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
                return redirect()->route('admin.subclubteams.index')->with('error', 'Have No Access');
            } else {
                return $this->pageUnauthorized();
            }
        }

        $this->validate($request, [
            'lang_name' => 'required|max:255',
        ]);

        $input = $request->all();
        $input=finalDataInputAdmin($input);
            foreach ($input as $key => $value) {
               if ($key != "tags" && $key != "lang" && $key != "lang_name" && $key != "image") {
                $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
        }

        $input['type'] = "subteam";
        if (empty($input['link'])) {
            $input['link'] = get_RandLink($input['name'],1);
        }$input['is_active'] = 1;
        $input['user_id'] = $this->user->id;
        $team = Team::create($input);
        $team_id = $team['id'];
        if ($input['lang'] == 'ar') {
            Team::updateColum($team_id, 'lang_id', $team_id);
        }
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
        if (!empty($input['parent_id'])) {
            return redirect()->route('admin.clubteams.show', $input['parent_id'])->with('success', 'Created successfully');
        } else {
            return redirect()->route('admin.subclubteams.index')->with('success', 'Created successfully');
        }
    }

    /**

     * Display the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */
 public function show(Request $request, $id) {
        $team = Team::where('type', '<>', 'banner')->find($id);
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
            $type_action = 'لاعبين الفريق';
            $data = Team::where('parent_id', $id)->where('type', 'userteam')->orderBy('id', 'DESC')->paginate($this->limit);
            return view('admin.userteams.index', compact('type_action', 'parent_id', 'data', 'team_active', 'team_create', 'team_edit', 'team_show', 'team_delete'))->with('i', ($request->input('page', 1) - 1) * 5);
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
                    return redirect()->route('admin.subclubteams.index')->with('error', 'Have No Access');
                } else {
                    return $this->pageUnauthorized();
                }
            }
            $tags = Tag::pluck('name', 'name');
            $team_active = 1;
            $teamTags = $team->tags->pluck('name', 'name')->toArray();
            $categories_all = Team::where('type', 'team')->where('parent_id', 0)->where('is_active', 1)->where('id', '<>', $id)->pluck('id', 'name')->toArray();
            $first_title = ['اختر الفريق الرئيسى' => 0];
            $categories = array_flip(array_merge($first_title, $categories_all));
            $new = 0;
            $icon_image = $team->icon_image;
            $icon = $team->icon;
            $parent_id =$team->parent_id;
            if (!empty($team->parent_id)) {
                $link_return = route('admin.clubteams.show', $team->parent_id);
            } else {
                $link_return = route('admin.subclubteams.index');
            }
            $lang='ar';
            return view('admin.subteams.edit', compact('icon','lang','parent_id', 'icon_image', 'link_return', 'team', 'categories', 'team_active', 'tags', 'teamTags', 'new'));
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
                    return redirect()->route('admin.subclubteams.index')->with('error', 'Have No Access');
                } else {
                    return $this->pageUnauthorized();
                }
            }

            $this->validate($request, [
                'lang_name' => 'required|max:255',
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
            if (!empty($input['parent_id'])) {
                return redirect()->route('admin.clubteams.show', $input['parent_id'])->with('success', 'Updated successfully');
            } else {
                return redirect()->route('admin.subclubteams.index')->with('success', 'Updated successfully');
            }
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
                    return redirect()->route('admin.subclubteams.index')->with('error', 'Have No Access');
                } else {
                    return $this->pageUnauthorized();
                }
            }
            Team::find($id)->delete();
            Taggable::deleteTaggableType($id, "categories");
            return redirect()->route('admin.subclubteams.index')
                            ->with('success', 'Deleted successfully');
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
        $type_action = 'الفريق الفرعى';
//        $data = Team::with('user')->where('parent_id','<>', 0)->where('type', 'subteam')->get();
        $data = Team::where('parent_id', '<>', 0)->where('type', 'subteam')->get();
        return view('admin.subteams.search', compact('type_action', 'data', 'team_create', 'team_edit', 'team_show', 'team_active', 'team_delete'));
    }

}

//   UID' => 'required|unique:{tableName},{secondcolumn}'








