<?php
// +----------------------------------------------------------------------
// | Demo [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lmx0536.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <http://www.lmx0536.cn>
// +----------------------------------------------------------------------
// | Date: 2017/2/25 Time: 上午10:03
// +----------------------------------------------------------------------
namespace MyApp\Tasks\Swoole;

use Phalcon\Cli\Task;
use limx\phalcon\Cli\Color;

class WebSocketTask extends Task
{
    public function mainAction()
    {
        $server = new \swoole_websocket_server("0.0.0.0", 9501);

        $server->on('open', function (\swoole_websocket_server $server, $request) {
            echo "server: handshake success with fd{$request->fd}\n";
        });

        $server->on('message', function (\swoole_websocket_server $server, $frame) {
            echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
            $server->push($frame->fd, "this is server");
        });

        $server->on('close', function ($ser, $fd) {
            echo "client {$fd} closed\n";
        });

        $server->start();
        echo Color::colorize("Hello world", Color::FG_LIGHT_CYAN) . PHP_EOL;
    }

}