<?php
// +----------------------------------------------------------------------
// | Mongo.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Utils;

use MongoDB\BSON\UTCDateTime;
use MongoDB\Driver\Query;
use MongoDB\Driver\WriteConcern;
use MongoDB\Driver\BulkWrite;

class Mongo
{
    // 大于
    const _GT = '$gt';
    // 大于等于
    const _GTE = '$gte';
    // 小于
    const _LT = '$lt';
    // 小于等于
    const _LTE = '$lte';
    // 不等于
    const _NE = '$ne';
    // 匹配键值等于指定数组中任意值的文档。类似sql中in.
    const _IN = '$in';
    // 匹配键不存在或者键值不等于指定数组的任意值的文档。类似sql中not in(SQL中字段不存在使用会有语法错误).
    const _NIN = '$nin';
    // 如果$exists的值为true,选择存在该字段的文档；若值为false则选择不包含该字段的文档(我们上面在查询键值为null的文档时使用"$exists"判定集合中文档是否包含该键)。
    const _EXISTS = '$exists';
    // 匹配那些指定键的键值中包含数组，而且该数组包含条件指定数组的所有元素的文档
    const _ALL = '$all';
    // 用其查询指定长度的数组。
    const _SIZE = '$size';
    // 指定一个至少包含两个表达式的数组，选择出满足该数组中所有表达式的文档。$and操作符使用短路操作，若第一个表达式的值为“false”,余下的表达式将不会执行。
    // 查询name键值为“t1”,amount键值小于50的文档：db.inventory.find({ $and: [ { name: "t1" }, { amount: { $lt：50 } } ] } )
    const _AND = '$and';
    // 执行逻辑NOR运算,指定一个至少包含两个表达式的数组，选择出都不满足该数组中所有表达式的文档。
    // 查询name键值不为“t1”,amount键值不小于50的文档：db.inventory.find( { $nor: [ { name: "t1" }, { qty: { $lt: 50 } } ] } )
    const _NOR = '$nor';
    // 执行逻辑NOT运算，选择出不能匹配表达式的文档 ，包括没有指定键的文档。$not操作符不能独立使用，必须跟其他操作一起使用（除$regex）。
    const _NOT = '$not';
    // 执行逻辑OR运算,指定一个至少包含两个表达式的数组，选择出至少满足数组中一条表达式的文档。
    const _OR = '$or';
    // 匹配字段值对（divisor）取模，值等于（remainder）的文档。
    const _MOD = '$mod';
    // 操作符查询中可以对字符串的执行正则匹配。 MongoDB使用Perl兼容的正则表达式（PCRE)库来匹配正则表达式.
    const _REGEX = '$regex';
    // 操作符功能强大而且灵活，他可以使用任意的JavaScript作为查询的一部分,包含JavaScript表达式的字符串或者JavaScript函数。
    // JavaScrip字符串形式
    // db.fruit.find( { $where: "this.banana == this.peach" } )
    // db.fruit.find( { $where: "obj.banana == obj.peach" } )

    // JavaScript函数形式
    // db.fruit.find( { $where: function() { return (this.banana == this.peach) } } )
    // db.fruit.find( { $where: function() { return obj.banana == obj.peach; } } )
    const _WHERE = '$where';

    const _SET = '$set';

    const SORT_ASC = 1;
    const SORT_DESC = -1;

    // Update:
    // If filter does not match an existing document, insert a single document.
    // The document will be created from newObj if it is a replacement document (i.e. no update operators);
    // otherwise, the operators in newObj will be applied to filter to create the new document.
    const OPTION_UPSERT = 'upsert';
    // Update only the first matching document (multi=false), or all matching documents (multi=true).
    const OPTION_MULTI = 'multi';

    const OPTION_COLLATION = 'collation';

    // Delete all matching documents (FALSE), or only the first matching document (TRUE)
    const OPTION_LIMIT = 'limit';

    protected static function manager()
    {
        return di('mongoManager');
    }

    protected static function config()
    {
        return di('config')->mongo;
    }

    /**
     * @desc   查询
     * @author limx
     * @param $filter
     * @param $options
     * @return array(obj,obj)
     *
     *  $filter = ['id' => ['$gt' => 1]];
     *  $options = [
     *      'projection' => ['_id' => 0],
     *      'sort' => ['id' => -1],
     *  ];
     */
    public static function query($table, $filter = [], $options = [])
    {
        $db = static::config()->db;
        $manager = static::manager();

        $namespace = sprintf("%s.%s", $db, $table);
        $query = new Query($filter, $options);
        $cursor = $manager->executeQuery($namespace, $query);
        return $cursor->toArray();
    }

    /**
     * @desc   插入数据
     * @author limx
     * @param       $document
     * @param  bool $single
     * @return \MongoDB\Driver\WriteResult;
     */
    public static function insert($table, $document, $single = true)
    {
        $db = static::config()->db;
        $manager = static::manager();

        $writeConcern = new WriteConcern(WriteConcern::MAJORITY, 1000);
        $bulk = new BulkWrite();

        if ($single) {
            $bulk->insert($document);
        } else {
            foreach ($document as $doc) {
                $bulk->insert($doc);
            }
        }
        $namespace = sprintf("%s.%s", $db, $table);

        return $manager->executeBulkWrite($namespace, $bulk, $writeConcern);
    }

    /**
     * @desc
     * @author limx
     * @param       $table
     * @param       $filter
     * @param       $newObj
     * @param array $updateOptions
     * @return \MongoDB\Driver\WriteResult;
     */
    public static function update($table, $filter, $newObj, array $updateOptions = [])
    {
        $db = static::config()->db;
        $manager = static::manager();

        $writeConcern = new WriteConcern(WriteConcern::MAJORITY, 1000);
        $bulk = new BulkWrite();

        $bulk->update($filter, [static::_SET => $newObj], $updateOptions);
        $namespace = sprintf("%s.%s", $db, $table);

        return $manager->executeBulkWrite($namespace, $bulk, $writeConcern);
    }

    /**
     * @desc
     * @author limx
     * @param       $table
     * @param       $filter
     * @param array $deleteOptions
     * @return \MongoDB\Driver\WriteResult;
     */
    public static function delete($table, $filter, array $deleteOptions = [])
    {
        $db = static::config()->db;
        $manager = static::manager();

        $writeConcern = new WriteConcern(WriteConcern::MAJORITY, 1000);
        $bulk = new BulkWrite();

        $bulk->delete($filter, $deleteOptions);
        $namespace = sprintf("%s.%s", $db, $table);

        return $manager->executeBulkWrite($namespace, $bulk, $writeConcern);
    }

    /**
     * @desc   获取mongodb的时间类型
     * @author limx
     * @param  int $microtime 毫秒数 microtime(true) * 1000
     */
    public static function datetime($microtime = null)
    {
        if (!isset($microtime)) {
            $microtime = microtime(true) * 1000;
        }
        return new UTCDateTime($microtime);
    }


}