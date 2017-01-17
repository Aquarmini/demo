<?php
// +----------------------------------------------------------------------
// | Demo [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lmx0536.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <http://www.lmx0536.cn>
// +----------------------------------------------------------------------
// | Date: 2017/1/17 Time: 下午1:41
// +----------------------------------------------------------------------
namespace App\Http\Traits;

trait Response
{
    public static function success($data = [], $msg = '')
    {
        $data['status'] = 1;
        $data['data'] = $data;
        $data['msg'] = $msg;
        $data['timestamp'] = time();
        return response()->json($data);
    }

    public static function error($msg = '', $status = 0, $data = [])
    {
        $data['status'] = $status;
        $data['data'] = $data;
        $data['msg'] = $msg;
        $data['timestamp'] = time();
        return response()->json($data);
    }
}