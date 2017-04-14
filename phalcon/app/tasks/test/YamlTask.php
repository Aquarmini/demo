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

class YamlTask extends Task
{
    public function mainAction()
    {
        if (!extension_loaded('yaml')) {
            echo Color::error('The yaml extension is not installed');
            return;
        }

        echo Color::head('Help:') . PHP_EOL;
        echo Color::colorize('  Yaml扩展测试') . PHP_EOL . PHP_EOL;

        echo Color::head('Usage:') . PHP_EOL;
        echo Color::colorize('  php run Test\\\\Yaml [action]', Color::FG_GREEN) . PHP_EOL . PHP_EOL;

        echo Color::head('Actions:') . PHP_EOL;
        echo Color::colorize('  check       验证转化与解析是否正确', Color::FG_GREEN) . PHP_EOL;
    }

    public function checkAction()
    {
        $data = array(
            "given" => "Chris",
            "family" => "Dumars",
            "address" => array(
                "lines" => "458 Walkman Dr.
                Suite #292",
                "city" => "Royal Oak",
                "state" => "MI",
                "postal" => 48046,
            ),
        );
        echo Color::head("原数据：") . PHP_EOL;
        print_r($data);
        $res = yaml_emit($data);
        echo Color::head("转化后数据：") . PHP_EOL;
        print_r($res);
        echo Color::head("解析后对比") . PHP_EOL;
        $rdata = yaml_parse($res);
        print_r($rdata == $data);


    }
}