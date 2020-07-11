<?php

include_once './src/Holiday.php';

use nyg\Holiday;

$holiday = new holiday();
for ($year=2011;$year<=2020;$year++){
    if($year==2020){
        $num_3=10;
    }else{
        $num_3=11;
    }
    echo "{$year}法定假期数:";
    if ($holiday->count(strtotime("{$year}-1-1"), strtotime("{$year}-12-31"), Holiday::TYPE_HOLIDAY) != $num_3) {
        echo 'false';
    }else{
        echo 'true';
    }
    echo "\n";
}

//var_dump($holiday->countDate(strtotime("2011-1-1"), strtotime("2011-12-31"), Holiday::TYPE_HOLIDAY));