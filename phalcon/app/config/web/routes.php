<?php
/**
 * 路由文件
 * 必须精确到控制器 App\Controllers\IndexController除外
 */
$router = new Phalcon\Mvc\Router(false);

$router->add('/:controller/:action/:params', [
    'namespace' => 'App\Controllers',
    'controller' => 1,
    'action' => 2,
    'params' => 3,
]);

$router->add('/:controller', [
    'namespace' => 'App\Controllers',
    'controller' => 1
]);

$router->add('/test/:controller/:action/:params', [
    'namespace' => 'App\Controllers\Test',
    'controller' => 1,
    'action' => 2,
    'params' => 3,
]);

$router->add('/test/:controller', [
    'namespace' => 'App\Controllers\Test',
    'controller' => 1
]);

$router->add('/admin/:controller/:action/:params', [
    'namespace' => 'App\Controllers\Admin',
    'controller' => 1,
    'action' => 2,
    'params' => 3,
]);

$router->add('/admin/:controller', [
    'namespace' => 'App\Controllers\Admin',
    'controller' => 1
]);

$router->add('/myrouter/:int/:controller/:action/:params', [
    'namespace' => 'App\Controllers\Test',
    'controller' => 2,
    'action' => 3,
    'params' => 4,
    'version' => 1,
]);

$router->add('/myrouter/:int/:controller', [
    'namespace' => 'App\Controllers\Test',
    'controller' => 2,
    'version' => 1,
]);

// 显性路由测试
$router->add('/route/index/index', 'App\\Controllers\\Route\\Index::index');

// 路由分组测试
$test = new \Phalcon\Mvc\Router\Group();
$test->setPrefix('/route');
$test->add('/index/group', 'App\\Controllers\\Route\\Index::group');
$router->mount($test);

return $router;
