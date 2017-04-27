<?php
// +----------------------------------------------------------------------
// | 测试脚本 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Tasks\Test;

use App\Utils\Mongo;
use Phalcon\Cli\Task;
use limx\phalcon\Cli\Color;
use MongoDB;
use MongoDB\BSON\Timestamp;


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

        echo Color::colorize('  utilQuery      工具类查询记录', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  utilInsert     工具类插入记录', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  utilUpdate     工具类更新记录', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  utilDelete     工具类删除记录', Color::FG_GREEN) . PHP_EOL;
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

    public function utilQueryAction()
    {
        $filter = ['id' => [Mongo::_GT => 1]];
        $filter = ['id' => 999];
        $options = [
            'projection' => ['_id' => 0],
            'sort' => ['id' => Mongo::SORT_DESC],
            // Mongo::OPTION_LIMIT => '1',
        ];
        $res = Mongo::query('user', $filter, $options);
        foreach ($res as $row) {
            echo Color::colorize(json_encode($row)), PHP_EOL;
        }
    }

    public function utilInsertAction($params = [])
    {
        $num = 1;
        if (isset($params[0])) {
            $num = $params[0];
        }
        if (!is_numeric($num)) {
            echo Color::error("条数必须为数字！"), PHP_EOL;
            return;
        }
        if ($num == 1) {
            $time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
            $document = ['id' => rand(1, 5), 'name' => uniqid(), 'create_at' => $time];
            $result = Mongo::insert('user', $document);
        } else {
            $documents = [];
            for ($i = 0; $i < $num; $i++) {
                $time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
                $documents[] = ['id' => rand(1, $num), 'name' => uniqid(), 'create_at' => $time];
            }
            $result = Mongo::insert('user', $documents, false);
        }

        echo Color::colorize("insertedCount： " . $result->getInsertedCount()), PHP_EOL;
        echo Color::colorize("upsertedIds： " . json_encode($result->getUpsertedIds())), PHP_EOL;
    }

    public function utilUpdateAction()
    {
        $filter = ['id' => 999];
        $document = ['name' => uniqid(), 'changed' => 2];
        $options = [
            Mongo::OPTION_UPSERT => true,
            Mongo::OPTION_MULTI => true,
        ];
        $result = Mongo::update('user', $filter, $document, $options);
        echo Color::colorize("modifiedCount： " . $result->getModifiedCount()), PHP_EOL;
        echo Color::colorize("upsertedCount： " . $result->getUpsertedCount()), PHP_EOL;

    }

    public function utilDeleteAction()
    {
        $filter = ['id' => 4];
        $filter = ['id' => [Mongo::_GT => 0]];
        $options = [Mongo::OPTION_LIMIT => false];
        $result = Mongo::delete('user', $filter, $options);
        echo Color::colorize("deletedCount： " . $result->getDeletedCount()), PHP_EOL;
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