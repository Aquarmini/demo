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

class PasswordTask extends Task
{
    public function mainAction()
    {
        echo Color::head('Help:') . PHP_EOL;
        echo Color::colorize('  Password Hashing函数测试') . PHP_EOL . PHP_EOL;

        echo Color::head('Usage:') . PHP_EOL;
        echo Color::colorize('  php run Test\\\\Password [action]', Color::FG_GREEN) . PHP_EOL . PHP_EOL;

        echo Color::head('Actions:') . PHP_EOL;
        echo Color::colorize('  check           密码测试', Color::FG_GREEN) . PHP_EOL;
    }

    public function checkAction()
    {
        $pwd = '910123';
        echo Color::colorize("密码是{$pwd}", Color::FG_LIGHT_GREEN) . PHP_EOL;
        echo Color::head("加密后") . PHP_EOL;
        $hash = password_hash($pwd, PASSWORD_DEFAULT);
        echo Color::colorize("  " . $hash) . PHP_EOL;
        echo Color::head("密码验证") . PHP_EOL;
        if (password_verify($pwd, $hash)) {
            echo Color::success("密码验证成功！");
        } else {
            echo Color::error("密码验证错误！");
        }
    }

}