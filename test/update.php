<?php

include_once './src/Holiday.php';

use nyg\Holiday;

$holiday = new holiday();
$start=strtotime('2000-1-1');
for ($i = 1; $i <= 12 * 30; $i++) {
    echo date("Y-m-d",$start + 86400 * 30 * $i);
    $result = $holiday->update($start + 86400 * 30 * $i);
    echo ":{$result}\n";
}