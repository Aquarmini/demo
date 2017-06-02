<?php
// +----------------------------------------------------------------------
// | TestServiceProvider.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Logics\TestPimple;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class TestServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['test'] = function ($pimple) {
            $test['config'] = $pimple['config'];
            $test['time'] = date("Y-m-d H:i:s");

            return $test;
        };

        // $pimple['test'] = $pimple->factory(function ($pimple) {
        //     $test['config'] = $pimple['config'];
        //     $test['time'] = date("Y-m-d H:i:s");
        //
        //     return $test;
        // });
    }

}