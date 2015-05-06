<?php defined('ALT_PATH') or die('No direct script access.');

$data = array();
$db = Alt_Db::instance();
$data = $db->query('select * from master_unit');
return $data;