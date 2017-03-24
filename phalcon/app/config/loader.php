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
            'MyApp\Controllers' => $config->application->controllersDir,
            'MyApp\Controllers\Admin' => $config->application->controllersDir . 'admin/',
            'MyApp\Controllers\Test' => $config->application->controllersDir . 'test/',
            'MyApp\Models' => $config->application->modelsDir,
            'MyApp\Models\Test' => $config->application->modelsDir . 'test/',
            'MyApp\Tasks' => $config->application->tasksDir,
            'MyApp\Tasks\System' => $config->application->tasksDir . 'system/',
            'MyApp\Tasks\Test' => $config->application->tasksDir . 'test/',
            'MyApp\Tasks\Swoole' => $config->application->tasksDir . 'swoole/',
            'MyApp\Traits' => $config->application->traitsDir,
            'MyApp\Traits\System' => $config->application->traitsDir . 'system/',
            'MyApp\Traits\Test' => $config->application->traitsDir . 'test/',
            'MyApp\Listeners' => $config->application->listenersDir,
            'MyApp\Listeners\System' => $config->application->listenersDir . 'system/',
        ]
    )->registerFiles(
        [
            'function' => $config->application->libraryDir . 'helper.php',
        ]
    )->register();
