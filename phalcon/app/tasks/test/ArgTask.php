<?php
// +----------------------------------------------------------------------
// | 测试脚本 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Tasks\Test;

use Phalcon\Cli\Task;
use limx\phalcon\Cli\Color;

class ArgTask extends Task
{
    public function mainAction()
    {
        echo Color::head('Help:'), PHP_EOL;
        echo Color::colorize('  PHP函数参数测试'), PHP_EOL, PHP_EOL;

        echo Color::head('Usage:'), PHP_EOL;
        echo Color::colorize('  php run Test\\\\Arg [action]', Color::FG_GREEN), PHP_EOL, PHP_EOL;

        echo Color::head('Actions:'), PHP_EOL;
        echo Color::colorize('  more        不定参数测试', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  callfunc    传入函数测试', Color::FG_GREEN), PHP_EOL;

    }

    /**
     * @desc   传入函数测试
     * @author limx
     */
    public function callfuncAction()
    {
        function test($func)
        {
            if (is_callable($func)) {
                $func();
            } else {
                echo Color::error("传入的参数不能被执行！");
            }
        }

        test(function () {
            echo Color::success("执行函数");
        });
        test('11111');
        $func = function () {
            echo Color::success("执行函数2");
        };
        test($func);
    }

    /**
     * @desc   不定参数测试
     * @author limx
     */
    public function moreAction()
    {
        function test(...$args)
        {
            print_r($args);
        }

        function test2()
        {
            $args = func_get_args();
            print_r($args);
        }

        test(1, 2, 3, 4, [1, 2, 3]);
        test2(1, 2, 3, 4, [1, 2, 3]);

    }

}