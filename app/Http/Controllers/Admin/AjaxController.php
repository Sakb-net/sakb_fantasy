<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\Comment;
use App\Models\Contact;
use App\Models\Search;
use App\Models\Language;
use Auth;
use App;
use Session;

class AjaxController extends AdminController {

    public function changeLanguage(Request $request) {
        if ($request->ajax()) {
            Language::ADDLanguageActive($request->locale);
            //save in table user->lang
            if (Auth::user()) {
                User::updateColum(Auth::user()->id, 'lang', $request->locale);
            }
            Session::flash('alert-success', ('app.locale_Change_Success'));
        }
//        print_r(session()->get('locale'));die;
        return response()->json(['status' => '1']);
    }
    public function ajax_get_subteam(Request $request) {
        $subteams = $cat_subteams = [];
//        if ($this->user->can(['access-all', 'user-all'])) { 
        $input = $request->all();
        if ($input['id'] != 0) {
            $category_main = Category::find($input['id']);
            $lang = 'ar';
            if (isset($category_main->lang)) {
                $lang = $category_main->lang;
            }
            $subteams_all = Category::where('type', 'subteam')->where('lang', $lang)->where('parent_id', $input['id'])->where('is_active', 1)->pluck('id', 'name')->toArray();
            if (count($subteams_all) != 0) {
                if ($this->user->lang == 'ar') {
                    $first_title = ['اختر الفريق الفرعى ' => 0];
                } else {
                    $first_title = ['Choose Ssub Team' => 0];
                }
                $subteams = array_flip(array_merge($first_title, $subteams_all));
            }
        }
//        }
        $response = view('admin.champions.ajax_get_subcategory', compact('subteams', 'cat_subteams'))->render();
//        return response()->json($response);
        return response()->json(['status' => '1', 'response' => $response]);
    }
    public function ajax_subcategoryProduct(Request $request) {
        $subcategories = $productSubcategories = [];
//        if ($this->user->can(['access-all', 'user-all'])) { 
        $input = $request->all();
        if ($input['id'] != 0) {
            $category_main = CategoryProduct::find($input['id']);
            $lang = 'ar';
            if (isset($category_main->lang)) {
                $lang = $category_main->lang;
            }
            $subcategories_all = CategoryProduct::where('type', 'sub')->where('lang', $lang)->where('parent_id', $input['id'])->where('is_active', 1)->pluck('id', 'name')->toArray();
            if (count($subcategories_all) != 0) {
                if ($this->user->lang == 'ar') {
                    $first_title = ['اختر القسم الفرعى' => 0];
                } else {
                    $first_title = ['Choose subCategory' => 0];
                }
                $subcategories = array_flip(array_merge($first_title, $subcategories_all));
            }
        }
//        }
        $response = view('admin.products.ajax_get_subcategory', compact('subcategories', 'productSubcategories'))->render();
//        return response()->json($response);
        return response()->json(['status' => '1', 'response' => $response]);
    }

    public function ajaxSubcategory(Request $request) {
        $subcategories = $postSubcategories = [];
//        if ($this->user->can(['access-all', 'user-all'])) { 
        $input = $request->all();
        if ($input['id'] != 0) {
            $category_main = Category::find($input['id']);
            $lang = 'ar';
            if (isset($category_main->lang)) {
                $lang = $category_main->lang;
            }
            $subcategories_all = Category::where('type', 'sub')->where('lang', $lang)->where('parent_id', $input['id'])->where('is_active', 1)->pluck('id', 'name')->toArray();
            if (count($subcategories_all) != 0) {
                if ($this->user->lang == 'ar') {
                    $first_title = ['اختر القسم الفرعى' => 0];
                } else {
                    $first_title = ['Choose subCategory' => 0];
                }
                $subcategories = array_flip(array_merge($first_title, $subcategories_all));
            }
        }
//        }
        $response = view('admin.posts.posts.ajax_get_subcategory', compact('subcategories', 'postSubcategories'))->render();
//        return response()->json($response);
        return response()->json(['status' => '1', 'response' => $response]);
    }

