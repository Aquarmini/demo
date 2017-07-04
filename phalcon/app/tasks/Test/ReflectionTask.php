<?php

namespace App\Tasks\Test;

use App\Models\User;
use limx\phalcon\Cli\Color;

class ReflectionTask extends \Phalcon\Cli\Task
{

    public function mainAction()
    {
        echo Color::head('Help:'), PHP_EOL;
        echo Color::colorize('  PHP 反射类测试'), PHP_EOL, PHP_EOL;

        echo Color::head('Usage:'), PHP_EOL;
        echo Color::colorize('  php run Test\\\\Reflection [action]', Color::FG_GREEN), PHP_EOL, PHP_EOL;

        echo Color::head('Actions:'), PHP_EOL;
        echo Color::colorize('  params        检测某方法的传入参数，并对其进行处理', Color::FG_GREEN), PHP_EOL;

    }

    public function paramsAction()
    {
        $user = User::findFirst(1);
        $params = [$user->id, $user->name, $user];
        $request = [];
        foreach ($params as $param) {
            if (is_object($param)) {
                $request['obj'] = $param;
            } else {
                $request['var'][] = $param;
            }
        }
        $class = new \ReflectionClass(\App\Tasks\Test\ReflectionTask::class);
        $method = $class->getMethod('test');
        $fields = [];
        print_r($method);
        print_r($method->getParameters());
        $i = 0;
        foreach ($method->getParameters() as $key => $param) {
            if ($param->getClass()) {
                $fields[] = $request['obj'];
                continue;
            }
            $fields[] = $request['var'][$i];
            $i++;
        }

        // print_r($fields);
        $res = static::test(...$fields);
        print_r($res);
    }

    private function test(User $user, $id, $name)
    {
        if ($id == $user->id && $name == $user->name) {
            return true;
        }
        return false;
    }
}

