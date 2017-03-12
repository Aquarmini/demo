<?php
// +----------------------------------------------------------------------
// | Demo [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lmx0536.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <http://www.lmx0536.cn>
// +----------------------------------------------------------------------
// | Date: 2017/1/17 Time: 下午3:01
// +----------------------------------------------------------------------
namespace MyApp\Tasks\Test;

use Phalcon\Cli\Task;
use limx\phalcon\Cli\Color;

class SortTask extends Task
{
    const NUM = 20000;

    public function mainAction()
    {
        echo Color::head('Help:') . PHP_EOL;
        echo Color::colorize('  PHP函数参数测试') . PHP_EOL . PHP_EOL;

        echo Color::head('Usage:') . PHP_EOL;
        echo Color::colorize('  php run Test\\\\Sort [action]', Color::FG_GREEN) . PHP_EOL . PHP_EOL;

        echo Color::head('Actions:') . PHP_EOL;
        echo Color::colorize('  compare     [$num]          php原生Zephir等速度比较', Color::FG_GREEN) . PHP_EOL;

    }

    public function compareAction($params)
    {
        if (count($params) == 0) {
            echo Color::error("请输入排序基数");
            return false;
        }
        $count = $params[0];

        echo Color::head("PHP原生实现的sort方法：") . PHP_EOL;
        $arr = [];
        for ($i = 0; $i < $count; $i++) {
            $arr[] = rand(0, $count);
        }
        $time = microtime(true);
        sort($arr);
        $etime = microtime(true);
        echo Color::colorize(sprintf("  耗时：%f", $etime - $time), Color::FG_LIGHT_GREEN) . PHP_EOL;
        echo PHP_EOL;

        echo Color::head("PHP实现的冒泡排序：") . PHP_EOL;
        $arr = [];
        for ($i = 0; $i < $count; $i++) {
            $arr[] = rand(0, $count);
        }
        $time = microtime(true);
        $this->sort($arr);
        $etime = microtime(true);
        echo Color::colorize(sprintf("  耗时：%f", $etime - $time), Color::FG_LIGHT_GREEN) . PHP_EOL;
        echo PHP_EOL;

        echo Color::head("PHP实现的快速排序：") . PHP_EOL;
        $arr = [];
        for ($i = 0; $i < $count; $i++) {
            $arr[] = rand(0, $count);
        }
        $time = microtime(true);
        $this->qsort($arr, 0, $count - 1);
        $etime = microtime(true);
        echo Color::colorize(sprintf("  耗时：%f", $etime - $time), Color::FG_LIGHT_GREEN) . PHP_EOL;
        echo PHP_EOL;

        echo Color::head("zephir实现的冒泡排序：") . PHP_EOL;
        $arr = [];
        for ($i = 0; $i < $count; $i++) {
            $arr[] = rand(0, $count);
        }
        $time = microtime(true);
        \Ly\Test\SortTest::sort($arr);
        $etime = microtime(true);
        echo Color::colorize(sprintf("  耗时：%f", $etime - $time), Color::FG_LIGHT_GREEN) . PHP_EOL;
        echo PHP_EOL;
    }

    /**
     * @desc   冒泡排序
     * @author limx
     * @param $arr
     * @return mixed
     */
    private function sort($arr)
    {
        $count = count($arr);
        for ($i = 0; $i < $count; $i++) {
            for ($j = 0; $j < $count; $j++) {
                if ($arr[$j] > $arr[$i]) {
                    $temp = $arr[$i];
                    $arr[$i] = $arr[$j];
                    $arr[$j] = $temp;
                }
            }
        }
        return $arr;
    }

    /**
     * @desc   快排
     * @author limx
     * @param $arr
     * @param $start
     * @param $end
     */
    private function qsort(&$arr, $start, $end)
    {
        if ($start >= $end) {
            return;
        }
        $index = $start;
        $index2 = $end;
        while ($start < $end) {
            if ($arr[$end] < $arr[$index]) {
                if ($arr[$start] >= $arr[$index]) {
                    $this->exchange($arr, $end, $start);
                } else {
                    $start++;
                }
            } else {
                $end--;
            }

        }
        $this->exchange($arr, $index, $start);
        $this->qsort($arr, $index, $start);
        $this->qsort($arr, $start + 1, $index2);
    }

    private function exchange(&$arr, $i, $j)
    {
        $temp = $arr[$j];
        $arr[$j] = $arr[$i];
        $arr[$i] = $temp;
    }
}