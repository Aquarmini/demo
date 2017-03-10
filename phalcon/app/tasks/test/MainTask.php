<?php
// +----------------------------------------------------------------------
// | 菜单 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lmx0536.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <http://www.lmx0536.cn>
// +----------------------------------------------------------------------
// | Date: 2017/3/6 Time: 上午8:13
// +----------------------------------------------------------------------
namespace MyApp\Tasks\Test;

use Phalcon\Cli\Task;
use limx\phalcon\Cli\Color;

class MainTask extends Task
{
    public function mainAction()
    {
        echo Color::head('Help:') . PHP_EOL;
        echo Color::colorize('  测试脚本列表') . PHP_EOL . PHP_EOL;

        echo Color::head('Usage:') . PHP_EOL;
        echo Color::colorize('  php run Test\\\\[task]', Color::FG_GREEN) . PHP_EOL . PHP_EOL;
        echo Color::head('Tasks:') . PHP_EOL;
        echo Color::colorize('  Arg                     php函数参数相关测试', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  Array                   array函数相关测试', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  Bitwise                 位运算相关测试', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  Curl                    Curl函数相关测试', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  OAuth                   OAuth函数相关测试', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  Ymal                    Ymal函数相关测试', Color::FG_GREEN) . PHP_EOL;


    }

}