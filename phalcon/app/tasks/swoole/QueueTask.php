<?php
// +----------------------------------------------------------------------
// | 子线程阻塞的消息队列 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lmx0536.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <http://www.lmx0536.cn>
// +----------------------------------------------------------------------
// | Date: 2017/2/3 Time: 下午8:48
// +----------------------------------------------------------------------

namespace MyApp\Tasks\Swoole;

use Phalcon\Cli\Task;
use limx\tools\LRedis;

class QueueTask extends Task
{
    private $worker = [];

    public function mainAction()
    {
        if (!extension_loaded('swoole')) {
            echo "没有安装swoole扩展" . PHP_EOL;
            return;
        }
        $worker_num = 2;//创建的进程数
        for ($i = 0; $i < $worker_num; $i++) {
            $process = new \swoole_process(function ($worker) {
                $config = [
                    'host' => '127.0.0.1',
                    'auth' => '',
                    'port' => '6379',
                ];
                $redis = LRedis::getInstance($config);
                echo "queue begin" . PHP_EOL;
                while (true) {
                    $res = $redis->brpop('phalcon:test:queue', 30);
                    if (empty($res)) {
                        echo "30s内没有消息进入！" . PHP_EOL;
                        continue;
                    }
                    print_r($res);
                }
            });
            $pid = $process->start();
            $this->worker[] = $process;
            echo PHP_EOL . $pid;//
        }
    }
}