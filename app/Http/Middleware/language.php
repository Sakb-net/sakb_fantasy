<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App;
use Config;
use Auth;

class language {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
//    public function handle($request, Closure $next)
//    {
//        return $next($request);
//    }


    public function handle($request, Closure $next) {
        $locale = '';
        if (Auth::user()) {
            $locale = Auth::user()->lang;
        }else{
            if (empty($locale)) {
                $locale = Session::get('locale');
            } 
            if (empty($locale)&&isset($_COOKIE['locale_lang'])) {
                $locale =$_COOKIE['locale_lang'];
            } 
            if (empty($locale)) {
               $locale = Config::get('app.locale');
            }
        }
        App::setLocale($locale);
        return $next($request);
    }

}
