<?php

namespace Nyg;

class Holiday
{
    const TYPE_ALL = 1; //全部节假日 包括周末
    const TYPE_WEENEND = 2; //普通的周末，2倍工资的那种
    const TYPE_HOLIDAY = 3; //只包含节日，3倍工资的那种，节日当天
    const TYPE_VACATION = 4; //节假日，节日+节日前后调休的部分
    private $data;

    /**
     * 获取时间段内假期天数
     * @param int $start 开始时间戳
     * @param int $end 结束时间戳
     * @param int $type 假期类型
     * @return int 假期天数
     * @throws \Exception
     * @author 牛永光 nyg1991@aliyun.com
     */
    public function count($start, $end, $type = self::TYPE_ALL)
    {
        return count($this->countDate($start, $end, $type));
    }

    /**
     * 获取时间段内所有假期日期
     * @param int $start 开始时间戳
     * @param int $end 结束时间戳
     * @param int $type 假期类型
     * @return array
     * @throws \Exception
     * @author 牛永光 nyg1991@aliyun.com
     */
    public function countDate($start, $end, $type = self::TYPE_ALL)
    {
        $data = [];
        $start = strtotime(date('Y-m-d', $start));
        $end = strtotime(date('Y-m-d', $end));
        for ($i = $start; $i <= $end; $i += 86400) {
            if ($this->isHoliday($i, $type)) {
                $data[] = date('Y-m-d', $i);
            }
        }
        return $data;
    }

    /**
     * 判断是否是节假日
     * @param int $time 时间戳
     * @param int $type
     * @return bool
     * @throws \Exception
     * @author 牛永光 nyg1991@aliyun.com
     */
    public function isHoliday($time, $type = self::TYPE_ALL)
    {
        $day = $this->getDay($time);
        //节假日判断
        if (isset($day['status']) && $day['status'] == 1) { //节假日
            if ($type == self::TYPE_VACATION) {
                return true;
            }
        }
        if ($this->holidayName($day) != '' && ($type == self::TYPE_ALL || $type == self::TYPE_HOLIDAY)) {
            return true;
        }
        $week = in_array(date('N', $time), [6, 7]);
        if ($week && (!isset($day['status']) || $day['status'] != 2)) { //1,周末未补班
            if ($type == self::TYPE_ALL || $type == self::TYPE_WEENEND) {
                return true;
            }
        }
        if (isset($day['status']) && $day['status'] == 1) {//2,非周末调休
            if ($type == self::TYPE_ALL || $type == self::TYPE_WEENEND) {
                return true;
            }
        }
        return false;
    }

    /**
     * 获取节气
     * @param int $time 时间戳
     * @return string
     * @throws \Exception
     * @author 牛永光 nyg1991@aliyun.com
     */
    public function term($time)
    {
        $day = $this->getDay($time);
        return $day['term'];
    }

    /**
     * 获取农历
     * @param int $time 时间戳
     * @return array year 农历年 month 农历月 day 农历日 lmonth 中文月 ldate 中文日 gz_*** 庚子年月日
     * @throws \Exception
     * @author 牛永光 nyg1991@aliyun.com
     * @date 2020/5/20 16:52
     */
    public function getLunar($time)
    {
        $day = $this->getDay($time);
        return [
            'year' => $day['lunarYear'],
            'month' => $day['lunarMonth'],
            'day' => $day['lunarDate'],
            'lmonth' => $day['lMonth'],
            'ldate' => $day['lDate'],
            'gz_year' => $day['gzYear'],
            'gz_month' => $day['gzMonth'],
            'gz_date' => $day['gzDate'],
        ];
    }

    /**
     * 获取当前所在节假日，为空则不在节假日
     * @param int $time 时间戳
     * @return string
     * @throws \Exception
     * @author 牛永光 nyg1991@aliyun.com
     */
    public function nowHoliday($time)
    {
        $before = $time;
        while ($this->isHoliday($before)) {
            $day = $this->getDay($before);
            if ($this->holidayName($day) != '') {
                return $this->holidayName($day);
            }
            $before -= 86400;
        }
        $after = $time + 86400;
        while ($this->isHoliday($after)) {
            $day = $this->getDay($after);
            if ($this->holidayName($day) != '') {
                return $this->holidayName($day);
            }
            $before += 86400;
        }
        return '';
    }

