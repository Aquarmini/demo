<?php
// +----------------------------------------------------------------------
// | 测试脚本 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Tasks\Test;

use limx\curl\Application;
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
        echo Color::colorize('  utilsCurl               limingxinleo/utils-curl测试', Color::FG_GREEN) . PHP_EOL;
        echo Color::colorize('  multi                   multi模式', Color::FG_GREEN) . PHP_EOL;

    }

    public function multiAction()
    {
        $url = 'https://demo.phalcon.lmx0536.cn/test/api/api';
        $count = 10;

        $starttime = microtime(true);
        $mh = curl_multi_init();
        for ($i = 0; $i < $count; $i++) {
            $ch[$i] = curl_init();
            curl_setopt($ch[$i], CURLOPT_URL, $url);
            curl_setopt($ch[$i], CURLOPT_HEADER, 0); //不输出头
            curl_setopt($ch[$i], CURLOPT_RETURNTRANSFER, 1); //exec返回结果而不是输出,用于赋值
            curl_setopt($ch[$i], CURLOPT_FOLLOWLOCATION, 1);

            curl_multi_add_handle($mh, $ch[$i]); //决定exec输出顺序
        }
        $running = null;
        //执行批处理句柄(类似pthreads多线程里的start开始和join同步)
        do {
            //CURLOPT_RETURNTRANSFER如果为0,这里会直接输出获取到的内容.如果为1,后面可以用curl_multi_getcontent获取内容.
            curl_multi_exec($mh, $running);
            //阻塞直到cURL批处理连接中有活动连接,不加这个会导致CPU负载超过90%.
            curl_multi_select($mh);
        } while ($running > 0);
        echo microtime(true) - $starttime . "\n"; //耗时约2秒
        foreach ($ch as $v) {
            $info[] = curl_getinfo($v);
            $json[] = curl_multi_getcontent($v);
            curl_multi_remove_handle($mh, $v);
        }
        curl_multi_close($mh);
        $endtime = microtime(true);
        echo Color::colorize("multi耗时：" . ($endtime - $starttime)) . PHP_EOL;
        // print_r($info);
        // print_r($json);

        $starttime = microtime(true);
        for ($i = 0; $i < $count; $i++) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0); //不输出头
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //exec返回结果而不是输出,用于赋值
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            //执行命令
            $result = curl_exec($ch);
            // print_r($result);
            //关闭URL请求
            curl_close($ch);
        }
        $endtime = microtime(true);
        echo Color::colorize("普通耗时：" . ($endtime - $starttime)) . PHP_EOL;

    }

    public function utilsCurlAction()
    {
        $curl = new Application();
        $url = 'https://demo.phalcon.lmx0536.cn/test/api/api';
        $headers = [
            'Test' => 'Test'
        ];
        $data = [
            'Data' => 'Value'
        ];
        $result = $curl->client->setHeaders($headers)->post($url, $data)->getJsonContent();
        print_r($result);
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
        curl_setopt($ch, CURLOPT_SSLKEY, ROOT_PATH . '/data/ssl/client.key');
        // 设置SSL私钥加密类型
        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
        // 设置一个包含PEM格式证书的文件名
        curl_setopt($ch, CURLOPT_SSLCERT, ROOT_PATH . '/data/ssl/client.crt');

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