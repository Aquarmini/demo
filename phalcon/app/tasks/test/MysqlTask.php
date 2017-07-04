<?php
// +----------------------------------------------------------------------
// | 测试脚本 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Tasks\Test;

use App\Models\UserTitle;
use limx\Support\Str;
use Phalcon\Cli\Task;
use limx\phalcon\Cli\Color;
use App\Logics\Test;

class MysqlTask extends Task
{
    public function mainAction()
    {
        echo Color::head('Help:') . PHP_EOL;
        echo Color::colorize('  PHP函数参数测试') . PHP_EOL . PHP_EOL;

        echo Color::head('Usage:') . PHP_EOL;
        echo Color::colorize('  php run Test\\\\Arg [action]', Color::FG_GREEN) . PHP_EOL . PHP_EOL;

        echo Color::head('Actions:') . PHP_EOL;
        echo Color::colorize('  inc             新建记录如果重复则修改', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  modelSave       通过模型新建数据', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  modelUpdate     通过模型更新数据', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  modelUpdateNoIndex     通过模型更新没有主键的数据', Color::FG_GREEN) . PHP_EOL;
    }

    public function modelUpdateNoIndexAction()
    {
        $res = UserTitle::findFirst([
            'conditions' => 'uid=?0',
            'bind' => [1, 8],
        ]);
        print_r($res->toArray());
        $res->title_id = 4;
        $res->save();
    }

    public function modelSaveAction()
    {
        $user = new \App\Models\User();
        $user->username = Str::quickRandom(12);
        $user->name = '测试';
        $user->password = md5(910123);
        $user->role_id = 1;
        $res = $user->save();
        print_r($res);
    }

    public function modelUpdateAction()
    {
        $user = \App\Models\User::findFirst(24);
        $user->username = Str::quickRandom(12);
        $res = $user->save();
        print_r($res);

        $user = \App\Models\User::findFirst([
            'conditions' => 'id=?0',
            'bind' => [24],
            // 'columns' => 'id,username',
        ]);
        $user->username = Str::quickRandom(12);
        $res = $user->save();
        print_r($res);

        // $user = new \App\Models\User();
        // $user->id = 24;
        // $user->username = Str::quickRandom(6);
        // $res = $user->save();
        // print_r($res);
    }

    public function incAction()
    {
        $res = (new Test())->incrSql();
        dump($res);
    }

}