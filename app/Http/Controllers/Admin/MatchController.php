<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Match;
// use App\Models\Video;
use App\Models\Language;
use App\Models\Subeldwry;
use App\Models\Team;
use App\Models\Tag;
use App\Models\Taggable;
use App\Models\DetailMatche;

use DB;

class MatchController extends AdminController {

    /**z

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */
    public function index(Request $request) {

        if (!$this->user->can(['access-all', 'match-type-all', 'match-all', 'match-list', 'match-edit', 'match-delete', 'match-show'])) {
            return $this->pageUnauthorized();
        }

        $match_active = $match_edit = $match_create = $match_delete = $match_show = $comment_list = $comment_create = 0;

        if ($this->user->can(['access-all', 'match-type-all', 'match-all'])) {
            $match_active = $match_edit = $match_create = $match_delete = $match_show = $comment_list = $comment_create = 1;
        }

        if ($this->user->can('match-edit')) {
            $match_active = $match_edit = $match_create = $match_show = 1;
        }

        if ($this->user->can('match-delete')) {
            $match_delete = 1;
        }

        if ($this->user->can('match-show')) {
            $match_show = 1;
        }

        if ($this->user->can('match-create')) {
            $match_create = 1;
        }

        if ($this->user->can(['comment-all', 'comment-edit'])) {
            $comment_list = $comment_create = 1;
        }

        if ($this->user->can('comment-list')) {
            $comment_list = 1;
        }

        if ($this->user->can('comment-create')) {
            $comment_create = 1;
        }

        $name = 'match';
        $type_action = trans('app.match');
        $type_name = trans('app.matches');

        return view('admin.matches.index', compact('type_name', 'type_action', 'name','match_create'));
    }

