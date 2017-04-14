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
use limx\phalcon\Cli\Color;

class CompareTask extends Task
{

    public function mainAction()
    {
        echo Color::head('Help:') . PHP_EOL;
        echo Color::colorize('  数据比较测试') . PHP_EOL . PHP_EOL;

        echo Color::head('Usage:') . PHP_EOL;
        echo Color::colorize('  php run Test\\\\Compare [action]', Color::FG_GREEN) . PHP_EOL . PHP_EOL;

        echo Color::head('Actions:') . PHP_EOL;
        echo Color::colorize('  compare             一部分数据==与===测试', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  null                null 和 0之间的比较', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  int                 int 和 string之间的比较', Color::FG_GREEN) . PHP_EOL;
    }

    public function intAction()
    {
        echo Color::head("1=='1'") . PHP_EOL;
        if (1 == '1') {
            echo Color::colorize("  结果：成立", Color::FG_LIGHT_GREEN) . PHP_EOL;
        } else {
            echo Color::colorize("  结果：不成立", Color::FG_LIGHT_RED) . PHP_EOL;
        }

        echo Color::head("0=='a123'") . PHP_EOL;
        if (0 == 'a123') {
            echo Color::colorize("  结果：成立", Color::FG_LIGHT_GREEN) . PHP_EOL;
        } else {
            echo Color::colorize("  结果：不成立", Color::FG_LIGHT_RED) . PHP_EOL;
        }

        echo Color::head("0=='123a'") . PHP_EOL;
        if (0 == '123a') {
            echo Color::colorize("  结果：成立", Color::FG_LIGHT_GREEN) . PHP_EOL;
        } else {
            echo Color::colorize("  结果：不成立", Color::FG_LIGHT_RED) . PHP_EOL;
        }

        echo Color::head("123=='123a'") . PHP_EOL;
        if (123 == '123a') {
            echo Color::colorize("  结果：成立", Color::FG_LIGHT_GREEN) . PHP_EOL;
        } else {
            echo Color::colorize("  结果：不成立", Color::FG_LIGHT_RED) . PHP_EOL;
        }

        echo Color::head("0===intval('b1')") . PHP_EOL;
        if (0 === intval('b1')) {
            echo Color::colorize("  结果：成立", Color::FG_LIGHT_GREEN) . PHP_EOL;
        } else {
            echo Color::colorize("  结果：不成立", Color::FG_LIGHT_RED) . PHP_EOL;
        }

        echo Color::head("0===intval('a')") . PHP_EOL;
        if (0 === intval('a')) {
            echo Color::colorize("  结果：成立", Color::FG_LIGHT_GREEN) . PHP_EOL;
        } else {
            echo Color::colorize("  结果：不成立", Color::FG_LIGHT_RED) . PHP_EOL;
        }

        echo Color::head("0=='0'") . PHP_EOL;
        if (0 == '0') {
            echo Color::colorize("  结果：成立", Color::FG_LIGHT_GREEN) . PHP_EOL;
        } else {
            echo Color::colorize("  结果：不成立", Color::FG_LIGHT_RED) . PHP_EOL;
        }
    }

    public function nullAction()
    {
        echo Color::head("null==0") . PHP_EOL;
        if (null == 0) {
            echo Color::colorize("  结果：成立", Color::FG_LIGHT_GREEN) . PHP_EOL;
        } else {
            echo Color::colorize("  结果：不成立", Color::FG_LIGHT_RED) . PHP_EOL;
        }

        echo Color::head("null=='0'") . PHP_EOL;
        if (null == '0') {
            echo Color::colorize("  结果：成立", Color::FG_LIGHT_GREEN) . PHP_EOL;
        } else {
            echo Color::colorize("  结果：不成立", Color::FG_LIGHT_RED) . PHP_EOL;
        }

        echo Color::head("null==false") . PHP_EOL;
        if (null == false) {
            echo Color::colorize("  结果：成立", Color::FG_LIGHT_GREEN) . PHP_EOL;
        } else {
            echo Color::colorize("  结果：不成立", Color::FG_LIGHT_RED) . PHP_EOL;
        }

        echo Color::head("null==[]") . PHP_EOL;
        if (null == []) {
            echo Color::colorize("  结果：成立", Color::FG_LIGHT_GREEN) . PHP_EOL;
        } else {
            echo Color::colorize("  结果：不成立", Color::FG_LIGHT_RED) . PHP_EOL;
        }
    }

    public function compareAction()
    {
        $data = [
            [1, '1'],
            ['1', '1'],
            ['0e11111111', '0e22222'],
            ['1111111111111111111111110', '111111111111111111111111b'],
            ['0111', '111'],
            [0777, '777'],
            [0777, 777],
            [0, 'a'],
            [0, null],
            ['a', null],
            [1, 'a'],
            [0, '0'],
        ];
        foreach ($data as $item) {
            echo Color::head(sprintf(
                    "[%s|%s]&&[%s|%s]",
                    $item[0],
                    gettype($item[0]),
                    $item[1],
                    gettype($item[1])
                )) . PHP_EOL;
            if ($item[0] == $item[1]) {
                $str = sprintf("%s==%s  成功", $item[0], $item[1]);
                echo Color::colorize($str, Color::FG_LIGHT_GREEN) . PHP_EOL;
            } else {
                $str = sprintf("%s==%s  不成功", $item[0], $item[1]);
                echo Color::colorize($str, Color::FG_LIGHT_RED) . PHP_EOL;
            }
            if ($item[0] === $item[1]) {
                $str = sprintf("%s===%s  成功", $item[0], $item[1]);
                echo Color::colorize($str, Color::FG_LIGHT_GREEN) . PHP_EOL;
            } else {
                $str = sprintf("%s===%s  不成功", $item[0], $item[1]);
                echo Color::colorize($str, Color::FG_LIGHT_RED) . PHP_EOL;
            }
            echo PHP_EOL;
        }
    }

}