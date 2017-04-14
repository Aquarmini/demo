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
use ZipArchive;

class ZipTask extends Task
{
    public function MainAction()
    {   //加压缩文件
        $zip = new ZipArchive();
        $file = BASE_PATH . "/public/uploads/file.zip";
        $output = BASE_PATH . "/public/uploads/";
        // open archive
        if ($zip->open($file) !== TRUE) {
            echo "Don`t find zip file!\n";
            return;
        }
        // extract contents to destination directory
        if (!is_dir($output)) {
            mkdir($output, 0777, true);
        }
        $zip->extractTo($output);
        // close archive
        $zip->close();
        echo "SUCCESS\n";
    }
}