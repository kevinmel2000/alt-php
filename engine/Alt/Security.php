<?php defined('ALT_PATH') or die('No direct script access.');

class Alt_Security {
    public static function set_permission($permission){
        if ($permission !== null){
            $userdata = Alt::get_user_data();
            $level = $userdata->userlevel;

            if ($level == null || ($permission == 0 && !self::islogin()))
                throw new Alt_Exception('Anda belum login atau session anda sudah habis!', Alt::STATUS_UNAUTHORIZED);
            if (!self::check($permission))
                throw new Alt_Exception('Anda tidak berhak mengakses!', Alt::STATUS_FORBIDDEN);
        }
    }

    public static function check($permission){
        if ($permission == null)
            return true;
        else {
            $userdata = Alt::get_user_data();
            $level = (int)$userdata->userlevel;
            return (((int)$level & (int)$permission) > 0);
        }
    }

    public static function islogin() {
        $userdata = Alt::get_user_data();
        return isset($userdata->userlevel);
    }
    
}