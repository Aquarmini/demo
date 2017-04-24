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
    protected $workers = [];

    public function mainAction()
    {
        echo Color::head('Help:'), PHP_EOL;
        echo Color::colorize('  Swoole相关测试'), PHP_EOL, PHP_EOL;

        echo Color::head('Usage:'), PHP_EOL;
        echo Color::colorize('  php run Test\\\\Swoole [action]', Color::FG_GREEN), PHP_EOL, PHP_EOL;

        echo Color::head('Actions:'), PHP_EOL;
        echo Color::colorize('  check               检验是否有Swoole扩展', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  processSimple       简单的进程测试', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  processAdd          子进程计算父进程总结测试', Color::FG_GREEN), PHP_EOL;

    }

    public function processAddAction()
    {
        $arr = [];
        for ($i = 0; $i < 10000; $i++) {
            $arr[] = rand(1, 1000);
        }
        $btime = microtime(true);
        pcntl_signal(SIGCHLD, [$this, "signalHandler"]);
        foreach (array_chunk($arr, 500) as $res) {
            $process = new swoole_process([$this, 'taskAdd']);
            $process->write(serialize($res));
            if ($process->start()) {
                $this->workers[] = $process;
                $this->process++;
            }
        }
        $result = 0;
        foreach ($this->workers as $worker) {
            $result += $worker->read();
        }
        $etime = microtime(true);
        echo Color::colorize("结果：" . $result, Color::FG_GREEN), PHP_EOL;
        echo Color::colorize("耗时：" . ($etime - $btime), Color::FG_GREEN), PHP_EOL;
        $result = 0;
        $btime = microtime(true);
        foreach ($arr as $num) {
            $result += $num;
        }
        $etime = microtime(true);
        echo Color::colorize("结果：" . $result, Color::FG_GREEN), PHP_EOL;
        echo Color::colorize("耗时：" . ($etime - $btime), Color::FG_GREEN), PHP_EOL;
    }

    public function taskAdd(swoole_process $worker)
    {
        swoole_event_add($worker->pipe, function ($pipe) use ($worker) {
            $recv = $worker->read();
            echo $recv;
            $data = unserialize($recv);
            $result = 0;
            foreach ($data as $num) {
                $result += $num;
            }
            $worker->write($result);
            $worker->exit(0);
            swoole_event_del($pipe);
        });
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

    private function signalHandler($signo)
    {
        switch ($signo) {
            case SIGCHLD:
                while (swoole_process::wait(false)) {
                    logger("kill deadprocess successful! ID=" . ($this->process--));
                    //echo Color::colorize("kill deadprocess successful!", Color::FG_LIGHT_RED) . PHP_EOL;
                }
        }
    }


}