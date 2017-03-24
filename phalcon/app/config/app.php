<?php
// +----------------------------------------------------------------------
// | APP ENV [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lmx0536.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <http://www.lmx0536.cn>
// +----------------------------------------------------------------------
return [
    'project-name' => 'limx-phalcon-ss-project',
    // 定时执行的脚本
    'cron-tasks' => [
        ['task' => 'Test\\Test1', 'action' => 'main', 'params' => [], 'time' => '10:02'],
        ['task' => 'Test\\Test2', 'action' => 'main', 'params' => [1], 'time' => ''],

        // ['task' => 'System\\Cron', 'action' => 'test', 'params' => [], 'time' => '05:21'],
    ],
    'error-code' => [
        500 => '服务器错误！',
    ],
];