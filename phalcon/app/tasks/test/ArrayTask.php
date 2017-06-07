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

class ArrayTask extends Task
{
    public function mainAction()
    {
        echo Color::head('Help:'), PHP_EOL;
        echo Color::colorize('  Array函数测试'), PHP_EOL, PHP_EOL;

        echo Color::head('Usage:'), PHP_EOL;
        echo Color::colorize('  php run Test\\\\Array [action]', Color::FG_GREEN), PHP_EOL, PHP_EOL;

        echo Color::head('Actions:'), PHP_EOL;
        echo Color::colorize('  chunk                   数组重新分组', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  case    [upper|lower]   修改键名为全大写或小写', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  combine                 拼接key数组和val数组', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  count                   统计数组中所有的值出现的次数', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  diff                    计算数组差集', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  fill                    用给定的值填充数组', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  filter                  用回调函数过滤数组中的单元', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  intersect               返回两个数组值的差集，如果值相等返回数组1对应的数据', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  keys                    返回数组的key值', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  map                     返回callback处理之后的数组', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  unique                  数组去重', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  mergeRecursive          合并多个数组，键值相同则合并成一个数组', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  pad     [:len] [:val]   根据指定长度填充数组', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  splice  [:off] [:len]   根据指定长度切割', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  multisort               数组排序', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  intersectKey            返回两个数组KEY的差集 若KEY相等返回数组1对应的数据', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  flip                    返回两个数组的差集[检测KEY是否相等]', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  fillKeys                填充数据', Color::FG_GREEN), PHP_EOL;

    }

    public function fillKeysAction()
    {
        $arr = ['id', 'name', 'username'];
        echo Color::head("原数组："), PHP_EOL;
        echo Color::colorize(json_encode($arr), Color::FG_LIGHT_GREEN), PHP_EOL;
        echo Color::head("结果："), PHP_EOL;
        echo Color::colorize(json_encode(array_fill_keys($arr, '')), Color::FG_LIGHT_GREEN), PHP_EOL;

    }

    public function flipAction()
    {
        $arr = ['id', 'name', 'username'];
        echo Color::head("原数组："), PHP_EOL;
        echo Color::colorize(json_encode($arr), Color::FG_LIGHT_GREEN), PHP_EOL;
        echo Color::head("结果："), PHP_EOL;
        echo Color::colorize(json_encode(array_flip($arr)), Color::FG_LIGHT_GREEN), PHP_EOL;

    }

    public function intersectKeyAction()
    {
        $arr1 = ['id' => 1, 'name' => 'limx', 'username' => 'limx'];
        $arr2 = ['id' => 0, 'name' => 1, 'username' => 2, 'email' => '715557344@qq.com'];
        echo Color::head("原数组1："), PHP_EOL;
        echo Color::colorize(json_encode($arr1), Color::FG_LIGHT_GREEN), PHP_EOL;
        echo Color::head("原数组2："), PHP_EOL;
        echo Color::colorize(json_encode($arr2), Color::FG_LIGHT_GREEN), PHP_EOL;
        echo Color::head("结果："), PHP_EOL;
        echo Color::colorize(json_encode(array_intersect_key($arr1, $arr2)), Color::FG_LIGHT_GREEN), PHP_EOL;

    }

    public function multisortAction()
    {
        $arr = [];
        $sort1 = [];
        $sort2 = [];
        for ($i = 0; $i < 100; $i++) {
            $s1 = rand(1, 10);
            $s2 = rand(1, 100);
            $arr[] = ['sort1' => $s1, 'sort2' => $s2, 'val' => uniqid()];
            $sort1[] = $s1;
            $sort2[] = $s2;
        }
        echo Color::head("原数组arr："), PHP_EOL;
        echo Color::colorize(json_encode($arr), Color::FG_LIGHT_GREEN), PHP_EOL;
        echo Color::head("结果："), PHP_EOL;
        array_multisort($sort1, SORT_DESC, $sort2, SORT_ASC, $arr);
        echo Color::colorize(json_encode($arr), Color::FG_LIGHT_GREEN), PHP_EOL;
    }

    public function uniqueAction()
    {
        $arr = [1, 1, 222, 222, 3, 4, 5];
        echo Color::head("原数组arr："), PHP_EOL;
        echo Color::colorize(json_encode($arr), Color::FG_LIGHT_GREEN), PHP_EOL;
        echo Color::head("结果："), PHP_EOL;
        $res = array_unique($arr);
        echo Color::colorize(json_encode($res), Color::FG_LIGHT_GREEN), PHP_EOL;
    }

    public function spliceAction($params)
    {
        if (count($params) < 2) {
            echo Color::error("请输入起始位置 与 长度！");
            return;
        }

        $arr = [1, 2, 3, 4, 5];
        echo Color::head("原数组arr："), PHP_EOL;
        echo Color::colorize(json_encode($arr), Color::FG_LIGHT_GREEN), PHP_EOL;
        echo Color::head("结果："), PHP_EOL;
        $res = array_splice($arr, $params[0], $params[1]);
        echo Color::head("新数组res："), PHP_EOL;
        echo Color::colorize(json_encode($res), Color::FG_LIGHT_GREEN), PHP_EOL;
        echo Color::head("原数组arr："), PHP_EOL;
        echo Color::colorize(json_encode($arr), Color::FG_LIGHT_GREEN), PHP_EOL;
    }

    public function padAction($params)
    {
        if (count($params) < 2) {
            echo Color::error("请输入数组长度 与 填充值！");
            return;
        }

        $arr = [1, 2, 3, 4, 5];
        echo Color::head("原数组："), PHP_EOL;
        echo Color::colorize(json_encode($arr), Color::FG_LIGHT_GREEN), PHP_EOL;
        echo Color::head("结果："), PHP_EOL;
        $res = array_pad($arr, $params[0], $params[1]);
        echo Color::colorize(json_encode($res), Color::FG_LIGHT_GREEN), PHP_EOL;
    }

    public function mergeRecursiveAction()
    {
        $arr1 = ["color" => ["favorite" => "red"], 5];
        $arr2 = [10, "color" => ["favorite" => "green", "blue"]];
        echo Color::head("原数组1："), PHP_EOL;
        echo Color::colorize(json_encode($arr1), Color::FG_LIGHT_GREEN), PHP_EOL;
        echo Color::head("原数组2："), PHP_EOL;
        echo Color::colorize(json_encode($arr2), Color::FG_LIGHT_GREEN), PHP_EOL;
        echo Color::head("结果："), PHP_EOL;
        $res = array_merge_recursive($arr1, $arr2);
        echo Color::colorize(json_encode($res), Color::FG_LIGHT_GREEN), PHP_EOL;
    }

    public function mapAction()
    {
        $arr = [1, 2, 3, 4, 5];
        echo Color::head("原数组："), PHP_EOL;
        echo Color::colorize(json_encode($arr), Color::FG_LIGHT_GREEN), PHP_EOL;
        echo Color::head("结果："), PHP_EOL;
        $res = array_map(function ($n) {
            return $n * $n;
        }, $arr);
        echo Color::colorize(json_encode($res), Color::FG_LIGHT_GREEN), PHP_EOL;
    }

    public function keysAction()
    {
        $arr = array("a" => "green", "red", "blue", "b" => "green", 22 => "yellow", "red");
        echo Color::head("原数组1："), PHP_EOL;
        echo Color::colorize(json_encode($arr), Color::FG_LIGHT_GREEN), PHP_EOL;
        echo Color::head("结果："), PHP_EOL;
        echo Color::colorize(json_encode(array_keys($arr)), Color::FG_LIGHT_GREEN), PHP_EOL;
    }

    public function intersectAction()
    {
        $arr1 = array("a" => "green", "red", "blue");
        $arr2 = array("b" => "green", "yellow", "red");
        echo Color::head("原数组1："), PHP_EOL;
        echo Color::colorize(json_encode($arr1), Color::FG_LIGHT_GREEN), PHP_EOL;
        echo Color::head("原数组2："), PHP_EOL;
        echo Color::colorize(json_encode($arr2), Color::FG_LIGHT_GREEN), PHP_EOL;
        echo Color::head("结果："), PHP_EOL;
        echo Color::colorize(json_encode(array_intersect($arr1, $arr2)), Color::FG_LIGHT_GREEN), PHP_EOL;
    }

    public function filterAction()
    {
        $arr = [1, 2, 3, 'a' => 4, 5, 6, 7, 'b' => 9, 8, 10];
        echo Color::head("原数组："), PHP_EOL;
        echo Color::colorize(json_encode($arr), Color::FG_LIGHT_GREEN), PHP_EOL;
        echo Color::head("结果："), PHP_EOL;
        echo Color::colorize(json_encode(array_filter($arr, function ($var) {
            return ($var & 1);
        })), Color::FG_LIGHT_GREEN), PHP_EOL;

    }

    public function fillAction($params)
    {
        if (count($params) < 3) {
            echo Color::error("请输入起始值和长度和值。");
            return false;
        }
        $begin = $params[0];
        $len = $params[1];
        $val = $params[2];
        if (!is_numeric($begin) || !is_numeric(($len))) {
            echo Color::error("起始值和长度必须为INT。");
            return false;
        }
        $res = array_fill($begin, $len, $val);
        echo Color::colorize(json_encode($res), Color::FG_LIGHT_GREEN), PHP_EOL;
    }

    public function diffAction()
    {
        $arr1 = [1, 2, 3, '4' => 4, '5' => 5];
        $arr2 = [2, 3, '5' => 5, '6' => 5];
        echo Color::head("原数组："), PHP_EOL;
        echo Color::colorize(json_encode($arr1), Color::FG_LIGHT_GREEN), PHP_EOL;
        echo Color::colorize(json_encode($arr2), Color::FG_LIGHT_GREEN), PHP_EOL;
        echo Color::head("结果："), PHP_EOL;
        echo Color::colorize(json_encode(array_diff($arr1, $arr2)), Color::FG_LIGHT_GREEN), PHP_EOL;
    }

    public function countAction()
    {
        $data = [1, 2, 'hello', 1, 'hello', 'hello', '1'];
        echo Color::head("原数组："), PHP_EOL;
        echo Color::colorize(json_encode($data), Color::FG_LIGHT_GREEN), PHP_EOL;
        echo Color::colorize(json_encode(array_count_values($data)), Color::FG_LIGHT_GREEN), PHP_EOL;
    }

    public function combineAction()
    {
        $key = ['key1', 'key2', 'key3'];
        $val = ['val1', 'val2', 'val3'];
        echo Color::head("原数组："), PHP_EOL;
        echo Color::colorize(json_encode($key), Color::FG_LIGHT_GREEN), PHP_EOL;
        echo Color::colorize(json_encode($val), Color::FG_LIGHT_GREEN), PHP_EOL;
        echo Color::head("结果："), PHP_EOL;
        echo Color::colorize(json_encode(array_combine($key, $val)), Color::FG_LIGHT_GREEN), PHP_EOL;
    }

    public function caseAction($params)
    {
        if (count($params) == 0) {
            echo Color::error("请输入参数！upper|lower");
            return;
        }
        $type = $params[0] == 'upper' ? CASE_UPPER : CASE_LOWER;
        $data = ['test' => 'tt', 'Tes' => 123, 'AA' => 'sdf'];
        echo Color::head("原数组："), PHP_EOL;
        echo Color::colorize("  " . json_encode($data), Color::FG_LIGHT_GREEN), PHP_EOL;
        echo Color::head("array_change_key_case(data,type)"), PHP_EOL;
        echo Color::colorize("  " . json_encode(array_change_key_case($data, $type)), Color::FG_LIGHT_GREEN), PHP_EOL;
    }

    public function chunkAction()
    {
        $arr = [1, 2, 3, 4, 5, 6, 7, 8, 9, 0];
        echo Color::head("原数组："), PHP_EOL;
        echo Color::colorize("  " . json_encode($arr), Color::FG_LIGHT_GREEN), PHP_EOL;
        $res = array_chunk($arr, 3);
        echo Color::head("3个一组分组："), PHP_EOL;
        echo Color::colorize("  " . json_encode($res), Color::FG_LIGHT_GREEN), PHP_EOL;
        $res = array_chunk($arr, 20);
        echo Color::head("20个一组分组："), PHP_EOL;
        echo Color::colorize("  " . json_encode($res), Color::FG_LIGHT_GREEN), PHP_EOL;
    }


}