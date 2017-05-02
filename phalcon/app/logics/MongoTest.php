<?php
// +----------------------------------------------------------------------
// | MongoDB与Mysql 的增删改查 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Logics;

use App\Models\User;
use App\Utils\Mongo;
use Phalcon\Di\Injectable;

class MongoTest extends Injectable
{
    protected $expiretime = 3600;
    protected $expireField = 'timerd';
    protected $table = 'user';

    public function getExpireTimeArray()
    {
        return [$this->expireField => Mongo::datetime()];
    }

    public function getProjection()
    {
        return ['_id' => 0, 'timerd' => 0];
    }


    public function insertWithCache()
    {
        // DB 存储
        $user = new User();
        $user->username = "MongoTestUserName" . uniqid();
        $user->password = md5("MongoTestPassWord" . uniqid());
        $user->name = "MongoTest" . uniqid();
        $user->email = "715557344@qq.com";
        $user->role_id = 1;
        if ($user->save()) {
            // 构建缓存 MongoDB 存储
            $filter = ['id' => $user->id];
            $document = array_merge($user->toArray(), $this->getExpireTimeArray());
            $options = [
                Mongo::OPTION_UPSERT => true,
                Mongo::OPTION_MULTI => true,
            ];
            $result = Mongo::update($this->table, $filter, $document, $options);
            return true;
        }
        return false;
    }

    public function updateWithCache()
    {
        $id = 1;
        $user = User::findFirst($id);
        $user->name = "MongoTestName" . uniqid();
        if ($user->save()) {
            // 构建缓存 MongoDB 存储
            $filter = ['id' => $id];
            $document = array_merge(['name' => $user->name], $this->getExpireTimeArray());
            $options = [
                Mongo::OPTION_UPSERT => true,
                Mongo::OPTION_MULTI => true,
            ];
            $result = Mongo::update($this->table, $filter, $document, $options);
            return true;
        }
        return false;
    }

    public function findFirstWithCache()
    {
        $id = 1;
        $requireFields = ['id', 'username', 'password', 'name', 'role_id', 'created_at', 'updated_at'];
        // 获取缓存
        $filter = ['id' => $id];
        $options = ['projection' => $this->getProjection()];
        $result = Mongo::query($this->table, $filter, $options);
        if ($result) {
            // 判断字段
            $cacheCanUse = true;
            foreach ($requireFields as $field) {
                if (isset($result[0]->$field)) {
                    continue;
                }
                $cacheCanUse = false;
                break;
            }
            if ($cacheCanUse) {
                return $result[0];
            }
        }

        $user = User::findFirst([
            'conditions' => 'id=?0',
            'bind' => $id,
            // 'columns' => $requireFields
        ]);
        if ($user) {
            // 构建缓存
            $filter = ['id' => $user->id];
            $document = array_merge($user->toArray(), $this->getExpireTimeArray());
            $options = [
                Mongo::OPTION_UPSERT => true,
                Mongo::OPTION_MULTI => true,
            ];
            $result = Mongo::update($this->table, $filter, $document, $options);
            return $user;
        }

        return false;
    }

}