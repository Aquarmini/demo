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

class CompareTask extends Task
{
    private $data = [
        [1, '1'],
        ['1', '1'],
        ['0e11111111', '0e22222'],
        ['111111111111111111111111a', '111111111111111111111111b'],
        ['0111', '111'],
        [0777, '777'],
        [0777, 777],
        [0, 'a'],
        [0, null],
        ['a', null],
        [0, '0'],
    ];

    public function mainAction()
    {
        foreach ($this->data as $item) {
            if ($item[0] == $item[1]) {
                echo Color::success(sprintf("%s==%s  成功", $item[0], $item[1]));
            } else {
                echo Color::error(sprintf("%s==%s  不成功", $item[0], $item[1]));
            }
            if ($item[0] === $item[1]) {
                echo Color::success(sprintf("%s===%s  成功", $item[0], $item[1]));
            } else {
                echo Color::error(sprintf("%s===%s  不成功", $item[0], $item[1]));
            }
        }
    }

}