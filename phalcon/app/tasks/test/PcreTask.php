<?php
// +----------------------------------------------------------------------
// | Demo [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lmx0536.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <http://www.lmx0536.cn>
// +----------------------------------------------------------------------
// | Date: 2017/3/7 Time: 下午2:32
// +----------------------------------------------------------------------
namespace MyApp\Tasks\Test;

use Phalcon\Cli\Task;
use limx\phalcon\Cli\Color;

class PcreTask extends Task
{
    public function mainAction()
    {
        echo Color::head('Help:') . PHP_EOL;
        echo Color::colorize('  PCRE函数参数测试') . PHP_EOL . PHP_EOL;

        echo Color::head('Usage:') . PHP_EOL;
        echo Color::colorize('  php run Test\\\\Pcre [action]', Color::FG_GREEN) . PHP_EOL . PHP_EOL;

        echo Color::head('Actions:') . PHP_EOL;
        echo Color::colorize('  match       正则匹配', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  quote       转移正则表达式', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  replace     执行一个正则表达式的搜索和替换', Color::FG_GREEN) . PHP_EOL;
    }

    public function replaceAction()
    {
        $str = "http://demo.phalcon.lmx0536.cn/test/api/api";
        $preg = "/^(http)(.*\/)(\w+)$/";
        echo Color::head("原字符串：") . PHP_EOL;
        echo Color::colorize("  " . $str, Color::FG_LIGHT_GREEN) . PHP_EOL;
        echo Color::head("正则替换后：") . PHP_EOL;
        echo Color::colorize(preg_replace($preg, 'https${2}${3}/test', $str), Color::FG_LIGHT_GREEN) . PHP_EOL;
        echo PHP_EOL;
        echo Color::head("原字符串：") . PHP_EOL;
        echo Color::colorize("  " . $str, Color::FG_LIGHT_GREEN) . PHP_EOL;
        $preg = [
            '/demo/',
            '/\d+/',
        ];
        $replace = [
            'demos',
            'limx',
        ];
        echo Color::head("正则替换后：") . PHP_EOL;
        echo Color::colorize(preg_replace($preg, $replace, $str), Color::FG_LIGHT_GREEN) . PHP_EOL;

    }

    public function quoteAction()
    {
        $str = "http://sss\nfz$";
        echo Color::head("原字符串：") . PHP_EOL;
        echo Color::colorize("  " . $str, Color::FG_LIGHT_GREEN) . PHP_EOL;
        echo Color::head("转义后：") . PHP_EOL;
        echo Color::colorize(preg_quote($str, '/'), Color::FG_LIGHT_GREEN) . PHP_EOL;
    }

    public function matchAction()
    {
        $str = "com.peppertv.rmb6";
        preg_match("/rmb[0-9]+$/", $str, $rmb);
        echo Color::head("原字符串：") . PHP_EOL;
        echo Color::colorize("  " . $str, Color::FG_LIGHT_GREEN) . PHP_EOL;
        echo Color::head("结果：") . PHP_EOL;
        echo Color::colorize($rmb[0], Color::FG_LIGHT_GREEN) . PHP_EOL;
    }
}