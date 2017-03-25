<?php
// +----------------------------------------------------------------------
// | 自定义逻辑层 Test.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace MyApp\Logic;

use limx\phalcon\Cli\Color;

class Test extends \Phalcon\Di\Injectable
{
    public function index()
    {
        echo Color::colorize("index@MyApp\\Logic\\Test", Color::FG_LIGHT_GREEN) . PHP_EOL;
        $cache = $this->cache;
        $cache->save('logic-test-index', ['time' => time()]);
    }
}