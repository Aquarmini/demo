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
use Phalcon\Http\Client\Provider\Exception;

class IconvTask extends Task
{
    public function mainAction()
    {
        $input = "public/iconv/input/";
        foreach (glob($input . "*.lrc") as $file) {
            try {
                $name = [];
                preg_match("/\w+.lrc$/", $file, $name);
                $content = file_get_contents($file);
                $res = iconv('GBK', 'UTF-8', $content);
                if (empty($res)) {
                    $res = iconv('', 'UTF-8', $content);
                } else if (empty($res)) {
                    $res = $content;
                }
                file_put_contents("public/iconv/output/{$name[0]}", $res);
            } catch (Exception $e) {
                echo $name[0] . "失败\n";
            }
        }
    }
}