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