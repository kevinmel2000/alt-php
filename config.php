<?php defined('ALT_PATH') OR die('No direct access allowed.');

return array (
    'app' => array(
        'id' => '',
        'name' => 'alt',
    ),
    'session' => array(
        'lifetime' => 43200,
    ),
    'security' => array(
        'algorithm' => MCRYPT_RIJNDAEL_256,
        'mode' => MCRYPT_MODE_CBC,
        'key' => 'tes',
    ),
    'database' => array(
        'default' => array (
            'type'       => 'Mysql',
            'connection' => array(
                'hostname'   => 'localhost',
                'username'   => 'root',
                'password'   => '',
                'persistent' => FALSE,
                'database'   => 'alt-php',
            )
        ),
    ),
);