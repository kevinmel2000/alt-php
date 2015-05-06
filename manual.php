<?php

header('Content-type: application/json');

$rows = array();

$connect = mysqli_connect('localhost', 'root', '', 'bmsv2');
$result = mysqli_query($connect, 'select * from master_unit;');

while($row = $result->fetch_assoc()){
    $rows[] = $row;
}

mysqli_free_result($result);

mysqli_close($connect);

echo json_encode(array(
    's' => 200,
    't' => round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 4),
    'u' => memory_get_peak_usage(true) / 1000,
    'm' => '',
    'd' => $rows,
));