    public function matchesList(){

        if (!$this->user->can(['access-all', 'match-type-all', 'match-all', 'match-list', 'match-edit', 'match-delete', 'match-show'])) {
            return $this->pageUnauthorized();
        }

        $match_active = $match_edit = $match_create = $match_delete = $match_show = $comment_list = $comment_create = 0;

        if ($this->user->can(['access-all', 'match-type-all', 'match-all'])) {
            $match_active = $match_edit = $match_create = $match_delete = $match_show = $comment_list = $comment_create = 1;
        }

        if ($this->user->can('match-edit')) {
            $match_active = $match_edit = $match_create = $match_show = 1;
        }

        if ($this->user->can('match-delete')) {
            $match_delete = 1;
        }

        if ($this->user->can('match-show')) {
            $match_show = 1;
        }

        if ($this->user->can('match-create')) {
            $match_create = 1;
        }

        if ($this->user->can(['comment-all', 'comment-edit'])) {
            $comment_list = $comment_create = 1;
        }

        if ($this->user->can('comment-list')) {
            $comment_list = 1;
        }

        if ($this->user->can('comment-create')) {
            $comment_create = 1;
        }
        $data = Match::orderBy('id', 'DESC');

        return datatables()->of($data)
            ->addColumn('action', function ($data)use($match_edit,$match_delete,$match_show) {

                $button = null;

                if($match_edit == 1){
                    $button .= '&emsp;<a  class="btn btn-primary fa fa-edit" data-toggle="tooltip" data-placement="top" data-title="تعديل " href="'.route('admin.matches.edit', $data->id).'"></a>';
                }
                
                if($match_delete == 1){
                    $button .= '&emsp;<a id="delete" class="btn btn-danger fa fa-trash"  data-toggle="tooltip" data-placement="top" data-title="حذف  المباراه" data-id="'. $data->id.' " data-name="'.$data->name.'"></a>';
                }
    
                if($match_show == 1){
                $button .= '&emsp;<a class="btn btn-primary fa fa-eye" data-toggle="tooltip" data-placement="top" data-title="تفاصيل" href="'.route('admin.matches.details', $data->id).'"></a>';

                }
                return $button;
            })

            ->addColumn('subeldwryName', function ($data) {
                return $data->sub_eldwry->name;
            })

            ->addColumn('firstTeam', function ($data) {
                return $data->teams_first->name;
            })

            ->addColumn('secondTeam', function ($data) {
                return $data->teams_second->name;
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
            ->rawColumns(['action', 'active','subeldwryName','firstTeam','secondTeam'])
            ->make(true);
    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */
    public function create(Request $request) {
        $type = 'match';
        $type_name = trans('app.matches');

        if (!$this->user->can(['access-all', 'match-type-all', 'match-all', 'match-create', 'match-edit'])) {
            return $this->pageUnauthorized();
        }
        if ($this->user->can(['access-all', 'match-type-all', 'match-all', 'match-edit'])) {
            $match_active = $image = 1;
        } else {
            $match_active = $image = 0;
        }
        if ($this->user->can(['image-upload', 'image-edit'])) {
            $image = 1;
        }
        $tags = Tag::pluck('name', 'name');
        $dataTags = $all_first = $all_second = [];
        $new = 1;
        if ($this->user->lang == 'ar') {
            $first_file = ['اختر ملف ' => 0];
            $first_video = ['اختر فيديو ' => 0];
        } else {
            $first_file = ['Choose File ' => 0];
            $first_video = ['Choose Video ' => 0];
        }
        $show_description=1;
        $image_link='';
        $teams = Team::where('is_active',1)->pluck('name', 'id');
        $files_all = []; // File::where('table_id', null)->where('is_active', 1)->pluck('id', 'name')->toArray();
        $files = array_flip(array_merge($first_file, $files_all));
        $videos_all =[];// Video::where('table_id', null)->where('is_active', 1)->pluck('id', 'name')->toArray();
        $videos = array_flip(array_merge($first_video, $videos_all));
        $subeldwry=Subeldwry::where('is_active',1)->pluck('name', 'id');

        $link_return = route('admin.matches.index');
        $mainLanguage = Language::get_Languag('is_active', 1, 'lang', 1);
        return view('admin.matches.create', compact('teams','image_link','show_description','mainLanguage','subeldwry', 'videos', 'tags', 'dataTags', 'files', 'type_name', 'type', 'link_return', 'new', 'match_active', 'image'));
    }

    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */
    public function store(Request $request) {

        if (!$this->user->can(['access-all', 'match-type-all', 'match-all', 'match-create', 'match-edit'])) {
            if ($this->user->can(['match-list'])) {
                return redirect()->route('admin.matches.index')->with('error', 'Have No Access');
            } else {
                return $this->pageUnauthorized();
            }
        }
        $request->validate([
//            'link' => "max:255|uniquePostLinkType:{$request->type}",
            'first_team_id' => 'required',
            'second_team_id' => 'required',
//            'video_id' => 'required',
        ]);
        $input = $request->all();
        $input=finalDataInputAdmin($input);
        foreach ($input as $key => $value) {
            if ($key != "video_id" && $key != "first_add" && $key != "second_add" && $key != "content" && $key != "tags" && $key != "description"&& $key != "lang" && $key != "lang_name") {
                $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
        }
        $input['is_active'] = 1;
        if (!isset($input['first_goon']) || empty($input['first_goon'])) {
            $input['first_goon'] = 0;
        }
        if (!isset($input['second_goon']) || empty($input['second_goon'])) {
            $input['second_goon'] = 0;
        }
        
        // if (!empty($input['date_booking'])) {
        //     $date_booking = explode('/', $input['date_booking']);
        //     $input['start_booking'] = $date_booking[0];
        //     $input['end_booking'] = $date_booking[1];
        // }
        // if (!empty($input['date']) && !empty($input['time'])) {
        //     $time = explode(' ', $input['time']);
        //     $input['time'] = $time[1];
        //     $input['date'] = $input['date'] . ' ' . $time[0];
        // }
        if ($input['link'] == Null) {
            $input['link'] = get_RandLink(time(),1);
        }
        $input['user_id'] = $this->user->id;
        $input['update_by'] = $this->user->id;
        $match = Match::create($input);
        $match_id = $match['id'];
        if ($input['lang'] == 'ar') {
            Match::updateColum($match_id, 'lang_id', $match_id);
        }
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
                    $taggable_id = Taggable::foundTaggable($tag_id, $match_id, "match");
                    if ($taggable_id == 0) {
                        $taggable->insertTaggable($tag_id, $post_id, "match");
                    }
                }
            }
        }
        return redirect()->route('admin.matches.index')->with('success', 'Created successfully');
    }

    /**

     * Display the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */
    public function show($id) {
//        $match = Match::find($id);
        return redirect()->route('admin.matches.edit', $id);
    }

    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */
    public function edit($id) {
        $match = Match::find($id);
        $type = 'match';
        $type_name = trans('app.matches');
        if (!empty($match)) {
            if ($this->user->id != $match->user_id) {
                if (!$this->user->can(['access-all', 'match-type-all', 'match-all', 'match-edit', 'match-edit-only'])) {
                    if ($this->user->can(['match-list', 'match-create'])) {
                        return redirect()->route('admin.matches.index')->with('error', 'Have No Access');
                    } else {
                        return $match->pageUnauthorized();
                    }
                }
            }
            if ($this->user->can('match-edit-only') && !$this->user->can(['access-all', 'match-type-all', 'match-all', 'match-edit'])) {
                if (($this->user->id != $this->user_id)) {
                    if ($this->user->can(['match-list', 'match-create'])) {
                        return redirect()->route('admin.matches.index')->with('error', 'Have No Access');
                    } else {
                        return $match->pageUnauthorized();
                    }
                }
            }
            $lang = 'ar';
            $image = $new = 0;
            if ($this->user->can(['access-all', 'match-type-all', 'match-all', 'match-edit'])) {
                $match_active = $image = 1;
            } else {
                $match_active = 0;
            }
            if ($this->user->can(['image-edit'])) {
                $image = 1;
            }
            $image_link = '';
            $show_description=1;
            if ($this->user->can(['access-all', 'match-type-all', 'match-all', 'match-edit'])) {
                $match_active = 1;
            } else {
                $match_active = 0;
            }
            $tags = Tag::pluck('name', 'name');
//            $dataTags = $match->tags->pluck('name', 'name')->toArray();
            $dataTags = Tag::whereIn('id', function($query)use ($id) {
                        $query->select('tag_id')
                                ->from(with(new Taggable)->getTable())
                                ->where('is_search', 1)->where('taggable_id', '=', $id)->where('taggable_type', '=', 'match');
                    })->pluck('name', 'name')->toArray();

            if ($this->user->lang == 'ar') {
                $first_file = ['اختر ملف ' => 0];
                $first_video = ['اختر فيديو ' => 0];
            } else {
                $first_file = ['Choose File ' => 0];
                $first_video = ['Choose Video ' => 0];
            }
            $files_all = []; // File::where('table_id', null)->where('is_active', 1)->pluck('id', 'name')->toArray();
            $files = array_flip(array_merge($first_file, $files_all));
            $videos_all = [];//Video::where('table_id', null)->where('is_active', 1)->pluck('id', 'name')->toArray();
            $videos = array_flip(array_merge($first_video, $videos_all));
            $teams = Team::where('is_active',1)->pluck('name', 'id');
            $subeldwry=Subeldwry::where('is_active',1)->pluck('name', 'id');
            $link_return = route('admin.matches.index');
            $array_name = json_decode($match->lang_name, true);
            $array_image = json_decode($match->image, true);
            $array_description = json_decode($match->description, true);
            $mainLanguage = Language::get_Languag('is_active', 1, 'lang', 1);
            return view('admin.matches.edit', compact('teams','mainLanguage','show_description','array_image','array_name','array_description','subeldwry', 'match',  'videos', 'show_description','image_link', 'tags', 'dataTags', 'files', 'type_name', 'type', 'link_return', 'new', 'match_active', 'image'));
        } else {
            return $match->pageError();
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
        $match = Match::find($id);
        $image = 0;
        if (!empty($match)) {
            if (!$this->user->can(['access-all', 'match-type-all', 'match-all', 'match-edit', 'match-edit-only'])) {
                if ($this->user->can(['match-list', 'match-create'])) {
                    return redirect()->route('admin.matches.index')->with('error', 'Have No Access');
                } else {
                    return $match->pageUnauthorized();
                }
            }

            if ($this->user->can('match-edit-only') && !$this->user->can(['access-all', 'match-type-all', 'match-all', 'match-edit'])) {
                if (($this->user->id != $this->user_id)) {
                    if ($this->user->can(['match-list', 'match-create'])) {
                        return redirect()->route('admin.matches.index')->with('error', 'Have No Access');
                    } else {
                        return $match->pageUnauthorized();
                    }
                }
            }
            $request->validate([
//                    'link' => "max:255|uniquePostUpdateLinkType:$request->type,$id",
                'first_team_id' => 'required',
                'second_team_id' => 'required',
            ]);
            $input = $request->all();
            $input=finalDataInputAdmin($input);
            foreach ($input as $key => $value) {
                if ($key != "video_id" && $key != "first_add" && $key != "second_add" && $key != "first" && $key != "second" && $key != "content" && $key != "tags" && $key != "description" && $key != "lang" && $key != "lang_name") {
                $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
        }
            $input['update_by'] = $this->user->id;
            $match->update($input);
            $match_id = $match->id;

            Taggable::deleteTaggableType($id,'match');
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
                        $taggable_id = Taggable::foundTaggable($tag_id, $match->id, "match");
                        if ($taggable_id == 0) {
                            $taggable->insertTaggable($tag_id, $match->id, "match");
                        }
                    }
                }
            }

            if ($this->user->can(['access-all', 'match-type-all', 'match-all', 'match-edit'])) {
                return redirect()->route('admin.matches.index')
                                ->with('success', 'Updated successfully');
            } elseif ($this->user->can('match-edit-only')) {
                return redirect()->route('admin.users.index')->with('success', 'Updated successfully');
            }
        } else {
            return $match->pageError();
        }
    }

