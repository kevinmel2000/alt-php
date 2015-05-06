<?php defined('ALT_PATH') or die('No direct script access.');

class Alt_Security {


    public static function set_permission($permission){
        if ($permission !== null){
            $userdata = self::get_user_data();
            $level = $userdata->userlevel;
            if ($level == null)
                throw new Alt_Exception('Anda belum login atau session anda sudah habis!', 401);

        }
    }

    public static function check($permission){
        if ($permission == null)
            return true;
        else {
            $userdata = self::get_user_data();
            $level = (int)$userdata->userlevel;
            return (((int)$level & (int)$permission) > 0);
        }
    }

    public static function islogin() {
        $userdata = self::get_user_data();
        return isset($userdata->userlevel);
    }

    public static function generate_token($data){
        if(isset($data) && $data){
            $session = self::$config['session'];
            $data->exp = time() + $session['native']['lifetime'];
            $data->sessionid = md5(microtime());

            return Alt_Jwt::encode($data, self::$config['app_name']);
        }else{
            return '';
        }
    }

    public static function get_user_data($token = ''){
        try{
            $token = $token ?: $_REQUEST['token'];
            $userdata = Alt_Jwt::decode($token, self::$config['app_name']);
            return $userdata;
        }catch (Exception $e){
            return new stdClass();
        }
    }
}