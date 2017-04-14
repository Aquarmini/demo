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

class OAuthTask extends Task
{
    public function mainAction()
    {
        if (!extension_loaded('oauth')) {
            echo Color::error('The oauth extension is not installed');
            return;
        }
        echo Color::head('Help:') . PHP_EOL;
        echo Color::colorize('  OAuth测试') . PHP_EOL . PHP_EOL;

        echo Color::head('Usage:') . PHP_EOL;
        echo Color::colorize('  php run Test\\\\OAuth [action]', Color::FG_GREEN) . PHP_EOL . PHP_EOL;

        echo Color::head('Actions:') . PHP_EOL;
        echo Color::colorize('  func            普通函数测试', Color::FG_GREEN) . PHP_EOL;
    }

    public function funcAction()
    {
        $url = "http://demo.phalcon.lmx0536.cn/test/api/api";
        echo Color::head("oauth_urlencode(url)") . PHP_EOL;
        echo Color::colorize("  " . oauth_urlencode($url)) . PHP_EOL;

        echo Color::head("urlencode(url)") . PHP_EOL;
        echo Color::colorize("  " . urlencode($url)) . PHP_EOL;

        echo Color::head("oauth_get_sbs(method ,url ,params)") . PHP_EOL;
        echo Color::colorize("  " . oauth_get_sbs('GET', $url, ['a' => 1])) . PHP_EOL;
    }
}