    /**

     * Remove the specified resource from storage.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */
    public function destroy($id) {
        $match = Match::find($id);
        if (!empty($match)) {
            if (!$this->user->can(['access-all', 'match-type-all', 'match-all', 'match-delete', 'match-delete-only'])) {
                if ($this->user->can(['match-list'])) {
                    return redirect()->route('admin.matches.index')->with('error', 'Have No Access');
                } else {
                    return $match->pageUnauthorized();
                }
            }

            if ($this->user->can('match-delete-only') && !$this->user->can(['access-all', 'match-type-all', 'match-all', 'match-delete'])) {
                if (($this->user->id != $this->user_id)) {
                    if ($this->user->can(['match-list'])) {
                        return redirect()->route('admin.matches.index')->with('error', 'Have No Access');
                    } else {
                        return $match->pageUnauthorized();
                    }
                }
            }
            Taggable::deleteTaggableType($id,'match');
            Match::find($id)->delete();
            if ($this->user->can(['access-all', 'match-type-all', 'match-all', 'match-delete'])) {
            $arr = array('msg' =>__('app.delete_success'), 'status' => true);
            return Response()->json($arr);
            } elseif ($this->user->can(['match-delete-only'])) {
            $arr = array('msg' =>__('app.delete_success'), 'status' => true);
            return Response()->json($arr);
            }
        } else {
            return $match->pageError();
        }
    }

