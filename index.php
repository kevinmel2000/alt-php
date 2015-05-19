<?php define('ALT_PATH', __DIR__ . DIRECTORY_SEPARATOR);

error_reporting(E_ALL ^ E_NOTICE);

require ALT_PATH . 'engine' . DIRECTORY_SEPARATOR . 'Alt.php';
spl_autoload_register(array('Alt', 'autoload'));

Alt::route('unit', 'Minerva_Master_Unit', 0);

Alt::start(array(
    'environment' => Alt::ENV_DEVELOPMENT,
));