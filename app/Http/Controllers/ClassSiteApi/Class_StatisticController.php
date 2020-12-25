<?php
namespace App\Http\Controllers\ClassSiteApi;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Player;
use App\Models\LocationPlayer;
use App\Models\Options;
use App\Http\Resources\GameSubstitutesResource;
use App\Http\Controllers\SiteController;
//use App\Http\Controllers\ClassSiteApi\Class_NotifController;

class Class_StatisticController extends SiteController {

    public function __construct() {
        parent::__construct();
    }
    
    public static function get_data_player_public_statistics($is_active = '1', $lang='ar',$order_play='', $link_team = '', $type_key='', $offset=1, $api=0, $array=1,$limit = 20) {
        // $all_loction = LocationPlayer::get_DataAll(1);
        $array_all_data = Player::get_DataAllPlayer($is_active, $lang, $order_play, $link_team, $type_key, $offset,$api,$array,$limit);
        return $array_all_data;
    }


}