<?php defined('ALT_PATH') or die('No direct script access.');

$data = array();
/*$db = Alt_Db::instance();
$data = $db->query('select * from master_unit');
*/
//$data = Minerva_Master_Unit::get();
$data = Minerva_Master_Unit::instance()->db->query('select * from master_unit');
return $data;
/*
switch(Alt::$output){
    case Alt::OUTPUT_HTML:
*/?><!--
        <table>
            <?php /*foreach($data as $k => $v){ */?>
                <tr>
                    <?php /*foreach($v as $k2 => $v2){ */?>
                        <td><?php /*echo $v2 */?></td>
                    <?php /*} */?>
                </tr>
            <?php /*} */?>
        </table>
--><?php
/*        break;
    default:
        return $data;
        break;
}*/