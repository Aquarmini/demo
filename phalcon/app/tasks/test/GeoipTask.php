<?php
// +----------------------------------------------------------------------
// | PasswordTask php 加密扩展 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lmx0536.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <http://www.lmx0536.cn>
// +----------------------------------------------------------------------
// | Date: 2017/3/4 Time: 下午9:00
// +----------------------------------------------------------------------
namespace MyApp\Tasks\Test;

use Phalcon\Cli\Task;
use limx\phalcon\Cli\Color;

class GeoipTask extends Task
{
    public function mainAction()
    {
        echo Color::head('Help:') . PHP_EOL;
        echo Color::colorize('  GeoIP函数测试') . PHP_EOL . PHP_EOL;

        echo Color::head('Usage:') . PHP_EOL;
        echo Color::colorize('  php run Test\\\\Geoip [action]', Color::FG_GREEN) . PHP_EOL . PHP_EOL;

        echo Color::head('Actions:') . PHP_EOL;
        echo Color::colorize('  asnum           获取自治系统号(ASN)', Color::FG_GREEN) . PHP_EOL;
    }

    public function asnumAction()
    {
        $ip = '121.21.193.38';
        echo Color::colorize("geoip_asnum_by_name", Color::FG_LIGHT_GREEN) . PHP_EOL;
        echo Color::head($ip) . PHP_EOL;
        echo Color::colorize(geoip_asnum_by_name($ip), Color::FG_LIGHT_GREEN);
    }

}