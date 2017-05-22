<?php

namespace App\Tasks\Test;

use App\Logics\Test;
use App\Utils\Cache;
use limx\phalcon\Cli\Color;

class CacheTask extends \Phalcon\Cli\Task
{
    const CACHE_KEY = 'phalcon:test';

    public function mainAction()
    {
        echo Color::head('Help:') . PHP_EOL;
        echo Color::colorize('  缓存测试') . PHP_EOL . PHP_EOL;

        echo Color::head('Usage:') . PHP_EOL;
        echo Color::colorize('  php run Test\\\\Cache [action]', Color::FG_GREEN) . PHP_EOL . PHP_EOL;

        echo Color::head('Actions:') . PHP_EOL;
        echo Color::colorize('  save                   缓存存储', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  logic                  逻辑层缓存测试', Color::FG_GREEN), PHP_EOL;

    }

    public function logicAction()
    {
        $res = Test::getTimeFromCache();
        echo $res;
        $res = Test::getTimeFromCache(1);
        echo $res;
    }

    public function saveAction()
    {
        Cache::save(self::CACHE_KEY, uniqid());
        $res = Cache::get(self::CACHE_KEY);
        echo $res;
    }

}

