<?php
// +----------------------------------------------------------------------
// | 测试脚本 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace MyApp\Tasks\Test;

use limx\phalcon\Utils\Str;
use Phalcon\Cli\Task;
use limx\phalcon\Cli\Color;

class StrTask extends Task
{
    public function mainAction()
    {
        echo Color::head('Help:') . PHP_EOL;
        echo Color::colorize('  PHP函数参数测试') . PHP_EOL . PHP_EOL;

        echo Color::head('Usage:') . PHP_EOL;
        echo Color::colorize('  php run Test\\\\Str [action]', Color::FG_GREEN) . PHP_EOL . PHP_EOL;

        echo Color::head('Actions:') . PHP_EOL;
        echo Color::colorize('  random      {$1}        随机字符串', Color::FG_GREEN) . PHP_EOL;
    }

    public function randomAction($params)
    {
        $num = rand(1, 10);
        if (count($params) > 0) {
            $num = intval($params[0]);
        }
        echo Color::colorize(Str::random($num), Color::FG_GREEN) . PHP_EOL;
    }
}