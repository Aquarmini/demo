<?php
// +----------------------------------------------------------------------
// | Demo [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lmx0536.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <http://www.lmx0536.cn>
// +----------------------------------------------------------------------
// | Date: 2016/12/16 Time: 13:49
// +----------------------------------------------------------------------
namespace MyApp\Tasks\Test;

use Phalcon\Cli\Task;
use limx\phalcon\DB;
use limx\phalcon\Cli\Color;

class DbTask extends Task
{
    public function mainAction()
    {
        // for ($i = 0; $i < 10000; $i++) {
        //     $start = rand(1, 5);
        //     $end = rand(5, 10);
        //     $this->test($start, $end);
        // }
        // echo "FINISH\n";

        $sql = "UPDATE book SET name=? WHERE id = ?";
        // $res = DB::execute($sql, [111, 1], true);
        // $res = DB::execute($sql, [11, 1]);
        $res = DB::execWithRowCount($sql, [111, 1]);

        if ($res === false) {
            echo Color::colorize("FALSE", Color::FG_LIGHT_RED) . PHP_EOL;
        } else if ($res === true) {
            echo Color::colorize("TRUE", Color::FG_LIGHT_CYAN) . PHP_EOL;
        } else {
            echo Color::colorize($res, Color::FG_LIGHT_CYAN) . PHP_EOL;
        }
    }

    public function test($start, $end)
    {
        $sql = "SELECT * FROM book WHERE uid > ? AND uid < ?;";
        $res = DB::query($sql, [$start, $end]);
        $count1 = count($res);

        $sql = "SELECT * FROM book WHERE uid > {$start} AND uid < {$end};";
        $res = DB::query($sql);
        $count2 = count($res);

        if ($count1 !== $count2 || $count1 = 0) {
            echo $count1 . '|' . $count2 . "\n";
        }
    }

}