    /**
     * 刷新数据
     * @param int $time 时间戳
     * @return bool
     * @author 牛永光 nyg1991@aliyun.com
     * @date 2020/5/20 18:17
     */
    public function update($time){
        return $this->getNewData($time);
    }

    /**
     * 获取日数据
     * @param $time
     * @return mixed
     * @throws \Exception
     * @author 牛永光 nyg1991@aliyun.com
     */
    private function getDay($time)
    {
        $month = $this->getMonth($time);
        $day = $month[date('j', $time)];
        return $day;
    }

    /**
     * 获取月数据
     * @param $time
     * @return bool
     * @throws \Exception
     * @author 牛永光 nyg1991@aliyun.com
     */
    private function getMonth($time)
    {
        $key = date('Ym', $time);
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
        //去文件中查文件是否存在
        $file = __DIR__ . '/../cache/' . $key . ".json";
        if (!file_exists($file)) { //文件不存在
            $this->getNewData($time);
            if (isset($this->data[$key])) {
                return $this->data[$key];
            }
        }
        if (time() - filemtime($file) > 259200 && $time > time()) { //获取未来的节假日，超过3天，更新数据
            $this->getNewData($time);
            if (isset($this->data[$key])) {
                return $this->data[$key];
            }
        }
        if (file_exists($file)) {
            $data = file_get_contents($file);
            $this->data[$key] = json_decode($data, true);
            return $this->data[$key];
        }
        throw new \Exception("获取时间失败！");
    }

    /**
     * 获取并生成最新的数据json
     * @param int $time
     * @return bool
     * @author 牛永光 nyg1991@aliyun.com
     */
    private function getNewData($time)
    {
        $time = date('Y年n月', $time);
        $url = "http://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?query={$time}&co=&resource_id=39043&ie=utf8&format=json&tn=wisetpl";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_TIMEOUT_MS, 500);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($curl);
        if (curl_error($curl)) {
            return false;
        }
        // 显示错误信息
        curl_close($curl);
        $data = iconv('GBK', 'UTF-8', $data);
        $json = json_decode($data, true);
        if (!isset($json['status']) || $json['status'] != 0) {
            return false;
        }
        $result = [];
        foreach ($json['data'][0]['almanac'] as $v) {
            $result[$v['year'] . ($v['month'] > 9 ? $v['month'] : '0' . $v['month'])][$v['day']] = $v;
        }
        foreach ($result as $k => $v) {
            $tmp = [];
            foreach ($v as $v2) {
                $tmp[$v2['day']] = $v2;
            }
            $this->data[$k] = $tmp;
            file_put_contents(__DIR__ . '/../cache/' . $k . ".json", json_encode($tmp));
        }
        return true;
    }

    /**
     * 获取节日名称
     * @param $day
     * @return string
     * @author 牛永光 nyg1991@aliyun.com
     */
    private function holidayName($day)
    {
        if (!isset($day['status']) || $day['status'] != 1) {
            return '';
        }
        $time = $this->dayToTimestamp($day);
        $name = '';
        switch (date('md', $time)) {
            case '0101':
                $name = '元旦节';
                break;
            case '0501':
                $name = '劳动节';
                break;
            case '1001':
                $name = '国庆节';
                break;
        }
        if (isset($day['value'])) {
            switch ($day['value']) {
                case '除夕':
                case '春节':
                    $name = '春节';
                    break;
                case '端午节':
                    $name = '端午节';
                    break;
                case '中秋节':
                    $name = '中秋节';
                    break;
            }
        }
        if ($day['lMonth'] == '正' && $day['lDate'] == '初二') {//正月初二属于中国假期
            $name = '春节';
        }
        if ($day['term'] == '清明') { //中国节气
            $name = '清明节';
        }
        return $name;
    }

    /**
     * 日期转时间戳
     * @param $day
     * @return false|int
     * @author 牛永光 nyg1991@aliyun.com
     * @date 2020/5/20 15:50
     */
    private function dayToTimestamp($day)
    {
        return strtotime($day['year'] . '-' . $day['month'] . '-' . $day['day']);
    }
}