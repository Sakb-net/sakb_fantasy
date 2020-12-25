<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Team;
class TeamUser extends Model {

    protected $table = 'team_users';
    protected $fillable = [
        'team_id', 'user_id','is_notif','is_email','is_sms'
    ];

    public function insertTeamUser($team_id, $user_id) {
        $this->team_id = $team_id;
        $this->user_id = $user_id;
        return $this->save();
    }

    public static function deleteTeamUser($user_id, $team_id) {
        return self::where('user_id', $user_id)->where('team_id', $team_id)->delete();
    }

    public static function deleteUser($user_id) {
        return self::where('user_id', $user_id)->delete();
    }

    public static function deleteTeam($team_id) {
        return self::where('team_id', $team_id)->delete();
    }

    public static function foundTeamUser($user_id, $team_id) {

        $data = self::where('user_id', $user_id)->where('team_id', $team_id)->first();
        if (isset($data)) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function getColume($colum, $columValue, $columReturn) {
        $data = static::where($colum, $columValue)->first();
        if (isset($data->$columReturn)) {
            $result = $data->$columReturn;
        } else {
            $result = '';
        }
        return $result;
    }

    public static function selectFollowingTeams($userId) {
        $data = static :: select('team_id')->where('user_id',$userId)->pluck('team_id')->toArray();
        return $data;
    }


    public static function addFollowTeams($user,$teams) {

            foreach($teams as $value){
                $teamId = Team::where('link', $value->link)->select('id')->first();
                $input['user_id'] = $user;
                $input['team_id'] = $teamId->id;
                $input['is_notif'] = $value->is_notif;
                $input['is_email'] = $value->is_email;
                static::create($input);
                }
            }

    }