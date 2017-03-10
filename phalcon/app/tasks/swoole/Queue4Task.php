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
namespace MyApp\Tasks\Swoole;

use MyApp\Models\Test\User;
use MyApp\Tasks\System\QueueTask;
use limx\tools\LRedis;
use limx\phalcon\Cli\Color;
use limx\tools\MyPDO;

class Queue4Task extends QueueTask
{
    // 最大进程数
    protected $maxProcesses = 10;
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

    protected function rewrite($data)
    {
        $db = MyPDO::getInstance([
            'host' => '127.0.0.1',
            'dbname' => 'phalcon',
            'user' => 'root',
            'pwd' => '910123',
        ]);
        $res = $db->fetch("SELECT * FROM user WHERE id = ?", [1]);
        return json_encode($res);
    }

    protected function run($data)
    {
        //$user = User::findFirst(1);
        // $sql = "UPDATE `user` SET name=? WHERE id=?";
        // DB::begin();
        // DB::execute($sql, [time(), 1]);
        // DB::commit();
        // sleep(1);
        echo Color::success($data);
    }


}