<?php
// +----------------------------------------------------------------------
// | PhalconTask.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Tasks\Test;

use Phalcon\Cli\Task;
use limx\phalcon\Cli\Color;
use Phalcon\Annotations\Adapter\Memory as MemoryAdapter;

class PhalconTask extends Task
{
    public function mainAction()
    {
        echo Color::head('Help:') . PHP_EOL;
        echo Color::colorize('  Phalcon框架功能测试') . PHP_EOL . PHP_EOL;

        echo Color::head('Usage:') . PHP_EOL;
        echo Color::colorize('  php run Test\\\\Phalcon [action]', Color::FG_GREEN) . PHP_EOL . PHP_EOL;

        echo Color::head('Actions:') . PHP_EOL;
        echo Color::colorize('  annotations         注释解析器', Color::FG_GREEN) . PHP_EOL;
    }

    /**
     * @desc
     * @author limx
     */
    public function annotationsAction()
    {
        $reader = new MemoryAdapter();

        // 反射在Example类的注释
        $methods = $reader->getMethods("App\\Tasks\\Test\\PhalconTask");
        foreach ($methods as $method) {
            // 读取类中注释块中的注释
            $annotations = $method->getAnnotations();

            // 遍历注释
            foreach ($annotations as $annotation) {
                // 打印注释名称
                echo $annotation->getName(), PHP_EOL;

                // 打印注释参数个数
                echo $annotation->numberArguments(), PHP_EOL;

                // 打印注释参数
                print_r($annotation->getArguments());
            }
        }
    }
}