<?php
// +----------------------------------------------------------------------
// | 测试脚本 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Tasks\Test;

use limx\phalcon\Redis;
use limx\tools\LRedis;
use App\Utils\Redis as RedisUtil;
use Phalcon\Cli\Task;
use limx\phalcon\DB;
use limx\phalcon\Cli\Color;
use Phalcon\Exception;
use Predis\Client;
use swoole_process;

class RedisTask extends Task
{
    const TEST_KEY = 'phalcon:test:key';

    public function mainAction()
    {
        echo Color::head('Help:'), PHP_EOL;
        echo Color::colorize('  Redis方法测试'), PHP_EOL, PHP_EOL;

        echo Color::head('Usage:'), PHP_EOL;
        echo Color::colorize('  php run Test\\\\Redis [action]', Color::FG_GREEN), PHP_EOL, PHP_EOL;

        echo Color::head('Actions:'), PHP_EOL;
        echo Color::colorize('  sadd            sadd测试', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  hmget           hmget测试', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  hget            hget测试', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  multi           事务测试', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  static          静态方法', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  utilsRedis      测试limningxinleo/utils-redis', Color::FG_GREEN), PHP_EOL;

        echo Color::colorize('  predisKeys      predis扩展的keys方法', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  luaGet          lua脚本get方法', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  lua             lua脚本操作方法', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  luasha1         lua脚本evalsha操作方法', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  withtime        写入数据同时设置超时时间', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  model           RedisModel测试', Color::FG_GREEN), PHP_EOL;
    }

    public function modelAction()
    {
        $info = \App\Models\User::findFirst(1)->toArray();
        $model = new \App\Models\RedisModel\User();
        echo $model->replace(1, $info);
    }

    public function withtimeAction()
    {
        $res = RedisUtil::incr(static::TEST_KEY, 10);
        echo Color::colorize($res) . PHP_EOL;
        $res = RedisUtil::incrBy(static::TEST_KEY, 10, 10);
        echo Color::colorize($res) . PHP_EOL;

        // $res = RedisUtil::incr(static::TEST_KEY);
        // echo Color::colorize($res) . PHP_EOL;
        // $res = RedisUtil::incrBy(static::TEST_KEY, 10);
        // echo Color::colorize($res) . PHP_EOL;
    }

    public function luasha1Action($params = [])
    {
        $client = $this->predisClient();
        if (count($params) == 0) {
            $len = 10;
        } else {
            $len = intval($params[0]);
        }
        $script = <<<LUA
    local values = {};
    for i=1,$len do 
        values[#values+1] = redis.pcall('incr',KEYS[1]); 
    end 
    return values;
LUA;
        $res = $client->evalsha(sha1($script), 1, 'phalcon:test:index');
        echo Color::colorize(sprintf("Incr phalcon:test:index * %d：%s", $len, json_encode($res))) . PHP_EOL;
    }

    public function luaAction()
    {
        $client = $this->predisClient();
        $script = <<<LUA
    local values = {}; 
    for i,v in ipairs(KEYS) do 
        values[#values+1] = v; 
    end 
    return #values;
LUA;
        $res = $client->eval($script, 2, "val1", "val2");
        echo Color::colorize("返回两个传入数据的数量：" . $res) . PHP_EOL;

        $script = <<<LUA
    local values = {}; 
    for i,v in ipairs(KEYS) do 
        values[#values+1] = v; 
    end 
    return values;
LUA;
        $res = $client->eval($script, 2, "val1", "val2");
        echo Color::colorize("返回两个传入数据：" . json_encode($res)) . PHP_EOL;

        $script = <<<LUA
    local values = {};
    local keys = redis.pcall('keys','*')
    for i,v in ipairs(keys) do 
        values[#values+1] = v; 
    end 
    return values;
LUA;
        $res = $client->eval($script, 0);
        echo Color::colorize("返回keys *：" . json_encode($res)) . PHP_EOL;

        $script = <<<LUA
    local values = {};
    for i=1,10 do 
        values[#values+1] = redis.pcall('incr',KEYS[1]); 
    end 
    return values;
LUA;
        $res = $client->eval($script, 1, 'phalcon:test:index');
        echo Color::colorize("Incr phalcon:test:index * 10：" . json_encode($res)) . PHP_EOL;

        for ($i = 0; $i < 10; $i++) {
            $process = new swoole_process([$this, 'luaTask']);
            $process->start();
        }

    }

    public function luaTask()
    {
        $client = $this->predisClient();
        $script = <<<LUA
    local values = {};
    for i=1,100 do 
        local val = redis.pcall('get',KEYS[1]);
        values[#values+1]=redis.pcall('set',KEYS[1],val+1)
    end 
    return values;
LUA;
        $client->eval($script, 1, 'phalcon:test:index');
    }

    public function luaGetAction()
    {
        $client = $this->predisClient();
        $script = <<<LUA
    local values = {}; 
    for i,v in ipairs(KEYS) do 
        values[#values+1] = redis.pcall('get',v); 
    end 
    return {KEYS,values};
LUA;
        $res = $client->eval($script, 2, 'phalcon:test:key', 'phalcon:test:index');
        print_r($res);
    }

    public function predisKeysAction()
    {
        $client = $this->predisClient();
        $res = $client->keys("*");
        print_r($res);
    }

    public function utilsRedisAction()
    {
        $config = di('config');
        $redis = \limx\utils\Redis::getInstance(
            $config->redis->host,
            $config->redis->auth,
            $config->redis->index,
            $config->redis->port
        );
        print_r($redis->keys("*"));
        print_r($redis);

        $redis = \limx\utils\Redis::getInstance(
            $config->redis->host,
            $config->redis->auth,
            $config->redis->index,
            $config->redis->port
        );
        print_r($redis);

        $redis = \limx\utils\Redis::getInstance(
            $config->redis->host,
            $config->redis->auth,
            $config->redis->index,
            $config->redis->port,
            uniqid()
        );
        print_r($redis);

        print_r($redis instanceof \limx\utils\Redis);
    }

    public function staticAction()
    {
        $res = RedisUtil::keys("*");
        echo Color::colorize(sprintf("当前结果集:%s", json_encode($res)), Color::FG_LIGHT_GREEN), PHP_EOL;
        $res = RedisUtil::del(self::TEST_KEY);
        $res = RedisUtil::keys("*");
        echo Color::colorize(sprintf("删除后结果集:%s", json_encode($res)), Color::FG_LIGHT_GREEN), PHP_EOL;
        $res = RedisUtil::set(self::TEST_KEY, 'hello world!');
        $res = RedisUtil::keys("*");
        echo Color::colorize(sprintf("新增后结果集:%s", json_encode($res)), Color::FG_LIGHT_GREEN), PHP_EOL;
        $res = RedisUtil::get(self::TEST_KEY);
        echo Color::colorize(sprintf("新增后结果:%s", $res), Color::FG_LIGHT_GREEN), PHP_EOL;

    }

    public function multiAction()
    {
        $redis = $this->redisClient();
        $redis->multi();
        $redis->incr(self::TEST_KEY);
        $redis->incr(self::TEST_KEY);
        $res = $redis->exec();
        // $res = $redis->discard();

        echo Color::colorize(sprintf("执行结果:%d", $res), Color::FG_LIGHT_GREEN), PHP_EOL;

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
        echo Color::head('KEY值为'), PHP_EOL;
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
        echo Color::head('KEY值为'), PHP_EOL;
        echo Color::colorize('  ' . self::TEST_KEY, Color::FG_LIGHT_GREEN), PHP_EOL;
        $res = $redis->sadd(self::TEST_KEY, $val);
        echo Color::head('第一次结果：');
        echo Color::colorize('  ' . $res, Color::FG_LIGHT_GREEN), PHP_EOL;
        $res = $redis->sadd(self::TEST_KEY, $val);
        echo Color::head('第二次结果：');
        echo Color::colorize('  ' . $res, Color::FG_LIGHT_GREEN), PHP_EOL;
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

    private function predisClient()
    {
        $config = di('config');

        $client = new Client([
            'host' => $config->redis->host,
            'password' => $config->redis->auth,
            'port' => $config->redis->port,
        ]);

        return $client;
    }
}