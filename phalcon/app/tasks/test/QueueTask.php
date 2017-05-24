<?php
// +----------------------------------------------------------------------
// | 子线程阻塞的消息队列 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lmx0536.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <http://www.lmx0536.cn>
// +----------------------------------------------------------------------

namespace App\Tasks\Test;

use App\Utils\Log;
use limx\phalcon\Redis;
use limx\phalcon\Cli\Color;

class QueueTask extends \App\Tasks\System\QueueTask
{
    // 最大进程数
    protected $maxProcesses = 2;
    // 当前进程数
    protected $process = 0;
    // 消息队列Redis键值
    protected $queueKey = 'phalcon:test:queue';
    // 等待时间
    protected $waittime = 1;

    protected function redisClient()
    {
        return Redis::getInstance('127.0.0.1', '910123');
    }

    protected function redisChildClient()
    {
        return Redis::getInstance('127.0.0.1', '910123', 0, 6379, uniqid());
    }

    /**
     * @desc   子进程也能监听消息队列
     *         3秒内没有消息自动回收
     * @author limx
     * @param $data
     */
    protected function run($data)
    {
        $this->handle($data);
        $redis = $this->redisChildClient();
        while (true) {
            // 无任务时,阻塞等待
            $data = $redis->brpop($this->queueKey, 3);
            if (!$data) {
                break;
            }
            if ($data[0] != $this->queueKey) {
                // 消息队列KEY值不匹配
                continue;
            }
            if (isset($data[1])) {
                $this->handle($data[1]);
            }
        }
    }

    /**
     * @desc   消息队列处理逻辑
     * @author limx
     * @param $data
     */
    protected function handle($data)
    {
        echo Color::success($data);
        Log::info($data);
        swoole_timer_after(1000, function () use ($data) {
            Log::info("after" . $data);
        });
    }
}