    //****************************************************************
    public function userStatus(Request $request) {
        $response = false;
        if ($this->user->can(['access-all', 'user-all'])) {
            $input = $request->all();
            if ($input['id'] != 1) {
                $user = User::find($input['id']);
                $user->is_active = $input['status'];
                $response = $user->save();
            }
        }
        return response()->json($response);
    }

    public function postStatus(Request $request) {
        $response = false;
        if ($this->user->can(['access-all', 'post-type-all', 'post-all', 'post-edit'])) {
            $input = $request->all();
            $post = new Post();
            $response = $post->updatePostActive($input['id'], $input['status']);
        }

        return response()->json($response);
    }

    public function postRead(Request $request) {
        $response = false;
        if ($this->user->can(['access-all', 'post-type-all', 'post-all', 'post-edit'])) {
            $input = $request->all();
            $post = new Post();
            $response = $post->updatePostRead($input['id']);
        }

        return response()->json($response);
    }

    public function categoryStatus(Request $request) {
        $response = false;
        if ($this->user->can(['access-all', 'post-type-all', 'post-all', 'category-all', 'category-edit'])) {
            $input = $request->all();
            $category = new Category();
            $response = $category->updateCategoryActive($input['id'], $input['status']);
        }

        return response()->json($response);
    }

    public function searchStatus(Request $request) {
        $response = false;
        if ($this->user->can(['access-all', 'post-type-all', 'post-all'])) {
            $input = $request->all();
            $search = new Search();
            $response = $search->updateSearchActive($input['id'], $input['status']);
        }

        return response()->json($response);
    }

    public function commentStatus(Request $request) {
        $response = false;
        if ($this->user->can(['access-all', 'post-type-all', 'post-all', 'comment-all', 'comment-edit'])) {
            $input = $request->all();
            $comment = new Comment();
            $response = $comment->updateCommentActive($input['id'], $input['status']);
        }

        return response()->json($response);
    }

    public function commentRead(Request $request) {
        $response = false;
        if ($this->user->can(['access-all', 'post-type-all', 'post-all', 'comment-all', 'comment-edit'])) {
            $input = $request->all();
            $comment = new Comment();
            $response = $comment->updateCommentRead($input['id']);
        }

        return response()->json($response);
    }

    public function contactRead(Request $request) {
        $response = false;
        if ($this->user->can(['access-all', 'post-type-all', 'post-all'])) {
            $input = $request->all();
            $response = Contact::updateContactRead($input['id']);
        }

        return response()->json($response);
    }

    public function contactReply(Request $request) {
        $response = false;
        if ($this->user->can(['access-all', 'post-type-all', 'post-all'])) {
            $input = $request->all();
            $contact = new Contact();
            $response = $contact->updateContactReply($input['id']);
        }

        return response()->json($response);
    }
//********************Search*********************** */

public function searchUser(Request $request) {
    $data = [];
    $user_role = $user_edit = $user_delete = $user_create = $user_access = $user_show = 0;
    if ($this->user->can(['access-all', 'user-all'])) {
        $user_role = $user_edit = $user_delete = $user_create = $user_show = 1;
    }
    if ($this->user->can(['access-all'])) {
        $user_access = 1;
    }
    if ($this->user->can('user-delete')) {
        $user_delete = 1;
    }
    if ($this->user->can('user-show')) {
        $user_show = 1;
    }
    if ($this->user->can('user-edit')) {
        $user_edit = 1;
    }
    if ($this->user->can('user-create')) {
        $user_create = 1;
    }
    if ($this->user->lang == 'ar') {
        $type_action = "العضو";
    } else {
        $type_action = "Member";
    }
    $stateType = 'user';
    $input = $request->all();
    $search = stripslashes(trim(filter_var($input['search'], FILTER_SANITIZE_STRING)));
    $data = User::get_searchUser($search, '', 0);
    $response = view('admin.layouts.search_ajax', compact('data', 'stateType', 'type_action', 'user_access', 'user_create', 'user_role', 'user_show', 'user_edit', 'user_delete'))->render();
    return response()->json(['status' => '1', 'response' => $response]);
}


}
