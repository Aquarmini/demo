<?php
// +----------------------------------------------------------------------
// | 测试脚本 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Tasks\Test;

use Phalcon\Cli\Task;
use limx\phalcon\Cli\Color;
use MongoDB;

class MongoDBTask extends Task
{
    public function mainAction()
    {
        echo Color::head('Help:') . PHP_EOL;
        echo Color::colorize('  PHP函数参数测试') . PHP_EOL . PHP_EOL;

        echo Color::head('Usage:') . PHP_EOL;
        echo Color::colorize('  php run Test\\\\MongoDB [action]', Color::FG_GREEN) . PHP_EOL . PHP_EOL;

        echo Color::head('Actions:') . PHP_EOL;
        echo Color::colorize('  insert         新建记录', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  query          查询记录', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  update         更新记录', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  delete         删除记录', Color::FG_GREEN) . PHP_EOL;
    }

    private function mongoManager()
    {
        $config = di('config');
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
    }

    public function deleteAction()
    {
        // $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
        $manager = $this->mongoManager("mongodb://localhost:27017");
        $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);

        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->delete(['id' => 1], ['limit' => 0]);   // limit 为 1 时，删除第一条匹配数据
        $bulk->delete(['id' => 2], ['limit' => 0]);   // limit 为 0 时，删除所有匹配数据
        $bulk->delete(['id' => 3], ['limit' => 0]);   // limit 为 0 时，删除所有匹配数据

        $result = $manager->executeBulkWrite('phalcon.user', $bulk, $writeConcern);
    }

    public function updateAction()
    {
        // $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
        $manager = $this->mongoManager("mongodb://localhost:27017");
        $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);

        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update(
            ['id' => 2],
            ['$set' => ['name' => '修改后']],
            ['multi' => false, 'upsert' => false]
        );

        $result = $manager->executeBulkWrite('phalcon.user', $bulk, $writeConcern);
    }

    public function queryAction()
    {
        // $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
        $manager = $this->mongoManager("mongodb://localhost:27017");
        // sleep(1);
        $filter = ['id' => ['$gt' => 1]];
        $options = [
            'projection' => ['_id' => 0],
            'sort' => ['id' => -1],
        ];

        // 查询数据
        $query = new MongoDB\Driver\Query($filter, $options);
        $cursor = $manager->executeQuery('phalcon.user', $query);

        foreach ($cursor as $document) {
            dump($document);
        }
    }

    public function insertAction()
    {
        // $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
        $manager = $this->mongoManager("mongodb://localhost:27017");
        $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
        $bulk = new MongoDB\Driver\BulkWrite;

        $document = ['id' => 1, 'name' => '李铭昕' . uniqid()];
        $bulk->insert($document);
        $document = ['id' => 2, 'name' => '李铭昕' . uniqid()];
        $bulk->insert($document);
        $document = ['id' => 3, 'name' => '李铭昕' . uniqid()];
        $bulk->insert($document);

        $result = $manager->executeBulkWrite('phalcon.user', $bulk, $writeConcern);
    }

}