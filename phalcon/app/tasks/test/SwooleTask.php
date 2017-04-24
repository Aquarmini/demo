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
use swoole_process;

class SwooleTask extends Task
{
    protected $process = 0;

    public function mainAction()
    {
        echo Color::head('Help:'), PHP_EOL;
        echo Color::colorize('  Swoole相关测试'), PHP_EOL, PHP_EOL;

        echo Color::head('Usage:'), PHP_EOL;
        echo Color::colorize('  php run Test\\\\Swoole [action]', Color::FG_GREEN), PHP_EOL, PHP_EOL;

        echo Color::head('Actions:'), PHP_EOL;
        echo Color::colorize('  check               检验是否有Swoole扩展', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  processSimple       简单的进程测试', Color::FG_GREEN), PHP_EOL;

    }

    public function processSimpleAction()
    {
        pcntl_signal(SIGCHLD, [$this, "signalHandler"]);
        for ($i = 0; $i < 10; $i++) {
            $process = new swoole_process([$this, 'taskSimple']);
            $process->write(uniqid());
            if ($process->start()) {
                $this->process++;
            }
        }
        while (true) {
            // echo $this->process, PHP_EOL;
            if ($this->process <= 0) {
                break;
            }
            sleep(3);
        }
    }

    public function checkAction()
    {
        if (!$this->checkSwooleExtension()) {
            echo Color::error('The swoole extension is not installed!');
            return;
        }
        echo Color::success('The swoole extension is installed!');
    }

    private function checkSwooleExtension()
    {
        if (!extension_loaded('swoole')) {
            return false;
        }
        return true;
    }

    public function taskSimple(swoole_process $worker)
    {
        swoole_event_add($worker->pipe, function ($pipe) use ($worker) {
            $recv = $worker->read();            //send data to master
            sleep(1);
            echo Color::colorize($recv, Color::FG_GREEN) . PHP_EOL;
            sleep(1);
            $worker->exit(0);
            swoole_event_del($pipe);
        });
        echo Color::colorize("taskSimple FINISH"), PHP_EOL;
    }

    private function signalHandler($signo)
    {
        switch ($signo) {
            case SIGCHLD:
                while (swoole_process::wait(false)) {
                    logger("kill deadprocess successful! ID=" . ($this->process--));
                    echo Color::colorize("kill deadprocess successful!", Color::FG_LIGHT_RED) . PHP_EOL;
                }
        }
    }


}