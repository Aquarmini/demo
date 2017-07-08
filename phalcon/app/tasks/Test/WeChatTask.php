<?php

namespace App\Tasks\Test;

use App\Utils\Cache;
use EasyWeChat\Foundation\Application;
use limx\phalcon\Cli\Color;

class WeChatTask extends \Phalcon\Cli\Task
{

    public function mainAction()
    {
        echo Color::head('Help:'), PHP_EOL;
        echo Color::colorize('  微信脚本'), PHP_EOL, PHP_EOL;

        echo Color::head('Usage:'), PHP_EOL;
        echo Color::colorize('  php run Test\\\\Test [action]', Color::FG_GREEN), PHP_EOL, PHP_EOL;

        echo Color::head('Actions:'), PHP_EOL;
        echo Color::colorize('  tempSms       小程序模板消息', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  tempSms2      小程序模板消息', Color::FG_GREEN), PHP_EOL;
    }

    public function tempSms2Action()
    {
        $curl = new \limx\curl\Application();
        $cache_key = 'mini-project-accesstoken';
        $result = Cache::get($cache_key, 3600);
        if (empty($result) || $result['expires_at'] < time()) {
            $appid = env('MONSTER_MINI_PROGRAM_APPID');
            $secret = env('MONSTER_MINI_PROGRAM_SECRET');
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential';
            $result = $curl->client->get($url, ['appid' => $appid, 'secret' => $secret])->getJsonContent(true);
            $expires_in = $result['expires_in'];
            $result['expires_at'] = time() + $expires_in;
            Cache::save($cache_key, $result);
        }

        $access_token = $result['access_token'];
        $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=' . $access_token;
        $params = [
            'touser' => 'or34b0XiHnOv2cnqvIgovXDxUjkc',
            'template_id' => 'b8oScGlt4Ou8SdWeTlz8BBlsRpSd1xcXjpPIpRPwDYA',
            'url' => 'index',
            'form_id' => 'btnAA',
            'data' => [
                'keyword1' => [
                    'value' => '工作汇报',
                    'color' => '#173177'
                ],
                'keyword2' => [
                    'value' => '多日没有收到订单',
                    'color' => '#173177'
                ],
                'keyword3' => [
                    'value' => '5条',
                    'color' => '#173177'
                ],
            ],
        ];
        $result = $curl->client->format('json')->post($url, $params)->getJsonContent(true);
        print_r($result);
    }

    public function tempSmsAction()
    {
        $config = app('easywechat')->toArray();
        $app = new Application($config);
        $mini_program = $app->mini_program;
        $res = $mini_program->notice->send([
            'touser' => 'or34b0XiHnOv2cnqvIgovXDxUjkc',
            'template_id' => 'b8oScGlt4Ou8SdWeTlz8BBlsRpSd1xcXjpPIpRPwDYA',
            'url' => 'xxxxx',
            'form_id' => 'FORMID',
            'data' => [
                'keyword1' => '工作汇报',
                'keyword2' => '多日没有收到订单',
                'keyword3' => '5条'
            ],
        ]);

        print_r($res);
    }

}

