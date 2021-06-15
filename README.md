# 这是一个查询节假日和农历扩展包

## 准确度说明
1，目前包里已包含2001年至2050年的节假日信息，农历节气可查询1970年-2050年的，节假日可查询2011年-2050年（自动跟随政府公布的信息更新）

2，查询当前时间和之前的节假日信息，会走缓存的数据信息，因为已经过去的假期是已经固定的

3，查询未来的节假日，如果缓存的数据超过3天（即使是临时性放假，也至少会提前3天公布吧），会自动从百度拉取最新的假期信息，如果拉取失败会继续走缓存

4，查询未来的节假日，如果没有缓存，会自动从百度拉取最新的假期信息，如果拉取失败会抛出异常

5，对于要求比较高的，可以先强制刷新数据再查询
## 使用方法
### 安装扩展
> composer require nyg/holiday

#### 使用方法
###### 1,判断当前日期是否是节假日
``` php
use nyg\Holiday;

$holiday=new holiday();

$result = $holiday->isHoliday(time());

```
返回结果 true 或 false,此函数有第二个参数type，可选值为  
TYPE_ALL = 1; //全部节假日 包括周末 默认  
TYPE_WEENEND = 2; //普通的周末，2倍工资的那种  
TYPE_HOLIDAY = 3; //只包含节日，3倍工资的那种，节日当天  
TYPE_VACATION = 4; //节假日，节日+节日前后调休的部分

如果只判断节假日，不考虑周末
```
$result = $holiday->isHoliday(time(),Holiday::TYPE_VACATION);
```
###### 2,获取时间段内假期天数
```
$result = $holiday->count(strtotime('2020-1-1'),strtotime('2020-12-30'),Holiday::TYPE_VACATION);
// 返回30
```
###### 3,获取时间段内假期列表
```
$result = $holiday->countDate(strtotime('2020-1-1'),strtotime('2020-3-1'),Holiday::TYPE_VACATION);
//返回数组
["2020-01-01","2020-01-24","2020-01-25","2020-01-26","2020-01-27","2020-01-28","2020-01-29","2020-01-30","2020-01-31","2020-02-01","2020-02-02"]
```

###### 4,节假日名称
```
$result = $holiday->nowHoliday(strtotime('2020-1-1'));
// 元旦节
$result = $holiday->nowHoliday(strtotime('2020-1-24'));
// 春节
$result = $holiday->nowHoliday(strtotime('2020-2-1'));
// 春节
$result = $holiday->nowHoliday(strtotime('2020-10-1'));
// 国庆节 当节假日重复时，以阳历节假日优先
```
###### 5,获取农历日历
```
$result = $holiday->getLunar(strtotime('2020-10-1'));
// 返回数组 
[
    "year" => "2020",
    "month" => "8",
    "day" => "15",
    "lmonth" => "八",
    "ldate" => "十五",
    "gz_year" => "庚子",
    "gz_month" => "乙酉",
    "gz_date" => "丁丑",
]
```

###### 6，获取节气
```
$result = $holiday->term(strtotime('2020-5-20'));
// 小满
```

###### 7，刷新数据
```
$result = $holiday->update(strtotime('2020-5-20'));
// 强制刷新当前月份及前后1个月的数据 返回逻辑值
```


