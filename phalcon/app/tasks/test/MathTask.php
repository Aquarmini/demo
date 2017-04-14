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

class MathTask extends Task
{
    public function mainAction()
    {
        echo Color::head('Help:') . PHP_EOL;
        echo Color::colorize('  PHP数学运算测试') . PHP_EOL . PHP_EOL;

        echo Color::head('Usage:') . PHP_EOL;
        echo Color::colorize('  php run Test\\\\Math [action]', Color::FG_GREEN) . PHP_EOL . PHP_EOL;

        echo Color::head('Actions:') . PHP_EOL;
        echo Color::colorize('  floor [...$1]      取整测试', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  fmod  [$1] [$2]    浮点数除法取余测试', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  max   [...$1]      取最大值', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  rand               mt_rand 与 rand取随机数', Color::FG_GREEN) . PHP_EOL;
    }

    public function randAction()
    {
        echo Color::head("mt_rand与rand生成随机数的速度比较") . PHP_EOL;
        $btime = microtime(true);
        for ($i = 0; $i < 10000000; $i++) {
            $val = rand();
        }
        $etime = microtime(true);
        $randTime = $etime - $btime;

        $btime = microtime(true);
        for ($i = 0; $i < 10000000; $i++) {
            $val = mt_rand();
        }
        $etime = microtime(true);
        $mtrandTime = $etime - $btime;
        $method = $randTime > $mtrandTime ? "mt_rand" : "rand";
        echo Color::colorize(sprintf("  rand    耗时  %f", $randTime)) . PHP_EOL;
        echo Color::colorize(sprintf("  mt_rand 耗时  %f", $mtrandTime)) . PHP_EOL;
        echo Color::colorize(sprintf("  结论：%s速度更快！", $method), Color::FG_LIGHT_GREEN) . PHP_EOL;
    }

    public function maxAction($params)
    {
        if (count($params) == 0) {
            echo Color::error("请输入比较的参数！！");
            return;
        }

        echo Color::colorize(sprintf("(%s)中的最大值为：", implode(',', $params)), Color::FG_LIGHT_RED);
        echo Color::colorize("  " . max($params), Color::FG_LIGHT_GREEN) . PHP_EOL;
    }

    public function fmodAction($params)
    {
        if (count($params) < 2) {
            echo Color::error("请输入除数与被除数！！");
            return;
        }
        list($a, $b) = $params;
        echo Color::colorize("运算式为fmod({$a},{$b})=", Color::FG_LIGHT_RED);
        echo Color::colorize("  " . fmod($a, $b), Color::FG_LIGHT_GREEN) . PHP_EOL;
    }

    public function floorAction($params)
    {
        foreach ($params as $num) {
            echo Color::colorize("取整数字为{$num}", Color::FG_LIGHT_RED) . PHP_EOL;
            echo Color::colorize("  floor 舍去法       " . floor($num)) . PHP_EOL;
            echo Color::colorize("  ceil  进一法       " . ceil($num)) . PHP_EOL;
            echo Color::colorize("  round 四舍五入     " . round($num)) . PHP_EOL;
            echo PHP_EOL;
        }
    }
}