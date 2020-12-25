<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Language;
use DB;

class LanguageController extends AdminController {

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */
    public function index(Request $request) {

        if (!$this->user->can(['access-all', 'translation', 'admin-panel', 'display_translation', 'update_translation', 'delete_translation', 'show_translation'])) {
            return $this->pageUnauthorized();
        }

        $post_active = $post_edit = $post_create = $post_delete = $post_show = 0;

        if ($this->user->can(['access-all', 'translation', 'admin-panel'])) {
            $post_active = $post_edit = $post_create = $post_delete = $post_show = $comment_list = $comment_create = 1;
        }

        if ($this->user->can('update_translation')) {
            $post_active = $post_edit = $post_create = $post_show = 1;
        }

        if ($this->user->can('delete_translation')) {
            $post_delete = 1;
        }

        if ($this->user->can('show_translation')) {
            $post_show = 1;
        }

        if ($this->user->can('add_translation')) {
            $post_create = 1;
        }

        $name = 'language';
        $type_action = 'اللغة';
        return view('admin.languages.index', compact('type_action', 'name', 'post_create'));
    }



    public function languagesList(){
        if (!$this->user->can(['access-all', 'translation', 'admin-panel', 'display_translation', 'update_translation', 'delete_translation', 'show_translation'])) {
            return $this->pageUnauthorized();
        }

        $post_active = $post_edit = $post_create = $post_delete = $post_show = 0;

        if ($this->user->can(['access-all', 'translation', 'admin-panel'])) {
            $post_active = $post_edit = $post_create = $post_delete = $post_show = $comment_list = $comment_create = 1;
        }

        if ($this->user->can('update_translation')) {
            $post_active = $post_edit = $post_create = $post_show = 1;
        }

        if ($this->user->can('delete_translation')) {
            $post_delete = 1;
        }

        if ($this->user->can('show_translation')) {
            $post_show = 1;
        }

        if ($this->user->can('add_translation')) {
            $post_create = 1;
        }
        $data = language::orderBy('id', 'DESC');

        return datatables()->of($data)
            ->addColumn('action', function ($data)use($post_edit,$post_delete) {

                $button = null;

                if($post_edit == 1){
                    $button .= '&emsp;<a  class="btn btn-primary fa fa-edit" data-toggle="tooltip" data-placement="top" data-title="تعديل " href="'.route('admin.languages.edit', $data->id).'"></a>';
                }
                
                if($post_delete == 1){
                    $button .= '&emsp;<a id="delete" class="btn btn-danger fa fa-trash"  data-toggle="tooltip" data-placement="top" data-title="حذف  المباراه" data-id="'. $data->id.' " data-name="'.$data->name.'"></a>';
                }
                return $button;
            })

            ->addColumn('default', function ($data) {
                $checkDefault = null;
                if ($data->is_default == 1) {
                    $checkDefault = '<a class="btn btn-success btn-state"  data-id="{{ '.$data->id.' }}" data-status="0" >default</a>';
                }
                return $checkDefault;
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
            ->rawColumns(['action', 'active','default'])
            ->make(true);
    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */
    public function create() {

        if (!$this->user->can(['access-all', 'translation', 'admin-panel', 'add_translation', 'update_translation'])) {
            return $this->pageUnauthorized();
        }
        if ($this->user->can(['access-all', 'translation', 'admin-panel', 'update_translation'])) {
            $post_active = 1;
        } else {
            $post_active = 0;
        }
        $new = 1;
        $link_return = route('admin.languages.index');
        return view('admin.languages.create', compact('new', 'post_active', 'link_return'));
    }

    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */
    public function store(Request $request) {
        if (!$this->user->can(['access-all', 'translation', 'admin-panel', 'add_translation', 'update_translation'])) {
            if ($this->user->can(['display_translation'])) {
                session()->put('error', trans('app.no_access'));
                return redirect()->route('admin.languages.index');
            } else {
                return $this->pageUnauthorized();
            }
        }

        $this->validate($request, [
            'lang' => 'required',
            'name' => 'required',
        ]);
        $input = $request->all();
        foreach ($input as $key => $value) {
            $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
        }
        $input['is_active'] = 1;
        $input['user_id'] = $this->user->id;
        $language = Language::create($input);
        session()->put('success', trans('app.save_success'));
        return redirect()->route('admin.languages.index');
    }

    /**

     * Display the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */
    public function show($id) {
//        $language = Language::find($id);
        return redirect()->route('admin.languages.edit', $id);
    }

    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */
    public function edit($id) {
        $language = Language::find($id);
        if (!empty($language)) {
            if ($this->user->can('update_translation-only') && !$this->user->can(['access-all', 'translation', 'admin-panel', 'update_translation'])) {
                if ($this->user->can(['display_translation', 'add_translation'])) {
                    session()->put('error', trans('app.no_access'));
                    return redirect()->route('admin.languages.index');
                } else {
                    return $this->pageUnauthorized();
                }
            }
            $new = 0;
            if ($this->user->can(['access-all', 'translation', 'admin-panel', 'update_translation'])) {
                $post_active = 1;
            } else {
                $post_active = 0;
            }
            if ($this->user->can(['access-all', 'translation', 'admin-panel', 'update_translation'])) {
                $post_active = 1;
            } else {
                $post_active = 0;
            }
            $link_return = route('admin.languages.index');
            return view('admin.languages.edit', compact('language', 'new', 'post_active', 'link_return'));
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
        $language = Language::find($id);
        $image = 0;
        if (!empty($language)) {
            if (!$this->user->can(['access-all', 'translation', 'admin-panel', 'update_translation', 'update_translation-only'])) {
                if ($this->user->can(['display_translation', 'add_translation'])) {
                    session()->put('error', trans('app.no_access'));
                    return redirect()->route('admin.languages.index');
                } else {
                    return $this->pageUnauthorized();
                }
            }

            if ($this->user->can('update_translation-only') && !$this->user->can(['access-all', 'translation', 'admin-panel', 'update_translation'])) {
                if ($this->user->can(['display_translation', 'add_translation'])) {
                    session()->put('error', trans('app.no_access'));
                    return redirect()->route('admin.languages.index');
                } else {
                    return $this->pageUnauthorized();
                }
            }
            $this->validate($request, [
//                'lang' => 'required',
                'name' => 'required',
            ]);
            $input = $request->all();
            foreach ($input as $key => $value) {
                $input[$key] = stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING)));
            }
//            $input['type'] = "lang";
            $input['lang'] = $language->lang;
            $language->update($input);
            session()->put('success', trans('app.update_success'));
            return redirect()->route('admin.languages.index');
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

        $language = Language::find($id);
        if (!empty($language)) {
            if (!$this->user->can(['access-all', 'translation', 'admin-panel', 'delete_translation', 'delete_translation-only'])) {
                if ($this->user->can(['display_translation'])) {
                    $arr = array('msg' =>__('app.delete_success'), 'status' => true);
                    return Response()->json($arr);
                } else {
                    return $this->pageUnauthorized();
                }
            }

            if ($this->user->can('delete_translation-only') && !$this->user->can(['access-all', 'translation', 'admin-panel', 'delete_translation'])) {
                if ($this->user->can(['display_translation'])) {
                    $arr = array('msg' =>__('app.delete_success'), 'status' => true);
                    return Response()->json($arr);
                } else {
                    return $this->pageUnauthorized();
                }
            }

            Language::find($id)->delete();
            $arr = array('msg' =>__('app.delete_success'), 'status' => true);
            return Response()->json($arr);
        } else {
            return $post->pageError();
        }
    }

    public function search() {

        if (!$this->user->can(['access-all', 'translation', 'admin-panel', 'display_translation', 'update_translation', 'delete_translation', 'show_translation'])) {
            return $post->pageUnauthorized();
        }

        $post_active = $post_edit = $post_create = $post_delete = $post_show = $comment_list = $comment_create = 0;

        if ($this->user->can(['access-all', 'translation', 'admin-panel'])) {
            $post_active = $post_edit = $post_create = $post_delete = $post_show = $comment_list = $comment_create = 1;
        }

        if ($this->user->can('update_translation')) {
            $post_active = $post_edit = $post_create = $post_show = $comment_list = $comment_create = 1;
        }

        if ($this->user->can('delete_translation')) {
            $post_delete = 1;
        }

        if ($this->user->can('show_translation')) {
            $post_show = 1;
        }

        if ($this->user->can('add_translation')) {
            $post_create = 1;
        }
        if ($this->user->lang == 'ar') {
            $type_action = "اللغة ";
        } else {
            $type_action = "Language";
        }
        $name = 'language';
        $data = Language::get();
        return view('admin.languages.search', compact('type_action', 'data', 'name', 'post_active', 'post_create', 'post_edit', 'post_delete', 'post_show'));
    }

}
