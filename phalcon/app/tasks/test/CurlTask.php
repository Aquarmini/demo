<?php
// +----------------------------------------------------------------------
// | CurlTask Curl测试 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lmx0536.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <http://www.lmx0536.cn>
// +----------------------------------------------------------------------
// | Date: 2017/3/3 Time: 上午11:18
// +----------------------------------------------------------------------
namespace MyApp\Tasks\Test;

use Phalcon\Cli\Task;
use limx\phalcon\Cli\Color;

class CurlTask extends Task
{
    public function mainAction()
    {
        echo Color::head('Help:') . PHP_EOL;
        echo Color::colorize('  Curl测试') . PHP_EOL . PHP_EOL;

        echo Color::head('Usage:') . PHP_EOL;
        echo Color::colorize('  php run Test\\\\Curl [action]', Color::FG_GREEN) . PHP_EOL . PHP_EOL;

        echo Color::head('Actions:') . PHP_EOL;
        echo Color::colorize('  getbaidu    [--error]   获取百度首页页面', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  get         [...$1]     GET方法', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  postUrl     [...$1]     POST http_build_query方法', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  postJson    [...$1]     POST json方法', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  postHttps   [...$1]     POST Https', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  header      [...$1]     获取Curl请求头的方法', Color::FG_GREEN) . PHP_EOL;

    }

    public function headerAction($params)
    {
        $res = [];
        foreach ($params as $i => $param) {
            $res['key' . $i] = $param;
        }
        $body = http_build_query($res);
        $url = "http://demo.phalcon.lmx0536.cn/test/api/api";
        $ch = curl_init();
        // 设置抓取的url
        curl_setopt($ch, CURLOPT_URL, $url);
        // 启用时会将头文件的信息作为数据流输出。
        curl_setopt($ch, CURLOPT_HEADER, false);
        // 启用时将获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 启用时会将服务器服务器返回的"Location: "放在header中递归的返回给服务器，使用CURLOPT_MAXREDIRS可以限定递归返回的数量。
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        // 设置访问 方法
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        // 设置POST BODY
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

        // 设置header
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'APPID:' . uniqid(),
            'APPSECRET:' . md5(uniqid()),
        ]);

