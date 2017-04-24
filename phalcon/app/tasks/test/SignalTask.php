<?php
// +----------------------------------------------------------------------
// | 测试脚本 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
declare(ticks=1);

namespace App\Tasks\Test;

use Phalcon\Cli\Task;
use limx\phalcon\Cli\Color;

class SignalTask extends Task
{
    public function mainAction()
    {
        echo Color::head('Help:'), PHP_EOL;
        echo Color::colorize('  PHP函数参数测试'), PHP_EOL, PHP_EOL;

        echo Color::head('Usage:'), PHP_EOL;
        echo Color::colorize('  php run Test\\\\Signal [action]', Color::FG_GREEN), PHP_EOL, PHP_EOL;

        echo Color::head('Actions:'), PHP_EOL;
        echo Color::colorize('  sigint        接收SIGINT信号时推出循环', Color::FG_GREEN), PHP_EOL;

    }

    public function sigintAction()
    {
        pcntl_signal(SIGINT, [$this, "signalHandler"]);

        while (true) {
            echo 'I am doing something important', PHP_EOL;
            echo 'If I am interruptted, the data will be corrupted', PHP_EOL;
            echo 'Be careful', PHP_EOL;
            echo PHP_EOL;
            sleep(3);
        }
    }

    private function signalHandler()
    {
        echo 'Caught signal SIGINT, exit', PHP_EOL;
        die();
    }
}