<?php
// +----------------------------------------------------------------------
// | 测试脚本 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Tasks\Test;

use Phalcon\Cli\Task;
use limx\phalcon\Cli\Color;

class DateTask extends Task
{
    public function mainAction()
    {
        echo Color::head('Help:'), PHP_EOL;
        echo Color::colorize('  PHP日期函数测试'), PHP_EOL, PHP_EOL;

        echo Color::head('Usage:'), PHP_EOL;
        echo Color::colorize('  php run Test\\\\Date [action]', Color::FG_GREEN), PHP_EOL, PHP_EOL;

        echo Color::head('Actions:'), PHP_EOL;
        echo Color::colorize('  format              format测试', Color::FG_GREEN), PHP_EOL;
    }

    public function formatAction()
    {
        $today = date("Y-m-d");
        echo Color::colorize("今天：" . $today, Color::FG_LIGHT_CYAN), PHP_EOL;
        $tomorrow = date("Y-m-d", strtotime("+1 days", strtotime($today)));
        echo Color::colorize("明天：" . $tomorrow, Color::FG_LIGHT_CYAN), PHP_EOL;
        $t2 = date("Y-m-d", strtotime("+1 days", strtotime($tomorrow)));
        echo Color::colorize("后天：" . $t2, Color::FG_LIGHT_CYAN), PHP_EOL;

    }

}