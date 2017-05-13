<?php
// +----------------------------------------------------------------------
// | 测试脚本 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Tasks\Test;

use App\Utils\Redis;
use Phalcon\Cli\Task;
use limx\phalcon\Cli\Color;

class FileTask extends Task
{
    public function mainAction()
    {
        echo Color::head('Help:'), PHP_EOL;
        echo Color::colorize('  PHP函数参数测试'), PHP_EOL, PHP_EOL;

        echo Color::head('Usage:'), PHP_EOL;
        echo Color::colorize('  php run Test\\\\File [action]', Color::FG_GREEN), PHP_EOL, PHP_EOL;

        echo Color::head('Actions:'), PHP_EOL;
        echo Color::colorize('  save        文件存储', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  csv         CSV文件解析', Color::FG_GREEN), PHP_EOL;
    }

    public function csvAction()
    {
        $file = fopen(ROOT_PATH . '/data/file/device_key.csv', 'r');
        while ($data = fgetcsv($file)) { //每次读取CSV里面的一行内容
            //print_r($data); //此为一个数组，要获得每一个数据，访问数组下标即可
            $goods_list[] = $data;
        }
        Redis::select(1);

        foreach ($goods_list as $item) {
            if ($item[0] != "") {
                Redis::hset($item[1], 'key1', $item[2]);
                Redis::hset($item[1], 'key2', $item[3]);
                echo Color::colorize($item[1]) . PHP_EOL;
            }
        }

        fclose($file);
    }

    public function saveAction()
    {
        $url = "https://avatars1.githubusercontent.com/u/16648551?v=3&s=460";
        $target = ROOT_PATH . "/public/uploads/";
        $data = file_get_contents($url);
        if (!is_dir($target)) {
            mkdir($target, 0777, true);
        }
        $name = uniqid() . ".jpg";
        return file_put_contents($target . $name, $data);
    }


}