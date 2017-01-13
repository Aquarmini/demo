<?php
// +----------------------------------------------------------------------
// | 定时器 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lmx0536.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <http://www.lmx0536.cn>
// +----------------------------------------------------------------------
// | Date: 2017/1/13 Time: 上午9:42
// +----------------------------------------------------------------------
//每隔2000ms触发一次
$tick_id = swoole_timer_tick(2000, function ($timer_id) {
    echo "ID:{$timer_id} tick-2000ms\n";
});
//3000ms后执行此函数
swoole_timer_after(3000, function () {
    echo "after 3000ms.\n";
});

swoole_timer_after(10000, function () use ($tick_id) {
    echo "after 10000ms clear all task\n";
    swoole_timer_clear($tick_id);
});