        // 设置可以查看请求头的参数
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);

        //执行命令
        $result = curl_exec($ch);
        if ($result === false) {
            echo 'Curl error: ' . curl_error($ch);
            return false;
        }
        // 返回请求头
        $header = curl_getinfo($ch, CURLINFO_HEADER_OUT);
        dump($header);

        //关闭URL请求
        curl_close($ch);
        $res = json_decode($result, true);
        print_r($res);
    }

    public function postHttpsAction($params)
    {
        $res = [];
        foreach ($params as $i => $param) {
            $res['key' . $i] = $param;
        }
        $body = http_build_query($res);
        $url = "https://demo.phalcon.lmx0536.cn/test/api/api";
        $ch = curl_init();
        // 设置抓取的url
        curl_setopt($ch, CURLOPT_URL, $url);
        // 启用时会将头文件的信息作为数据流输出。
        curl_setopt($ch, CURLOPT_HEADER, false);
        // 启用时将获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 启用时会将服务器服务器返回的"Location: "放在header中递归的返回给服务器，使用CURLOPT_MAXREDIRS可以限定递归返回的数量。
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        // 设置访问 方法
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        // 设置POST BODY
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

        // 是否检测服务器的证书是否由正规浏览器认证过的授权CA颁发的
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // 是否检测服务器的域名与证书上的是否一致
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        // 设置SSL私钥加密类型
        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
        // 设置SSL私钥文件名
        curl_setopt($ch, CURLOPT_SSLKEY, BASE_PATH . '/data/ssl/client.key');
        // 设置SSL私钥加密类型
        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
        // 设置一个包含PEM格式证书的文件名
        curl_setopt($ch, CURLOPT_SSLCERT, BASE_PATH . '/data/ssl/client.crt');

        // 设置header
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'APPID:' . uniqid(),
            'APPSECRET:' . md5(uniqid()),
        ]);

        //执行命令
        $result = curl_exec($ch);
        if ($result === false) {
            echo 'Curl error: ' . curl_error($ch);
            return false;
        }
        //关闭URL请求
        curl_close($ch);
        $res = json_decode($result, true);
        print_r($res);
    }

    public function postJsonAction($params)
    {
        $res = [];
        foreach ($params as $i => $param) {
            $res['key' . $i] = $param;
        }
        $body = json_encode($res);
        $url = "http://demo.phalcon.lmx0536.cn/test/api/api";
        $ch = curl_init();
        // 设置抓取的url
        curl_setopt($ch, CURLOPT_URL, $url);
        // 启用时会将头文件的信息作为数据流输出。
        curl_setopt($ch, CURLOPT_HEADER, false);
        // 启用时将获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 启用时会将服务器服务器返回的"Location: "放在header中递归的返回给服务器，使用CURLOPT_MAXREDIRS可以限定递归返回的数量。
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        // 设置访问 方法
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        // 设置POST BODY
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        // 设置JSON HEADER
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($body),
            'APPID:' . uniqid(),
            'APPSECRET:' . md5(uniqid()),
        ]);

        //执行命令
        $result = curl_exec($ch);
        if ($result === false) {
            echo 'Curl error: ' . curl_error($ch);
            return false;
        }
        //关闭URL请求
        curl_close($ch);
        $res = json_decode($result, true);
        print_r($res);
    }

    public function postUrlAction($params)
    {
        $res = [];
        foreach ($params as $i => $param) {
            $res['key' . $i] = $param;
        }
        $body = http_build_query($res);
        $url = "http://demo.phalcon.lmx0536.cn/test/api/api";
        $ch = curl_init();
        // 设置抓取的url
        curl_setopt($ch, CURLOPT_URL, $url);
        // 启用时会将头文件的信息作为数据流输出。
        curl_setopt($ch, CURLOPT_HEADER, false);
        // 启用时将获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 启用时会将服务器服务器返回的"Location: "放在header中递归的返回给服务器，使用CURLOPT_MAXREDIRS可以限定递归返回的数量。
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        // 设置访问 方法
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        // 设置POST BODY
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

        // 设置header
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'APPID:' . uniqid(),
            'APPSECRET:' . md5(uniqid()),
        ]);

        //执行命令
        $result = curl_exec($ch);
        if ($result === false) {
            echo 'Curl error: ' . curl_error($ch);
            return false;
        }
        //关闭URL请求
        curl_close($ch);
        $res = json_decode($result, true);
        print_r($res);
    }

    public function getAction($params)
    {
        $res = [];
        foreach ($params as $i => $param) {
            $res['key' . $i] = $param;
        }
        $body = http_build_query($res);
        $url = "http://demo.phalcon.lmx0536.cn/test/api/api?" . $body;
        $ch = curl_init();
        // 设置抓取的url
        curl_setopt($ch, CURLOPT_URL, $url);
        // 启用时会将头文件的信息作为数据流输出。
        curl_setopt($ch, CURLOPT_HEADER, false);
        // 启用时将获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 启用时会将服务器服务器返回的"Location: "放在header中递归的返回给服务器，使用CURLOPT_MAXREDIRS可以限定递归返回的数量。
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        // 设置header
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'APPID:' . uniqid(),
            'APPSECRET:' . md5(uniqid()),
        ]);

        //执行命令
        $result = curl_exec($ch);
        if ($result === false) {
            echo 'Curl error: ' . curl_error($ch);
            return false;
        }
        //关闭URL请求
        curl_close($ch);
        $res = json_decode($result, true);
        print_r($res);
    }

    public function getbaiduAction($params)
    {
        $url = "http://www.baidu.com";
        if (count($params) > 0 && $params[0] == '--error') {
            $url = "http://www.baidu.com1";
        }
        $ch = curl_init();
        // 设置抓取的url
        curl_setopt($ch, CURLOPT_URL, $url);
        // 启用时会将头文件的信息作为数据流输出。
        curl_setopt($ch, CURLOPT_HEADER, false);
        // 启用时将获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 启用时会将服务器服务器返回的"Location: "放在header中递归的返回给服务器，使用CURLOPT_MAXREDIRS可以限定递归返回的数量。
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        //执行命令
        $result = curl_exec($ch);
        if ($result === false) {
            echo 'Curl error: ' . curl_error($ch);
            return false;
        }
        //关闭URL请求
        curl_close($ch);
        echo $result;
    }
}