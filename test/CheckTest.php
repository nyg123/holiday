<?php
include_once __DIR__ . '/../src/Holiday.php';

use Nyg\Holiday;

class CheckTest extends \PHPUnit\Framework\TestCase
{
    public function testYear2022()
    {
        $year = 2022;
        //法定节假日
        $holiday_3 = [
            '2022-01-01',
            '2022-01-31',
            '2022-02-01',
            '2022-02-02',
            '2022-04-05',
            '2022-05-01',
            '2022-06-03',
            '2022-09-10',
            '2022-10-01',
            '2022-10-02',
            '2022-10-03',
        ];
        //节假日
        $holiday_4 = [
            '2022-01-02',
            '2022-01-03',
            '2022-02-03',
            '2022-02-04',
            '2022-02-05',
            '2022-02-06',
            '2022-04-03',
            '2022-04-04',
            '2022-04-30',
            '2022-05-02',
            '2022-05-03',
            '2022-05-04',
            '2022-06-04',
            '2022-06-05',
            '2022-09-11',
            '2022-09-12',
            '2022-10-04',
            '2022-10-05',
            '2022-10-06',
            '2022-10-07',
        ];
        //上班
        $work = ['2022-01-29', '2022-01-30', '2022-04-02', '2022-04-24', '2022-05-07', '2022-10-08', '2022-10-09',];
        $holiday = new Holiday();
        foreach ($holiday_3 as $v) {
            $result = $holiday->isHoliday(strtotime($v), Holiday::TYPE_HOLIDAY);
            $this->assertTrue($result);
        }
        foreach ($holiday_4 as $v) {
            $result = $holiday->isHoliday(strtotime($v), Holiday::TYPE_VACATION);
            $this->assertTrue($result);
        }
        foreach ($work as $v) {
            $result = $holiday->isHoliday(strtotime($v));
            $this->assertFalse($result);
        }

        $day3 = $holiday->count(strtotime("{$year}-1-1"), strtotime("{$year}-12-31"), Holiday::TYPE_HOLIDAY);
        $this->assertEquals(11, $day3);
    }

    public function testYear2021()
    {
        $year = 2021;
        $holiday = new Holiday();
        $day1 = $holiday->count(strtotime("{$year}-1-1"), strtotime("{$year}-12-31"), Holiday::TYPE_ALL);
        $this->assertEquals(115, $day1);
        $day3 = $holiday->count(strtotime("{$year}-1-1"), strtotime("{$year}-12-31"), Holiday::TYPE_HOLIDAY);
        $this->assertEquals(11, $day3);
    }

    public function testYear2020()
    {
        $year = 2020;
        $holiday = new Holiday();
        $day1 = $holiday->count(strtotime("{$year}-1-1"), strtotime("{$year}-12-31"), Holiday::TYPE_ALL);
        $this->assertEquals(117, $day1);
        $day3 = $holiday->count(strtotime("{$year}-1-1"), strtotime("{$year}-12-31"), Holiday::TYPE_HOLIDAY);
        $this->assertEquals(10, $day3);
    }

    public function testYear2019()
    {
        $year = 2019;
        $holiday = new Holiday();
        $day1 = $holiday->count(strtotime("{$year}-1-1"), strtotime("{$year}-12-31"), Holiday::TYPE_ALL);
        $this->assertEquals(115, $day1);
        $day3 = $holiday->count(strtotime("{$year}-1-1"), strtotime("{$year}-12-31"), Holiday::TYPE_HOLIDAY);
        $this->assertEquals(11, $day3);
    }

    public function testYear2018()
    {
        $year = 2018;
        $holiday = new Holiday();
        $day1 = $holiday->count(strtotime("{$year}-1-1"), strtotime("{$year}-12-31"), Holiday::TYPE_ALL);
        $this->assertEquals(115, $day1);
        $day3 = $holiday->count(strtotime("{$year}-1-1"), strtotime("{$year}-12-31"), Holiday::TYPE_HOLIDAY);
        $this->assertEquals(11, $day3);
    }

    public function testYear2017()
    {
        $year = 2017;
        $holiday = new Holiday();
        $day1 = $holiday->count(strtotime("{$year}-1-1"), strtotime("{$year}-12-31"), Holiday::TYPE_ALL);
        $this->assertEquals(116, $day1);
        $day3 = $holiday->count(strtotime("{$year}-1-1"), strtotime("{$year}-12-31"), Holiday::TYPE_HOLIDAY);
        $this->assertEquals(11, $day3);
    }

    public function testYear2014()
    {
        $year = 2014;
        $holiday = new Holiday();
        $day1 = $holiday->count(strtotime("{$year}-1-1"), strtotime("{$year}-12-31"), Holiday::TYPE_ALL);
        $this->assertEquals(115, $day1);
        $day3 = $holiday->count(strtotime("{$year}-1-1"), strtotime("{$year}-12-31"), Holiday::TYPE_HOLIDAY);
        $this->assertEquals(11, $day3);
    }

}