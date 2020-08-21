<?php
include_once './src/Holiday.php';

use Nyg\Holiday;

class CheckTest extends \PHPUnit\Framework\TestCase
{
    public function testYear2020()
    {
        $year=2020;
        $holiday = new Holiday();
        $day1=$holiday->count(strtotime("{$year}-1-1"), strtotime("{$year}-12-31"), Holiday::TYPE_ALL);
        $this->assertEquals(117,$day1);
        $day3 = $holiday->count(strtotime("{$year}-1-1"), strtotime("{$year}-12-31"), Holiday::TYPE_HOLIDAY);
        $this->assertEquals(10,$day3);
    }

    public function testYear2019()
    {
        $year=2019;
        $holiday = new Holiday();
        $day1=$holiday->count(strtotime("{$year}-1-1"), strtotime("{$year}-12-31"), Holiday::TYPE_ALL);
        $this->assertEquals(115,$day1);
        $day3 = $holiday->count(strtotime("{$year}-1-1"), strtotime("{$year}-12-31"), Holiday::TYPE_HOLIDAY);
        $this->assertEquals(11,$day3);
    }

    public function testYear2018()
    {
        $year=2018;
        $holiday = new Holiday();
        $day1=$holiday->count(strtotime("{$year}-1-1"), strtotime("{$year}-12-31"), Holiday::TYPE_ALL);
        $this->assertEquals(115,$day1);
        $day3 = $holiday->count(strtotime("{$year}-1-1"), strtotime("{$year}-12-31"), Holiday::TYPE_HOLIDAY);
        $this->assertEquals(11,$day3);
    }

    public function testYear2017()
    {
        $year=2017;
        $holiday = new Holiday();
        $day1=$holiday->count(strtotime("{$year}-1-1"), strtotime("{$year}-12-31"), Holiday::TYPE_ALL);
        $this->assertEquals(116,$day1);
        $day3 = $holiday->count(strtotime("{$year}-1-1"), strtotime("{$year}-12-31"), Holiday::TYPE_HOLIDAY);
        $this->assertEquals(11,$day3);
    }

}