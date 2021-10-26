<?php

include_once __DIR__ . '/../src/Holiday.php';

use nyg\Holiday;

$holiday = new holiday();
$start = time();
for ($i = 1; $i <= 12 * 30; $i++) {
    echo date("Y-m-d", $start + 86400 * 30 * $i);
    $result = $holiday->update($start + 86400 * 30 * $i);
    echo ":{$result}\n";
}