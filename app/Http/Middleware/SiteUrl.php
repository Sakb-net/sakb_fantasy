<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
// use DB;
// use Illuminate\Support\Facades\Auth;

class SiteUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    
    
   public function handle($request, Closure $next)
   {
        $redirect_url='';
        $server_name=$_SERVER['SERVER_NAME'];
        if($server_name=='sakbfantasy.sakb-co.com.sa'){
            $current_url=\Request::url();
            if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
                $redirect_url='';
            }else{
                $current_url=$redirect_url= "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            }
            $array_public= explode('/public', $current_url);
            if(count($array_public)==2){
                $redirect_url='https://'.$server_name.$array_public[1];
            }
        }
        if(!empty($redirect_url)){
            return redirect($redirect_url);
        }else{
            return $next($request);
        }

        
   }
    


}
