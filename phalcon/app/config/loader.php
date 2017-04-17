<?php
// +----------------------------------------------------------------------
// | 自动加载文件 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader
    ->registerNamespaces(
        [
            'App\Controllers' => $config->application->controllersDir,
            'App\Controllers\Admin' => $config->application->controllersDir . 'admin/',
            'App\Controllers\Test' => $config->application->controllersDir . 'test/',

            'App\Listeners' => $config->application->listenersDir,
            'App\Listeners\System' => $config->application->listenersDir . 'system/',

            'App\Logics' => $config->application->logicsDir,

            'App\Models' => $config->application->modelsDir,

            'App\Tasks' => $config->application->tasksDir,
            'App\Tasks\System' => $config->application->tasksDir . 'system/',
            'App\Tasks\Test' => $config->application->tasksDir . 'test/',
            'App\Tasks\Swoole' => $config->application->tasksDir . 'swoole/',

            'App\Traits' => $config->application->traitsDir,
            'App\Traits\System' => $config->application->traitsDir . 'system/',
            'App\Traits\Test' => $config->application->traitsDir . 'test/',

            'App\Utils' => $config->application->utilsDir,
        ]
    )->registerFiles(
        [
            'function' => $config->application->libraryDir . 'helper.php',
        ]
    )->register();
