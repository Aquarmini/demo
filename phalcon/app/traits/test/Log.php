<?php
// +----------------------------------------------------------------------
// | TRAIT RESPONSE [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lmx0536.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <http://www.lmx0536.cn>
// +----------------------------------------------------------------------
// | Date: 2017/1/8 Time: 上午11:31
// +----------------------------------------------------------------------
namespace App\Traits\Test;

use limx\phalcon\Logger;

trait Log
{
    protected static function logInfo($msg, $file)
    {
        $logger = Logger::getInstance('file', $file, 'system');
        $logger->info($msg);
    }
}