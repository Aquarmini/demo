<?php
// +----------------------------------------------------------------------
// | 主线程阻塞的消息队列 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lmx0536.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <http://www.lmx0536.cn>
// +----------------------------------------------------------------------
// | Date: 2017/2/4 Time: 上午10:00
// +----------------------------------------------------------------------
declare(ticks=1);

namespace App\Tasks\Swoole;

use Phalcon\Cli\Task;
use limx\phalcon\Redis;

class Queue2Task extends Task
{
    private $maxProcesses = 500;
    private $child = 0;
    private $close = 0;
    private $redis_queue = 'phalcon:test:queue';
    const TEST_TASK = 5000;

    public function mainAction()
    {
        if (!extension_loaded('swoole')) {
            echo "没有安装swoole扩展" . PHP_EOL;
            return;
        }
        // install signal handler for dead kids
        pcntl_signal(SIGCHLD, [$this, "sig_handler"]);
        set_time_limit(0);
        // ini_set('default_socket_timeout', -1); //队列处理不超时,解决redis报错:read error on connection

        while (true) {
            echo "当前进程数:", $this->child, " SIGCHLD次数:", $this->close, PHP_EOL;
            if ($this->child < $this->maxProcesses) {
                $rds = $this->redis_client();
                $data_pop = $rds->brpop($this->redis_queue, 3);//无任务时,阻塞等待
                if (!$data_pop) {
                    continue;
                }
                echo "开始任务-当前进程数：", $this->child, PHP_EOL;
                $this->child++;
                if ($data_pop[0] != $this->redis_queue) {
                    // 消息队列KEY值不匹配
                    continue;
                }
                if (isset($data_pop[1])) {
                    $process = new \swoole_process([$this, 'process']);
                    $process->write($data_pop[1]);
                    $pid = $process->start();
                    if ($pid === false) {
                        $rds->lpush($this->redis_queue, $data_pop[1]);
                    }
                }
            } else {
                sleep(1);
            }
        }
    }

    public function testAction()
    {
        $redis = $this->redis_client();
        for ($i = 0; $i < self::TEST_TASK; $i++) {
            $data = [
                'abc' => $i,
                'timestamp' => time() . rand(100, 999)
            ];
            $redis->lpush($this->redis_queue, json_encode($data));
        }
    }

    /**
     * [process desc]
     * @desc   子进程
     * @author limx
     * @param \swoole_process $worker
     */
    public function process(\swoole_process $worker)
    {
        swoole_event_add($worker->pipe, function ($pipe) use ($worker) {
            $recv = $worker->read();            //send data to master
            sleep(1);
            echo "数据包: ", $recv . PHP_EOL;
            $worker->exit(0);
            swoole_event_del($pipe);
        });

        // $recv = $worker->read();
        // sleep(1);
        // echo "数据包: ", $recv . PHP_EOL;
        // $worker->exit(0);
    }


    private function redis_client()
    {
        return Redis::getInstance('127.0.0.1', '910123');
    }

    private function sig_handler($signo)
    {
        switch ($signo) {
            case SIGCHLD:
                while ($ret = \swoole_process::wait(false)) {
                    //echo "PID={$ret['pid']}\n";
                    $this->child--;
                    $this->close++;
                }
        }
    }
}