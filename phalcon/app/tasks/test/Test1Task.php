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

class Test1Task extends Task
{
    private $money = [5, 15, 50, 99, 299, 999];

    public function mainAction()
    {
        foreach ($this->money as $money) {
            $mei = $money - (floatval($money) * 0.029 + 0.3);
            $ren = $mei * 7;
            $coin = $ren * 10;
            echo "$" . $money . "->" . $coin . "\n";
        }
    }
}