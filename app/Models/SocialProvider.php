<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialProvider extends Model {

    protected $table = 'social_providers';
//    public $timestamps = false;

    protected $fillable = ['provider_id', 'provider', 'user_id', 'is_active'];

    public function user() {
        return $this->belongsTo(\App\Models\User::class);
    }

    public static function insertProviderUser($user_id, $provider_id, $provider, $is_active = 1) {
        $user_reg = SocialProvider::create([
                    'user_id' => $user_id,
                    'provider_id' => $provider_id,
                    'provider' => $provider,
                    'is_active' => $is_active
        ]);
        return $user_reg;
    }

    public static function getProviderUser($provider_id, $provider, $is_active = 1) {
        $user_reg = SocialProvider::where('provider' , $provider)->where('provider_id' ,$provider_id)->where('is_active' , $is_active)->first();
        return $user_reg;
    }

}
