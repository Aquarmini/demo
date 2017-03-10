<?php
// +----------------------------------------------------------------------
// | Demo [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lmx0536.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <http://www.lmx0536.cn>
// +----------------------------------------------------------------------
// | Date: 2017/2/1 Time: 下午9:11
// +----------------------------------------------------------------------
namespace MyApp\Tasks\Test;

use Phalcon\Cli\Task;
use limx\func\Curl;

class CarPeccancyTask extends Task
{
    public function mainAction()
    {
        $data['hpzl'] = '02';// 号牌种类
        $data['fzjg'] = 'G';
        $data['hphm'] = env('HPHM');// 号牌号码
        $data['clsbdh'] = env('CLSBDH');// 车辆识别代号
        $data['type'] = 'wfcx';
        $res = Curl::post("http://119.191.61.214:9080/wscgsxxcx/jdcwfcx.do", $data);
        preg_match('/name="state" value="(.*)"/', $res, $ret);
        //print_r($ret);
        echo $ret[1];
    }
}