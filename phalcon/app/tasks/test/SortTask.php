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

class SortTask extends Task
{
    const NUM = 20000;

    /**
     * [mainAction desc]
     * @desc
     * @author limx
     * @result
     * php 原生方法 sort:
     * 0.0013320446014404
     * php 实现的方法 冒泡排序:
     * 2.888885974884
     * php 实现的方法 快速排序:
     * 1.5615661144257
     * zephir 实现的方法 冒泡排序:
     * 1.8319699764252
     */
    public function mainAction()
    {
        $arr = [];
        for ($i = 0; $i < self::NUM; $i++) {
            $arr[] = rand(1, self::NUM);
        }
        $num = self::NUM - 1;

        $time = time() + microtime();
        sort($arr);
        echo "php 原生方法 sort:", PHP_EOL;
        echo time() + microtime() - $time, PHP_EOL;

        $time = time() + microtime();
        $arr = $this->sort($arr);
        echo "php 实现的方法 冒泡排序:", PHP_EOL;
        echo time() + microtime() - $time, PHP_EOL;

        $time = time() + microtime();
        $this->qsort($arr, 0, $num);
        echo "php 实现的方法 快速排序:", PHP_EOL;
        echo time() + microtime() - $time, PHP_EOL;

        $time = time() + microtime();
        $arr = \Ly\Test\Hello::sort($arr);
        echo "zephir 实现的方法 冒泡排序:", PHP_EOL;
        echo time() + microtime() - $time, PHP_EOL;
    }

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