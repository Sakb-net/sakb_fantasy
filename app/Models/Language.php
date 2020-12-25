<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;
use Config;
use App\Models\User;
use Auth;
use App;

class Language extends Model {

    protected $table = 'languages';
    protected $fillable = [
        'name', 'lang', 'user_id', 'is_active','is_default',
    ];

    public function user() {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function insertLanguage($user_id, $name, $lang, $is_active = 1) {
        $this->user_id = $user_id;
        $this->name = $name;
        $this->lang = $lang;
        $this->is_active = $is_active;
        return $this->save();
    }

    public static function updateLanguage($id, $name, $lang, $is_active = 1) {
        $language = static::findOrFail($id);
        $language->name = $name;
        $language->lang = $lang;
        $language->is_active = $is_active;
        return $language->save();
    }

    public static function foundLink($lang) {
        $lang_found = static::where('lang', $lang)->first();
        if (isset($lang_found)) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function updateLanguageLang($id, $lang) {
        $language = static::findOrFail($id);
        $language->lang = $lang;
        return $language->save();
    }

    public static function updateLanguageActive($id, $is_active = 1) {
        $language = static::findOrFail($id);
        $language->is_active = $is_active;
        return $language->save();
    }

    public static function ADDLanguageActive($lang) {
        setcookie('locale_lang',$lang, time() + (86400 * 10), "/");
        Session::put('locale', $lang);
        Session::save();
        App::setLocale($lang);
        return true;
    }
    public static function GetDefaultLang($is_active = 1,$is_default = 1) {
        $lang_key='ar';
        $language = static::where('is_active',$is_active)->where('is_default',$is_default)->first();
        if(isset($language->id)){
            $lang_key=$language->lang;
        }
        return $lang_key;
    }

    public static function currentLang($ask = 1) {
        $cuRRlocal='';
        if (Auth::user()) {
            $cuRRlocal = Auth::user()->lang;
        }else{
            if (empty($cuRRlocal)) {
                $cuRRlocal = Session::get('locale');
            } 
            if (empty($cuRRlocal)&&isset($_COOKIE['locale_lang'])) {
                $cuRRlocal =$_COOKIE['locale_lang'];
            } 
            if (empty($cuRRlocal)) {
               $cuRRlocal = Config::get('app.locale');
            }
        } 
        //***************Note: delete this condition when join post with lang*************
        // if ($ask == 1) {
        //     $cuRRlocal = 'ar';
        // }
        //***********************************
        App::setLocale($cuRRlocal);
        return $cuRRlocal;
    }

    public static function get_Languag($colum='is_active', $columValue=1, $valueArray = '', $is_array = 0) {
        $data = Language::where($colum, $columValue)->orderBy('is_default', 'DESC');
        if ($is_array == 1) {
            $language =$data->pluck($valueArray,'id')->toArray();
        } else {
            $language = $data->get();
        }
        return $language;
    }
    
    public static function get_AllLanguagExceptLang($lang, $colum, $columValue, $valueArray = '', $is_array = 0)
    {
        $data = Language::where('lang', '<>', $lang)->where($colum, $columValue)->orderBy('is_default', 'DESC');
        if ($is_array == 1) {
            $language = $data->pluck($valueArray, 'id')->toArray();
        } else {
            $language = $data->get();
        }
        return $language;
    }

    public static function SearchLanguage($search, $is_active = '', $limit = 0) {
        $data = static::Where('name', 'like', '%' . $search . '%')
                ->orWhere('lang', 'like', '%' . $search . '%')
                ->orWhere('user_id', 'like', '%' . $search . '%');
        if (!empty($is_active)) {
            $result = $data->where('is_active', $is_active);
        }
        if ($limit > 0) {
            $result = $data->paginate($limit);
        } elseif ($limit == -1) {
            $result = $data->pluck('id', 'id')->toArray();
        } else {
            $result = $data->get();
        }
        return $result;
    }

}
