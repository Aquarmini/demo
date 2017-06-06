<?php

namespace App\Models\RedisModel;

use limx\utils\RedisModel\Model;

class BaseModel extends Model
{
    protected function initRedisClient($parameters, $options)
    {
        if (!isset($parameters['host'])) {
            $parameters['host'] = env('REDIS_HOST', 'localhost');
        }

        if (!isset($parameters['port'])) {
            $parameters['port'] = env('REDIS_PORT', 6379);
        }

        if (!isset($parameters['auth'])) {
            $parameters['password'] = env('REDIS_AUTH', null);
        }

        if (!isset($parameters['database'])) {
            $parameters['database'] = env('REDIS_INDEX', 0);
        }

        parent::initRedisClient($parameters, $options);
    }
}