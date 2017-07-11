<?php
// +----------------------------------------------------------------------
// | Config.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Library\Alipay\Mapi;

use limx\Support\Str;

class Config implements \ArrayAccess
{
    public $item = [];

    public function __construct()
    {
        $this->item['partner'] = env('MONSTER_ALIPAY_PID');
        $this->item['key'] = env('MONSTER_ALIPAY_MD5_KEY');
        $this->item['seller_id'] = env("MONSTER_ALIPAY_SELLERID");
        $this->item['sign_type'] = strtoupper('MD5');
        $this->item['input_charset'] = strtolower('UTF-8');
        $this->item['cacert'] = ROOT_PATH . '/data/wechat/cacert.pem';
        $this->item['transport'] = 'http';
    }

    public function offsetExists($offset)
    {
        return isset($this->item[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->item[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->item[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->item[$offset]);
    }

}