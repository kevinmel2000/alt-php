<?php defined('ALT_PATH') or die('No direct script access.');

Alt::$output = Alt::OUTPUT_HTML;

$data = array(
    array(
        1,
        'Hello'
    ),
    array(
        2,
        'World'
    ),
);

return '<h1>Test</h1>' . json_encode($data);