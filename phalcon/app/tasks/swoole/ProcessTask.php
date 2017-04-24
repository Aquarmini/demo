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
declare(ticks=1);

namespace App\Tasks\Swoole;

use App\Utils\Redis;
use Phalcon\Cli\Task;
use limx\phalcon\Cli\Color;
use swoole_process;

class ProcessTask extends Task
{
    protected $process = 0;
    protected $result = [];
    const TEST_KEY = "phalcon:test:index";

    public function mainAction()
    {
        Redis::del(self::TEST_KEY);
        for ($i = 0; $i < 10000; $i++) {
            $arr[] = rand(1, 100);
        }
        pcntl_signal(SIGCHLD, [$this, "signalHandler"]);
        foreach (array_chunk($arr, 1000) as $data) {
            $process = new swoole_process([$this, 'task']);
            $process->write(json_encode($data));
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
        echo array_sum($arr), PHP_EOL;
        echo Redis::get(self::TEST_KEY), PHP_EOL;
    }

    public function task(swoole_process $worker)
    {
        // $recv = $worker->read();
        // $result = array_sum(json_decode($recv, true));
        // echo $result, PHP_EOL;
        // $worker->exit(0);

        swoole_event_add($worker->pipe, function ($pipe) use ($worker) {
            $recv = $worker->read();            //send data to master
            $result = array_sum(json_decode($recv, true));
            $worker->write($result);
            Redis::incrBy(self::TEST_KEY, $result);
            // echo $result, PHP_EOL;
            $worker->exit(0);
            swoole_event_del($pipe);
        });
    }

    /**
     * @desc   信号处理方法 回收已经dead的子进程
     * @author limx
     * @param $signo
     */
    private function signalHandler($signo)
    {
        switch ($signo) {
            case SIGCHLD:
                while (swoole_process::wait(false)) {
                    logger("kill deadprocess successful! ID=" . ($this->process--));
                    // echo Color::colorize("kill deadprocess successful!", Color::FG_LIGHT_RED) . PHP_EOL;
                }
        }
    }
}