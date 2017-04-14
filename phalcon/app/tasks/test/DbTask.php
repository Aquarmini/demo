<?php
// +----------------------------------------------------------------------
// | 测试脚本 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace MyApp\Tasks\Test;

use Phalcon\Cli\Task;
use limx\phalcon\DB;
use limx\phalcon\Cli\Color;

class DbTask extends Task
{
    public function mainAction()
    {
        echo Color::head('Help:') . PHP_EOL;
        echo Color::colorize('  DB测试') . PHP_EOL . PHP_EOL;

        echo Color::head('Usage:') . PHP_EOL;
        echo Color::colorize('  php run Test\\\\Db [action]', Color::FG_GREEN) . PHP_EOL . PHP_EOL;

        echo Color::head('Actions:') . PHP_EOL;
        echo Color::colorize('  execute             执行sql并返回影响的行数', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  where               测试WHERE区间方法', Color::FG_GREEN) . PHP_EOL;
    }

    public function executeAction()
    {
        $val = uniqid();
        $sql = "UPDATE book SET name=? WHERE id = ?";

        for ($i = 0; $i < 2; $i++) {
            $res = DB::execWithRowCount($sql, [$val, 1]);
            echo Color::colorize("影响的行数：" . $res, Color::FG_LIGHT_CYAN), PHP_EOL;
        }

    }

    public function whereAction($params)
    {
        if (count($params) < 2) {
            echo Color::error("请输入需要查询的区间！");
            return;
        }
        $start = $params[0];
        $end = $params[1];

        $sql1 = "SELECT * FROM book WHERE uid > ? AND uid < ?;";
        $res = DB::query($sql1, [$start, $end]);
        $count1 = count($res);

        $sql2 = "SELECT * FROM book WHERE uid > {$start} AND uid < {$end};";
        $res = DB::query($sql2);
        $count2 = count($res);

        echo Color::head("结果："), PHP_EOL;
        echo Color::colorize(sprintf("%s搜索结果的行数：%d", $sql1, $count1), Color::FG_LIGHT_CYAN), PHP_EOL;
        echo Color::colorize(sprintf("%s搜索结果的行数：%d", $sql2, $count2), Color::FG_LIGHT_CYAN), PHP_EOL;

    }

}