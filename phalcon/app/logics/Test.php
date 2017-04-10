<?php
// +----------------------------------------------------------------------
// | 自定义逻辑层 Test.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace MyApp\Logics;

use limx\phalcon\Cli\Color;
use limx\phalcon\DB;
use limx\phalcon\Utils\Str;

class Test extends \Phalcon\Di\Injectable
{
    public function index()
    {
        echo Color::colorize("index@MyApp\\Logics\\Test", Color::FG_LIGHT_GREEN) . PHP_EOL;
        $cache = $this->cache;
        $cache->save('logic-test-index', ['time' => time()]);
    }

    public function incrSql()
    {
        $username = 'limx';
        $password = md5(910123);
        $name = Str::random(6);
        $test_name = "test";

        // 单唯一索引
        $sql = "INSERT INTO user(`username`,`password`,`name`,`role_id`)
            VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE `name`= IF (`name`='test',?,?)";
        $res = DB::execute($sql, [$username, $password, $name, 1, $name, $test_name], true);

        // 联合唯一索引
        // $sql = "INSERT INTO user_title(`uid`,`title_id`,`created_at`,`updated_at`)
        //     VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE `updated_at`=?";
        // $res = DB::execute($sql, [1, 18, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), date('Y-m-d H:i:s')], true);

        return $res;
    }
}