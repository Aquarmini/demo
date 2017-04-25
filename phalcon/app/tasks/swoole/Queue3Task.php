<?php
// +----------------------------------------------------------------------
// | Demo [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lmx0536.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <http://www.lmx0536.cn>
// +----------------------------------------------------------------------
// | Date: 2017/2/23 Time: 下午2:39
// +----------------------------------------------------------------------
namespace App\Tasks\Swoole;

use App\Models\Test\User;
use App\Tasks\System\QueueTask;
use limx\phalcon\Redis;
use limx\phalcon\Cli\Color;
use limx\phalcon\DB;

class Queue3Task extends QueueTask
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

    protected function run($data)
    {
        sleep(1);
        echo Color::colorize($data, Color::FG_GREEN) . PHP_EOL;
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
                echo Color::colorize($data[1], Color::FG_GREEN) . PHP_EOL;
            }
        }
        return;


        $user = User::findFirst(1);

        if (is_numeric($user->name)) {
            $sql = "UPDATE `user` SET name=name+1 WHERE id=?";
            DB::execute($sql, [1]);
        }

        // $sql = "UPDATE `user` SET name=? WHERE id=?";
        // DB::begin();
        // DB::execute($sql, [time(), 1]);
        // DB::commit();
        // sleep(1);
        echo Color::success($user->name);
    }


}