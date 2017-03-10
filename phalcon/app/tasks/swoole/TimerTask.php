<?php
// +----------------------------------------------------------------------
// | Swoole Timer 测试 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lmx0536.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <http://www.lmx0536.cn>
// +----------------------------------------------------------------------
// | Date: 2017/2/23 Time: 下午2:39
// +----------------------------------------------------------------------
namespace MyApp\Tasks\Swoole;

use Phalcon\Cli\Task;
use limx\phalcon\Cli\Color;

class TimerTask extends Task
{
    public function mainAction()
    {
        $id = swoole_timer_tick(1000, function () {
            echo Color::colorize("Hello world", Color::FG_LIGHT_BLUE) . PHP_EOL;
        });

        swoole_timer_after(10000, function () use ($id) {
            swoole_timer_clear($id);
            echo Color::colorize("Clear The Timer Id = " . $id, Color::FG_LIGHT_RED) . PHP_EOL;
        });
    }
}