    public function search(Request $request) {
        $type = 'match';
        $type_name = trans('app.matches');

        $type_action = $type_name;
        if (!$this->user->can(['access-all', 'match-type-all', 'match-all', 'match-list', 'match-edit', 'match-delete', 'match-show'])) {
            return $this->pageUnauthorized();
        }

        $match_active = $match_edit = $match_create = $match_delete = $match_show = $comment_list = $comment_create = 0;

        if ($this->user->can(['access-all', 'match-type-all', 'match-all'])) {
            $match_active = $match_edit = $match_create = $match_delete = $match_show = $comment_list = $comment_create = 1;
        }

        if ($this->user->can('match-edit')) {
            $match_active = $match_edit = $match_create = $match_show = $comment_list = $comment_create = 1;
        }

        if ($this->user->can('match-delete')) {
            $match_delete = 1;
        }

        if ($this->user->can('match-show')) {
            $match_show = 1;
        }

        if ($this->user->can('match-create')) {
            $match_create = 1;
        }

        if ($this->user->can(['comment-all', 'comment-edit'])) {
            $comment_list = $comment_create = 1;
        }

        if ($this->user->can('comment-list')) {
            $comment_list = 1;
        }

        if ($this->user->can('comment-create')) {
            $comment_create = 1;
        }
        $name = 'matches';
        $data = Match::orderBy('id', 'DESC')->get();
        return view('admin.matches.search', compact('type', 'type_name', 'type_action', 'data', 'name', 'comment_create', 'comment_list', 'match_active', 'match_create', 'match_edit', 'match_delete', 'match_show'));
    }

    //*****************************************Not use comment *************************************************
    public function comments(Request $request, $id) {

        $match = Match::find($id);
        if (!empty($match)) {
            if (!$this->user->can(['access-all', 'match-type-all', 'match-all', 'match-edit', 'match-edit-only', 'match-show', 'match-show-only'])) {
                if ($this->user->can(['match-list', 'match-create'])) {
                    return redirect()->route('admin.matches.index')->with('error', 'Have No Access');
                } else {
                    return $match->pageUnauthorized();
                }
            }

            if ($this->user->can(['match-edit-only', 'match-show-only']) && !$this->user->can(['access-all', 'match-type-all', 'match-all', 'match-edit', 'match-show'])) {
                if (($this->user->id != $this->user_id)) {
                    if ($this->user->can(['match-list', 'match-create'])) {
                        return redirect()->route('admin.matches.index')->with('error', 'Have No Access');
                    } else {
                        return $match->pageUnauthorized();
                    }
                }
            }

            if (!$this->user->can(['access-all', 'match-type-all', 'match-all', 'comment-all', 'comment-list', 'comment-edit', 'comment-delete'])) {
                return $match->pageUnauthorized();
            }

            $comment_active = $comment_edit = $comment_delete = $comment_list = $comment_create = 0;

            if ($this->user->can(['access-all', 'match-type-all', 'match-all', 'comment-all'])) {
                $comment_active = $comment_edit = $comment_delete = $comment_list = $comment_create = 1;
            }

            if ($this->user->can(['comment-edit', 'comment-edit-match-only'])) {
                $comment_active = $comment_edit = $comment_list = $comment_create = 1;
            }

            if ($this->user->can(['comment-delete', 'comment-delete-match-only'])) {
                $comment_delete = 1;
            }

            if ($this->user->can('comment-create')) {
                $comment_create = 1;
            }

            $name = 'matches';

            $data = CommentMatch::where('commentable_id', $id)->where('commentable_type', 'matches')->paginate($this->limit);
            return view('admin.matches.comments', compact('data', 'id', 'name', 'comment_create', 'comment_list', 'comment_active', 'comment_edit', 'comment_delete'))->with('i', ($request->input('page', 1) - 1) * 5);
        } else {
            return $match->pageError();
        }
    }

