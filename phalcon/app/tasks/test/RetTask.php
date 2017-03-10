<?php
// +----------------------------------------------------------------------
// | RetTask php 函数返回值测试 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lmx0536.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <http://www.lmx0536.cn>
// +----------------------------------------------------------------------
// | Date: 2017/3/2 Time: 下午9:28
// +----------------------------------------------------------------------
namespace MyApp\Tasks\Test;

use Phalcon\Cli\Task;
use limx\phalcon\Cli\Color;

class RetTask extends Task
{
    public function mainAction()
    {
        echo Color::head('Help:') . PHP_EOL;
        echo Color::colorize('  PHP函数返回值测试') . PHP_EOL . PHP_EOL;

        echo Color::head('Usage:') . PHP_EOL;
        echo Color::colorize('  php run Test\\\\Ret [action]', Color::FG_GREEN) . PHP_EOL . PHP_EOL;

        echo Color::head('Actions:') . PHP_EOL;
        echo Color::colorize('  declare     规定返回值测试', Color::FG_GREEN) . PHP_EOL;
    }

    public function declareAction()
    {
        function test($val): int
        {
            return $val;
        }

        if (is_int(test("123"))) {
            echo Color::success("被强转为int");
        }
        if (is_string(test("123.1a"))) {
            echo Color::success("被强转为int");
        } else {
            print_r(test("123.1a"));
            echo Color::success("不是string类型");
        }
    }
}