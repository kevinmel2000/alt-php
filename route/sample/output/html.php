<?php defined('ALT_PATH') or die('No direct script access.');

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

?>

<table>
    <?php foreach($data as $k => $v){ ?>
        <tr>
            <?php foreach($v as $k2 => $v2){ ?>
                <td><?php echo $v2 ?></td>
            <?php } ?>
        </tr>
    <?php } ?>
</table>