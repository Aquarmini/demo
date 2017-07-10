<?php
// +----------------------------------------------------------------------
// | 测试脚本 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Tasks\Test;

use limx\phalcon\Utils\Str;
use Phalcon\Cli\Task;
use limx\phalcon\Cli\Color;
use Phalcon\Text;

class StrTask extends Task
{
    public function mainAction()
    {
        echo Color::head('Help:') . PHP_EOL;
        echo Color::colorize('  PHP函数参数测试') . PHP_EOL . PHP_EOL;

        echo Color::head('Usage:') . PHP_EOL;
        echo Color::colorize('  php run Test\\\\Str [action]', Color::FG_GREEN) . PHP_EOL . PHP_EOL;

        echo Color::head('Actions:') . PHP_EOL;
        echo Color::colorize('  random      {$1}        随机字符串', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  strPad                  不足位数补0', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  strstr                  检测字符串是否包含另外一个字符串', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  textRandom              Phalcon\\Text随机字符串', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  camelize                大驼峰转化', Color::FG_GREEN) . PHP_EOL;
    }

    public function camelizeAction()
    {
        $str = 'HelloWorld';
        echo Color::colorize("Text::uncamelize($str)=" . Text::uncamelize($str)) . PHP_EOL;
        $str = 'HelloWorld';
        echo Color::colorize("Text::uncamelize($str,'-')=" . Text::uncamelize($str, '-')) . PHP_EOL;

        $str = 'hello_world';
        echo Color::colorize("Text::camelize($str,'-')=" . Text::camelize($str, '-')) . PHP_EOL;

        $str = 'hello_world';
        echo Color::colorize("Text::camelize($str)=" . Text::camelize($str)) . PHP_EOL;

        $str = 'hello_world';
        echo Color::colorize("lcfirst(Text::camelize($str))=" . lcfirst(Text::camelize($str))) . PHP_EOL;

        $str = 'hello _world';
        echo Color::colorize("lcfirst(Text::camelize($str))=" . lcfirst(Text::camelize($str))) . PHP_EOL;

    }

    public function textRandomAction()
    {
        $str = Text::random(Text::RANDOM_NUMERIC, 12);
        echo Color::colorize("RANDOM_NUMERIC " . $str, Color::FG_GREEN) . PHP_EOL;

        $str = Text::random(Text::RANDOM_ALNUM, 12);
        echo Color::colorize("RANDOM_ALNUM " . $str, Color::FG_GREEN) . PHP_EOL;

        $str = Text::random(Text::RANDOM_ALPHA, 12);
        echo Color::colorize("RANDOM_ALPHA " . $str, Color::FG_GREEN) . PHP_EOL;

        $str = Text::random(Text::RANDOM_DISTINCT, 12);
        echo Color::colorize("RANDOM_DISTINCT " . $str, Color::FG_GREEN) . PHP_EOL;

        $str = Text::random(Text::RANDOM_HEXDEC, 12);
        echo Color::colorize("RANDOM_HEXDEC " . $str, Color::FG_GREEN) . PHP_EOL;

        $str = Text::random(Text::RANDOM_NOZERO, 12);
        echo Color::colorize("RANDOM_NOZERO " . $str, Color::FG_GREEN) . PHP_EOL;
    }

    public function strstrAction()
    {
        $str = 'asdfinfo3-erroradsf';
        $res = strstr($str, 'info4-error');
        if ($res) {
            echo 'success';
        } else {
            echo 'error';
        }

        $res = strstr($str, 'info3-error');
        if ($res) {
            echo 'success';
        } else {
            echo 'error';
        }
    }

    public function strPadAction()
    {
        $num = rand(1, 9999);
        echo Color::colorize("数字：" . $num, Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize("结果：" . str_pad($num, 5, '0', STR_PAD_LEFT));
    }

    public function randomAction($params)
    {
        $num = rand(1, 10);
        if (count($params) > 0) {
            $num = intval($params[0]);
        }
        echo Color::colorize(Str::random($num), Color::FG_GREEN) . PHP_EOL;
    }
}