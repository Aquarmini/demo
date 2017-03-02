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
namespace MyApp\Tasks;

use MyApp\Models\User;
use MyApp\Tasks\System\QueueTask;
use limx\tools\LRedis;
use limx\phalcon\Cli\Color;

class QueueTestTask extends QueueTask
{
    // 最大进程数
    protected $maxProcesses = 500;
    // 当前进程数
    protected $process = 0;
    // 消息队列Redis键值
    protected $queueKey = 'phalcon:test:queue';
    // 等待时间
    protected $waittime = 1;

    protected function redisClient()
    {
        $config = [
            'host' => '127.0.0.1',
            'auth' => '',
            'port' => '6379',
        ];
        return LRedis::getInstance($config);
    }

    protected function run($data)
    {
        $user = User::findFirst(1);
        echo Color::success($user->name);
    }


}