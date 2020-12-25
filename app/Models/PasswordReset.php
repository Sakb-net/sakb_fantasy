<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $table = 'password_resets';
    protected $primaryKey = 'email';
    public $timestamps = false;
    protected $fillable = [
        'email', 'token','created_at'
    ];

    public static function get_LastRow($email)
    {
        $data = static::where('email', $email)->orderBy('created_at', 'DESC')->first();
        return $data;
    }

    public static function get_User($email)
    {
        $data = static::where('email', $email)->get();
        return $data;
    }

    public static function get_DataEmailTime($colum,$val_colm, $check = 0,$email='', $time = 3600) {
        if(!empty($email)){
            $data = static::where('email',$email)->where($colum,$val_colm)->orderBy('created_at', 'DESC')->first();
        }else{
         $data = static::where($colum,$val_colm)->orderBy('created_at', 'DESC')->first();
        }
        if ($check == 1) {
            if (isset($data->email)) {
                $result = get_Time_restPasssword($data->created_at, $time);
                return $result;
            } else {
                return -1;
            }
        } else {
            return $data;
        }
    }

    public static function get_Date_Email($email, $date = null, $check = 0) {
        if (empty($date)) {
            $date = get_beforTime_date(3600); //2019-09-30 00:00:18
        }
        $data = static::where('email', $email)->where('created_at', '<=', $date)->orderBy('created_at', 'DESC')->first();
        if ($check == 1) {
            if (isset($data->email)) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return $data;
        }
    }
    
}