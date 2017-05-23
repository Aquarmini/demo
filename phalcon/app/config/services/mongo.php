<?php
// +----------------------------------------------------------------------
// | mongo.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
if ($config->mongo->utils) {
    $di->setShared('mongoManager', function () use ($config) {
        $host = $config->mongo->host;
        $port = $config->mongo->port;
        $uri = "mongodb://{$host}:{$port}";
        $options = [
            'connect' => $config->mongo->connect, // true表示Mongo构造函数中建立连接。
            'timeout' => $config->mongo->timeout, // 配置建立连接超时时间，单位是ms
            'replicaSet' => $config->mongo->replicaSet, // 配置replicaSet名称
            'username' => $config->mongo->username, // 覆盖$server字符串中的username段，如果username包含冒号:时，选用此种方式。
            'password' => $config->mongo->password, // 覆盖$server字符串中的password段，如果password包含符号@时，选用此种方式。
            'db' => $config->mongo->db // 覆盖$server字符串中的database段
        ];
        return new MongoDB\Driver\Manager($uri, $options);
    });
}