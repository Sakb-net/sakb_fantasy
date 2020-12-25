<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\RoleUser;
use App\Models\SocialProvider;

class SocialLoginRepository
{

    public function __construct()
    {
    	$this->user_role = 2;	
        $this->response['StatusCode'] = 0;
        $this->response['Message'] = trans('app.SUCCESS_MESSAGE');
    }

    public function SocialLogin($provider,$userSocial,$device_id=null,$fcm_token=null,$display_name=null,$reg_site='ios',$best_team=null,$lang='ar',$api=0)
	{	$state_login=1; 
		$requestemail = $userSocial->getEmail();
        if (empty($requestemail)) {
            $requestemail= $userSocial->getId().'@baims.com';
        }
        $display_name=$this->GetSocialName($userSocial,$display_name);
		$socialprovider = SocialProvider::fetch_ProviderUser($userSocial->getId(),$provider,1);
    	$user_reg = User::where('email', $requestemail)->first();
        if (isset($user_reg->id)) {
            if (empty($user_reg->display_name)) {
                $user_reg->display_name=$display_name;
                $user_reg->image=generateDefaultImage($display_name);
                User::updateColumTwo($user_reg->id,'display_name', $display_name,'image', $user_reg->image);
            }    
        } else {
            $state_login=0;
            //create user and provider
            $user_reg = User::addCreate('', '', $display_name, $requestemail,'Mobile_password_change',null, $fcm_token,$device_id,$reg_site,null,null,null,$best_team,$userSocial->getAvatar());
            //send email
            $sen_email = User::SendEmailTOUser($user_reg['id'], 'register');
        }
        if (!$socialprovider) {
            //create provider
            $user_reg->socialproviders()->create(
                    ['provider_id' => $userSocial->getId(), 'provider' => $provider]
                );
        }
        Auth()->login($user_reg);
       	$user = User::SelectCoulumUser($user_reg,$lang,$api);
        $user['new_fcm_token'] = $fcm_token;
        if($state_login==1){
            $user['access_token']=$this->getTokenAndUpdate($user_reg,$device_id,$fcm_token);
        }
        $this->response['data'] =$user;
        return $this->response;
    }

    function GetSocialName($userSocial,$display_name){
        if(empty($display_name)){
            $display_name=$userSocial->getName();
            if(empty($display_name)){
                $requestemail = $userSocial->getEmail();
                $array_email=explode('@', $requestemail);
                if(isset($array_email[0])){
                    $display_name=$array_email[0]; 
                }
            }
        }
        return $display_name;
    }

    function getTokenAndUpdate($user,$device_id,$fcm_token){
        $access_token = generateRandomValue(1); 
        User::updateColumTwo($user->id, 'access_token', $access_token, 'fcm_token', $fcm_token);
        return $access_token;
    }
}