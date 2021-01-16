<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use App\Models\AllType;
use App\Models\LocationPlayer;
use App\Models\Player;
use App\Models\Language;

//use URL;
class PlayerController extends AdminController {

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */
    public function index(Request $request) {

        if (!$this->user->can(['access-all', 'Player-type-all', 'Player-all', 'Player-list', 'Player-edit', 'Player-delete', 'Player-show'])) {
            return $this->pageUnauthorized();
        }

        $player_active = $player_edit = $player_create = $player_delete = $player_show = $comment_list = $comment_create = 0;

        if ($this->user->can(['access-all', 'Player-type-all', 'Player-all'])) {
            $player_active = $player_edit = $player_create = $player_delete = $player_show = $comment_list = $comment_create = 1;
        }

        if ($this->user->can('Player-edit')) {
            $player_active = $player_edit = $player_create = $player_show = 1;
        }

        if ($this->user->can('Player-delete')) {
            $player_delete = 1;
        }

        if ($this->user->can('Player-show')) {
            $player_show = 1;
        }

        if ($this->user->can('Player-create')) {
            $player_create = 1;
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

        $name = trans('app.Player');
        $type_action = trans('app.Player');
        $type_name = trans('app.Player');

        //Players 
        return view('admin.players.index', compact('type_action','player_create'));
    }



    public function playersList(){

        if (!$this->user->can(['access-all', 'Player-type-all', 'Player-all', 'Player-list', 'Player-edit', 'Player-delete', 'Player-show'])) {
            return $this->pageUnauthorized();
        }

        $player_active = $player_edit = $player_create = $player_delete = $player_show = $comment_list = $comment_create = 0;

        if ($this->user->can(['access-all', 'Player-type-all', 'Player-all'])) {
            $player_active = $player_edit = $player_create = $player_delete = $player_show = $comment_list = $comment_create = 1;
        }

        if ($this->user->can('Player-edit')) {
            $player_active = $player_edit = $player_create = $player_show = 1;
        }

        if ($this->user->can('Player-delete')) {
            $player_delete = 1;
        }

        if ($this->user->can('Player-show')) {
            $player_show = 1;
        }

        if ($this->user->can('Player-create')) {
            $player_create = 1;
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
        $data = Player::orderBy('id', 'DESC');

        return datatables()->of($data)
            ->addColumn('action', function ($data)use($player_edit,$player_delete,$player_show) {

                $button = null;

                if($player_edit == 1){
                    $button .= '&emsp;<a  class="btn btn-primary fa fa-edit" data-toggle="tooltip" data-placement="top" data-title="تعديل " href="'.route('admin.players.edit', $data->id ,$data->type_id).'"></a>';
                }
                
                if($player_delete == 1){
                    $button .= '&emsp;<a id="delete" class="btn btn-danger fa fa-trash"  data-toggle="tooltip" data-placement="top" data-title="حذف  لاعب" data-id="'. $data->id.' " data-name="'.$data->name.'"></a>';
                }

                if($player_show == 1){
                    $button .= '&emsp;<a class="btn btn-primary fa fa-eye" data-toggle="tooltip" data-placement="top" data-title="تفاصيل" href="'.route('admin.players.showDetails', $data->id).'"></a>';
                }

                return $button;
            })

            ->editColumn('active', function ($data) {
                if ($data->is_active == 1) {
                    $activeStatus = '<a class="playerstatus fa fa-check btn  btn-success"  data-id="{{ '.$data->id.' }}" data-status="0" ></a>';
                } else {
                    $activeStatus = '<a class="playerstatus fa fa-remove btn  btn-danger"  data-id="{{ '.$data->id.' }}" data-status="1" ></a>';
                }
                return $activeStatus;
            })
            

            ->addColumn('team', function ($data) {
                return $data->teams->name ;
            })
            
            ->addIndexColumn()
            ->rawColumns(['action', 'active','team'])
            ->make(true);
    }



   
    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */
    public function create() {

        if (!$this->user->can(['access-all', 'Player-all', 'Player-create', 'Player-edit'])) {
            return $this->pageUnauthorized();
        }
        if ($this->user->can(['access-all', 'Player-all', 'Player-edit'])) {
            $player_active = 1;
        } else {
            $player_active = 0;
        }
        $playerTags = [];
        $tags = []; //Tag::pluck('name', 'name');
        $link_return = route('admin.players.index');
        $new = $image = 1;
        $image_link = NULL;
        $lang = 'ar';
        $lang_id = '';
        $type_ids=AllType::where('is_active',1)->whereIn('type_key',['basic','spare'])->pluck('value_ar', 'id');
        $teams=Team::where('is_active',1)->pluck('name', 'id');
        $locations=LocationPlayer::where('is_active',1)->whereIn('id',[1,2,5,8])->pluck('value_ar', 'id');
        $mainLanguage = Language::get_Languag('is_active', 1, 'lang', 1);
        return view('admin.players.create', compact('mainLanguage','teams','type_ids','locations', 'lang_id', 'lang', 'image_link', 'image', 'link_return', 'tags', 'new', 'player_active', 'playerTags'));
    }

    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */
    public function store(Request $request) {
        if (!$this->user->can(['access-all', 'Player-all', 'Player-create', 'Player-edit'])) {
            if ($this->user->can('Player-list')) {
                session()->put('error', trans('app.no_access'));
                return redirect()->route('admin.players.index');
            } else {
                return $this->pageUnauthorized();
            }
        }
        $request->validate([
            'lang_name' => 'required|max:255',
//            'link' => "max:255|uniqueplayerLinkType:{$request->type}",
        ]);
        $input = $request->all();
        $input=finalDataInputAdmin($input);
        foreach ($input as $key => $value) {
           if ($key != "tags" && $key != "lang" && $key != "lang_name"&& $key != "content" && $key != "description") {
                $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
        }
        $input['user_id'] = $this->user->id;
        $input['update_by'] = $this->user->id;
        if ($input['link'] == Null) {
            $input['link'] = get_RandLink($input['name'],1);
        }
        if (empty($input['num_t_shirt'])) {
            $input['num_t_shirt'] = 0;
        }
        $input['is_active'] = 1;
        $player = Player::create($input);
        $player_id = $taggable_id = $player['id'];
        
        session()->put('success', trans('app.save_success'));
        return redirect()->route('admin.players.index');
    }

    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */
    public function show(Request $request, $id) {
//        $player = Player::find($id);
        return redirect()->route('admin.players.edit', $id);
    }

    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */
    public function edit($id) {
        $player = Player::find($id);
        if (!empty($player)) {
            if (!$this->user->can(['access-all', 'Player-all', 'Player-edit', 'Player-edit-only'])) {
                if ($this->user->can(['Player-list', 'Player-create'])) {
                    session()->put('error', trans('app.no_access'));
                    return redirect()->route('admin.players.index');
                } else {
                    return $player->pageUnauthorized();
                }
            }

            if ($this->user->can('Player-edit-only') && !$this->user->can(['access-all', 'Player-all', 'Player-edit'])) {
                if ($this->user->can(['Player-list', 'Player-create'])) {
                    session()->put('error', trans('app.no_access'));
                    return redirect()->route('admin.players.index');
                } else {
                    return $player->pageUnauthorized();
                }
            }

            $image = $new = 0;
            if ($this->user->can(['access-all', 'Player-all', 'Player-edit'])) {
                $player_active = $image = 1;
            } else {
                $player_active = 0;
            }
            if ($this->user->can(['image-edit'])) {
                $image = 1;
            }
            $image_link ='';
            if ($this->user->can(['access-all', 'Player-all', 'Player-edit'])) {
                $player_active = 1;
            } else {
                $player_active = 0;
            }
//            $tags =Tag::pluck('name', 'name');
            $tags = $playerTags=[];
            $lang = $this->user->lang;
            $type_ids=AllType::where('is_active',1)->whereIn('type_key',['basic','spare'])->pluck('value_ar', 'id');
            $teams=Team::where('is_active',1)->pluck('name', 'id');
            $locations=LocationPlayer::where('is_active',1)->whereIn('id',[1,2,5,8])->pluck('value_ar', 'id');
            $array_name = json_decode($player->lang_name, true);
            $array_image = json_decode($player->image, true);
            $mainLanguage = Language::get_Languag('is_active', 1, 'lang', 1);
            $link_return = route('admin.players.index');
            return view('admin.players.edit', compact('mainLanguage','array_name','array_image','teams','locations', 'type_ids', 'lang', 'link_return', 'player', 'playerTags', 'tags', 'new', 'image', 'image_link', 'player_active'));
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
        $player = Player::find($id);
        $image = 0;
        if (!empty($player)) {
            if (!$this->user->can(['access-all', 'Player-all', 'Player-edit', 'Player-edit-only'])) {
                if ($this->user->can(['Player-list', 'Player-create'])) {
                    session()->put('error', trans('app.no_access'));
                    return redirect()->route('admin.players.index');
                } else {
                    return $player->pageUnauthorized();
                }
            }

            if ($this->user->can('Player-edit-only') && !$this->user->can(['access-all', 'Player-all', 'Player-edit'])) {
                if ($this->user->can(['Player-list', 'Player-create'])) {
                    session()->put('error', trans('app.no_access'));
                    return redirect()->route('admin.players.index');
                } else {
                    return $player->pageUnauthorized();
                }
            }
            $request->validate([
                'lang_name' => 'required|max:255',
//                'link' => "max:255|uniquePostUpdateLinkType:$request->type,$id",
            ]);
            $input = $request->all();
            $input=finalDataInputAdmin($input);
            foreach ($input as $key => $value) {
               if ($key != "tags" && $key != "lang" && $key != "lang_name"&& $key != "content" && $key != "description") {
                    $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
                }
            }
            if (empty($input['num_t_shirt'])) {
                $input['num_t_shirt'] = 0;
            }
            $input['update_by'] = $this->user->id;
            $input['link'] = str_replace(' ', '_', $input['link']);
            $player->update($input);
            session()->put('success', trans('app.save_success'));
            return redirect()->route('admin.players.index');
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

        $player = Player::find($id);
        if (!empty($player)) {
            if (!$this->user->can(['access-all', 'Player-all', 'Player-delete'])) {
                if ($this->user->can(['Player-list', 'Player-edit'])) {
                    session()->put('error', trans('app.no_access'));
                    return redirect()->route('admin.players.index');
                } else {
                    return $this->pageUnauthorized();
                }
            }
            Player::find($id)->delete();
            $arr = array('msg' =>__('app.delete_success'), 'status' => true);
            return Response()->json($arr);
        } else {
            $error = new AdminController();
            return $error->pageError();
        }
    }

    public function search() {

        if (!$this->user->can(['access-all', 'Player-type-all', 'Player-all', 'Player-list', 'Player-edit', 'Player-delete', 'Player-show'])) {
            return $player->pageUnauthorized();
        }

        $player_active = $player_edit = $player_create = $player_delete = $player_show = $comment_list = $comment_create = 0;

        if ($this->user->can(['access-all', 'Player-type-all', 'Player-all'])) {
            $player_active = $player_edit = $player_create = $player_delete = $player_show = $comment_list = $comment_create = 1;
        }

        if ($this->user->can('Player-edit')) {
            $player_active = $player_edit = $player_create = $player_show = $comment_list = $comment_create = 1;
        }

        if ($this->user->can('Player-delete')) {
            $player_delete = 1;
        }

        if ($this->user->can('Player-show')) {
            $player_show = 1;
        }

        if ($this->user->can('Player-create')) {
            $player_create = 1;
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
        if ($this->user->lang == 'ar') {
            $type_action = "الاعب ";
        } else {
            $type_action = "Player";
        }
        $name = 'Players';
        $data = Player::orderBy('id', 'DESC')->paginate($this->limit);
        return view('admin.players.search', compact('type_action','data', 'name', 'comment_create', 'comment_list', 'player_active', 'player_create', 'player_edit', 'player_delete', 'player_show'));
    }

///*****************  comment for Player ***********************************
    public function comments(Request $request, $id) {
        $player = Player::find($id);
        if (!empty($player)) {
            if (!$this->user->can(['access-all', 'Player-type-all', 'Player-all', 'Player-edit', 'Player-edit-only', 'Player-show', 'Player-show-only'])) {
                if ($this->user->can(['Player-list', 'Player-create'])) {
                    session()->put('error', trans('app.no_access'));
                    return redirect()->route('admin.' . $player->type . 's.index');
                } else {
                    return $player->pageUnauthorized();
                }
            }
            if ($this->user->can(['Player-edit-only', 'Player-show-only']) && !$this->user->can(['access-all', 'Player-type-all', 'Player-all', 'Player-edit', 'Player-show'])) {
                if (($this->user->id != $this->user_id)) {
                    if ($this->user->can(['Player-list', 'Player-create'])) {
                        session()->put('error', trans('app.no_access'));
                        return redirect()->route('admin.' . $player->type . 's.index');
                    } else {
                        return $player->pageUnauthorized();
                    }
                }
            }

            if (!$this->user->can(['access-all', 'Player-type-all', 'Player-all', 'comment-all', 'comment-list', 'comment-edit', 'comment-delete'])) {
                return $player->pageUnauthorized();
            }

            $comment_active = $comment_edit = $comment_delete = $comment_list = $comment_create = 0;

            if ($this->user->can(['access-all', 'Player-type-all', 'Player-all', 'comment-all'])) {
                $comment_active = $comment_edit = $comment_delete = $comment_list = $comment_create = 1;
            }

            if ($this->user->can(['comment-edit', 'comment-edit-Player-only'])) {
                $comment_active = $comment_edit = $comment_list = $comment_create = 1;
            }

            if ($this->user->can(['comment-delete', 'comment-delete-Player-only'])) {
                $comment_delete = 1;
            }

            if ($this->user->can('comment-create')) {
                $comment_create = 1;
            }

            $name = $player->type;
            $type_action = '';
            $data = CommentPlayer::where('player_id', $id)->paginate($this->limit);  //where('type','question')->
            $link_return = route('admin.players.index'); //route('admin.players.comments.index',$id);
            return view('admin.Playercomments.index', compact('link_return','Player', 'type_action', 'data', 'id', 'name', 'comment_create', 'comment_list', 'comment_active', 'comment_edit', 'comment_delete'))->with('i', ($request->input('page', 1) - 1) * 5);
        } else {
            $error = new AdminController();
            return $error->pageError();
        }
    }

    public function commentCreate($id) {

        $player = Player::find($id);
        if (!empty($player)) {
            if (!$this->user->can(['access-all', 'Player-type-all', 'Player-all', 'comment-all', 'comment-create', 'comment-edit'])) {
                return $player->pageUnauthorized();
            }
            $users = User::pluck('id', 'name');
            $comment_active = $user_active = 0;
            if ($this->user->can(['access-all', 'Player-type-all', 'Player-all', 'comment-all', 'comment-edit'])) {
                $comment_active = 1;
            }
            $new = 1;
            $user_id = $this->user->id;
            $link_return = route('admin.players.comments.index',$player->id);
            return view('admin.Playercomments.create', compact('users','Player','link_return','user_id', 'id', 'new', 'comment_active'));
        } else {
            $error = new AdminController();
            return $error->pageError();
        }
    }

    public function commentStore(Request $request, $id) {
        $player = Player::find($id);
        if (!empty($player)) {
            if (!$this->user->can(['access-all', 'Player-type-all', 'Player-all', 'comment-all', 'comment-create', 'comment-edit'])) {
                if ($this->user->can(['Player-list'])) {
                    session()->put('error', trans('app.no_access'));
                    return redirect()->route('admin.' . $player->type . 's.index');
                } else {
                    return $player->pageUnauthorized();
                }
            }
            $request->validate([
                'content' => 'required',
            ]);

            $input = $request->all();
            foreach ($input as $key => $value) {
                $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
            $input['type'] = $player->type;
            $is_read = 1;
            if (!isset($input['is_active'])) {
                $comment_active = \App\Models\Options::where('option_key', 'comment_active')->value('option_value');
                $input['is_active'] = is_numeric($comment_active) ? $comment_active : 0;
                $input['user_id'] = $this->user->id;
                $is_read = 0;
            }
            $name = $this->user->display_name;
            $email = $this->user->email;
            $user_image = $this->user->image;
            if(empty($user_image)){
                $user_image = '/images/user.png';
            }
            $visitor = $request->ip();
            $comment = new CommentPlayer();
            $comment->insertComment($input['user_id'], $user_image, $name, $email, $id, $input['type'], $input['content'], null, "text", $is_read, $input['is_active']);
            session()->put('success', trans('app.save_success'));
            return redirect()->route('admin.' . $player->type . 's.comments.index', [$id]);
//            return redirect()->route('admin.lectures.comments.index', [$id]);
        } else {
            $error = new AdminController();
            return $error->pageError();
        }
    }

    public function details(Request $request, $id){
        $player = Player::find($id);

        if (!empty($player)) {
            if (!$this->user->can(['access-all', 'Player-type-all', 'Player-all'])) {
                if ($this->user->can(['Player-show'])) {
                    return redirect()->route('admin.players.index')->with('error', 'Have No Access');
                } else {
                    return $player->pageUnauthorized();
                }
            }

            $details = Player::where('id',$id)->first();
            // dd($details);

            return view('admin.players.details')->with(compact('details'));
        } else {
            return $error->pageError();
        }
    }

}
