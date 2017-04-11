<?php
// +----------------------------------------------------------------------
// | Redis方法测试 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace MyApp\Tasks\Test;

use limx\phalcon\Redis;
use limx\tools\LRedis;
use Phalcon\Cli\Task;
use limx\phalcon\DB;
use limx\phalcon\Cli\Color;

class RedisTask extends Task
{
    const TEST_KEY = 'phalcon:test:key';

    public function mainAction()
    {
        echo Color::head('Help:') . PHP_EOL;
        echo Color::colorize('  Redis方法测试') . PHP_EOL . PHP_EOL;

        echo Color::head('Usage:') . PHP_EOL;
        echo Color::colorize('  php run Test\\\\Redis [action]', Color::FG_GREEN) . PHP_EOL . PHP_EOL;

        echo Color::head('Actions:') . PHP_EOL;
        echo Color::colorize('  sadd            sadd测试', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  hmget           hmget测试', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  hget            hget测试', Color::FG_GREEN) . PHP_EOL;
    }

    public function hgetAction()
    {
        $redis = $this->redisClient();
        $keys = ['key1' => uniqid(), 'key2' => uniqid(), 'key3' => uniqid(), 'key4' => uniqid()];
        echo Color::head('KEY值为'), PHP_EOL;
        echo Color::colorize('  ' . self::TEST_KEY, Color::FG_LIGHT_GREEN), PHP_EOL;
        echo Color::head('设置测试数据：');
        $redis->hmset(self::TEST_KEY, $keys);
        echo Color::colorize('  设置成功', Color::FG_LIGHT_GREEN), PHP_EOL;

        $res = $redis->hget(self::TEST_KEY, 'key1');
        echo Color::head('存在时结果：');
        echo Color::colorize(sprintf("  %s", json_encode($res)), Color::FG_LIGHT_GREEN), PHP_EOL;

        $res = $redis->hget(self::TEST_KEY, 'key11');
        echo Color::head('不存在时结果：');
        echo Color::colorize(sprintf("  %s", json_encode($res)), Color::FG_LIGHT_GREEN), PHP_EOL;
    }

    public function hmgetAction()
    {
        $redis = $this->redisClient();
        $keys = ['key1' => uniqid(), 'key2' => uniqid(), 'key3' => uniqid(), 'key4' => uniqid()];
        echo Color::head('KEY值为') . PHP_EOL;
        echo Color::colorize('  ' . self::TEST_KEY, Color::FG_LIGHT_GREEN), PHP_EOL;
        echo Color::head('设置测试数据：');
        $redis->hmset(self::TEST_KEY, $keys);
        echo Color::colorize('  设置成功', Color::FG_LIGHT_GREEN), PHP_EOL;

        $res = $redis->hmget(self::TEST_KEY, ['key1', 'key2', 'key3']);
        echo Color::head('结果：');
        echo Color::colorize(sprintf("  %s", json_encode($res)), Color::FG_LIGHT_GREEN), PHP_EOL;
    }

    public function saddAction()
    {
        $redis = $this->redisClient();
        $val = 'hello world';
        echo Color::head('KEY值为') . PHP_EOL;
        echo Color::colorize('  ' . self::TEST_KEY, Color::FG_LIGHT_GREEN) . PHP_EOL;
        $res = $redis->sadd(self::TEST_KEY, $val);
        echo Color::head('第一次结果：');
        echo Color::colorize('  ' . $res, Color::FG_LIGHT_GREEN) . PHP_EOL;
        $res = $redis->sadd(self::TEST_KEY, $val);
        echo Color::head('第二次结果：');
        echo Color::colorize('  ' . $res, Color::FG_LIGHT_GREEN) . PHP_EOL;
    }

    private function redisClient()
    {
        $config = di('config');
        $redis = Redis::getInstance(
            $config->redis->host,
            $config->redis->auth,
            $config->redis->index,
            $config->redis->port
        );
        $redis->del(self::TEST_KEY);
        return $redis;

        $redis = LRedis::getInstance([
            'host' => $config->redis->host,
            'auth' => $config->redis->auth,
            'port' => $config->redis->port,
        ]);
        return $redis;
    }
}