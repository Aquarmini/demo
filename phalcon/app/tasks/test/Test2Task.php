<?php
// +----------------------------------------------------------------------
// | Demo [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lmx0536.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <http://www.lmx0536.cn>
// +----------------------------------------------------------------------
// | Date: 2016/12/26 Time: 9:51
// +----------------------------------------------------------------------
namespace MyApp\Tasks\Test;

use Phalcon\Cli\Task;
use limx\phalcon\Cli\Color;

class Test2Task extends Task
{
    public function mainAction($params = [])
    {
        $a = "0e11111111111111";
        $b = "0e22222222222222";
        if ($a === $b) {
            echo Color::success("相等");
        } else {
            echo Color::error("不相等");
        }
    }

}