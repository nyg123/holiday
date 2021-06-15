<?php

include_once './src/Holiday.php';

use nyg\Holiday;

$holiday = new holiday();
for ($year=2011;$year<=2021;$year++){
    if($year==2020){
        $holiday_num=10;
    }else{
        $holiday_num=11;
    }
    echo "{$year}法定假期数:";
    if ($holiday->count(strtotime("{$year}-1-1"), strtotime("{$year}-12-31"), Holiday::TYPE_HOLIDAY) != $holiday_num) {
        echo 'false';
    }else{
        echo 'true';
    }
    echo "\n";
}

//var_dump($holiday->countDate(strtotime("2011-1-1"), strtotime("2011-12-31"), Holiday::TYPE_HOLIDAY));