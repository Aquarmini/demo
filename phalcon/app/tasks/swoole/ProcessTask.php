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
declare(ticks = 1);
namespace App\Tasks\Swoole;

use Phalcon\Cli\Task;
use limx\phalcon\Cli\Color;

class ProcessTask extends Task
{
    public function mainAction()
    {
        pcntl_signal(SIGCHLD, [$this, "signalHandler"]);
        for ($i = 0; $i < 10; $i++) {
            $process = new \swoole_process([$this, 'task']);
            $date = uniqid();
            $process->write($date);
            $process->start();
        }
        sleep(100);
    }

    public function task(\swoole_process $worker)
    {
        // $data = $worker->read();
        // echo Color::colorize($data, Color::FG_RED) . PHP_EOL;
        // $worker->exit(0);

        swoole_event_add($worker->pipe, function ($pipe) use ($worker) {
            $recv = $worker->read();            //send data to master
            sleep(1);
            echo Color::colorize($recv, Color::FG_LIGHT_CYAN) . PHP_EOL;
            sleep(1);
            $worker->exit(0);
            swoole_event_del($pipe);
        });
        echo Color::colorize("FINISH", COlor::FG_LIGHT_BLUE) . PHP_EOL;
    }

    /**
     * @desc 信号处理方法 回收已经dead的子进程
     * @author limx
     * @param $signo
     */
    private function signalHandler($signo)
    {
        switch ($signo) {
            case SIGCHLD:
                while ($ret = \swoole_process::wait(false)) {
                    echo Color::colorize("kill deadprocess successful!", Color::FG_LIGHT_RED) . PHP_EOL;
                }
        }
    }
}