    public function commentCreate($id) {

        $match = Match::find($id);
        if (!empty($match)) {
            if (!$this->user->can(['access-all', 'match-type-all', 'match-all', 'comment-all', 'comment-create', 'comment-edit'])) {
                return $match->pageUnauthorized();
            }
            $users = User::pluck('id', 'name');
            $comment_active = $user_active = 0;
            if ($this->user->can(['access-all', 'match-type-all', 'match-all', 'comment-all', 'comment-edit'])) {
                $comment_active = 1;
            }
            $new = 1;
            $user_id = $this->user->id;
            return view('admin.matches.comment_create', compact('users', 'user_id', 'id', 'new', 'comment_active'));
        } else {
            return $match->pageError();
        }
    }

    public function commentStore(Request $request, $id) {

        $match = Match::find($id);
        if (!empty($match)) {
            if (!$this->user->can(['access-all', 'match-type-all', 'match-all', 'comment-all', 'comment-create', 'comment-edit'])) {
                if ($this->user->can(['match-list'])) {
                    return redirect()->route('admin.matches.index')->with('error', 'Have No Access');
                } else {
                    return $match->pageUnauthorized();
                }
            }
            $match->validate($request, [
                'content' => 'required',
            ]);

            $input = $request->all();
            foreach ($input as $key => $value) {
                $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            $input['type'] = "matches";
            $is_read = 1;

            if (!isset($input['is_active'])) {
                $comment_active = DB::table('options')->where('option_key', 'comment_active')->value('option_value');
                $input['is_active'] = is_numeric($comment_active) ? $comment_active : 0;
                $input['user_id'] = $this->user->id;
                $is_read = 0;
            }
            $name = User::userData($input['user_id'], "name");
            $email = User::userData($input['user_id'], "email");
            $visitor = $request->ip();
            $comment = new CommentMatch();
            $comment->insertCommentMatch($input['user_id'], $visitor, $name, $email, $id, $input['type'], $input['content'], 0, "text", $is_read, $input['is_active']);
            return redirect()->route('admin.matches.comments.index', [$id])->with('success', 'CommentMatch created successfully');
        } else {
            return $match->pageError();
        }
    }

    public function details(Request $request, $id){

        $match = Match::find($id);
        if (!empty($match)) {
            if (!$this->user->can(['access-all', 'match-type-all', 'match-all'])) {
                if ($this->user->can(['match-details'])) {
                    return redirect()->route('admin.matches.index')->with('error', 'Have No Access');
                } else {
                    return $match->pageUnauthorized();
                }
            }

            $details = DetailMatche::where('match_id',$id)->get();

            foreach ($details as $key => $value) {
                $state_add=0;
    
                if(strpos($value->reason,'Goal') !== false){
                    $type='var_goal';
                    $state_add=1;
                    if(strpos($value->reason,'Cancelled') !== false){

                        DetailMatche::updateState_Opta_DetailMatch($value->team_id,'goal',$value->match_id,$value->player_id,'goon',0);
                    }
                } elseif(strpos($value->reason,'Card') !== false){
                    $type='var_card';
                    $state_add=1;
                    if(strpos($value->reason,'Cancelled') !== false){
                        DetailMatche::updateState_Opta_DetailMatch($value->team_id,'card',$value->match_id,$value->player_id,'yellow_cart',0,'red_cart',0);
                    }
                }
                if($state_add==1){
                    $input['type']=$type;
                    $input['penalties']=0;
                    $value->update($input);
                }
    
            }

            return view('admin.matches.details')->with(compact('details','match'));
        } else {
            return $match->pageError();
        }
    }
}
