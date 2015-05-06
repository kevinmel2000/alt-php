<?php defined('ALT_PATH') OR die('No direct access allowed.');

return array (
    'app_name' => 'alt',
    'session' => array(
        'lifetime' => 43200,
    ),
    'database' => array(
        'default' => array (
            'type'       => 'Mysql',
            'connection' => array(
                'hostname'   => 'localhost',
                'username'   => 'root',
                'password'   => '',
                'persistent' => FALSE,
                'database'   => 'bmsv2',
            )
        ),
    ),
);